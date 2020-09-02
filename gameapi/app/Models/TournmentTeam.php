<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TournmentTeam extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'tournment_teams';


     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'players' => 'array',
    ];

     public function foot_match_details() {
        return $this->hasOne('App\Models\Schedule','key','match_key');
    }

    public function match_players() {
        return $this->hasMany('App\Models\MatchPlayers','match_key','name');
    }

     public function match_details() {
        return $this->hasOne('App\Models\Schedule','session_name','session_name');
    }
  
    public function contests() {
        return $this->belongsTo('App\Models\Contest','contest_id')->with('category');
    }
    
 



}
