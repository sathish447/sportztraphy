<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPassword;
use App\Http\Requests\LoginOtpRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequests;
use App\Http\Requests\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Carbon;
use App\Mail\Forgetpassword;
use Illuminate\Support\Str;
use App\Models\Subscribers;
use App\Mail\Register;
use App\Mail\Subscribe;


use App\Models\MatchPlayers;
use App\Models\Player;
use App\Models\Wallet;
use App\Models\Schedule;
use App\Models\FantasyTeam;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTManager as JWT;
use App\User;
use Mail;

use Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login','register','subscriber','forgotpassword_submit']]);
    }

    /**
     * register user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [number] mobile_number
     */

    public function register(RegisterRequest $request)
    {

        if(isset($request->referral)){
            $bonus_amount = 50; 
        } else {
            $bonus_amount = 0;
        }
        $password = $request->password;
        $confirmnewpwd = $request->confirm_password;
        $send_sms = $this->smsotpsend($request);
        if($password != $confirmnewpwd)
        {
            $yourData = ['status' =>false, 'password_status' =>false, 'response' => null, 'message' => 'password and confirm password mismatch.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        } else {
            $user = new User();

            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->remember_token = Str::random(40);
            $user->status = 1;
            $user->mobile_verify = 1;
            $user->email_verify = 0;
            $user->pan_status = 0;
            $user->bank_status = 0;
            $user->referral = (isset($request->referral) && $request->referral != '') ? $request->referral : '';
            $user->register_type = $request->registertype;
            $user->register_device = $request->register_device;
            $user->ip_address = $_SERVER['REMOTE_ADDR'];

            $user->invitecode = Str::random(8);
            $user->teamname = strtoupper(substr($request->email, 0, 6)) . date('d') . 'NO' . date('M');
            $user->wallet = array('currency' => '₹', 'total' => 0, 'winnings' => 0, 'bonus' => $bonus_amount, 'deposit' => 0);
            $user->name = '';
            $user->sms_session_id = $send_sms['Details'];
            $user->smscount = 0;  
            $user->image = '';   
            $user->is_login = 0;
            $user->save();

            //  transaction details update dummy

            $this->updateDummyTransaction($user->_id);
            // $this->updateDummycreatetream($user->_id);

            if ($user) {
                Mail::to($user->email)->send(new Register($user));
            }

            $response = array('msg_session_id' => $send_sms['Details']);
            $yourData = ['status' => true, 'response' => $response, 'message' => 'Registered Successfully.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            // if (isset($send_sms)) {
            //     if ($send_sms['Status'] == 'Success') {
            //             $response = array('msg_session_id' => $send_sms['Details']);
            // $yourData = ['status' => true, 'response' => $response, 'message' => 'OTP Send Your Registered Mobile Number.'];
            // return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                    
            //     } else {
            //         $yourData = ['status' => false, 'response' => null, 'message' => 'Invalid Mobile Number.'];
            //         return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            //     }
            // } else {
            //     $yourData = ['status' => false, 'response' => null, 'message' => 'Invalid Mobile Number.'];
            //     return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            // }
        }
    }

    public function smsotpsend($data)
    {
        $mobile_number = $data->phone;
        $api_key = '975ac160-c1b3-11ea-9fa5-0200cd936042';

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

    public function otpVerify(LoginOtpRequest $request)
    {
        $verify_sms = $this->smsotpverify($request);
        if (isset($verify_sms)) {
            if ($verify_sms['Status'] == 'Success') {

                $user_check = User::where('sms_session_id', $request->otp_session_id)->first();
                $user_check->mobile_verify = 1;
                $user_check->save();

                try {
                    if (!$token = auth()->login($user_check)) {
                        $yourData = ['status' => false, 'response' => null, 'message' => 'OTP Invalid.'];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                    }
                } catch (JWTException $e) {
                    $yourData = ['status' => false, 'response' => null, 'message' => 'OTP Invalid.'];
                    return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                }

                return $this->respondWithToken($token);
            } else {
                $yourData = ['status' => false, 'response' => null, 'message' => 'OTP Invalid.'];
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            }
        }
    }

    public function smsotpverify(Request $request)
    {
        $enter_otp = $request->login_otp;
        $otp_session_id = $request->otp_session_id;
        $api_key = '975ac160-c1b3-11ea-9fa5-0200cd936042';

        $url = "https://2factor.in/API/V1/$api_key/SMS/VERIFY/$otp_session_id/$enter_otp";

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

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequests $request)
    {
        $user_check_email = User::where('email', $request->login_input)->first();
        $user_check_phone = User::where('phone', $request->login_input)->first();

        if (is_object($user_check_phone) || is_object($user_check_email)) {

            if (is_object($user_check_phone)) {
                //$yourData = ['status' => false, 'response' => null, 'message' => 'Only login with email.'];
                //return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

                $request->phone = $user_check_phone->phone;
                $send_sms = $this->smsotpsend($request);

                if (isset($send_sms)) {
                    if ($send_sms['Status'] == 'Success') {
                        $user_check_phone->sms_session_id = $send_sms['Details'];
                        $user_check_phone->save();

                        $response = array('type' => 'phone_login', 'msg_session_id' => $send_sms['Details']);
                        $yourData = ['status' => true, 'response' => $response, 'message' => 'OTP send rour mobile number'];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

                        // return response()->json(['status' => 'sms_success', 'msg_session_id' => $send_sms['Details']]);
                    } else {
                        $yourData = ['status' => true, 'response' => null, 'message' => ''];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                        // return response()->json(['status' => 'sms_error']);
                    }
                }
            } elseif (is_object($user_check_email)) {

                $response = array('type' => 'email_login', 'email' => $request->login_input);
                $yourData = ['status' => true, 'response' => $response, 'message' => ''];
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            }
        } else {
            $yourData = ['status' => false, 'response' => null, 'message' => 'Invalid Email.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
    }

    public function password(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        $user_check_email = User::where('email', $request->email)->first();

        if (Hash::check($request->password, $user_check_email->password)) {

            $credentials = request(['email', 'password']);
            try {
                if (!$token = auth()->attempt($credentials)) {
                    // return response()->json(['status' => 'Invalid_credentials'], 401);
                    $yourData = ['status' => false, 'response' => null, 'message' => 'Invalid credentials try'];
                    return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                }
            } catch (JWTException $e) {
                // return response()->json(['status' => 'Invalid_credentials'], 401);
                $yourData = ['status' => false, 'response' => null, 'message' => 'Invalid credentials catch'];
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            }

            if ($user_check_email->mobile_verify == 1) {
                return $this->respondWithToken($token);
            } else {
                $request->phone = $user_check_email->phone;
                $send_sms = $this->smsotpsend($request);
                if (isset($send_sms)) {
                    if ($send_sms['Status'] == 'Success') {
                        $user_check_email->sms_session_id = $send_sms['Details'];
                        $user_check_email->save();

                        $response = array('msg_session_id' => $send_sms['Details']);
                        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

                        // return response()->json(['status' => 'sms_success', 'msg_session_id' => $send_sms['Details']]);
                    } else {

                        $yourData = ['status' => false, 'response' => null, 'message' => 'Invalid credentials not verified mobile'];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                        // return response()->json(['status' => 'sms_error']);
                    }
                }
            }
        } else {
            $yourData = ['status' => false, 'response' => null, 'message' => 'Invalid credentials'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out.']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $response = array(
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1440,
            'user' => auth()->user(),
            'status' => 'success');

        $yourData = ['status' => true, 'response' => $response, 'message' => 'Login Successfully.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken1($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1440,
            'user' => auth()->user(),
            'status' => 'success',
        ]);
    }

    public function users()
    {
        $user = User::all();
        return response()->json($user);
    }

    public function delete_user()
    {
        $user_check_email = User::where('email', 'sathish.pixelweb@gmail.com')->delete();
        dd('deleted');
        return response()->json($user_check_email);
    }

    public function check_mongo()
    {
        $record = Schedule::where('status', '!=' ,'completed')
                ->where('type','cricket')
                ->where('key','cplt20_2020_g23')
                ->first();

        // foreach ($record as $key => $value) {
            $mplayer = MatchPlayers::where('match_key',$record->key)->exists();

            if(isset($mplayer)){

                $players_list = Player::select('teams','player_key')
                    ->where('player_key','!=','')
                    ->groupBy('player_key')
                    ->get();


            foreach ($players_list as $pkey => $pvalue) {
                if(!empty($pvalue->teams)){

                    if (in_array($record->team1, $pvalue->teams)) {
                        $MatchPlayers =new MatchPlayers();
                        $MatchPlayers->match_key  = $record->key;
                        $MatchPlayers->player_key = $pvalue->player_key;
                        $MatchPlayers->team_key   = $record->team1_season_key;
                        $MatchPlayers->teamlevel  = 'team1';
                        $MatchPlayers->playing_11 = (int)0;
                        $MatchPlayers->profile    = '';
                        $MatchPlayers->type       = 'cricket';
                        $MatchPlayers->updated_at = '';
                        $MatchPlayers->save();                              
                    }  

                    if (in_array($record->team2, $pvalue->teams)) {
                        $MatchPlayers =new MatchPlayers();
                        $MatchPlayers->match_key  = $record->key;
                        $MatchPlayers->player_key = $pvalue->player_key;
                        $MatchPlayers->team_key   = $record->team2_season_key;
                        $MatchPlayers->teamlevel  = 'team2';
                        $MatchPlayers->playing_11 = (int)0;
                        $MatchPlayers->profile    = '';
                        $MatchPlayers->type       = 'cricket';
                        $MatchPlayers->updated_at = '';
                        $MatchPlayers->save();                             
                    }   
                }else{
                    echo "no team \n";
                }
                
              }
            }
        // }

        return response()->json($book);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function resetpassword(ForgotPassword $request)
    {
        $email = $request->email;
        $otp = mt_rand();
        if ($email) {
            $forgetsecurity = User::where('email', $email)->first();

            if ($forgetsecurity) {
                $forgetsecurity->forgot_secrect = $get_id = Crypt::encrypt($request->email);
                $forgetsecurity->save();
                $thisUser = User::findOrFail($forgetsecurity->id);
                $this->forgetsendEmail($thisUser);
                return response()->json(['status' => true, 'success' => true, 'response' => null, 'message' => 'A mail has been sent to your registered email.' . $email . 'Please check your inbox/spam for further instructions.'], 200);
            } else {
                $success['error'] = '';
                return response()->json(['status' => false, 'success' => false, 'response' => null, 'message' => 'Email id does not exists.'], 200);
            }
        }
    }

    public function forgetsendEmail($thisUser)
    {
        try {
            Mail::to($thisUser['email'])->send(new Forgetpassword($thisUser));
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function changeresetpassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|string|email',
            'password' => 'required|string|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirmpassword' => 'required|same:password',

        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, 'result' => '', 'message' => $validator->errors()->first()], 200);
        }

        $forgot_secrect = Crypt::decrypt($request->forgot_secrect);
        $otp = $request->otp;
        $password = $request->password;

        //Change Password
        $user = User::where('email',$forgot_secrect)->first();
        if ($user != "") {
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(['result' => '', 'success' => true, 'message' => 'Password changed successfully.'], 200);
        } else {
            return response()->json(['result' => '', 'success' => false, 'message' => 'Email id does not exits.'], 200);
        }
    }

    public function subscriberEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, 'result' => '', 'message' => $validator->errors()->first()], 200);
        }

        try {
            Mail::to($request['email'])->send(new Subscribe());
        } catch (Exception $e) {
            dd($e);
        }

        $ip = $request->getClientIp();
        $check_email = Subscribers::where('email', $request['email'])->exists();

        if ($check_email) {} else {
            $save_details = Subscribers::create([
                'email' => $request['email'],
                'ip' => $ip,
            ]);
        }

        return response()->json(["success" => true, 'result' => '', 'message' => 'Thanks for your subscription.'], 200);
    }


    public function updateDummyTransaction($user_id)
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

        $user = new Transaction();
        $user->orderAmount = '500';
        $user->uid = $user_id;
        $user->txStatus = "SUCCESS";
        $user->paymentMode = 'Wallet';
        $user->orderId = $order_id;
        $user->txTime = Carbon::now();
        $user->type = 'deposit';
        $user->save();


        $user = new Transaction();
        $user->orderAmount = '118';
        $user->uid = $user_id;
        $user->txStatus = "SUCCESS";
        $user->paymentMode = 'Wallet';
        $user->txTime = Carbon::now();
        $user->type = 'join contest';
        $user->save();

    }

    public function updateDummycreatetream($user_id)
    {
        $dummay_players = array(['name' => 'Joe Root',
                                    'player_key' => 'j_root',
                                    'credit' => 9,
                                    'role' => 'bat',
                                    'team_name' => 'ENG',
                                    'caption' => 1,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],
                                ['name' => 'Jofra Chioke Archer',
                                    'player_key' => 'jc_archer',
                                    'credit' => 8.5,
                                    'role' => 'bowl',
                                    'team_name' => 'ENG',
                                    'caption' => 0,
                                    'v_caption' => 1,
                                    'fantasy_points' => 0,
                                ],['name' => 'Jos Buttler',
                                    'player_key' => 'j_buttler',
                                    'credit' => 9.5,
                                    'role' => 'wk',
                                    'team_name' => 'ENG',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Ben Stokes',
                                    'player_key' => 'b_stokes',
                                    'credit' => 9,
                                    'role' => 'ar',
                                    'team_name' => 'ENG',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'CR Woakes',
                                    'player_key' => 'c_woakes',
                                    'credit' => 8.5,
                                    'role' => 'ar',
                                    'team_name' => 'ENG',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Matt Parkinson',
                                    'player_key' => 'mat_parkinson',
                                    'credit' => 8,
                                    'role' => 'bowl',
                                    'team_name' => 'ENG',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Quinton de Kock',
                                    'player_key' => 'de_kock',
                                    'credit' => 8.5,
                                    'role' => 'wk',
                                    'team_name' => 'RSA',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Second',
                                    'player_key' => 'r_second',
                                    'credit' => 8,
                                    'role' => 'ar',
                                    'team_name' => 'RSA',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Kevin Pietersen',
                                    'player_key' => 'ke_petersen',
                                    'credit' => 9.5,
                                    'role' => 'bat',
                                    'team_name' => 'RSA',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Faf du Plessis',
                                    'player_key' => 'du_plessis',
                                    'credit' => 9.5,
                                    'role' => 'bat',
                                    'team_name' => 'RSA',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'B Hendricks',
                                    'player_key' => 'b_hendricks',
                                    'credit' => 9.5,
                                    'role' => 'bowl',
                                    'team_name' => 'RSA',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],
                            );

        $team = new FantasyTeam();

        $team->contest_id = '5def40b3d59ef822b1ba756f';
        $team->match_key = 'rsaeng_2019_t20_03';
        $team->user_id = $user_id;
        $team->user_name = '';
        $team->players = $dummay_players;
           $team->match_type = 'cricket';
        $team->fantasy_points = 0;
        $team->price_update_status = 0;
        $team->winner_status = 0;
        $team->rank = 0;
        $team->cancelled = 0;
        $team->price_winning_amount = 0;
        $team->paid_status = 1;
 
        $team->save();


           $team = new FantasyTeam();

        $team->contest_id = '5def40b3d59ef822b1ba756f';
        $team->match_key = 'rsaeng_2019_t20_02';
        $team->user_id = $user_id;
        $team->user_name = '';
        $team->players = $dummay_players;
           $team->match_type = 'cricket';
        $team->fantasy_points = 0;
        $team->price_update_status = 0;
        $team->winner_status = 0;
        $team->rank = 0;
        $team->cancelled = 0;
        $team->price_winning_amount = 0;
        $team->paid_status = 1;
 
        $team->save();


           $dummay_players = array(['name' => 'Rishabh Pant',
                                    'player_key' => 'r_pant',
                                    'credit' => 9,
                                    'role' => 'wk',
                                    'team_name' => 'IND',
                                    'caption' => 1,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],
                                ['name' => 'Lokesh Rahul',
                                    'player_key' => 'l_rahul',
                                    'credit' => 9.5,
                                    'role' => 'bat',
                                    'team_name' => 'IND',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Shreyas Iyer',
                                    'player_key' => 's_iyer',
                                    'credit' => 9,
                                    'role' => 'bat',
                                    'team_name' => 'IND',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Washington Sundar',
                                    'player_key' => 'was_sundar',
                                    'credit' => 9.5,
                                    'role' => 'ar',
                                    'team_name' => 'IND',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Ravindra Jadeja',
                                    'player_key' => 'r_jadeja',
                                    'credit' => 9.5,
                                    'role' => 'ar',
                                    'team_name' => 'IND',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Jasprit Bumrah',
                                    'player_key' => 'j_bumrah',
                                    'credit' => 9.5,
                                    'role' => 'bowl',
                                    'team_name' => 'IND',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Matt Henry',
                                    'player_key' => 'm_henry',
                                    'credit' => 9.5,
                                    'role' => 'bowl',
                                    'team_name' => 'NZ',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'KS Williamson',
                                    'player_key' => 'k_williamson',
                                    'credit' => 9.5,
                                    'role' => 'bat',
                                    'team_name' => 'NZ',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'N Wagner',
                                    'player_key' => 'n_wagner',
                                    'credit' => 9.5,
                                    'role' => 'bowl',
                                    'team_name' => 'NZ',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'William Somerville',
                                    'player_key' => 'w_somerville',
                                    'credit' => 9.5,
                                    'role' => 'bowl',
                                    'team_name' => 'NZ',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],['name' => 'Jeet Raval',
                                    'player_key' => 'j_raval',
                                    'credit' => 9.5,
                                    'role' => 'bat',
                                    'team_name' => 'NZ',
                                    'caption' => 0,
                                    'v_caption' => 0,
                                    'fantasy_points' => 0,
                                ],
                            );

        $team = new FantasyTeam();

        $team->contest_id = '5def40b3d59ef822b1ba756f';
        $team->match_key = 'nzind_2020_test_01';
        $team->user_id = $user_id;
        $team->user_name = '';
        $team->players = $dummay_players;
           $team->match_type = 'cricket';
        $team->fantasy_points = 0;
        $team->price_update_status = 0;
        $team->winner_status = 0;
        $team->rank = 0;
        $team->cancelled = 0;
        $team->price_winning_amount = 0;
        $team->paid_status = 1;
 
        $team->save();


    }
}