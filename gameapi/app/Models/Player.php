<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Database\Eloquent\Model;

class Player extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'players';

 
    public function matchplayer() {
        return $this->belongsTo('App\Models\MatchPlayers','player_key','player_key');
      }
    
}
