<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class HeroTeam extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'heros_teams';


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


}
