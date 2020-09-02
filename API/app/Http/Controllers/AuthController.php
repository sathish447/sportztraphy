<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPassword;
use App\Http\Requests\LoginOtpRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequests;
use App\Http\Requests\ResetPassword;
use App\Mail\Forgetpassword;
use App\Mail\Register;
use App\Mail\Subscribe;
use App\Models\Subscribers;
use App\Models\Wallet;
use App\Models\Schedule;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTManager as JWT;

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
        $api_key = '6723085f-20da-11ea-9fa5-0200cd9360421';

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
        // dd($request->all());
        $user_check_email = User::where('email', $request->login_input)->first();
        $user_check_phone = User::where('phone', $request->login_input)->first();

        if (is_object($user_check_phone) || is_object($user_check_email)) {

            if (is_object($user_check_phone)) {
                        $yourData = ['status' => false, 'response' => null, 'message' => 'Only login with email.'];
                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

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
        $user = User::where('email', 'sathish.pixelweb@gmail.com')->first();

        $book = new wallet(['currency' => 'EUR', 'amount' => 10]);

        // $book = $user->wallet()->save($book);
        // or
        $book = $user->Wallet()->create(['currency' => 'EUR', 'amount' => 10]);

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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|string|email',
            'password' => 'required|string|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirmpassword' => 'required|same:password',

        ]);
        if ($validator->fails()) {
            return response()->json(["success" => false, 'result' => '', 'message' => $validator->errors()->first()], 200);
        }

        $forgot_secrect = Crypt::decrypt($request->forgot_secrect);
        // dd($forgot_secrect);
        $otp = $request->otp;
        $password = $request->password;

        //Change Password
        $user = User::where('email',$forgot_secrect)->first();
        // dd($user);
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
}