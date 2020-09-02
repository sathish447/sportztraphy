<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Kyc;
use App\Models\Country;
use App\Models\FantasyTeam;
use App\Http\Requests\PanRequest;
use App\Http\Requests\BankRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileInfoRequest;
use App\Http\Requests\InviteRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use App\Mail\Sendverification;
use App\Mail\Invite;
use Mail;
use Hash; 

class UserController extends Controller
{
    public function userdata()
    {
        $user_details = User::where('_id', auth()->user()->_id)->first();
        $teams = FantasyTeam::where('user_id',auth()->user()->_id)->get();
        $wins=$matches=$contests=0;
        $matchArr = [];
        $contestArr = [];
        foreach($teams as $fteam):
            if($fteam->winner_status ==1) $wins ++;
            if(!in_array($fteam->match_key, $matchArr)) $matchArr[] = $fteam->match_key;
            if(!in_array($fteam->contest_id, $contestArr)) $contestArr[] = $fteam->contest_id;
        endforeach;
        $matches = count($matchArr);
        $contests = count($contestArr);
        $details  = array(            
            'id'        => $user_details->_id,
            'useremail' => $user_details->email,
            'userphone' => $user_details->phone,
            'mobile_verify' => $user_details->mobile_verify,
            'email_verify' => $user_details->email_verify,
            'deposit'   => number_format($user_details->wallet['deposit'], 2, '.', ''),
            'bonus'     => number_format($user_details->wallet['bonus'], 2, '.', ''),
            'winnings'  => number_format($user_details->wallet['winnings'], 2, '.', ''),
            'balance'   => number_format($user_details->wallet['total'], 2, '.', ''),
            'username' => $user_details->teamname,
            'dob' => date('d/m/Y',strtotime($user_details->dob)),
            // 'dob_pan' => date('d/m/Y',strtotime($user_details->pan->dob_pan)),
            'invite_code' => $user_details->invitecode,
            'name' => $user_details->name,
            'state' => $user_details->state,
            'pan' => $user_details->pan,
            'bank' => $user_details->bank,
            'pan_status' => $user_details->pan_status,
            'bank_status' => $user_details->bank_status,
            'gender' => isset($user_details->gender)?$user_details->gender:"",
            'image' => (isset($user_details->image)) ? $user_details->image :"",
            /// Additional info for mobile tob section ////
            'wins'=>$wins,
            'matches' =>$matches,
            'contests'=>$contests,
            'series'=>0,
        );
        return response()->json($details);
    }

     /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
    */

    public function UploadProfileValidator(array $data) {
        $validator = Validator::make($data, [
            'profile' => 'required|mimes:jpeg,jpg,png|max:8192'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 200);
        }
    }

