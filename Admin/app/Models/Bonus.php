<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Bonus extends Eloquent
{	
    protected $connection = 'mongodb';
    protected $collection = 'bonus';

    public static function searchList($request)
    {
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = Bonus::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('referalbonus', 'like', "%{$value}%");
                $q->orWhere('joinedbonus', 'like', "%{$value}%");
            }
        })->paginate(15);

        return $users;
    }
    public static function managesearchList($request)
    {
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = User::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('teamname', 'like', "%{$value}%");
            }
        })->paginate(15);

        return $users;
    }
}
