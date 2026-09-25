# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

GUMA@NET — a Laravel 5.7 / PHP 7.2+ ERP web app for Grupo Guma (Nicaragua). Multi-company:
Unimark (`company_id` 1) and Innova (`company_id` 4). Company context lives in the **session**,
not in the URL or a tenant column, and controllers/views branch on it.

`readme.md` is the stock Laravel scaffold — ignore it. `AGENTS.md` (gitignored) covers similar
ground and may drift; verify against source.

## Commands

```bash
composer install
copy .env.example .env      # then fill DB creds; note .env.example is gitignored too
php artisan key:generate
php artisan serve           # dev server

npm install && npm run dev  # Laravel Mix build (see caveat below)
npm run watch
npm run prod

vendor/bin/phpunit                                   # all tests
vendor/bin/phpunit --testsuite Unit                  # one suite (Unit | Feature)
vendor/bin/phpunit tests/Feature/ExampleTest.php     # single file
vendor/bin/phpunit --filter testBasicTest            # single test
```

There is **no linter, formatter, or static analysis** configured. `tests/` contains only the
Laravel scaffold (two `ExampleTest.php`), so there is effectively no test coverage — don't
expect a test suite to catch regressions here.

**Mix caveat:** the real layouts (`layouts/main.blade.php`, `layouts/kardex.blade.php`, …) load
~40 hand-placed `<script>`/`<link>` tags pointing at files committed under `public/js` and
`public/css`. Only the unused stock `layouts/app.blade.php` references the Mix bundles
(`js/app.js`, `css/app.css`). Adding a front-end dependency means committing the vendored file
to `public/js` and adding a tag to the layout — not editing `resources/js` and rebuilding.

## Architecture

### Dual database: MySQL for the app, SQL Server for the ERP

- **MySQL** (`mysql`, default, `db_gumanet`) — Eloquent territory: `users`, `companies`,
  `company_user`, `roles`, `role_user`, `menu`, `menu_rol`. The 9 migrations in
  `database/migrations` only cover these.
- **SQL Server** (`sqlsrv` → DB `PRODUCCION`, `sqlsrv_old` → `DESARROLLO`) — all ERP
  transactional data (Softland/iweb tables). Accessed via **raw SQL strings**, never Eloquent.
  Queries hard-code database and schema prefixes (`PRODUCCION.dbo.iweb_articulos`,
  `Softland.dbo.VtasTotal_UMK`), so changing the target DB means editing SQL, not just config.
- Three more MySQL connections: `mysql_kardex_inn` (`DB_*_kardex`), `mysql_pedido`
  (`DB_*_PEDIDO`), `mysql_stat` (`DB_*_STAT`). All MySQL connections use
  `PDO::ATTR_EMULATE_PREPARES => true` and `'strict' => false`; SQLSRV uses a 300s query timeout.

### Two ways raw SQL gets executed

