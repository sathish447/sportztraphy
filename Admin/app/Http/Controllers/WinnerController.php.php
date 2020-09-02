<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\MatchPlayers;
use App\Models\FantasyTeam;
use App\Models\Player;
use App\Models\Contest;

class WinnerController extends Controller
{
    public function winner_balance_update()
    {
        $team_details = FantasyTeam::where('price_update_status',0)->get();

        foreach($team_details as $value){
            
            $dbmatch = Schedule::where('key',$value->match_key)->where('status', 'completed')->first();

            if(is_object($dbmatch)){
                dd($dbmatch);
            }
        }


    }
}
