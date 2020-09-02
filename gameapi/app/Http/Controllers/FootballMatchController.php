<?php

namespace App\Http\Controllers;

use App\Models\FantasyTeam;
use App\Models\Schedule;
use App\Models\SockerTeam;
use App\Models\Contest;
use App\Models\MatchPlayers;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class FootballMatchController extends Controller
{
    public function myfootballmatchdetails()
    {
         $lists = FantasyTeam::with('foot_match_details')
            ->where('user_id', auth()->user()->_id)
            // ->where('paid_status', 1)
            ->where('match_type', 'football')
            ->orderBy('created_at', 'DESC')
            ->groupBy('match_key')
            ->get(
                ['created_at', 'user_id', 'user_name', 'fantasy_points', 'price_update_status', 'winner_status', 'rank', 'price_winning_amount', 'paid_status']
            );
        // dd($lists);
        $data = array();
        $upindex = $liveindex = $compindex = '0';
        $upcount = $livecount = $compcount = '0';

        foreach ($lists as $key => $value) {
            $footlogo1 = SockerTeam::where('short_code',@$value->foot_match_details->team1_key)->get();
            $footlogo2 = SockerTeam::where('short_code',@$value->foot_match_details->team2_kay)->get();
            $end = Carbon::parse(@$value->foot_match_details->start_date)->setTimezone('Asia/Kolkata');
            //$start = Carbon::now();
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';
            $date = explode('T', @$value->foot_match_details->start_date);
            if (@$value->foot_match_details->status == 'notstarted') {

                $data[$key]['left_time'] = strtotime($date[0]);
                $data[$key]['start_date'] = $value->foot_match_details->start_date;

                $dt = new DateTime(@$value->foot_match_details->start_date);
                $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
                $data[$key]['start_indian_date'] = $dt->format('d-m-Y H:i:s');
                $data[$key]['time_left'] = $day . $hour . $diff->i . ' minutes left';
            } else {
                $data[$key]['start_indian_date'] = @$value->foot_match_details->status;
                $data[$key]['start_date'] = @$value->foot_match_details->status;
                $data[$key]['left_time'] = @$value->foot_match_details->status;
                $data[$key]['time_left'] = @$value->foot_match_details->status;
            }

            $text_status = '';

            if ($value->cancelled == 1) {
                $text_status = 'Cancelled';
            } else {
                if ($value->paid_status == 1) {
                    $text_status = 'Joined';
                } else {
                    $text_status = 'Not Joined';
                }
            }
            foreach ($footlogo1 as $key => $logovalue1) {
                foreach ($footlogo2 as $key => $logovalue2) {
                    if (@$value->foot_match_details->status == 'notstarted') {

                        $upcount++;

                        $data['upcomming'][$upindex]['format'] = $value->foot_match_details->format;
                        $data['upcomming'][$upindex]['match_key'] = $value->match_key;
                        $data['upcomming'][$upindex]['contest_id'] = $value->contest_id;
                        $data['upcomming'][$upindex]['players'] = $value->players;
                        $data['upcomming'][$upindex]['season_name'] = $value->foot_match_details->season_name;
                        $data['upcomming'][$upindex]['name'] = $value->foot_match_details->name;
                        $data['upcomming'][$upindex]['team1_key'] = $value->foot_match_details->team1_key;
                        $data['upcomming'][$upindex]['team2_key'] = $value->foot_match_details->team2_kay;
                        $data['upcomming'][$upindex]['status'] = $value->foot_match_details->status;
                        $data['upcomming'][$upindex]['text_status'] = $text_status;
                        $data['upcomming'][$upindex]['time_left'] = $day . $hour . $diff->i . ' minutes';
                        $data['upcomming'][$upindex]['footteamlogo1'] = @$logovalue1->logo_path;
                        $data['upcomming'][$upindex]['footteamlogo2'] = @$logovalue2->logo_path;
                        $upindex++;
                    } elseif (@$value->foot_match_details->status == 'completed') {

                        $compcount++;

                        $data['completed'][$compindex]['format'] = $value->foot_match_details->format;
                        $data['completed'][$compindex]['match_key'] = $value->foot_match_details->match_id;
                        $data['completed'][$compindex]['contest_id'] = $value->contest_id;
                        $data['completed'][$compindex]['players'] = $value->players;
                        $data['completed'][$compindex]['season_name'] = $value->foot_match_details->season_name;
                        $data['completed'][$compindex]['name'] = $value->foot_match_details->name;
                        $data['completed'][$compindex]['team1_key'] = $value->foot_match_details->team1_key;
                        $data['completed'][$compindex]['team2_key'] = $value->foot_match_details->team2_kay;
                        $data['completed'][$compindex]['status'] = $value->foot_match_details->status;
                        $data['completed'][$compindex]['text_status'] = $text_status;
                        $data['completed'][$compindex]['time_left'] = $day . $hour . $diff->i . ' minutes';
                        $data['completed'][$upindex]['footteamlogo1'] = @$logovalue1->logo_path;
                        $data['completed'][$upindex]['footteamlogo2'] = @$logovalue2->logo_path;
                        $compindex++;

                    } elseif (@$value->foot_match_details->status == 'started') {

                        $livecount++;

                        $data['live'][$liveindex]['format'] = $value->foot_match_details->format;
                        $data['live'][$liveindex]['match_key'] = $value->match_key;
                        $data['live'][$liveindex]['contest_id'] = $value->contest_id;
                        $data['live'][$liveindex]['players'] = $value->players;
                        $data['live'][$liveindex]['season_name'] = $value->foot_match_details->season_name;
                        $data['live'][$liveindex]['name'] = $value->foot_match_details;
                        $data['live'][$liveindex]['team1_key'] = $value->foot_match_details->team1_key;
                        $data['live'][$liveindex]['team2_key'] = $value->foot_match_details->team2_kay;
                        $data['live'][$liveindex]['status'] = $value->foot_match_details->status;
                        $data['live'][$liveindex]['text_status'] = $text_status;
                        $data['live'][$liveindex]['time_left'] = $day . $hour . $diff->i . ' minutes';
                        $data['live'][$upindex]['footteamlogo1'] = @$logovalue1->logo_path;
                        $data['live'][$upindex]['footteamlogo2'] = @$logovalue2->logo_path;
                        $liveindex++;
                    }
                }
            }                  
        }

        $data['live_length'] = $livecount;
        $data['up_length'] = $upcount;
        $data['comp_length'] = $compcount;

        $auth_id = auth()->user()->_id;
        $yourData = ['status' => true, 'response' => $data, 'authid' => $auth_id, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function footmymatch()
    {
        $lists = FantasyTeam::with('foot_match_details')
            ->where('user_id', auth()->user()->_id)
            ->groupBy('match_key')
            ->where('match_type', 'football')
            ->orderBy('created_at', 'DESC')
            ->get(['created_at', 'user_id', 'user_name', 'fantasy_points', 'price_update_status', 'winner_status', 'rank', 'price_winning_amount', 'paid_status']);
        $data = array();

        foreach ($lists as $key => $value) {
                
            if (@$value->foot_match_details->status == 'notstarted') {
                $end = Carbon::parse(@$value->foot_match_details->start_date)->setTimezone('Asia/Kolkata');
                //$start = Carbon::now();
                $diff = $end->diff(now());
                $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
                $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';

                $date = explode('T', @$value->foot_match_details->start_date);
                $data[$key]['left_time'] = strtotime($date[0]);
                $data[$key]['start_date'] = @$value->foot_match_details->start_date;

                $dt = new DateTime(@$value->foot_match_details->start_date);
                $dt->setTimezone(new DateTimezone('Asia/kolkata'));
                $data[$key]['start_indian_date'] = $dt->format('d-m-Y H:i:s');
                $data[$key]['time_left'] = $day . $hour . $diff->i . ' minutes left';
            } else {
                $data[$key]['start_indian_date'] = @$value->foot_match_details->status;
                $data[$key]['start_date'] = @$value->foot_match_details->status;
                $data[$key]['left_time'] = @$value->foot_match_details->status;
                $data[$key]['time_left'] = @$value->foot_match_details->status;
            }

            $date = explode('T', $value->created_at);

            $data[$key]['format'] = @$value->foot_match_details->format;
            $data[$key]['match_key'] = $value->match_key;
            $data[$key]['contest_id'] = $value->contest_id;
            $data[$key]['players'] = $value->players;
            $data[$key]['season_name'] = @$value->foot_match_details->season_name;
            $data[$key]['short_name'] = @$value->foot_match_details->short_name;
            $data[$key]['name'] = @$value->foot_match_details->name;
            $data[$key]['team1_key'] = @$value->foot_match_details->team1_key;
            $data[$key]['team2_key'] = @$value->foot_match_details->team2_kay;
            $data[$key]['status'] = @$value->foot_match_details->status;
            $data[$key]['created_at'] = $date[0];
            // $footlogo1 = SockerTeam::where('short_code',$value->foot_match_details->team1_key)->get();
            // $footlogo2 = SockerTeam::where('short_code',$value->foot_match_details->team2_kay)->get();
            // foreach ($footlogo1 as $key => $logovalue1) {
            //     foreach ($footlogo2 as $key => $logovalue2) {
            //         $data[$key]['footteamlogo1'] = @$logovalue1->logo_path;
            //         $data[$key]['footteamlogo2'] = @$logovalue2->logo_path;
                    
            //     }
            // }
            $data[$key]['team1_flag_url'] = 'https://fantasyapi.demozab.com/assets/images/flag/' . @$value->foot_match_details->team1_key . '.svg';
            $data[$key]['team2_flag_url'] = 'https://fantasyapi.demozab.com/assets/images/flag/' . @$value->foot_match_details->team2_kay . '.svg';
        }

        $auth_id = auth()->user()->_id;
        $response = array('mymatch' => $data);
        $yourData = ['status' => true, 'response' => $response, 'authid' => $auth_id, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footmatchDetails(Request $request)
    {
        // dd($request->all());
        // $match_key = 'qtrlt10_2019_g1';
        $match_key = $request->id;
        $contest_id = $request->contestid;
        $details = Schedule::where('match_id', $match_key)->first();
        $details->foot_logo1 = SockerTeam::where('short_code',$details->team1_key)->first();
        $details->foot_logo2 = SockerTeam::where('short_code',$details->team2_kay)->first();
        // dd($details);
        // $end = Carbon::parse($details->start_date);
        // $end->setTimezone('Asia/kolkata');
        // $start = Carbon::now();
        // $diff = $end->diff(now());
        // $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
        // $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';
        // $details->left_time =  $day . $hour . $diff->i . ' minutes';

        $lists = FantasyTeam::where('match_key', $match_key)
            ->where('contest_id', $contest_id)
            ->get();

        $data = array();

        // foreach ($lists as $key => $value) {

        //     $player_stats = [];
        //     $players = $value['players'];
        //     $player_stats['fantasy_points'] = $value['fantasy_points'];
        //     foreach ($players as $key => $player) {
        //         $match_players = MatchPlayers::where('match_key',$value['match_key'])->where('player_key',$player['player_key'])->first();
        //         if(is_object($match_players)){
        //             // if($player['caption'] != 1 && $player['v_caption'] != 1){
        //                 $match_players->fantasy_points = $value['fantasy_points'];
        //                 $match_players->save();
        //             // }
        //         } else {

        //         }
        //     }
        // }
        // $player_stats = MatchPlayers::where('match_key', $match_key)->get();

        // dd($lists);
        /*foreach ($players as $key => $player) {
        // echo 'key = '.$key.'<br />';
        // $playerss = $player['name'];
        // $playerss_points = $player['fantasy_points'];

        foreach ($match_players as $keys => $player_details) {

        // echo $player_details['player_key'].'='.$player['player_key'].'<br />';
        // echo 'keys = '.$keys.'<br />';
        if($player_details['player_key'] == $player['player_key']){
        // echo 'keys = '.$keys.'<br />';
        $fantasy_points['player_key'] =  $player['fantasy_points'];
        // $player_details->fantasy_points =  $player['fantasy_points'];
        $data[$player_details['player_key']] =  $player['fantasy_points'];
        }
        else{
        $data[$player_details['player_key']] =  '0';
        }

        }

        $player_stats[] = $player_details;
        }*/

        if ($details->status == 'not started') {
            $end = Carbon::parse($details->start_date)->setTimezone('Asia/Kolkata');
            //$start = Carbon::now();
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';

            $date = explode('T', $details->start_date);
            // $data[$key]['left_time'] = strtotime($date[0]);
            // $data[$key]['start_date'] = $value->match_details->start_date;

            $dt = new DateTime($details->start_date);
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
            // $data[$key]['start_indian_date'] = $dt->format('d-m-Y H:i:s');
            // $data[$key]['time_left'] = $day . $hour . $diff->i . ' minutes left';
            $details->left_time = $day . $hour . $diff->i . 'minutes left';
        } else {
            $details->left_time = $details->status;
        }

        $team_name = FantasyTeam::where('user_id', auth()->user()->_id)
            ->where(['match_key' => $match_key])
            ->where(['contest_id' => $contest_id])
            ->where(['match_type' => 'football'])
            ->first();

        // dd($team_name);
        $lists = FantasyTeam::where('match_key', $match_key)
            ->where('contest_id', $contest_id)
            ->where(['match_type' => 'football'])
            ->get();
        // dd($lists);
        // foreach ($lists as $key => $value) {
        //     $players = $value['players'];
        //         $match_players = MatchPlayers::where('match_key',$value['match_key'])->get();
        //         foreach ($match_players as $key => $value) {
        //                     $lists = FantasyTeam::where('match_key', $match_key)
        //                     ->where('contest_id', $contest_id)
        //                     ->get();
        //         }
        //         // dd($match_players->player_details);
        //     // $players
        // }

        $contes_count = Contest::where('created_by', 'admin')->get();
        foreach ($contes_count as $value) {

            // ->where(['match_key' => $match_key])
            // ->first();
            $listcount = FantasyTeam::where('contest_id', $value->_id)->count();
            $spotcount = FantasyTeam::where('contest_id', $request->contest_id)
                ->where('match_key', $request->id)
                ->count();
            $teamcount = $value->contest_size - $listcount;
            $range = $listcount / $value->contest_size * 100;
            $range = 100 - $range;

            $value->total_contest = $teamcount;
            $value->range = $range;
        }
        $auth_id = auth()->user()->_id;
        $yourData = ['status' => true,
            'response' => $details,
            'fantasypoints' => $lists,
            'team_name' => $team_name,
            'auth_id' => $auth_id,
            // 'teamcount' => $teamcount,
            // 'listcount' => $listcount,
            // 'spotcount' => $spotcount,
            // 'contestcount' => $contes_count,
            // 'range' => $range,
            'message' => ''];

        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footcreateTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        // $match_key = 'indwi_2019_t20_02';
        $match_key = (int)$request->id;
        // dd($match_key);
        // $lists = MatchPlayers::where('type','football')->get();
        // dd($lists[0]);
        $lists = MatchPlayers::where('match_key',$match_key)->with('footschedule')->with('footplayer')->where('type','football')->get();
        // dd($lists);
        $data = array();
        $gkindex = $stindex = $midindex = $defindex = '0';
        $data['selectteal1count'] = 6;
        $data['selectteal2count'] = 5;

        foreach ($lists as $key => $value) { 
            $player_team_name = '';

            if ($value->teamlevel == 'team1') {
                $player_team_name = @$value->footschedule->team1_key;
            } else if ($value->teamlevel == 'team2') {
                $player_team_name = @$value->footschedule->team2_kay;
            }
            // dd(@$value->footschedule->start_date);
            $data['short_name'] = @$value->footschedule->team1_key . ' vs ' . @$value->footschedule->team2_kay;
            $date = explode('T', @$value->footschedule->start_date);
            if($date[0] != '' && $date[0] != null){
                $data['left_time'] = $date[0];
            } else {
                $data['left_time'] = null;
            }
            $data['start_date'] = @$value->footschedule->start_date;
            $start = @$value->footschedule->start_date;

            $dt = new DateTime(@$value->footschedule->start_date);
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
            $data['start_indian_date'] = $dt->format('d-m-Y H:i:s');

            $profile = 'player.svg';

            if (isset($value->profile) && $value->profile != '') {
                $profile = $value->profile;
            }
            if ($value->role == 'Goalkeeper') {
                $data['gkeeper'][$gkindex]['name'] = $value->name;
                $data['gkeeper'][$gkindex]['credit_value'] = (double)$value->credit_value;
                $data['gkeeper'][$gkindex]['player_key'] = strval($value->player_key);
                $data['gkeeper'][$gkindex]['team'] = $value->teamlevel;
                $data['gkeeper'][$gkindex]['player_team_name'] = $player_team_name;
                $data['gkeeper'][$gkindex]['playing_11'] = $value->playing_11;
                $data['gkeeper'][$gkindex]['profile'] = $value->profile;
                $gkindex++;
            } elseif ($value->role == 'Midfielder') {
                $data['mfielder'][$midindex]['name'] = $value->name;
                $data['mfielder'][$midindex]['credit_value'] = (double)$value->credit_value;
                $data['mfielder'][$midindex]['player_key'] = strval($value->player_key);
                $data['mfielder'][$midindex]['team'] = $value->teamlevel;
                $data['mfielder'][$midindex]['player_team_name'] = $player_team_name;
                $data['mfielder'][$midindex]['playing_11'] = $value->playing_11;
                $data['mfielder'][$midindex]['profile'] = $value->profile;
                $midindex++;
            } elseif ($value->role == 'Defender') {
                $data['defe'][$defindex]['name'] = $value->name;
                $data['defe'][$defindex]['credit_value'] = (double)$value->credit_value;
                $data['defe'][$defindex]['player_key'] = strval($value->player_key);
                $data['defe'][$defindex]['team'] = $value->teamlevel;
                $data['defe'][$defindex]['player_team_name'] = $player_team_name;
                $data['defe'][$defindex]['playing_11'] = $value->playing_11;
                $data['defe'][$defindex]['profile'] = $value->profile;
                $defindex++;
            } elseif ($value->role == 'Attacker' || $value->role == 'Forward') {
                $data['attacker'][$stindex]['name'] = $value->name;
                $data['attacker'][$stindex]['credit_value'] = (double)$value->credit_value;
                $data['attacker'][$stindex]['player_key'] = strval($value->player_key);
                $data['attacker'][$stindex]['team'] = $value->teamlevel;
                $data['attacker'][$stindex]['player_team_name'] = $player_team_name;
                $data['attacker'][$stindex]['playing_11'] = $value->playing_11;
                $data['attacker'][$stindex]['profile'] = $value->profile;
                $stindex++;
            }
        }

        $yourData = ['status' => true, 'response' => $data, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footsaveteam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_key' => 'required',
            'player' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
        // Update or create team based on the team_id in param list
        if (isset($request->team_id) && $request->team_id != '') {
            $team = FantasyTeam::find($request->team_id);
        } else {
            $team = new FantasyTeam();
            $team->contest_id = '';
        }
        
        $match_key = $request->match_key;

        $team->match_key = $match_key;
        $team->user_id = auth()->user()->_id;
        $team->user_name = auth()->user()->teamname;
        $team->players = $request->player;
        $team->fantasy_points = 0;
        $team->profile = $request->profile;
        $team->match_type = "football"; 
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
        $yourData = ['status' => true, 'response' => $response, 'message' => 'Team created. Select Captain and Vise Captain.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footsavecvcteam(Request $request)
    {
        // dd($request->all());
        $details = FantasyTeam::where('_id', $request->teamid)->first();
        // dd($details);
        $options = $details->players;

        foreach ($options as $key => $player) {
            if ($player['name'] == $request->captaion) { // captain asisn
                $options[$key]['caption'] = 1;
            } elseif ($player['name'] == $request->vicecaptaion) { //vise  captain asisn
                $options[$key]['v_caption'] = 1;
            }
        }
        // $user_details = User::find(auth()->user()->_id);

        if ($details->paid_status == 1) {
            $details->paid_status = 1;
        } else {
            $details->paid_status = 0;
        }
        $details->players = $options;
        $details->save();

        $response = array('contestid' => $details->contest_id, 'matchkey' => $details->match_key, 'user_id' => auth()->user()->_id);

        $yourData = ['status' => true, 'response' => $response, 'message' => 'Team created join contest.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

    }
    public function footselectcvcteam(Request $request)
    {
        $details = FantasyTeam::where('_id', $request->id)->where('match_type','football')->first();
        // dd($details);
        $yourData = ['status' => true, 'response' => $details, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footmyviewteam(Request $request)
    {
            $details = FantasyTeam::where('_id', $request->team_id)->first();
            $gkindex = $midindex = $defindex = $stindex = '0';
            $data = array();

            if (is_object($details)) {

                foreach ($details->players as $key => $value) {

                    if ($value['role'] == 'gk') {
                        $data['gk'][$gkindex]['name'] = $value['name'];
                        $data['gk'][$gkindex]['credit'] = @$value['credit'];
                        $data['gk'][$gkindex]['caption'] = $value['caption'];
                        $data['gk'][$gkindex]['visecaption'] = $value['v_caption'];
                        $data['gk'][$gkindex]['player_key'] = $value['player_key'];
                        $data['gk'][$gkindex]['profile'] = (isset($value['profile'])&& $value['profile']!='')?$value['profile']:url("/images/user.png");;
                        $gkindex++;
                    } elseif ($value['role'] == 'mid') {
                    $data['mid'][$midindex]['name'] = $value['name'];
                    $data['mid'][$midindex]['credit'] = @$value['credit'];
                    $data['mid'][$midindex]['caption'] = $value['caption'];
                    $data['mid'][$midindex]['visecaption'] = $value['v_caption'];
                    $data['mid'][$midindex]['player_key'] = $value['player_key'];
                    $data['mid'][$midindex]['profile'] = (isset($value['profile'])&& $value['profile']!='')?$value['profile']:url("/images/user.png");;
                    $midindex++;
                } elseif ($value['role'] == 'def') {
                    $data['def'][$defindex]['name'] = $value['name'];
                    $data['def'][$defindex]['credit'] = @$value['credit'];
                    $data['def'][$defindex]['caption'] = $value['caption'];
                    $data['def'][$defindex]['visecaption'] = $value['v_caption'];
                    $data['def'][$defindex]['player_key'] = $value['player_key'];
                    $data['def'][$defindex]['profile'] = (isset($value['profile'])&& $value['profile']!='')?$value['profile']:url("/images/user.png");;
                    $defindex++;
                } elseif ($value['role'] == 'st') {
                    $data['st'][$stindex]['name'] = $value['name'];
                    $data['st'][$stindex]['credit'] = @$value['credit'];
                    $data['st'][$stindex]['caption'] = $value['caption'];
                    $data['st'][$stindex]['visecaption'] = $value['v_caption'];
                    $data['st'][$stindex]['player_key'] = $value['player_key'];
                    $data['st'][$stindex]['profile'] = (isset($value['profile'])&& $value['profile']!='')?$value['profile']:url("/images/user.png");;
                    $stindex++;
                }
            }
        }

        $total_created_list = FantasyTeam::where('match_key', $request->id)->where('match_type','football')->where('user_id', auth()->user()->_id)->get();

        $tlist = array();
        foreach ($total_created_list as $ind => $totallist) {
            $tlist[$ind]['created_at'] = $totallist->created_at;
            $tlist[$ind]['match_key'] = $totallist->match_key;
            $tlist[$ind]['team_id'] = $totallist->_id;
            foreach ($totallist->players as $payer) {
                if ($payer['caption'] == 1) {
                    $tlist[$ind]['cname'] = $payer['name'];
                }
                if ($payer['v_caption'] == 1) {
                    $tlist[$ind]['vcname'] = $payer['name'];
                }
            }
        }
        // dd($tlist);

        $response = array('details' => $data, 'tlist' => $tlist);

        $yourData = ['status' => true, 'response' => $response, 'message' => ''];

        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footselectviewteam(Request $request)
    {
        $details = FantasyTeam::where('_id', $request->team_id)->where('match_type','football')->where('match_key', $request->match_key)->where('user_id', auth()->user()->_id)->first();

        $gkindex = $midindex = $defindex = $stindex = '0';
        $data = array();

        if (is_object($details)) {

            foreach ($details->players as $key => $value) {
                if ($value['role'] == 'gk') {
                    $data['gk'][$gkindex]['name'] = $value['name'];
                    $data['gk'][$gkindex]['credit'] = isset($value['credit'])?$value['credit']:0;
                    $data['gk'][$gkindex]['caption'] = $value['caption'];
                    $data['gk'][$gkindex]['visecaption'] = $value['v_caption'];
                    $data['gk'][$gkindex]['profile'] = (isset($value['profile'])&& $value['profile']!='')?$value['profile']:url("/images/user.png");
                    $gkindex++;
                } elseif ($value['role'] == 'mid') {
                    $data['mid'][$midindex]['name'] = $value['name'];
                    $data['mid'][$midindex]['credit'] = isset($value['credit'])?$value['credit']:0;
                    $data['mid'][$midindex]['caption'] = $value['name'];
                    $data['mid'][$midindex]['visecaption'] = $value['v_caption'];
                    $data['mid'][$midindex]['profile'] = (isset($value['profile'])&& $value['profile']!='')?$value['profile']:url("/images/user.png");
                    $midindex++;
                } elseif ($value['role'] == 'def') {
                    $data['def'][$defindex]['name'] = $value['name'];
                    $data['def'][$defindex]['credit'] = isset($value['credit'])?$value['credit']:0;
                    $data['def'][$defindex]['caption'] = $value['caption'];
                    $data['def'][$defindex]['visecaption'] = $value['v_caption'];
                    $data['def'][$defindex]['profile'] = (isset($value['profile'])&& $value['profile']!='')?$value['profile']:url("/images/user.png");
                    $defindex++;
                } elseif ($value['role'] == 'st') {
                    $data['st'][$stindex]['name'] = $value['name'];
                    $data['st'][$stindex]['credit'] = isset($value['credit'])?$value['credit']:0;
                    $data['st'][$stindex]['caption'] = $value['caption'];
                    $data['st'][$stindex]['visecaption'] = $value['v_caption'];
                    $data['st'][$stindex]['profile'] = (isset($value['profile'])&& $value['profile']!='')?$value['profile']:url("/images/user.png");
                    $stindex++;
                }
            }
        }

        $yourData = ['status' => true, 'response' => $data];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footeditTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        // $match_key = 'indwi_2019_t20_02';
        $match_key = (int)$request->id;
        $schedule_match_key = $request->id;

        $lists = MatchPlayers::where('match_key', $match_key)->where('type','football')->with('footschedule')->with('footplayer')->get();
        $data = array();
        $gkindex = $midindex = $defindex = $stindex = '0';
        $data['player_count'] = 0;

        $data['selectteal2count'] = 5;
        $data['selectteal1count'] = 6;

        $data['gk_player_count'] = 0;
        $data['mid_player_count'] = 0;
        $data['def_player_count'] = 0;
        $data['st_player_count'] = 0;
        $data['total_selected_credit_score'] = 100;

        $details = Schedule::where('match_id', $schedule_match_key)->first();
        // dd($details);
        $team_short = explode('vs', $details->short_name);

        $check_selected_player = FantasyTeam::where('match_key', $request->id)
            ->where('_id', $request->team_id)->first();
        // $check_selected_player = FantasyTeam::where('match_type', 'cricket')
        //     ->first();
        $array_data = array();
        $tempplayer = array();

        foreach ($check_selected_player->players as $vv) {

            $array_data[] = $vv['player_key'];
            if (str_replace(' ', '', $vv['team_name']) == str_replace(' ', '', strtolower($team_short[0]))) {
                $data['selectteal1count']--;
            } elseif (str_replace(' ', '', $vv['team_name']) == str_replace(' ', '', strtolower($team_short[1]))) {
                $data['selectteal2count']--;
            }

            if ($vv['role'] == 'gk') {
                $data['gk_player_count']++;
            } elseif ($vv['role'] == 'mid') {
                $data['mid_player_count']++;
            } elseif ($vv['role'] == 'mid') {
                $data['def_player_count']++;
            } elseif ($vv['role'] == 'st') {
                $data['st_player_count']++;
            }
        }

        foreach ($lists as $key => $value) {

            $selected = 0;

            if (in_array($value->player_key, $array_data)) {
                $selected = 1;

                $data['total_selected_credit_score'] = $value->credit_value;

                if ($value->role == 'Goalkeeper') {
                    $role = 'gk';
                } else if ($value->role == 'Midfielder') {
                    $role = 'mid';
                } else if ($value->role == 'Defender') {
                    $role = 'def';
                } else if ($value->role == 'Forward') {
                    $role = 'st';
                }

                $data['tempplayer'][$data['player_count']] = array(
                    'name' => $value->name,
                    'player_key' => $value->player_key,
                    'role' => $role,
                    'team_name' => auth()->user()->teamname,
                    'caption' => '0',
                    'v_caption' => '0',
                    'fantasy_points' => '0',
                );

                $data['player_count']++;

            }

            $player_team_name = '';

            if ($value->teamlevel == 'team1') {
                $player_team_name = @$value->footschedule->team1_key;
            } else if ($value->teamlevel == 'team2') {
                $player_team_name = @$value->footschedule->team2_kay;
            }

            $data['short_name'] = @$value->footschedule->team1_key . ' vs ' . @$value->footschedule->team2_kay;
            $date = explode('T', @$value->footschedule->start_date);
            $data['left_time'] = strtotime($date[0]);
            $data['start_date'] = @$value->footschedule->start_date;
            $start = @$value->footschedule->start_date;

            $dt = new DateTime(@$value->footschedule->start_date);
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
            $data['start_indian_date'] = $dt->format('d-m-Y H:i:s');
                if ($value->role == 'Midfielder') {

                    $data['mid'][$midindex]['name'] = $value->name;
                    $data['mid'][$midindex]['credit_value'] = @$value->credit_value;
                    $data['mid'][$midindex]['player_key'] = $value->player_key;
                    $data['mid'][$midindex]['team'] = $value->teamlevel;
                    $data['mid'][$midindex]['player_team_name'] = $player_team_name;
                    $data['mid'][$midindex]['selected'] = $selected;
                    $data['mid'][$midindex]['profile'] = (isset($value->profile)&& $value->profile!='')?$value->profile:url("/images/user.png");
                    $midindex++;

                } elseif ($value->role == 'Defender') {
                    $data['def'][$defindex]['name'] = $value->name;
                    $data['def'][$defindex]['credit_value'] =  @$value->players->credit_value;
                    $data['def'][$defindex]['player_key'] = $value->player_key;
                    $data['def'][$defindex]['team'] = $value->teamlevel;
                    $data['def'][$defindex]['player_team_name'] = $player_team_name;
                    $data['def'][$defindex]['selected'] = $selected;
                    $data['def'][$defindex]['profile'] = (isset($value->profile)&& $value->profile!='')?$value->profile:url("/images/user.png");
                    $defindex++;

                } elseif ($value->role == 'Forward') {
                    $data['st'][$stindex]['name'] = $value->name;
                    $data['st'][$stindex]['credit_value'] =  @$value->credit_value;
                    $data['st'][$stindex]['player_key'] = $value->player_key;
                    $data['st'][$stindex]['team'] = $value->teamlevel;
                    $data['st'][$stindex]['player_team_name'] = $player_team_name;
                    $data['st'][$stindex]['selected'] = $selected;
                    $data['st'][$stindex]['profile'] = (isset($value->profile)&& $value->profile!='')?$value->profile:url("/images/user.png");
                    $stindex++;

                } elseif ($value->role == 'Goalkeeper') {
                    $data['gk'][$gkindex]['name'] = $value->name;
                    $data['gk'][$gkindex]['credit_value'] =  @$value->credit_value;
                    $data['gk'][$gkindex]['player_key'] = $value->player_key;
                    $data['gk'][$gkindex]['team'] = $value->teamlevel;
                    $data['gk'][$gkindex]['player_team_name'] = $player_team_name;
                    $data['gk'][$gkindex]['selected'] = $selected;
                    $data['gk'][$gkindex]['profile'] = (isset($value->profile)&& $value->profile!='')?$value->profile:url("/images/user.png");
                    $gkindex++;

                }
        }

        $yourData = ['status' => true, 'response' => $data, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footupdateTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teamid' => 'required',
            'match_key' => 'required',
            'player' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $team = FantasyTeam::find($request->teamid);
        $team->players = (isset($request->request_type) && $request->request_type == 'mobile') ? json_decode($request->player) : $request->player;
        $team->save();

        $response = array('team' => $team);
        $yourData = ['status' => true, 'response' => $response, 'message' => 'Team created select captain snd vise captain.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

}
