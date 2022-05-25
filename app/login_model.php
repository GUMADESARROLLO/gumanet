<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class login_model extends Model
{
     public static function getCompanies(){
        return DB::table('companies')->get();
    }
}
