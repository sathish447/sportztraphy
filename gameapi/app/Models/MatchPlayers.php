<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\Player;

class MatchPlayers extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'match_players';
    
  public static function playerList($match){

	$lists = MatchPlayers::where(['match_key'=>$match_key])->get(); // ->where('playing_11',1)
	$datas = []; 
 	
	if($lists->count() > 0){

	   foreach($lists as $list){
 
		$player = Player::where('player_key',$list->player_key)->first(); 
		if($list->teamlevel == 'team1')
 		  $datas['team1'][]  = ['name'=>$player->name,'profimg'=>'','credit_points'=>$list->credit_value,'match_points'=>$list->match_points,'type'=>'Team1','playing_11'=>$list->playing_11];
		else
		  $datas['team2'][]  = ['name'=>$player->name,'profimg'=>'','credit_points'=>$list->credit_value,'match_points'=>$list->match_points,'type'=>'Team2','playing_11'=>$list->playing11];			
           }  			
	}
 
	return  $datas;
  }

  public function schedule() {
    return $this->hasOne('App\Models\Schedule','key','match_key');
  }
  
  public function footschedule() {
    return $this->hasOne('App\Models\Schedule','key','match_key');
  }
  
  public function footplayer() {
    return $this->hasOne('App\Models\Player','player_id','player_key');
  }

  public function player() {
    return $this->hasOne('App\Models\Player','player_key','player_key');
  }

  public function player_details() {
    return $this->belongsTo('App\Models\FantasyTeam','match_key','player_key');
  }

  public function created_team_details() {
    return $this->belongsTo('App\Models\FantasyTeam','match_key','match_key');
  }

}	
