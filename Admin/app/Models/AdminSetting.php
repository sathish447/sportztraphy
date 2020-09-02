<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    public static function getAdminDetails($value='')
    {
    	return AdminSetting::where('id','=','1')->first();    	 
    }
}