1. `DB::connection('sqlsrv')->select(...)` — Laravel's PDO path (~108 `DB::connection` call sites).
2. **`app/Libraries/Sqlsrv.php`** — class `\sql_server`, a hand-rolled `sqlsrv_*` wrapper
   (`fetchArray`, `fetchObject`, `fetchCol`, `query`, `get_where`, `query_builder`, …). It is
   **not PSR-4**: `SqlSrvProvider::register()` does `require_once app_path().'/Libraries/Sqlsrv.php'`,
   and composer.json `autoload.files` loads it too, hence the root-namespace `new \sql_server()`.
   It carries hardcoded credentials as class properties, and `query_builder()` concatenates
   values straight into SQL (injection risk — don't extend that pattern).

`app/Libraries/gitVersion.php` (class `\git_version`, loaded the same way by
`gitVersionServiceProvider`) supplies the version string shown in the footer.

### Request flow

`routes/web.php` — ~271 flat `Route::` calls, no groups, no prefixes, no middleware in the route
file. Auth is applied **per controller constructor**: `$this->middleware(['auth','roles'])`.
`routes/api.php` has 2 routes.

Controllers → static methods on model classes → raw SQL or Eloquent → `view('pages.X', compact('data','Style'))`.
The recurring controller shape:

```php
$this->agregarDatosASession();                  // see below
$companie = Session::get('company_id');
$data  = ['page' => '...', 'name' => 'GUMA@NET'];
$Style = ['Logo' => ($companie == 4) ? 'img/innova.png' : 'img/unimark.png', ...];
return view($companie == 4 ? 'pages.X.innova' : 'pages.X.unimark', compact('data','Style'));
```

AJAX endpoints return `response()->json($obj)` or `DataTables::of($rows)->make(true)`
(`yajra/laravel-datatables`).

### Authorization is menu-driven, not middleware-driven

- `App\Http\Middleware\roles` is a **no-op** — both branches return `$next($request)`. It grants
  nothing and blocks nothing.
- Real access control is the sidebar: `AppServiceProvider::boot()` registers a view composer on
  `layouts.menu` that calls `menu::getMenu(true)`, which filters the `menu` table through the
  `menu_rol` pivot against `Auth::User()->activeRole()` (the scalar `users.role` column).
  A user who guesses a URL reaches the controller regardless.
- `LoginController::login()` additionally requires a row in `company_user` matching the selected
  company, then seeds session `user_email`, `company_id`, `user_role`.
  `LoginController::redirectTo()` maps `users.role` to a landing route.

### Views

- `resources/views/pages/**` — page Blade files, mostly `@extends('layouts.main')`
  (43 of them; also `ly_reorder`, `lytresumen`, `lyt_acciones`, `Budget`, `kardex`).
- `resources/views/jsViews/js_*.blade.php` — ~40 files that are **pure `<script>` blocks**,
  pulled into a page via `@include('jsViews.js_foo')` inside `@section('metodosjs')`. All the
  DataTables config, Highcharts/Chart.js setup, daterangepicker wiring, and AJAX lives here.
  When changing page behavior, the JS is almost always in the paired `jsViews` file, not the page.
- `layouts/main.blade.php` yields `title`, `content`, and `metodosjs`, and includes `layouts.menu`.

### Naming: two eras coexist

- Controllers: legacy `snake_case` (`inventario_controller.php`, `dashboard_controller.php`) and
  newer `CamelCase` (`FacturacionController.php`, `TransitoController.php`). 44 total.
- Models: 112 files directly in `app/` — a mix of real Eloquent models (`User`, `Company`, `menu`,
  `Articulo`) and query-bag classes of static methods (`dashboard_model.php` 3196 lines,
  `inventario_model.php` 2141 lines) that `extends Model` only nominally.
- Spanish domain vocabulary throughout (inventario, facturación, transito, recuperación, metas,
  presupuesto, comisiones, vinneta). Comments are Spanish. Match the surrounding language.

### Caching

`Cache::*` is unused; `dashboard_controller` and `inventario_controller` call the `Redis` facade
directly (`predis/predis`) to memoize expensive SQL Server aggregates.

## Gotchas

- **`agregarDatosASession()` is copy-pasted into ~18 controllers** with identical bodies (sets
  `ApplicationVersion` and `companyName` in session). There is no base trait or helper — if you
  change one, decide deliberately whether the other 17 need it.
- **`.env` and `.env.example` are both gitignored** (`*.env`, `*.example`, `.env.example`), so
  new env vars must be communicated out-of-band and added to the K8s ConfigMap.
- `config('global.url_server')` and `ASSET_IMG` point at an external GMV image CDN by raw IP.
- `FORCE_HTTPS` in `AppServiceProvider::boot()` has inverted logic: when `$_SERVER['HTTPS']`
  is on it calls `URL::forceScheme('http')`.
- Deprecated/heavy deps pinned: `phpoffice/phpexcel` (`dev-master`, abandoned — used for all
  Excel export/import), `barryvdh/laravel-dompdf` 0.8, `yajra/laravel-datatables-oracle` ~8.0.
- Composer pins `platform.php = 7.2.14` while the Docker base image is PHP 7.4.

## Deployment

Jenkins (`Jenkinsfile`) builds `gumadesarrollo/gumanet:v1.1.$BUILD_NUMBER` from `Dockerfile`
(base `kooldev/php:7.4-nginx-sqlsrv-prod`, app root `/app/gnet/public`), pushes to Docker Hub,
then pipes `Deployment.yaml` through `sed` into `kubectl apply` (namespace `grupoguma`,
3 replicas, env from ConfigMap `laravel-config`).

## Git conventions

Default working branch is **`Produccion`** (not `master`). Commit subjects follow
`<emoji> [TAG] Description` — e.g. `✅ [ADD] Facturas vencidas: listado, detalle y reversión`,
`🔥 [ADD] ...`, `[UP] ...`. Descriptions are in Spanish.
