<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Database\Eloquent\Model;

class Setting extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'settings';



    public static function commissionUpdate($request)
    {

        $commission = Setting::where('_id', $request->id)->first();
        
        $commission->withdraw_limit = floatval($request->withdraw_limit);
        $commission->withdraw_minimum = floatval($request->withdraw_minimum); 
        $commission->withdraw_maximum = floatval($request->withdraw_maximum);
        $commission->status = 1;
        $commission->save();
        
        return true;   
    }
}
