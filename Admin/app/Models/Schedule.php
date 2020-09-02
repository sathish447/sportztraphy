<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

//use Illuminate\Database\Eloquent\Model;

class Schedule extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'schedules';

   public function players() {
    return $this->hasMany(MatchPlayers::class);
   }

   public function available_players() {
        return $this->players()->where(['match_key'=>'indwi_2019_t20_02','playing_11'=>0]);
   }

   public function fantasyteam(){
      return $this->hasMany('App\Models\FantasyTeam','key','match_key');
   }  

   public static function searchList($request)
    {
        // $users_data = User::orderBy('_id', 'desc')->get();
        //  $users = User::orderBy('_id', 'desc')->paginate(15);
        $q = $request->searchitem;

        $searchValues = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $users = Schedule::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
              // dd($value);
                $q->orWhere('name', 'like', "%{$value}%");
                $q->orWhere('short_name', 'like', "%{$value}%");
                $q->orWhere('status', 'like', "%{$value}%");
            }
        })->paginate(15);

        return $users;
    }
  
}