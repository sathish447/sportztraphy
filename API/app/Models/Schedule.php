<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Schedule extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'schedules';
    //protected $dates = ['start_date'];
 
    public function fantasyteam() {
        return $this->hasMany('App\Models\FantasyTeam','match_key','key');
    }
    public function footfantasyteam() {
        return $this->hasMany('App\Models\FantasyTeam','match_key','match_id');
    }

    public function matchplayer() {
        return $this->belongsTo('App\Models\MatchPlayers','player_key','player_key');
      }

    
}
