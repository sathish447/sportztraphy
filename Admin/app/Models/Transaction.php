<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Transaction extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'transactions';

    public function users()
    {
    	return $this->hasOne('App\Models\User','_id','uid');
    }
    public static function searchList($request)
    {
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = Transaction::with('users')->where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
              // dd($value);
                $q->orWhere('user_name', 'like', "%{$value}%");
                $q->orWhere('paymentMode', 'like', "%{$value}%");
                $q->orWhere('orderAmount', 'like', "%{$value}%");
            }
        })->paginate(15);

        return $users;
    }

}
