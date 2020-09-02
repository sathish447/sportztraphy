<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Http\Requests\MobileRequest;
use App\Mail\Register;
use Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTManager as JWT;

use JWTAuth;
use JWTFactory;
use App\User;

class SocialloginController extends Controller
{
     /**
     * gmail login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [number] mobile_number
     */
    public function gmaillogin(Request $request)
    {  
        // dd($request->email);

        $validator = Validator::make($request->all(), [
            'email' => 'required',  
        ]); 

        if ($validator->fails()) {        
            $yourData =['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
       
        $user = User::where('email',$request->email)->first();
    
    
        if(is_object($user)){

            if($user->phone == ''){

                // $send_sms =$this->smsotpsend($request);
                $userToken=JWTAuth::fromUser($user);

                // $response = array('details' => $user,'msg_session_id' => $send_sms['Details']);
                $jwttoken = $this->respondWithToken($userToken);
                $response = array('token'=>$jwttoken,'details' => $user,'register_status'=> true);

                $yourData = ['status' => true,'session_id' => '5D6EBEE6-EC04-4776-846D', 'mobile_status' => false, 'response' => $response, 'message' => 'Mobile number not verified please verify your mobile number.'];  

                
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

            }

            if($user->mobile_verify == 0){

                $send_sms =$this->smsotpsend($user->phone);

                $response = array('details' => $user,'msg_session_id' => $send_sms['Details']);
                $userToken=JWTAuth::fromUser($user);

                $jwttoken = $this->respondWithToken($userToken);
                $response = array('token'=>$jwttoken,'details' => $user,'register_status'=> true,'msg_session_id' => $send_sms['Details']);

                $yourData = ['status' => true, 'otp_status' => false, 'response' => $response, 'message' => 'Mobile number not verified please verify your mobile number.'];  

                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

                // $yourData = ['status' => true, 'phone_verify' => false, 'response' => $response, 'message' => 'Mobile number not verified please verify your mobile number.'];

                // $jwttoken = $this->respondWithToken($userToken);
                //             $response = array('token'=>$jwttoken,'details' => $user,'register_status'=> true);


                // return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

            }

            $user->email = $request->email;
            $user->password = '';
            $user->phone = $user->phone;
            $user->remember_token = Str::random(40);
            $user->status = 1;
            $user->mobile_verify = 1;
            $user->email_verify = 0;
            $user->pan_status = 0;
            $user->bank_status = 0;
            $user->referral = '';
            $user->register_type = 'gmail'; 
            $user->register_device = $request->registerdevice;
            $user->ip_address = $_SERVER['REMOTE_ADDR'];  
            $user->invitecode = Str::random(8);
            $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
            $user->name = $request->name;
            $user->smscount = 0;        

            $user->gmailauthtoken = $request->token;            
            $user->gmail_id = $request->id;            
            $user->image = $request->image_url;          
            $user->is_login = 1;            
            $user->wallet = array('currency' => '₹', 'total' => 0, 'winnings' => 0, 'bonus' => 0, 'deposit' => 0);
            
            $user->save();

            $userToken=JWTAuth::fromUser($user);
            $credentials = $request->email;

            $jwttoken = $this->respondWithToken($userToken);
            $user = User::where('email',$request->email)->first();
            $response = array('token'=>$jwttoken,'details' => $user,'register_status'=> true);
            $yourData = ['status' => true, 'response' => $response, 'message' => 'Login Successfully.','session_id' => '5D6EBEE6-EC04-4776-846D'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

        }else{

            $user = new User();

            $user->email = $request->email;
            $user->password = '';
            $user->phone = '';
            $user->remember_token = Str::random(40);
            $user->status = 1;
            $user->mobile_verify = 0;
            $user->email_verify = 0;
            $user->pan_status = 0;
            $user->bank_status = 0;
            $user->referral = '';
            $user->register_type = 'gmail'; 
            $user->register_device = $request->registerdevice;
            $user->ip_address = $_SERVER['REMOTE_ADDR'];  
            $user->invitecode = Str::random(8);
            $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
            $user->name = $request->name;
            $user->smscount = 0;        

            $user->gmailauthtoken = $request->token;            
            $user->gmail_id = $request->id;            
            $user->image = $request->image_url;          
            $user->is_login = 1;            
            $user->wallet = array('currency' => '₹', 'total' => 0, 'winnings' => 0, 'bonus' => 0, 'deposit' => 0);
            
            $user->save();
            $userToken=JWTAuth::fromUser($user);

            $credentials = $request->email;
            $jwttoken = $this->respondWithToken($userToken);
            $response = array('details' => $user);
            $yourData = ['status' => true,'token'=>$jwttoken, 'register_status' => false, 'session_id' => '5D6EBEE6-EC04-4776-846D', 'response' => @$response, 'message' => 'Register Successfully.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

        }


        // if($gmail_email != null){
        //     if($gmail_email['email'] == $request->email){
        //         $user = User::where('email',  $request->email)->where('register_type','gmail')->first();
        //         $user->email = $request->email;
        //         $user->password = '';
        //         $user->phone = '';
        //         $user->remember_token = Str::random(40);
        //         $user->status = 1;
        //         $user->mobile_verify = '1';
        //         $user->email_verify = 0;
        //         $user->pan_status = 0;
        //         $user->bank_status = 0;
        //         $user->referral = '';
        //         $user->register_type = 'gmail'; 
        //         $user->register_device = $request->registerdevice;
        //         $user->ip_address = $_SERVER['REMOTE_ADDR'];  
        //         $user->invitecode = Str::random(8);
        //         $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
        //         $user->name = $request->name;
        //         $user->smscount = 0;        
    
        //         $user->gmailauthtoken = $request->token;            
        //         $user->gmail_id = $request->id;            
        //         $user->image = $request->image_url;          
        //         $user->is_login = 1;            
        //         $user->wallet = array('currency' => '₹', 'total' => 0, 'winnings' => 0, 'bonus' => 0, 'deposit' => 0);
                
        //         $user->save();
        //     } 

        //     // $send_sms = $this->smsotpsend($request);

        //     // if (isset($send_sms)) {

        //     //     $user = User::where('email',  $request->email)->first();
        //     //     if ($send_sms['Status'] == 'Success') {  
        //     //         $user->sms_session_id = $send_sms['Details'];
        //     //         $user->save();
        //     //     }
        //     // }          



        //     $response = array('token' => $gmail_email->token);
        //     $yourData =['status' => true, 'register_status' => true, 'response' => $response, 'message' => 'Your Email Already Registered','mobile_verify'=>1, 'gmail_verify'=>0];
        //     return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        //     // return response()->json(['register_status' => 'register_success','id' => $gmail_email->token]);
        // }

        // $response = array('session_id' => '5D6EBEE6-EC04-4776-846D','id' => $request->id,'token' => $request->token,'name' => $request->name,'email' => $request->email, 'image_url'=>$request->image_url, 'mobile_verify'=>0, 'register_response'=>1);
        // $yourData =['status' => true, 'response' => $response, 'message' => 'Register Successfully'];
        // return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);


        // return response()->json(['status' => 'sms_success' , 'session_id' => '5D6EBEE6-EC04-4776-846D','id' => $request->id,'token' => $request->token,'name' => $request->name,'email' => $request->email, 'image_url'=>$request->image_url]);
    }


    public function mobile_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            // 'email' => 'required|email',
        
        ]); 

        if ($validator->fails()) {        
            $yourData =['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        if(@$request->phone != null){
              
                $send_sms =$this->smsotpsend($request);

                if(isset($send_sms)){

                    // if($send_sms['Status'] == 'Success')
                    // { 
                        // dd('if');
                        $user = User::where('email',$request->email)->first();
                        $user->email = $request->email;
                        $user->password = '';
                        $user->phone = $request->phone;
                        $user->remember_token = Str::random(40);
                        $user->status = 1;
                        $user->mobile_verify = '1';
                        $user->email_verify = 0;
                        $user->pan_status = 0;
                        $user->bank_status = 0;
                        $user->referral = '';
                        $user->register_type = 'gmail'; 
                        $user->register_device = $request->registerdevice;
                        $user->ip_address = $_SERVER['REMOTE_ADDR'];  
                        $user->invitecode = Str::random(8);
                        $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
                        $user->name = $request->name;
                        $user->smscount = 0;                                
                        $user->sms_session_id = $send_sms['Details'];
            
                        $user->gmailauthtoken = $request->token;            
                        $user->gmail_id = $request->id;            
                        $user->image = $request->image_url;          
                        $user->is_login = 1;            
                        $user->wallet = array('currency' => '₹', 'total' => 0, 'winnings' => 0, 'bonus' => 0, 'deposit' => 0);

                        $user->save();
                        $response = array('msg_session_id' => $send_sms['Details'],'register_status' => true);
                        // $yourData =['status' => true, 'otp_status' => true, 'response' => $response, 'message' => 'OTP Send Your Registered Mobile Number..'];
                        $yourData =['status' => true, 'response' => $response, 'message' => 'Mobile Number Verified Successfully..'];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                        // return response()->json(['register_status' => 'register_success', 'msg_session_id' => $send_sms['Details']]);
                //     } else {                
                //         dd('else');
                //     $yourData =['status' => false, 'gmail_status' => false, 'response' => null, 'message' => 'Invalid Mobile Number.'];
                //     return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                // }
            } else {
                $yourData =['status' => true, 'gmail_status' => false, 'response' => null, 'message' => 'Invalid Mobile Number.'];
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            }
        }
    }

    public function facebooklogin(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'email' => 'required',  
        ]); 

        if ($validator->fails()) {        
            $yourData =['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
        
        $user_email = User::where('email',$request->email)->where('register_type','facebook')->first();
        $gmail_email = User::where('email',$request->email)->where('register_type','gmail')->first(); 

   
        $user = User::where('email',$request->email)->first();
        
        if(is_object($user)){

            if($user->phone == ''){
                $userToken=JWTAuth::fromUser($user);
                $jwttoken = $this->respondWithToken($userToken);

                $response = array('token'=>$jwttoken,'details' => $user,'register_status'=> true);

                $yourData = ['status' => true, 'response' => @$response, 'session_id' => '5D6EBEE6-EC04-4776-847D', 'mobile_status' => false,'message' => 'Mobile number not regiserd please register your mobile number.'];
                
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

            }

            if($user->mobile_verify == 0){

                $send_sms =$this->smsotpsend($user->phone);

                $userToken=JWTAuth::fromUser($user);

                $jwttoken = $this->respondWithToken($userToken);

                $response = array('token'=>$jwttoken,'details' => $user,'register_status'=> true);

                $yourData = ['otp_status'=> false,'status' => true, 'response' => $response, 'msg_session_id' => $send_sms['Details'],'message' => 'Mobile number not verified please verify your mobile number.'];
                
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

            }   
            $user->facebook_id = $request->id;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile_verify = 1;
            $user->email_verify = 0;
            $user->pan_status = 0;
            $user->bank_status = 0;
            $user->referral = '';
            $user->ip_address = $_SERVER['REMOTE_ADDR'];
            $user->register_type = 'facebook';
            $user->mobile_verify = '1';
            $user->is_login = 1;
            $user->remember_token = Str::random(40);
            $user->register_device = $request->registerdevice;
            $user->invitecode = Str::random(8);
            $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
            $user->wallet = array('total' => 13, 'winnings' => 0, 'bonus' => 13, 'deposit' => 0);
            $user->status = 1;
            $user->save();
            $userToken=JWTAuth::fromUser($user);
            $jwttoken = $this->respondWithToken($userToken);
            $user = User::where('email',$request->email_verify)->first();
            $response = array('token'=>$jwttoken,'details' => $user,'register_status' => true);
            $yourData = ['status' => true, 'response' => $response, 'message' => 'Login Successfully.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

        }else{

            $user = new User();

            $user->facebook_id = $request->id;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile_verify = 0;
            $user->email_verify = 0;
            $user->pan_status = 0;
            $user->bank_status = 0;
            $user->referral = '';
            $user->ip_address = $_SERVER['REMOTE_ADDR'];
            $user->register_type = 'facebook';
            $user->mobile_verify = '0';
            $user->is_login = 1;
            $user->remember_token = Str::random(40);
            $user->register_device = $request->registerdevice;
            $user->invitecode = Str::random(8);
            $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
            $user->wallet = array('total' => 13, 'winnings' => 0, 'bonus' => 13, 'deposit' => 0);
            $user->status = 1;
            $user->save();
            $userToken=JWTAuth::fromUser($user);
            // $credentials = $request->email;

            $jwttoken = $this->respondWithToken($userToken);
            $response = array('token'=>$jwttoken,'details' => $user);
            $yourData = ['status' => true,'register_status' => false, 'response' => $response, 'message' => 'Register Successfully.','session_id' => '5D6EBEE6-EC04-4776-847D'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

        }

        

        // $user_email = User::where('email',$request->email)->first();

        // $user_email = User::where('email',$request->email)->where('register_type','gmail')->first();

        // $facebook_email = User::where('email',$request->email)->where('register_type','facebook')->first(); 

        // if($user_email['email'] != $request->email && $user_email['email'] != null && $request->email != null){
        //     // return response()->json(['facebook_error' => 'facebook_error']);
        //     $yourData =['facebook_error' => false, 'response' => null, 'message' => 'Your Email Already Gmail Registered..'];
        //     return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        // }
        // else if($facebook_email != null){
        //     if($facebook_email['email'] == $request->email){
        //         $user = User::where('email',  $facebook_email->email)->where('register_type','facebook')->first();
        //         $user->referral = '';
                // $user->facebook_id = $request->id;
                // $user->first_name = $request->first_name;
                // $user->last_name = $request->last_name;
                // $user->email = $request->email;
                // $user->email_verify = 0;
                // $user->smscount = 0;
                // $user->ip_address = $_SERVER['REMOTE_ADDR'];
                // $user->register_type = 'facebook';
                // $user->mobile_verify = '1';
                // $user->is_login = 1;
                // $user->remember_token = Str::random(40);
                // $user->invitecode = Str::random(8);
                // $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
                // $user->wallet = array('total' => 13, 'winnings' => 0, 'bonus' => 13, 'deposit' => 0);
                // $user->status = 1;
        //         $user->save();
        //     }
        //     $response = array('id' => $facebook_email->facebook_id);
        //     $yourData =['register_status' => true, 'response' => $response, 'message' => 'Your Email Already Registered','mobile_verify'=>1, 'register_response'=>0];
        //     return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        //     // return response()->json(['register_status' => 'register_success','id' => $facebook_email->facebook_id]);
        // }
        // $response = array('session_id' => '5D6EBEE6-EC04-4776-847D','id' => $request->id,'first_name' => $request->first_name,'email' => $request->email, 'last_name'=>$request->last_name, 'access_token' => $request->access_token,'mobile_verify'=>0, 'register_response'=>1);
        // $yourData =['status' => true, 'response' => $response, 'message' => 'Register Successfully'];
        // return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

        // return response()->json(['status' => 'sms_success' , 'session_id' => '5D6EBEE6-EC04-4776-847D','id' => $request->id,'first_name' => $request->first_name,'email' => $request->email, 'last_name'=>$request->last_name, 'access_token' => $request->access_token]);
    }

    public function facebook_mobile_request(MobileRequest $request)
    {
        // dd($request->all());
        if(@$request->mobile_number != null){
              
                $send_sms =$this->smsotpsend($request);

                if(isset($send_sms)){

                    if($send_sms['Status'] == 'Success')
                    {
                            $user = User::where('email',$request->email)->first();
                        
                            $user = new User();
                            $user->referral = '';
                            $user->facebook_id = $request->id;
                            $user->first_name = $request->first_name;
                            $user->last_name = $request->last_name;
                            $user->email = $request->email;
                            $user->email_verify = 0;
                            $user->smscount = 0;
                            $user->ip_address = $_SERVER['REMOTE_ADDR'];
                            $user->register_type = 'facebook';
                            $user->mobile_number = $request->mobile_number;
                            $user->mobile_verify = '1';
                            $user->remember_token = Str::random(40);
                            $user->invitecode = Str::random(8);
                            $user->is_login = 1;
                            $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
                            $user->wallet = array('total' => 13, 'winnings' => 0, 'bonus' => 13, 'deposit' => 0,'currency' => '₹');
                            $user->status = 1;
                            $user->save();
                            // $token = auth()->attempt($credentials);
                            // return $this->respondWithToken($token);
                        
                        $response = array('msg_session_id' => $send_sms['Details']);
                        $yourData =['status' => true,'register_status' => true, 'response' => $response, 'message' => 'OTP Send Your Registered Mobile Number..'];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                        // return response()->json(['register_status' => 'register_success', 'msg_session_id' => $send_sms['Details']]);
                    } else {
                        $yourData =['status' => false, 'facebook_status' => 'false','response' => null, 'message' => 'Invalid Mobile Number.'];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                        // return response()->json(['status' => 'sms_error']);
                    }
                } else {
                    $yourData =['status' => true, 'facebook_status' => false, 'response' => null, 'message' => 'Invalid Mobile Number.'];
                    return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                }
            }
    }

    public function smsotpsend($data)
    {
        // dd($data);
        $mobile_number = $data->phone;
        $api_key = '6723085f-20da-11ea-9fa5-0200cd9360421';

        $url = "https://2factor.in/API/V1/$api_key/SMS/+91$mobile_number/AUTOGEN";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array();
        $headers[] = "Accept: application/json, text/plain";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (curl_errno($ch)) {
            echo $result = 'Error:' . curl_error($ch);
        } else {
            $result = curl_exec($ch);
        }
        curl_close($ch);
        return json_decode($result, true);
    }

    public function users()
    {
        $user = User::all();

        return response()->json($user);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($userToken)
    {

        $response = array(
            'access_token' => $userToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1440,
            'user' => auth()->user(),
            'status' => 'success');
        return $response;
        // $yourData = ['status' => true, 'response' => $response, 'message' => 'Login Successfully.'];
        // return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

}
