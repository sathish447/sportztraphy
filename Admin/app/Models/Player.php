<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Player extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'players';

    public static function searchList($request)
    {
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = Player::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
            	// dd($value);
                $q->orWhere('batting_style', 'like', "%{$value}%");
                $q->orWhere('bowling_style', 'like', "%{$value}%");
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->paginate(15);

        return $users;
    }
}
