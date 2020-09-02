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
    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'addmoney'       => 'required',
            'secretkey'      => 'required',     
            'appId'          => 'required',   
            'orderAmount'    => 'required',   
            'orderCurrency'  => 'required',   
            'orderNote'      => 'required',   
            'customerName'   => 'required',   
            // 'customerName'   => 'required',   
            'customerPhone'  => 'required',   
            'customerEmail'  => 'required', 
      ]); 

   if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $secretKey = 'KbFC1m8YCQaA9DMse3D2fOaV';
        $appId = 'rzp_test_lVQoHcu2pnx7JP';
        $orderAmount = 10;
        $orderCurrency = 'INR';
        $orderNote = 'test';
        $customerName  = 'sathish';
        $customerPhone = '7904688687';
        $customerEmail = 'sathish@mailinator.com';
        $returnUrl = 'https://consummo.com/sportztrophyapi/public/api/response';
        $notifyUrl = 'https://consummo.com/sportztrophyapi/public/api/response';

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


//cash free api
        $url = "https://test.cashfree.com/api/v1/order/create";
                
        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => $url,
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_SSL_VERIFYHOST => false,
        //   CURLOPT_SSL_VERIFYPEER => false,
        //   CURLOPT_ENCODING => "",
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 30,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => "POST",
        //   CURLOPT_POSTFIELDS => "appId=".$appId."&secretKey=".$secretKey."&orderId=".$order_id."&orderCurrency=".$orderCurrency."&orderAmount=".$orderAmount."&customerName=".$customerName."&customerPhone=".$customerPhone."&customerEmail=".$customerEmail."&returnUrl=".$returnUrl."&notifyUrl=".$notifyUrl."&Content-Type=appilication%2Fx-www-form-urlencoded",
        //   CURLOPT_HTTPHEADER => array(
        //     "cache-control: no-cache",
        //     "content-type: application/x-www-form-urlencoded",
        //     "postman-token: 5ecb012d-6af2-a918-b3d0-c7873e54b093"
        //   ),
        // ));

        https://api.razorpay.com/v1/order

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

    public function depositresponse(Request $request)
    {

       $validator = Validator::make($request->all(), [
            'amount'     => 'required',
            'status'     => 'required',     
            // 'orderid'    => 'required',   
            // 'signature'  => 'required',   
            // 'paymentid'  => 'required',   
            // 'message'    => 'required',   
      ]); 

   if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $random = 'pay_'.substr(md5(microtime()),rand(0,26),5);

        $user_id = Auth::user()->id;

        $transaction =new Transaction();

        $transaction->uid = $user_id;
        $transaction->orderAmount = $request->amount;
        $transaction->txStatus = $request->status;
        $transaction->paymentMode = 'Bank';
        $transaction->type = 'Deposit';
        $transaction->payment_id = ($request->paymentid != '')?$request->paymentid:$random;
        $transaction->order_id = $request->orderid;
        $transaction->signature = $request->signature;
        $transaction->message = $request->message;
        $transaction->save();


        $user_details = User::find($user_id);
        $bonus = $user_details->wallet['bonus'];
        $deposit = $user_details->wallet['deposit'] + $request->amount;
        $winnings = $user_details->wallet['winnings'];
        $currency = $user_details->wallet['currency'];
        $total = $user_details->wallet['total'] + $request->amount;

        $user_details->wallet = ['deposit' => $deposit,
                      'total' => $total,
                      'bonus' => $bonus,
                      'currency' => $currency,
                      'winnings' => $winnings,
                    ];
        $user_details->paid_status = 1; 
        $user_details->save(); 

        if($request->status == 'success'){
            $message = 'Deposit Sussessfully';
        }else{
            $message = 'Deposit Failed.';
        }

        $yourData =['status' => true, 'response' => null, 'message' => $message];

        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

      }

    public function withdraw_update(WithdrawRequest $request)
    {
        $user_details = User::where('_id', auth()->user()->_id)->first();
        if(is_object($user_details)){

          $settings = WithdrawSettings::where('status','1')->first();
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
