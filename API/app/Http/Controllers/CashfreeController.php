<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashfreeRequest;

use App\User;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\WithdrawSettings;

use App\Http\Requests\WithdrawRequest;

use Mail;
use Illuminate\Support\Carbon;

class CashfreeController extends Controller
{
    public function index(CashfreeRequest $request)
    {
      //   $validator = Validator::make($request->all(), [
      //       'addmoney'  => 'required',
      //       'secretkey'  => 'required',     
      //       'appId'     => 'required',   
      //       'orderAmount'     => 'required',   
      //       'orderCurrency'     => 'required',   
      //       'orderNote'     => 'required',   
      //       'customerName'     => 'required',   
      //       'customerName'     => 'required',   
      //       'customerPhone'     => 'required',   
      //       'customerEmail'     => 'required', 
      // ]); 

      //   if ($validator->fails()) {        
      //       $yourData =['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
      //       return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
      //   }

        $secretKey = $request->secretkey;
        $appId = $request->appId;
        $orderAmount = $request->orderAmount;
        $orderCurrency = $request->orderCurrency;
        $orderNote = $request->orderNote;
        $customerName  = $request->customerName;
        $customerPhone = $request->customerPhone;
        $customerEmail = $request->custo;
        $returnUrl = $request->returnUrl;
        $notifyUrl = $request->notifyUrl;

        $previous_client_id = Transaction::limit(1)->orderBy('orderId','desc')->first();
        if(isset($previous_client_id)){
            $find_id = explode('order', $previous_client_id->orderId);     
            $assign_id = $find_id[1]+'1';         
            $num_added = sprintf("%03d", $assign_id);
            $order_id='order'.$num_added;     

        }else{
            $order_id='order001'; 
        } 

        $user = new Transaction();
        $user->uid = auth()->user()->_id;
        $user->orderId = $order_id;
        $user->save();
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://test.cashfree.com/api/v1/order/create",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "appId=".$appId."&secretKey=".$secretKey."&orderId=".$order_id."&orderCurrency=".$orderCurrency."&orderAmount=".$orderAmount."&customerName=".$customerName."&customerPhone=".$customerPhone."&customerEmail=".$customerEmail."&returnUrl=".$returnUrl."&notifyUrl=".$notifyUrl."&Content-Type=appilication%2Fx-www-form-urlencoded",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "postman-token: 5ecb012d-6af2-a918-b3d0-c7873e54b093"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
    }

    public function response(Request $request)
    {
        $user =  Transaction::where('orderId',  $request->orderId)->first();
        $user->orderAmount = $request->orderAmount;
        $user->referenceId = $request->referenceId;
        $user->txStatus = $request->txStatus;
        $user->paymentMode = $request->paymentMode;
        $user->txMsg = $request->txMsg;
        $user->txTime = $request->txTime;
        $user->signature = $request->signature;
        $user->type = 'deposit';
        $user->save();

        $user_details = User::find($user->uid);
        $bonus = $user_details->wallet['bonus'];
        $deposit = $user_details->wallet['deposit'] + $request->orderAmount;
        $winnings = $user_details->wallet['winnings'];
        $currency = $user_details->wallet['currency'];
        $total = $user_details->wallet['total'] + $request->orderAmount;

        $user_details->wallet = ['deposit' => $deposit,
                      'total' => $total,
                      'bonus' => $bonus,
                      'currency' => $currency,
                      'winnings' => $winnings,
                    ];
        $user_details->paid_status = 1; 
        $user_details->save(); 
    }

    public function withdraw_update(WithdrawRequest $request)
    {
      // dd($request->all());
        $user_details = User::where('_id', auth()->user()->_id)->first();
        if(is_object($user_details)){

          $settings = WithdrawSettings::where('status','1')->first();

          // dd($settings);
          if($settings->minimumwithdrawlimit > $request->withdrawmoney){              

            $yourData =['status' => false, 'response' => null, 'message' => 'minimum withdraw amount greater than '.$settings->withdraw_minimum];
                 return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
           } 

           if($settings->maximumwithdrawlimit < $request->withdrawmoney){

              $yourData =['status' => false, 'response' => null, 'message' => 'minimum withdraw amount lesser than '.$settings->withdraw_maximum];              
              return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

           }
         
            if($user_details->wallet['winnings'] <= $request->withdrawmoney){

              $yourData =['status' => false, 'response' => null, 'message' => 'Insufficient Fund'];              
              return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

           }

            $bonus = $user_details->wallet['bonus'];
            $deposit = $user_details->wallet['deposit'];
            $winnings = $user_details->wallet['winnings'] - $request->withdrawmoney;
            $currency = $user_details->wallet['currency'];
            $total = $user_details->wallet['total'] - $request->withdrawmoney;

            
          $user = new Transaction();
          
          $user->orderAmount = $request->withdrawmoney;
          $user->uid = auth()->user()->_id;
          $user->txStatus = 'PENDING';
          $user->paymentMode = 'Bank';
          $user->txTime = Carbon::now();
          $user->type = 'withdraw';
          $user->save();
  
          $user_details->wallet = ['deposit' => $deposit,
                        'total' => $total,
                        'bonus' => $bonus,
                        'currency' => $currency,
                        'winnings' => $winnings,
                      ];
          $user_details->save(); 

          $yourData =['status' => true, 'response' => null, 'message' => 'withdarw Successfully.'];

          return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

        }
    }

    public function transaction_history(Request $request)
    {      
      $validator = Validator::make($request->all(), [
          'offset' => 'required',
          'limit' => 'required',        
      ]);
      if ($validator->fails()) {      
          $yourData =['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
      }
      $user_details = Transaction::where('uid', auth()->user()->_id)->offset((int)$request->offset)->limit((int)($request->limit))->orderBy('_id','DESC')->get();
      $data =array();
      foreach($user_details as $key => $value){
        $datetime = explode(' ',$value->created_at);
        $date = $datetime[0];
        $time = $datetime[1];
        $data[$key]['date'] =  $date;
        $data[$key]['time'] = $time;
        $data[$key]['reason'] = 'testing';
        $data[$key]['transaction_id'] = $value->referenceId;
        $data[$key]['amount'] = $value->orderAmount;
        $data[$key]['team_name'] = auth()->user()->teamname;
        $data[$key]['user_id'] = auth()->user()->_id;
        $data[$key]['order_id'] = auth()->user()->order_id;
        $data[$key]['type'] = $value->type;
        $data[$key]['status'] = $value->txStatus;
        $data[$key]['expected_date'] = '05-01-2020';
      }
      $response = array('details' => $data);
      $yourData =['status' => true, 'response' => $response, 'message' => ''];
      return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function transaction_details()
    {
      $user_details = Transaction::get();
      $response = array('details' => $user_details);
      $yourData =['status' => true, 'response' => $response, 'message' => ''];
      return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function order_id_generation()
    {
      $previous_client_id = Transaction::limit(1)->orderBy('orderId','desc')->first();
      if(isset($previous_client_id)){
          $find_id = explode('order', $previous_client_id->orderId);     
          $assign_id = $find_id[1]+'1';         
          $num_added = sprintf("%03d", $assign_id);
          $order_id='order'.$num_added;     

      }else{
          $order_id='order001'; 
      } 
      $response = array('order_id' => $order_id);
      $yourData =['status' => true, 'response' => $response, 'message' => ''];
      return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    } 
}
