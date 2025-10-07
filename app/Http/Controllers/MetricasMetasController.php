<?php

namespace App\Http\Controllers;

use App\Models;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class MetricasMetasController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        return view('pages.MetricasMetas.Meta');
    }
}