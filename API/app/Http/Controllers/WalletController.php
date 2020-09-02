<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\UserBtcAddress;
use App\Models\UserEthAddress;
use App\Models\UserLtcAddress;
use App\Models\Deposit;
use App\Models\AdminBankDetails;
use App\Http\Requests\DepositRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use App\User;
use App\Models\Wallet;
use App\Models\Setting;

use Razorpay\Api\Api;

class WalletController extends Controller
{ 
    public function getuser_balance()
    {        
        $user_check_email = User::where('_id', auth()->user()->_id)->first();
        $settings = Setting::first();

        if(isset($user_check_email)){
            $user_details = $user_check_email;
            if(isset($user_check_email->wallet)){
                $balance = number_format($user_check_email->wallet['total'],2,'.','');
            }else{
                $balance = number_format(0,2,'.','');
            }
        }

        $details  = array(
            'balance'       => $balance,
            'useremail'     => $user_details->email,
            'customername'  => $user_details->teamname,
            'userphone'     => $user_details->phone,
            'deposit'       => number_format($user_details->wallet['deposit'],2,'.',''),
            'bonus'         => number_format($user_details->wallet['bonus'],2,'.',''),
            'winnings'      => number_format($user_details->wallet['winnings'],2,'.',''),
            'bank_verify'   => $user_details->bank_status,
            'pan_verify'    => $user_details->pan_status,
            'mobile_verify' => $user_details->mobile_verify,
            'email_verify'          => $user_details->email_verify,
            'withdraw_limit'        => $settings->withdraw_limit,
            'maximum_withdraw_amt'  => $settings->withdraw_maximum,
            'minimum_withdraw_amt'  => $settings->withdraw_minimum,
        );

        return response()->json($details);
    }    

    public function adduserbalance(Request $request)
    {
        $Key_id     = env('RAZORPAY_KEY');
        $Key_secret = env('RAZORPAY_SECRET');   

        $api = new Api($Key_id, $$Key_secret);
        
        $order  = $api->order->create([
              'receipt'         => 'order_rcptid_11',
              'amount'          => 50000, // amount in the smallest currency unit
              'currency'        => 'INR', // <a href="https://razorpay.freshdesk.com/support/solutions/articles/11000065530-what-currencies-does-razorpay-support" target="_blank">See the list of supported currencies</a>.)
              'payment_capture' =>  '0']);

        $payment = $api->payment->fetch('pay_29QQoUBi66xm2f');
        $payment->capture(array('amount' => 5000, 'currency' => 'INR'));            
        
        return json_decode($responseBody);
    }


}
