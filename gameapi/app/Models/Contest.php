<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Contest extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'contests';

    public function category() {
      	return $this->belongsTo('App\Models\ContestsCategory','cat_id');
  	}

  	public function team_details() {
        return $this->hasMany('App\Models\FantasyTeam');
    }
}
