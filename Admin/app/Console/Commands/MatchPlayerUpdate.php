<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;
use App\Models\Schedule;
use App\Models\MatchPlayers;

class MatchPlayerUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:matchplayer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
         $record = Schedule::where('status', '!=' ,'completed')
                ->where('type','cricket')
                ->where('key','engaus_2020_one-day_01')
                ->where('match_player_updated',0)
                ->first();

        // foreach ($record as $key => $value) {
            $mplayer = MatchPlayers::where('match_key',$record->key)->exists();

            if(isset($mplayer)){

                $players_list = Player::select('teams','player_key')
                    ->where('player_key','!=','')
                    ->groupBy('player_key')
                    ->get();

            foreach ($players_list as $pkey => $pvalue) {
                if(!empty($pvalue->teams)){
                    
                    $check_player_exist = MatchPlayers::where('match_key',$record->key)->where('player_key',$pvalue->player_key)->first();
                if(!is_object($check_player_exist)){
                    if (in_array($record->team1, $pvalue->teams)) {
                        $MatchPlayers =new MatchPlayers();
                        $MatchPlayers->match_key  = $record->key;
                        $MatchPlayers->player_key = $pvalue->player_key;
                        $MatchPlayers->team_key   = $record->team1_season_key;
                        $MatchPlayers->teamlevel  = 'team1';
                        $MatchPlayers->playing_11 = (int)0;
                        $MatchPlayers->profile    = '';
                        $MatchPlayers->type       = 'cricket';
                        $MatchPlayers->updated_at = '';
                        $MatchPlayers->save();                              
                    }  
                    if (in_array($record->team2, $pvalue->teams)) {
                        $MatchPlayers =new MatchPlayers();
                        $MatchPlayers->match_key  = $record->key;
                        $MatchPlayers->player_key = $pvalue->player_key;
                        $MatchPlayers->team_key   = $record->team2_season_key;
                        $MatchPlayers->teamlevel  = 'team2';
                        $MatchPlayers->playing_11 = (int)0;
                        $MatchPlayers->profile    = '';
                        $MatchPlayers->type       = 'cricket';
                        $MatchPlayers->updated_at = '';
                        $MatchPlayers->save();
                    }   
                }
                }else{
                    echo "no team \n";
                }
              }

              $record->match_player_updated=1;
              $record->save();
            }
        // }

        return response()->json($book);
    }
}
