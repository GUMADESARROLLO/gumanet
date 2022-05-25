<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SqlSrvProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       require_once app_path().'/Libraries/Sqlsrv.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
