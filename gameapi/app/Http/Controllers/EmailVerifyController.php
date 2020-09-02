<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\User;
use Mail;
use App\Mail\Register;

class EmailVerifyController extends Controller
{
    public function sendEmailDone($email, $verifyToken)
    {
        $user = User::where('email', $email)->first();

         $userEmailVerifyUpdate = User::where(['email' => $email, 'remember_token' => $verifyToken])->update(['email_verify'=>1, 'remember_token' => NULL]);

        if($userEmailVerifyUpdate)
        { 
            return Redirect::to('https://fantasy.demozab.com/emailverify.html');
            // return Redirect::to('https://fantasy.demozab.com/emailverify.html');
        }
        else { 
            return Redirect::to('https://fantasy.demozab.com/emailverify.html');
            // return Redirect::to('https://fantasy.demozab.com/emailverify.html');
        }        
    }

    public function resendEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user)
        {
            Mail::to($user->email)->send(new Register($user));
        }

        echo 1;
    }
}
