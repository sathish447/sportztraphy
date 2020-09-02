<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MatchPlayers extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'match_players';

   public function schedule() {
    return $this->belongsTo('App\Models\Schedule','match_key','key');
   }
  
   public function match() {
    return $this->hasOne('App\Models\Schedule','match_key','key');
   }
	
  public static function playList($match_key){

	$lists = MatchPlayers::on('mysql2')->with('schedule')->where(['match_key'=>$match_key,'playing_11'=>0])->get();
	$datas = []; 
	if($lists->count() > 0){
	   foreach($lists as $list){
		$player = Player::find($list->player_key);
 		$datas[] = ['name'=>$player->name,'profimg'=>'','credit_points'=>$player->credit_value];			
           }  			
	}
	return $datas;
  }		
}
