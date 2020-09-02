<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Database\Eloquent\Model;

class Contest extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'contests';

    public function category()
    {
        return $this->hasone('App\Models\ContestsCategory','_id','cat_id'); 
    }
    
    public function fantasyteam()
    {
        return $this->hasone('App\Models\FantasyTeam','contest_id','_id'); 
    }			
    public static function searchList($request)
    {
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = Contest::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('cat_name', 'like', "%{$value}%");
                $q->orWhere('contest_name', 'like', "%{$value}%");
            }
        })->paginate(15);

        return $users;
    }
}
