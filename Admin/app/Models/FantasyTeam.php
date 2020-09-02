<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Database\Eloquent\Model;

class FantasyTeam extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'fantasy_teams';

    public function match(){
		return $this->belongsTo('App\Models\Schedule','match_key','key');   
	}
    public function contestinfo(){
		return $this->belongsTo('App\Models\Contest','contest_id','_id');
    }  
     
    // public function contests() {
    //     return $this->belongsTo('App\Models\Contest','contest_id')->with('category');
    // }

    public static function searchList($request)
    {
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = FantasyTeam::with('match')->with('contestinfo')->where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
              // dd($value);
                $q->orWhere('user_name', 'like', "%{$value}%");
                $q->orWhere('contest_name', 'like', "%{$value}%");
                $q->orWhere('team_name', 'like', "%{$value}%");
            }
        })->paginate(15);

        return $users;
    }

    				
}