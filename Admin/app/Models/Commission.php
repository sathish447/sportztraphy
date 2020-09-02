<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{

    protected $table ='commissions';
    public static function index()
    {
    	$commission = Commission::on('mysql2')->paginate(10);

    	return $commission;
    }

    public static function edit($id)
    {
    	$commission = Commission::on('mysql2')->where('id', $id)->first();

    	return $commission;
    }

    public static function commissionUpdate($request)
    {

    	$commission = Commission::on('mysql2')->where('id', $request->id)->where('source',$request->currency)->first();
        $commission->id = $request->id; 
        $commission->withdraw = floatval($request->withdraw);
        $commission->buy_trade = floatval($request->trade); 
        $commission->sell_trade = floatval($request->sell);
        $commission->save();
        
        return true;   
    }
}
