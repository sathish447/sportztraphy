<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\MatchPlayers;
use App\Models\HeroTeam;
use App\User;
use DateTime;
use DateTimeZone;

class HerosController extends Controller
{
    public function playerlist(Request $request)
    {
    	 $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        // $match_key = 'indwi_2019_t20_02';
        $match_key = $request->id;

        $lists = MatchPlayers::where('match_key', $match_key)
                ->with('schedule')
                ->with('player')
                ->where('type','cricket')
                ->get();    

        $data = array();
        $batindex = $bowlindex = $arindex = $wkindex = $playerindx = '0';
     

        foreach ($lists as $key => $value) {

            $player_team_name = '';

            if ($value->teamlevel == 'team1') {
                $player_team_name = isset($value->schedule->team1_key) ? $value->schedule->team1_key : '';
            } else if ($value->teamlevel == 'team2') {
                $player_team_name = isset($value->schedule->team2_kay) ? $value->schedule->team2_kay : '';
            }

            $data['short_name'] = @$value->schedule->team1_key . ' vs ' . @$value->schedule->team2_kay;
            $date = explode('T', @$value->schedule->start_date);
            $data['left_time'] = strtotime($date[0]);
            $data['start_date'] = @$value->schedule->start_date;
            $start = @$value->schedule->start_date;

            $dt = new DateTime(@$value->schedule->start_date);
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
            $data['start_indian_date'] = $dt->format('d-m-Y H:i:s');

            $profile = 'player.jpg';

            if (isset($value->player->profile) && $value->player->profile != '') {
                $profile = $value->player->profile;
            }

             $credit_value = 8.5;

            if (isset($value->player->credit_value) && $value->player->credit_value != '') {
                $credit_value = $value->player->credit_value;
            }

         if($value->player != ''){
                $data['player'][$playerindx]['name'] = $value->player->name;
                $data['player'][$playerindx]['credit_value'] = $credit_value;
                $data['player'][$playerindx]['player_key'] = $value->player_key;
                $data['player'][$playerindx]['team'] = $value->teamlevel;
                $data['player'][$playerindx]['player_team_name'] = $player_team_name;
                $data['player'][$playerindx]['playing_11'] = $value->playing_11;
                $data['player'][$playerindx]['profile'] = url('/images/players/'.$profile);

                $playerindx++;
            }

            
        }

        $yourData = ['status' => true, 'response' => $data, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function saveheros(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'match_key' => 'required',
            'player' => 'required',
        ]);

        $type = (isset($request->type) && $request->type!='')?$request->type:"cricket";

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
        
        /// Update or create team based on the team_id in param list
        if (isset($request->team_id) && $request->team_id != '') {
            $team = HeroTeam::find($request->team_id);
            if (isset($request->contest_id) && $request->contest_id != '') // update contest_id if provided
            {
                $team->contest_id = $request->contest_id;
            }
        } else {
            $team = new HeroTeam();
            $team->contest_id = '';
        }

        $team->match_key = $request->match_key;
        $team->user_id = auth()->user()->_id;
        $team->user_name = auth()->user()->teamname;
        $team->players = (isset($request->request_type) && $request->request_type == 'mobile') ? json_decode($request->player) : $request->player;
        $team->match_type = $request->type;
        $team->fantasy_points = 0;
        $team->price_update_status = 0;
        $team->winner_status = 0;
        $team->rank = 0;
        $team->cancelled = 0;
        $team->price_winning_amount = 0;
        if ($team->paid_status == 1) {
            $team->paid_status = 1;
        } else {
            $team->paid_status = 0;
        }
        $team->save();
        $response = array('team' => $team);
        $yourData = ['status' => true, 'response' => $response, 'message' => 'Team created.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);   
    }
}
