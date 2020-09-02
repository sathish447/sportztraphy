<?php

namespace App\Models;

use App\Models\Commission;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Wallet extends Eloquent
{
    use Notifiable; 

    protected $connection = 'mongodb';
    // protected $collection = 'users';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency', 'amount',
    ];

    // public static function userBalance($id,$coin)
    // {
    // 	$userWalletDetails = Wallet::where('user_id',$id)->where('currency',$coin)->first();

    // 	return $userWalletDetails;
    // } 

    public static function userTradeBalance($id,$coinone,$cointwo)
    {
        $coin_one_commission = Commission::pairCommission($coinone); 
        $coin_two_commission = Commission::pairCommission($cointwo);

        if($id!=0)
        {
            $bal_1 = Wallet::where('user_id',$id)->where('currency',$coinone)->first();
            $bal_2 = Wallet::where('user_id',$id)->where('currency',$cointwo)->first();

            if(isset($bal_1->balance)){
                $balance_1 = $bal_1->balance;
            }else{
                $balance_1 = 0;
            }

            if(isset($bal_2->balance)){
                $balance_2 = $bal_2->balance;
            }else{
                $balance_2 = 0;
            }

            $balance  = array(
                'coinone' => $coinone , 
                'coinone_bal' => number_format($balance_1,8,'.',''),
                'cointwo' => $cointwo,
                'cointwo_bal' => number_format($balance_2,8,'.',''),
                'coinone_buy' => $coin_one_commission->buy_trade,
                'coinone_sell' => $coin_one_commission->sell_trade,
                'cointwo_buy' => $coin_two_commission->buy_trade,
                'cointwo_sell' => $coin_two_commission->sell_trade,
            );
        }
        else
        {
            $balance  = array(
                'coinone' => $coinone , 
                'coinone_bal' => number_format(0,8,'.',''),
                'cointwo' => $cointwo,
                'cointwo_bal' => number_format(0,8,'.',''),
                'coinone_buy' => $coin_one_commission->buy_trade,
                'cointwo_sell' => $coin_two_commission->sell_trade,              
            );
        }

        return $balance;
    }
}