    public function upload_profile(Request $request)
    {
        $input = $request->all();
        $response = $this->UploadProfileValidator($input);
        if ($response) {          
            $yourData =['status' => false, 'response' => null, 'message' => $response->original];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
        $user_id = auth()->user()->_id;
        $check_enable_user = User::where(['_id' => $user_id, 'status' => 1])->first();
        // if(!is_object($check_enable_user)) {
        //     $yourData =['status' => false, 'response' => null, 'message' => 'Your account has been suspended by admin.'];
        //     return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        // }
        if($this->imgvalidaion($_FILES['profile']['tmp_name']) == 1) {            
            $front = Input::File('profile');
            
            $dir = 'profile/';
            $path = 'storage' . DIRECTORY_SEPARATOR .'app'. DIRECTORY_SEPARATOR.'public'. DIRECTORY_SEPARATOR. $dir;
            $location = 'public' . DIRECTORY_SEPARATOR .'storage'. DIRECTORY_SEPARATOR. $dir;
            $filenamewithextension = $front->getClientOriginalName();
            $photnam = str_replace('.','',microtime(true));
            $filename = pathinfo($photnam, PATHINFO_FILENAME);
            $extension = $front->getClientOriginalExtension();
            $photo = $filename.'.'. $extension;            
            $front->move($path, $photo);

            $front_img = $path.$photo;
            $check_enable_user->image = url($front_img);
            $check_enable_user->save();
            $yourData =['status' => true, 'response' => url($front_img), 'message' => 'Profile Image Updated Successfully.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        } else {
                $yourData =['status' => false, 'response' => null, 'message' =>'Profile Not Updated. Try Again.'];
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
    }

    public function pan_submit(PanRequest $request)
    {
        // dd($request->all());
        $user = User::where('_id', auth()->user()->_id)->first();

        if($request->file('upload_pan_image')){

            $dir = 'pan/';
            $path = 'storage' . DIRECTORY_SEPARATOR .'app'. DIRECTORY_SEPARATOR.'public'. DIRECTORY_SEPARATOR. $dir;
            $location = 'public' . DIRECTORY_SEPARATOR .'storage'. DIRECTORY_SEPARATOR. $dir;
            $fornt = $request->file('upload_pan_image');
            $filenamewithextension = $fornt->getClientOriginalName();
            $photnam = str_replace('.','',microtime(true));
            $filename = pathinfo($photnam, PATHINFO_FILENAME);
            $extension = $fornt->getClientOriginalExtension();
            $photo = $filename.'.'. $extension;
            $fornt->move($path, $photo);
            $pan_proof = $photo;
        } 

        $user->pan = ['pan_number' => $request->pan_number,
                      'pan_name' => $request->pan_name,
                      'dob_pan' => date('d-m-Y',strtotime($request->dob_pan)),
                      'pan_proof' => url('storage/app/public/pan/'.$pan_proof),
                    ];
        $user->pan_status = 1;
        $user->save();
        
        $yourData = ['status' => true, 'response' => null, 'message' => 'Updated Successfully.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function view_pan_details(Request $request)
    {
        $user = User::where('_id', auth()->user()->_id)->first();

        $response = array('details' => $user->pan, 'pan_verify_status' =>($user->pan_status)?$user->pan_status:'0' );
        
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        // return response()->json(['status' => 'success']);
    }

    public function bank_submit(BankRequest $request)
    {
        // dd($request->all());
        $file = $request->file('upload_bank_image');
        $user = User::where('_id', auth()->user()->_id)->first();

        // $book = new pan(['pan_number' => '', 'pan_name' => '' ]);
        
    if($request->bank_name =='' || $request->account_number == '' ||  $request->branch ==''){ //$request->ifsc ==''
     $yourData = ['status' => false, 'response' => null, 'message' => 'All fields are required!!!'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }   
    // $request->ifsc = 'HDFC0000001';
        $bankdata = [
            'name' => $request->bank_name,
            'phone' => $user->phone,
            'bankAccount' => $request->account_number,
            'ifsc' => $request->ifsc_code 
        ];
      if($request->file('upload_bank_image')){

            $dir = 'bank/';
            $path = 'storage' . DIRECTORY_SEPARATOR .'app'. DIRECTORY_SEPARATOR.'public'. DIRECTORY_SEPARATOR. $dir;
            $location = 'public' . DIRECTORY_SEPARATOR .'storage'. DIRECTORY_SEPARATOR. $dir;
            $fornt = $request->file('upload_bank_image');
            $filenamewithextension = $fornt->getClientOriginalName();
            $photnam = str_replace('.','',microtime(true));
            $filename = pathinfo($photnam, PATHINFO_FILENAME);
            $extension = $fornt->getClientOriginalExtension();
            $photo = $filename.'.'. $extension;
            $fornt->move($path, $photo);
            $bank_proof = $photo;
        }
        if($request->account_number != $request->retype_account_number){            

            $yourData = ['status' => false, 'response' => null, 'message' => 'account_number_mismatch.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

    $check_curl = $this->cashfree_curl('validation/bankDetails',$bankdata);
    if($check_curl !=''){  // need to change == ''
     $yourData = ['status' => false, 'response' => null, 'message' => 'Invalid response'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    if($check_curl !=''  && $check_curl->status == 'ERROR' && $rObcheck_curlj->message == 'Token is not valid'){
     
    $check_curl = $this->cashfree_curl('validation/bankDetails',$bankdata);
    }   
    if($check_curl !=''  && $check_curl->status =='SUCCESS' && ($check_curl->message == 'Invalid ifsc provided')) {
        $yourData = ['status' => false, 'response' => null, 'message' => $check_curl->message ];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    else if($check_curl !=''  && $check_curl->status =='ERROR'){
        $yourData = ['status' => false, 'response' => null, 'message' => $check_curl->message ];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
        
    else{ 
        $rand = rand(100,100000);
        $rand = $rand+1;
	$username = explode(' ',$user->teamname);
	$username = count($username) > 0 ? $username[0] : 'test';
        $beneid=$username.$rand;
    // dd($beneid);
	$benedata = ['beneid'=>$beneid,'name'=>$user->teamname,'email'=>$user->email,'phone'=>$user->phone,'address1'=>'test'];
        $add_bene = $this->cashfree_curl('payout/v1/addBeneficiary',$bankdata);
        
        $user->bank = [
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'branch' => $request->branch,
            'ifsc' => $request->ifsc_code,
            'bank_proof' => url('storage/app/public/bank/'.$bank_proof),
            'beneid' => $beneid
        ];
        $user->bank_status = 1;
        $user->save();
        // dd($user);
        $benedata = ['beneid'=>$beneid,'name'=>$user->teamname,'email'=>$user->email,'phone'=>$user->phone,'address1'=>'test'];

        $add_bene = $this->cashfree_curl('payout/v1/addBeneficiary',$benedata);
        // dd($add_bene);
        if($add_bene !='' && $add_bene['status']== 'SUCCESS' && $add_bene['subCode'] == 200){
	     $user->bank = [
		'bene_status' => 1
	     ];
	     $user->save();
        }	
        $yourData = ['status' => true, 'response' => null, 'message' => 'Updated Successfully.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
       }
    }

    public function Auth_API(){  
        
    $ch = curl_init();
    $header = array(
        "X-Client-Id: CF10554D0KIWPV02LAQ2Q2",
        "X-Client-Secret: 2b52fc4c542418b759bf077961b3c0b4998b1fce",
        "cache-control: no-cache"
      );

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL,"https://payout-gamma.cashfree.com/payout/v1/authorize");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header); 
    
    $response = curl_exec($ch);
    $err = curl_error($ch); 
     if ($err) {
      echo "cURL Error #:" . $err;
    } else {
        // dd('else');
     curl_close($ch);
     $res = json_decode($response, true);
     return $res;
    }
     
    }
 
    public function cashfree_curl($url, $data){
        // dd($data);
        $baseUrls = 'https://payout-gamma.cashfree.com/'; 
        $finalUrl = $baseUrls.$url;
        $response = $this->Auth_API(); 
    	$token = $response['data']['token'];
        // dd($token);
        $headers = array( 
        'Authorization: Bearer '.$token,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        if(!is_null($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
        $r = curl_exec($ch);
        if(curl_errno($ch)){
        print('error in posting');
        print(curl_error($ch));
        die();
        }
        curl_close($ch);
        $rObj = json_decode($r, true);    
        return $rObj;
    }

    public function view_bank_details(Request $request)
    {
        $user = User::where('_id', auth()->user()->_id)->first();
        $response = array('details' => $user->bank, 'bank_verify_status' =>($user->bank_status)?$user->bank_status:'0');
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        // return response()->json(['status' => 'success']);
    }

    public function profile_info_submit(ProfileInfoRequest $request)
    {
        // dd($request->all());
        $user = User::where('_id', auth()->user()->_id)->first();
        $user->name = $request->name;
        $user->state = $request->state;
        $user->dob = date('Y-m-d',strtotime($request->dob));
        $user->gender = $request->gender;
        $user->save();

        $yourData = ['status' => true, 'response' => $user, 'message' => 'Updated Successfully.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        // return response()->json(['status' => 'success']);
    }


    public function changepassword(ChangePasswordRequest $request)
    {
        // dd($request->all());
        $oldpwd = $request->oldpassword;
        $newpwd = $request->newpassword;
        $confirmnewpwd = $request->confirmpassword;

        $user = User::where('_id', auth()->user()->_id)->first();
        $hashedPassword = $user->password;

        if (Hash::check($oldpwd, $hashedPassword)) {
            if ($newpwd == $confirmnewpwd) {
                $data = ['password' =>  bcrypt($newpwd)];
                $user = \App\User::where('_id', auth()->user()->_id)->update($data);
                if ($user) {

                    $yourData = ['status' => true, 'response' => null, 'message' => 'Updated Successfully.'];
                    return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                    
                } else  {
                    
                    $yourData = ['status' =>false, 'response' => null, 'message' => 'Invalid Current password.'];
                    return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                }
            } else {
                $yourData = ['status' =>false, 'confirm_status' => false, 'response' => null, 'message' => 'password and confirm password mismatch.'];
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            }
        } else {
            $yourData = ['status' =>false, 'response' => null, 'message' => 'Wrong old password.'];
                    return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        // return response()->json(['status' => 'success']);
    }

    public function send_verification_mail()
    {
        $user = User::where('_id', auth()->user()->_id)->first();

        if ($user) {
            Mail::to($user->email)->send(new Sendverification($user));
        }
        $user->email_verify = 1;
        $user->save();
        $yourData = ['status' => true, 'response' => null, 'message' => 'Mail sent to registered mail-id. please check your inbox/spam.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function twoFactorStatus()
    {
        $twoFactorStatus = User::where('id', auth()->user()->id)->first();
        return response()->json($twoFactorStatus->google2fa_status);
    }

    public function google2FaImage()
    {
        $user = User::where('id', auth()->user()->id)->first();

        $google2fa = app('pragmarx.google2fa');

        if ($user->google2fa_secret == '') {
            $secret = $google2fa->generateSecretKey();
            $user->google2fa_secret = $secret;
            $user->save();
        } else {
            $secret = $user->google2fa_secret;
        }

        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return response()->json($QR_Image);
    }

    public function google2FaVerify(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();

        if ($request->googlecode != '') {
            $google_code = $request->input('googlecode');
            $user = User::where('id', auth()->user()->id)->first();
            $secret = $user->google2fa_secret;
            $google2fa = app('pragmarx.google2fa');
            $data = $google2fa->verifyKey(
                $secret,
                $google_code,
                config('window')
            );

            if ($data) {

                $user->google2fa_status = 1;
                $user->save();

                return response()->json($user);
            } else {

                return response()->json(['error' => 'You entered wrong authentication code.'], 401);
            }
        } else {
            return response()->json(['error' => 'Enter a authentication code.'], 401);
        }
    }

    public function kycStatus()
    {
        $kycStatus = Kyc::userKycStatus(auth()->user()->id);

        return response()->json($kycStatus->kyc_verify);
    }

    public function country()
    {
        $country = Country::index();

        return response()->json($country);
    }

    public function imgvalidaion($img)
    {
        $myfile = fopen($img, "r") or die("Unable to open file!");
        $value = fread($myfile,filesize($img));
        if (strpos($value, "<?php") !== false) {
            $img = 0;
        } 
        elseif (strpos($value, "<?=") !== false){
            $img = 0;
        }
        elseif (strpos($value, "eval") !== false) {
            $img = 0;
        }
        elseif (strpos($value,"<script") !== false) {
            $img = 0;
        }else{
            $img=1;
        }
        fclose($myfile);
        return $img;
    }
    public function invite_friends(InviteRequest $request)
    {
        $user_details = User::where('_id', auth()->user()->_id)->first();
        $user_details->email = $request->email;
        $user_details->invitecode = $user_details->invitecode;
        $user_details->teamname = $request->username;
        // dd($user_details);
        Mail::to($request->email)->send(new Invite($user_details));

        $yourData = ['status' => true, 'response' => null, 'message' => 'Email Send Successfully.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function profile_upload(Request $request)
    {
        // dd($request->all());
        if(isset($request->upload_profile_image)){
            $user_details = User::where('_id', auth()->user()->_id)->first();
            if($request->file('upload_profile_image')){

                $dir = 'profile/';
                $path = 'storage' . DIRECTORY_SEPARATOR .'app'. DIRECTORY_SEPARATOR.'public'. DIRECTORY_SEPARATOR. $dir;
                $location = 'public' . DIRECTORY_SEPARATOR .'storage'. DIRECTORY_SEPARATOR. $dir;
                $fornt = $request->file('upload_profile_image');
                $filenamewithextension = $fornt->getClientOriginalName();
                $photnam = str_replace('.','',microtime(true));
                $filename = pathinfo($photnam, PATHINFO_FILENAME);
                $extension = $fornt->getClientOriginalExtension();
                $photo = $filename.'.'. $extension;
                $fornt->move($path, $photo);
                $profile_proof = $photo;
            } 
            $user_details->image = url('storage/app/public/profile/'.$profile_proof);
            $user_details->save();
            $yourData = ['status' => true, 'response' => null, 'message' => 'Your profile Image changed Successfully.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        } else {
            $yourData = ['status' => false, 'response' => null, 'message' => 'Please upload image before save.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

    }
}
