<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\FantasyTeam;
use App\Models\MatchPlayers;
use App\Models\SockerTeam;
use App\Models\Player;
use App\Models\Schedule;
use App\Models\HeroTeam;
use App\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class MatchController extends Controller
{
    public function allmatch()
    {
        $details = Schedule::where('status', '!=', 'completed')->where('status', '!=', 'started')->where('type', '!=', 'football')->orderBy('start_date')->get();
        foreach ($details as $data) {

            $end = Carbon::parse($data->start_date)->setTimezone('Asia/Kolkata');
            //$start = Carbon::now();
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';
            $data->left_time = $day . $hour . $diff->i . ' minutes+';
            $data->start_date = strtotime($end);

            $data->team1_flag = 'https://fantasyapi.demozab.com/assets/images/flag/' . $data->team1_key . '.svg';
            $data->team2_flag = 'https://fantasyapi.demozab.com/assets/images/flag/' . $data->team2_kay . '.svg';
           
        }

        $response = array('matchdetails' => $details);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function footmatches()
    {
        $details = Schedule::where('status', '!=', 'completed')->where('type','football')->where('status', '!=', 'started')->orderBy('start_date')->get();

        foreach ($details as $data) {
            $end = Carbon::parse($data->start_date)->setTimezone('Asia/Kolkata');

            //$start = Carbon::now();
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';
            $data->left_time = $day . $hour . $diff->i . ' minutes';

            $dt = new DateTime($data->start_date);
            $dt->setTimezone(new DateTimezone('Asia/kolkata'));
            $data->start_indian_date = $dt->format('d-m-Y H:i:s');

            $data->team1_flag = 'https://fantasyapi.demozab.com/assets/images/flag/' . $data->team1_key . '.svg';
            $data->team2_flag = 'https://fantasyapi.demozab.com/assets/images/flag/' . $data->team2_kay . '.svg';
            $footlogo1 = SockerTeam::where('short_code',$data->team1_key)->first();
            $footlogo2 = SockerTeam::where('short_code',$data->team2_kay)->first();
            if(is_object($footlogo1))
                $data->footteamlogo1 = $footlogo1->logo_path;
            if(is_object($footlogo2))
                $data->footteamlogo2 = $footlogo2->logo_path;
        }

        $response = array('matchdetails' => $details);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function footmymatches()
    {
        // $details = Schedule::where('type','football')->orderBy('start_date')->get();
        $lists = FantasyTeam::with('match_details')
            ->where('user_id', auth()->user()->_id)
            ->with('foot_match_details')
            ->where('paid_status', 1)
            ->where('match_type', 'football')
            ->orderBy('created_at', 'DESC')
            ->groupBy('match_key')
        // ->distinct('match_key')
            ->get(
                ['created_at', 'user_id', 'user_name', 'fantasy_points', 'price_update_status', 'winner_status', 'rank', 'price_winning_amount', 'paid_status','players']
            );
        // $details = Schedule::where('type','football')->get();
        $data = array();

        foreach ($lists as $key => $value) {
            if ($value->foot_match_details->status == 'notstarted') {
                $end = Carbon::parse($value->foot_match_details->start_date)->setTimezone('Asia/Kolkata');
                //$start = Carbon::now();
                $diff = $end->diff(now());
                $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
                $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';

                $date = explode('T', $value->foot_match_details->start_date);
                $data[$key]['left_time'] = strtotime($date[0]);
                $data[$key]['start_date'] = $value->foot_match_details->start_date;

                $dt = new DateTime($value->foot_match_details->start_date);
                $dt->setTimezone(new DateTimezone('Asia/kolkata'));
                $data[$key]['start_indian_date'] = $dt->format('d-m-Y H:i:s');
                $data[$key]['time_left'] = $day . $hour . $diff->i . ' minute left';
            } else {
                $data[$key]['start_indian_date'] = $value->foot_match_details->status;
                $data[$key]['start_date'] = $value->foot_match_details->status;
                $data[$key]['left_time'] = $value->foot_match_details->status;
                $data[$key]['time_left'] = $value->foot_match_details->status;
            }

            $date = explode('T', $value->created_at);

            $data[$key]['format'] = $value->foot_match_details->format;
            $data[$key]['match_key'] = $value->match_key;
            $data[$key]['contest_id'] = $value->contest_id;
            $data[$key]['players'] = $value->players;
            $data[$key]['season_name'] = $value->foot_match_details->season_name;
            $data[$key]['short_name'] = $value->foot_match_details->short_name;
            $data[$key]['name'] = $value->foot_match_details->name;
            $data[$key]['team1_key'] = $value->foot_match_details->team1_key;
            $data[$key]['team2_key'] = $value->foot_match_details->team2_kay;
            $data[$key]['status'] = $value->foot_match_details->status;
            $data[$key]['created_at'] = $date[0];

            $data[$key]['team1_flag_url'] = 'https://fantasyapi.demozab.com/assets/images/flag/' . $value->foot_match_details->team1_key . '.svg';
            $data[$key]['team2_flag_url'] = 'https://fantasyapi.demozab.com/assets/images/flag/' . $value->foot_match_details->team2_kay . '.svg';
        }

        $auth_id = auth()->user()->_id;
        $response = array('mymatch' => $data);
        $yourData = ['status' => true, 'response' => $response, 'authid' => $auth_id, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function allmatchlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $details = Schedule::where('status', '!=', 'completed')->where('status', '!=', 'started')->offset((int) $request->offset)->limit((int) ($request->limit))->get();
        foreach ($details as $data) {
            $end = Carbon::parse($data->start_date)->setTimezone('Asia/Kolkata');
            //$start = Carbon::now();
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';
            $data->left_time = $day . $hour . $diff->i . ' minutes';

            $dt = new DateTime($data->start_date);
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
            $data->start_indian_date = $dt->format('d-m-Y H:i:s');

            $data->team1_flag = 'https://fantasyapi.demozab.com/assets/images/flag/' . $data->team1_key . '.svg';
            $data->team2_flag = 'https://fantasyapi.demozab.com/assets/images/flag/' . $data->team2_kay . '.svg';
        }

        $response = array('matchdetails' => $details);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function mymatch(Request $request)
    {
        $type = (isset($request->type) && $request->type!='') ? $request->type : "cricket";
        $lists = FantasyTeam::with('match_details')
            ->where('user_id', auth()->user()->_id)
            ->where('paid_status', 1)
            ->where('match_type', $type)
            ->orderBy('created_at', 'DESC')
            ->groupBy('match_key')
            ->get(
                ['created_at', 'user_id', 'user_name', 'fantasy_points', 'price_update_status', 'winner_status', 'rank', 'price_winning_amount', 'paid_status']
            );


        $data = array();

        foreach ($lists as $key => $value) {

            if (@$value->match_details->status == 'notstarted') {
                $end = Carbon::parse($value->match_details->start_date)->setTimezone('Asia/Kolkata');
                //$start = Carbon::now();
                $diff = $end->diff(now());
                $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
                $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';

                $date = explode('T', $value->match_details->start_date);
                $data[$key]['left_time'] = strtotime($date[0]);
                $data[$key]['start_date'] = $value->match_details->start_date;

                $dt = new DateTime($value->match_details->start_date);
                $dt->setTimezone(new DateTimezone('Asia/kolkata'));
                $data[$key]['start_indian_date'] = $dt->format('d-m-Y H:i:s');
                $data[$key]['time_left'] = $day . $hour . $diff->i . ' minutes left';
            } else {
                $data[$key]['start_indian_date'] = @$value->match_details->status;
                $data[$key]['start_date'] = @$value->match_details->status;
                $data[$key]['left_time'] = @$value->match_details->status;
                $data[$key]['time_left'] = @$value->match_details->status;
            }

            $date = explode('T', $value->created_at);

            $data[$key]['format'] = $value->match_details->format;
            $data[$key]['match_key'] = $value->match_details->match_key;
            $data[$key]['contest_id'] = $value->match_details->contest_id;
            $data[$key]['players'] = $value->match_details->players;
            $data[$key]['season_name'] = $value->match_details->season_name;
            $data[$key]['short_name'] = $value->match_details->short_name;
            $data[$key]['name'] = $value->match_details->name;
            $data[$key]['team1_key'] = $value->match_details->team1_key;
            $data[$key]['team2_key'] = $value->match_details->team2_kay;
            $data[$key]['status'] = @$value->match_details->status;
            $data[$key]['created_at'] = $date[0];

            $data[$key]['team1_flag_url'] = 'https://fantasyapi.demozab.com/assets/images/flag/' . $value->match_details->team1_key . '.svg';
            $data[$key]['team2_flag_url'] = 'https://fantasyapi.demozab.com/assets/images/flag/' . $value->match_details->team2_kay . '.svg';

            $footlogo1 = SockerTeam::where('short_code',$value->match_details->team1_key)->first();
            $footlogo2 = SockerTeam::where('short_code',$value->match_details->team2_kay)->first();
            if(is_object($footlogo1))
                $data[$key]['footteamlogo1'] = $footlogo1->logo_path;
            else
                $data[$key]['footteamlogo1'] = "";

            if(is_object($footlogo2))
                $data[$key]['footteamlogo2'] = $footlogo2->logo_path;
            else
                $data[$key]['footteamlogo2'] = "";
        }

        $auth_id = auth()->user()->_id;
        $response = array('mymatch' => $data);
        $yourData = ['status' => true, 'response' => $response, 'authid' => $auth_id, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function mymatchdetails()
    {
        $lists = FantasyTeam::where('user_id', auth()->user()->_id)
            ->with('match_details')
            ->groupBy('match_key')
            ->where('match_type', 'cricket')
        ->where('paid_status',1)
            ->get(['created_at', 'user_id', 'user_name', 'fantasy_points', 'price_update_status', 'winner_status', 'rank', 'price_winning_amount', 'paid_status','status']);
        $data = array();
        $upindex = $liveindex = $compindex = '0';
        $upcount = $livecount = $compcount = '0';

        foreach ($lists as $key => $value) {

            $end = Carbon::parse($value->match_details->start_date)->setTimezone('Asia/Kolkata');
            //$start = Carbon::now();
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';
            $date = explode('T', $value->match_details->start_date);
            if ($value->match_details->status == 'notstarted') {

                $data[$key]['left_time'] = strtotime($date[0]);
                $data[$key]['start_date'] = $value->match_details->start_date;

                $dt = new DateTime($value->match_details->start_date);
                $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
                $data[$key]['start_indian_date'] = $dt->format('d-m-Y H:i:s');
                $data[$key]['time_left'] = $day . $hour . $diff->i . ' minutes left';
            } else {
                $data[$key]['start_indian_date'] = $value->match_details->status;
                $data[$key]['start_date'] = $value->match_details->status;
                $data[$key]['left_time'] = $value->match_details->status;
                $data[$key]['time_left'] = $value->match_details->status;
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

            if ($value->match_details->status == 'notstarted') {

                $upcount++;

                $data['upcomming'][$upindex]['format'] = $value->match_details->format;
                $data['upcomming'][$upindex]['match_key'] = $value->match_key;
                $data['upcomming'][$upindex]['contest_id'] = $value->contest_id;
                $data['upcomming'][$upindex]['players'] = $value->players;
                $data['upcomming'][$upindex]['season_name'] = $value->match_details->season_name;
                $data['upcoming'][$upindex]['name'] = $value->match_details->name;
                $data['upcomming'][$upindex]['team1_key'] = $value->match_details->team1_key;
                $data['upcomming'][$upindex]['team2_key'] = $value->match_details->team2_kay;
                $data['upcomming'][$upindex]['status'] = $value->match_details->status;
                $data['upcomming'][$upindex]['text_status'] = $text_status;
                $data['upcomming'][$upindex]['time_left'] = $day . $hour . $diff->i . ' minutes';
                $upindex++;
            } elseif ($value->match_details->status == 'completed') {

                $compcount++;

                $data['completed'][$compindex]['format'] = $value->match_details->format;
                $data['completed'][$compindex]['match_key'] = $value->match_key;
                $data['completed'][$compindex]['contest_id'] = $value->contest_id;
                $data['completed'][$compindex]['players'] = $value->players;
                $data['completed'][$compindex]['season_name'] = $value->match_details->season_name;
                $data['completed'][$compindex]['name'] = $value->match_details->name;
                $data['completed'][$compindex]['team1_key'] = $value->match_details->team1_key;
                $data['completed'][$compindex]['team2_key'] = $value->match_details->team2_kay;
                $data['completed'][$compindex]['status'] = $value->match_details->status;
                $data['completed'][$compindex]['text_status'] = $text_status;
                $data['completed'][$compindex]['time_left'] = $day . $hour . $diff->i . ' minutes';
                $compindex++;

            } elseif ($value->match_details->status == 'started') {

                $livecount++;

                $data['live'][$liveindex]['format'] = $value->match_details->format;
                $data['live'][$liveindex]['match_key'] = $value->match_key;
                $data['live'][$liveindex]['contest_id'] = $value->contest_id;
                $data['live'][$liveindex]['players'] = $value->players;
                $data['live'][$liveindex]['season_name'] = $value->match_details->season_name;
                $data['live'][$liveindex]['name'] = $value->match_details->name;
                $data['live'][$liveindex]['team1_key'] = $value->match_details->team1_key;
                $data['live'][$liveindex]['team2_key'] = $value->match_details->team2_kay;
                $data['live'][$liveindex]['status'] = $value->match_details->status;
                $data['live'][$liveindex]['text_status'] = $text_status;
                $data['live'][$liveindex]['time_left'] = $day . $hour . $diff->i . ' minutes';
                $liveindex++;

            }
        }

        $data['live_length'] = $livecount;
        $data['up_length'] = $upcount;
        $data['comp_length'] = $compcount;

        $auth_id = auth()->user()->_id;
        $yourData = ['status' => true, 'response' => $data, 'authid' => $auth_id, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function playercredits()
    {
        $match_key = 'indwi_2019_t20_02';
        $list = [];
        $lists = MatchPlayers::playerList($match_key);
        $team1 = $lists['team1'];
        $team2 = $lists['team2'];

        $yourData = ['status' => true, 'response' => $lists, 'message' => '', 'team1' => $team1, 'team2' => $team2];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function playerdetails(Request $request)
    {
        $players = Player::where('player_key', $request->player_key)->first();
        $matchdetails = MatchPlayers::where('player_key', $request->player_key)->orderBy('updated_at', 'desc')->first();
        // print_r($matchdetails); exit;
        if (is_object($players)) {
            $players['total_points'] = 0.0;
            $players['bats'] = '';
            $players['bowls'] = '';
            $players['nationality'] = '';
            $players['birth_day'] = '';
            $players['current_credits'] = '0';
            $players['image'] = '';
            $previous['date'] = '';
            $previous['match'] = '';
            $previous['previous_match'] = $matchdetails->schedule->short_name;
            $previous['privious_points'] = $matchdetails->match_points;
            $previous['credits_value'] = $matchdetails->credit_value;
            $previous['selected_by'] = rand(10, 100);
            $players['previous'] = [$previous];
            $yourData = ['status' => true, 'response' => $players, 'message' => ''];
        } else {
            $yourData = ['status' => false, 'response' => [], 'message' => 'No such player in our db.'];
        }
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function fantasypoints()
    {
        $match_key = 'wbblt20_2019_g56';
        $contest_id = ''; //$request->contest_id,  ,'contest_id'=>$contest_id;
        $list = [];
        $lists = FantasyTeam::where(['match_key' => $match_key])->get();

        $yourData = ['status' => true, 'response' => $lists, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function createTeam(Request $request)
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

        $lists = MatchPlayers::where('match_key', $match_key)->with('schedule')->with('player')->where('type','cricket')
            ->get();    

        $data = array();
        $batindex = $bowlindex = $arindex = $wkindex = '0';
        $data['selectteal1count'] = 6;
        $data['selectteal2count'] = 5;

        $data['wk'] = $data['bat'] = $data['bowl'] = $data['ar'] = array();

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



            if (@$value->player->role == 'batsman') {
                $data['bat'][$batindex]['name'] = $value->player->name;
                $data['bat'][$batindex]['credit_value'] = sprintf("%.1f", $credit_value);
                $data['bat'][$batindex]['player_key'] = $value->player_key;
                $data['bat'][$batindex]['team'] = $value->teamlevel;
                $data['bat'][$batindex]['player_team_name'] = $player_team_name;
                $data['bat'][$batindex]['playing_11'] = $value->playing_11;
                $data['bat'][$batindex]['profile'] = url('/images/players/'.$profile);
                $batindex++;
            } elseif (@$value->player->role == 'bowler') {
                $data['bowl'][$bowlindex]['name'] = $value->player->name;
                $data['bowl'][$bowlindex]['credit_value'] = sprintf("%.1f", $credit_value);
                $data['bowl'][$bowlindex]['player_key'] = $value->player_key;
                $data['bowl'][$bowlindex]['team'] = $value->teamlevel;
                $data['bowl'][$bowlindex]['player_team_name'] = $player_team_name;
                $data['bowl'][$bowlindex]['playing_11'] = $value->playing_11;
                $data['bowl'][$bowlindex]['profile'] = url('/images/players/'.$profile);
                $bowlindex++;
            } elseif (@$value->player->role == 'all rounder') {
                $data['ar'][$arindex]['name'] = $value->player->name;
                $data['ar'][$arindex]['credit_value'] = sprintf("%.1f", $credit_value);
                $data['ar'][$arindex]['player_key'] = $value->player_key;
                $data['ar'][$arindex]['team'] = $value->teamlevel;
                $data['ar'][$arindex]['player_team_name'] = $player_team_name;
                $data['ar'][$arindex]['playing_11'] = $value->playing_11;
                $data['ar'][$arindex]['profile'] = url('/images/players/'.$profile);
                $arindex++;
            } elseif (@$value->player->role == 'keeper') {
                $data['wk'][$wkindex]['name'] = $value->player->name;
                $data['wk'][$wkindex]['credit_value'] = sprintf("%.1f", $credit_value);
                $data['wk'][$wkindex]['player_key'] = $value->player_key;
                $data['wk'][$wkindex]['team'] = $value->teamlevel;
                $data['wk'][$wkindex]['player_team_name'] = $player_team_name;
                $data['wk'][$wkindex]['playing_11'] = $value->playing_11;
                $data['wk'][$wkindex]['profile'] = url('/images/players/'.$profile);
                $wkindex++;
            }
        }

        $yourData = ['status' => true, 'response' => $data, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function saveTeam(Request $request)
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
            $team = FantasyTeam::find($request->team_id);
            if (isset($request->contest_id) && $request->contest_id != '') // update contest_id if provided
            {
                $team->contest_id = $request->contest_id;
            }
        } else {
            $team = new FantasyTeam();
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
        $yourData = ['status' => true, 'response' => $response, 'message' => 'Team created. Select Captain and Vise Captain.'];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function createdteam_list(Request $request)
    {
        $team = FantasyTeam::first();
        $yourData = ['status' => true, 'response' => $team, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function selectteamdetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $team = FantasyTeam::where('_id', $request->team_id)->first();

        if (is_object($team)) {
            $response = array('team' => $team->players);
            $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        } else {
            $yourData = ['status' => true, 'response' => '', 'message' => 'Invalid team id'];
        }

        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

    }

    public function matchDetails(Request $request)
    {
        dd($request->all());
        // $match_key = 'qtrlt10_2019_g1';
        $match_key = $request->id;
        $contest_id = $request->contestid;
        $details = Schedule::where('key', $match_key)->first();
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

        if (@$details->status == 'notstarted') {
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
            $details['left_time'] = @$details->status;
        }

        $team_name = FantasyTeam::where('user_id', auth()->user()->_id)
            ->where(['match_key' => $match_key])
            ->where(['contest_id' => $contest_id])
            ->first();

        $lists = FantasyTeam::where('match_key', $match_key)
            ->where('contest_id', $contest_id)
            ->get();

        // foreach ($lists as $key => $value) {
        //     $players = $value['players'];
        //         $match_players = MatchPlayers::where('match_key',$value['match_key'])->get();
        //         foreach ($match_players as $key => $value) {
        //                     $lists = FantasyTeam::where('match_key', $match_key)
        //                     ->where('contest_id', $contest_id)
        //                     ->get();
        //         }
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

        $yourData = ['status' => true,
            'response' => $details,
            'fantasypoints' => $lists,
            'team_name' => $team_name,
            'teamcount' => $teamcount,
            'listcount' => $listcount,
            'spotcount' => $spotcount,
            'contestcount' => $contes_count,
            'range' => $range,
            'message' => ''];

        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function selectcvcteam(Request $request)
    {
        $details = FantasyTeam::where('_id', $request->id)->first();

        $yourData = ['status' => true, 'response' => $details, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

    }

    public function savecvcteam(Request $request)
    {
        $details = FantasyTeam::where('_id', $request->teamid)->first();
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

    public function getelevenplayers(Request $request)
    {
        $details = FantasyTeam::where('_id', $request->teamid)->first();
        $response = array('players' => $details->players);

        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function myteamdetails(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'match_key' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $list = FantasyTeam::where('match_key',$request->match_key)
                ->where('user_id',auth()->user()->_id)
                ->with('match_players')
                ->with('foot_match_details')
                ->get();

                $data = array();
                $profile = 'player.jpg';

                foreach ($list as $key => $value) {

                    $batcount = $bowlcount = $arcount = $wkcount = 0;

                    foreach ($value->players as $playerkey => $player) {

                        if($player['role'] == 'wk'){
                            $wkcount++;
                        }elseif($player['role'] == 'ar'){
                            $arcount++;
                        }elseif($player['role'] == 'bat'){
                            $batcount++;
                        }elseif($player['role'] == 'bowl'){
                            $bowlcount++;
                        }

                        if($player['caption'] == 1){
                            $captain_name = $player['name'];

                            $player_image_check = Player::where('player_key',$player['player_key'])->first();

                        if (isset($player_image_check->profile) && $player_image_check->profile != '') {
                            $profile = $player_image_check->profile;
                        }
                              $player_image_check = Player::where('player_key',$player['player_key'])->first();

                                if (isset($player_image_check->profile) && $player_image_check->profile != '') {
                                    $profile = $player_image_check->profile;
                                }
                                $captain_pic  = url('/images/players/'.$profile);
                        }

                        if($player['v_caption'] == 1){

                            $vcaptain_name = $player['name'];       
                              $player_image_check = Player::where('player_key',$player['player_key'])->first();

                                if (isset($player_image_check->profile) && $player_image_check->profile != '') {
                                    $profile = $player_image_check->profile;
                                }
                                $vcaptain_pic  = url('/images/players/'.$profile);
                        }
                    }
                    

                    $data[$key]['ar']        = $arcount;
                    $data[$key]['wk']        = $wkcount;
                    $data[$key]['bowl']      = $bowlcount;
                    $data[$key]['bat']       = $batcount;

                    $data[$key]['team2name'] = $value->foot_match_details->team2_kay;
                    $data[$key]['team1name'] = $value->foot_match_details->team1_key;

                    $data[$key]['team_id']   = $value->foot_match_details->_id;

                    $data[$key]['team1count'] = 6;
                    $data[$key]['team2count'] = 5;

                    $data[$key]['captain'] = array('name' => $captain_name,'image' => $captain_pic );
                    $data[$key]['vicecaptain'] = array('name' => $vcaptain_name,'image' => $vcaptain_pic );

            }

                
        $yourData = ['status' => true, 'response' => $data, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

    }

    public function myteamdetailsnew(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'match_key' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $list = FantasyTeam::where('match_key',$request->match_key)
                ->where('user_id',auth()->user()->_id)
                ->with('match_players')
                ->with('foot_match_details')
                ->get();

                $data = array();
                $profile = 'player.jpg';
                $common_index = 0;

                foreach ($list as $key => $value) {

                    $batcount = $bowlcount = $arcount = $wkcount = 0;

                    foreach ($value->players as $playerkey => $player) {

                        if($player['role'] == 'wk'){
                            $wkcount++;
                        }elseif($player['role'] == 'ar'){
                            $arcount++;
                        }elseif($player['role'] == 'bat'){
                            $batcount++;
                        }elseif($player['role'] == 'bowl'){
                            $bowlcount++;
                        }

                        if($player['caption'] == 1){
                            $captain_name = $player['name'];

                            $player_image_check = Player::where('player_key',$player['player_key'])->first();

                        if (isset($player_image_check->profile) && $player_image_check->profile != '') {
                            $profile = $player_image_check->profile;
                        }
                            $captain_pic  = url('/images/players/'.$profile);
                        }

                     
                         if($player['v_caption'] == 1){
                            $vcaptain_name = $player['name'];       
                              $player_image_check = Player::where('player_key',$player['player_key'])->first();

                                if (isset($player_image_check->profile) && $player_image_check->profile != '') {
                                    $profile = $player_image_check->profile;
                                }
                                $vcaptain_pic  = url('/images/players/'.$profile);
                        }
                    }
                    

                $data[$common_index]['ar']        = $arcount;
                $data[$common_index]['wk']        = $wkcount;
                $data[$common_index]['bowl']      = $bowlcount;
                $data[$common_index]['bat']       = $batcount;

                $data[$common_index]['team2name'] = $value->foot_match_details->team2_kay;
                $data[$common_index]['team1name'] = $value->foot_match_details->team1_key;

                $data[$common_index]['team_id']   = $value->foot_match_details->_id;

                $data[$common_index]['team1count'] = 6;
                $data[$common_index]['team2count'] = 5;

                $data[$common_index]['captain'] = array('name' => $captain_name,'image' => $captain_pic );
                $data[$common_index]['vicecaptain'] = array('name' => $vcaptain_name,'image' => $vcaptain_pic );
                $data[$common_index]['contest_status'] = 'normal';
                $common_index++;
            }


              $plist = HeroTeam::where('match_key',$request->match_key)
                ->where('user_id',auth()->user()->_id)
                ->with('match_players')
                ->with('foot_match_details')
                ->get();


                foreach ($plist as $key => $value) {

                    $batcount = $bowlcount = $arcount = $wkcount = 0;

                    foreach ($value->players as $playerkey => $player) {

                        if($player['role'] == 'wk'){
                            $wkcount++;
                        }elseif($player['role'] == 'ar'){
                            $arcount++;
                        }elseif($player['role'] == 'bat'){
                            $batcount++;
                        }elseif($player['role'] == 'bowl'){
                            $bowlcount++;
                        }

                           if($player['man_match'] == 1){

                            $man_match_name = $player['name'];      

                              $player_image_check = Player::where('player_key',$player['player_key'])->first();

                                if (isset($player_image_check->profile) && $player_image_check->profile != '') {
                                    $profile = $player_image_check->profile;
                                }

                                $man_match_pic  = url('/images/players/'.$profile);
                        }


                        if($player['high_score'] == 1){

                            $high_score_name = $player['name'];

                            $player_image_check = Player::where('player_key',$player['player_key'])->first();

                                if (isset($player_image_check->profile) && $player_image_check->profile != '') {
                                    $profile = $player_image_check->profile;
                                }

                                $high_score_pic  = url('/images/players/'.$profile);
                        }

                        if($player['high_wickets'] == 1){

                            $high_wickets_name = $player['name']; 

                            $player_image_check = Player::where('player_key',$player['player_key'])->first();

                                if (isset($player_image_check->profile) && $player_image_check->profile != '') {
                                    $profile = $player_image_check->profile;
                                }
                                $high_wickets_pic  = url('/images/players/'.$profile);
                        }
                    }
                    

                $data[$common_index]['ar']        = $arcount;
                $data[$common_index]['wk']        = $wkcount;
                $data[$common_index]['bowl']      = $bowlcount;
                $data[$common_index]['bat']       = $batcount;

                $data[$common_index]['team2name'] = $value->foot_match_details->team2_kay;
                $data[$common_index]['team1name'] = $value->foot_match_details->team1_key;

                $data[$common_index]['team_id']   = $value->foot_match_details->_id;

                $data[$common_index]['team1count'] = 6;
                $data[$common_index]['team2count'] = 5;

                $data[$common_index]['man_match'] = array('name' => $man_match_name,'image' => $man_match_pic );

                $data[$common_index]['high_score'] = array('name' => $high_score_name,'image' => $high_score_pic );

                $data[$common_index]['high_wickets'] = array('name' => $high_wickets_name,'image' => $high_wickets_pic );

                $data[$common_index]['contest_status'] = 'pick';
                $common_index++;

            }

            $response = array('team_details' => $data);

                
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

    }

    public function time_Ago($time)
    {
        // Calculate difference between current
        // time and given timestamp in seconds
        $diff = time() - $time;

        // Time difference in seconds
        $sec = $diff;

        // Convert time difference in minutes
        $min = round($diff / 60);

        // Convert time difference in hours
        $hrs = round($diff / 3600);

        // Convert time difference in days
        $days = round($diff / 86400);

        // Convert time difference in weeks
        $weeks = round($diff / 604800);

        // Convert time difference in months
        $mnths = round($diff / 2600640);

        // Convert time difference in years
        $yrs = round($diff / 31207680);

        // Check for seconds
        if ($sec <= 60) {
            return "$sec seconds ago";
        }

        // Check for minutes
        else if ($min <= 60) {
            if ($min == 1) {
                return "one minute ago";
            } else {
                return "$min minutes ago";
            }
        }

        // Check for hours
        else if ($hrs <= 24) {
            if ($hrs == 1) {
                return "an hour ago";
            } else {
                return "$hrs hours ago";
            }
        }

        // Check for days
        else if ($days <= 7) {
            if ($days == 1) {
                return "Yesterday";
            } else {
                return "$days days ago";
            }
        }

        // Check for weeks
        else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                return "a week ago";
            } else {
                return "$weeks weeks ago";
            }
        }

        // Check for months
        else if ($mnths <= 12) {
            if ($mnths == 1) {
                return "a month ago";
            } else {
                return "$mnths months ago";
            }
        }

        // Check for years
        else {
                if ($yrs == 1) {
                    return "one year ago";
                } else {
                    return "$yrs years ago";
                }
            }
        }
        public function mymatchcount()
        {
            $lists = FantasyTeam::with('match_details')
                ->where('user_id', auth()->user()->_id)
                // ->where('paid_status', 1)
                ->orderBy('_id', 'DESC')
                ->groupBy('match_key')
            // ->distinct('match_key')
                ->get();
            $matchcount = count($lists);

            $yourData = ['status' => true, 'response' => $matchcount];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

    public function myviewteam(Request $request)
    {
            $details = FantasyTeam::where('match_key', $request->id)->where('user_id', auth()->user()->_id)->first();
            
            $wkindex = $bowlindex = $batindex = $arindex = '0';
            $data = array();

            if (is_object($details)) {

                foreach ($details->players as $key => $value) {

                    if ($value['role'] == 'wk') {
                        $data['wk'][$wkindex]['name'] = $value['name'];
                        $data['wk'][$wkindex]['credit'] = @$value['credit'];
                        $data['wk'][$wkindex]['caption'] = $value['caption'];
                        $data['wk'][$wkindex]['visecaption'] = $value['v_caption'];
                        $data['wk'][$wkindex]['player_key'] = $value['player_key'];
                        $wkindex++;
                    } elseif ($value['role'] == 'bat') {
                    $data['bat'][$batindex]['name'] = $value['name'];
                    $data['bat'][$batindex]['credit'] = @$value['credit'];
                    $data['bat'][$batindex]['caption'] = $value['caption'];
                    $data['bat'][$batindex]['visecaption'] = $value['v_caption'];
                    $data['bat'][$batindex]['player_key'] = $value['player_key'];
                    $batindex++;
                } elseif ($value['role'] == 'bowl') {
                    $data['bowl'][$bowlindex]['name'] = $value['name'];
                    $data['bowl'][$bowlindex]['credit'] = @$value['credit'];
                    $data['bowl'][$bowlindex]['caption'] = $value['caption'];
                    $data['bowl'][$bowlindex]['visecaption'] = $value['v_caption'];
                    $data['bowl'][$bowlindex]['player_key'] = $value['player_key'];
                    $bowlindex++;
                } elseif ($value['role'] == 'ar') {
                    $data['ar'][$arindex]['name'] = $value['name'];
                    $data['ar'][$arindex]['credit'] = $value['credit'];
                    $data['ar'][$arindex]['caption'] = $value['caption'];
                    $data['ar'][$arindex]['visecaption'] = $value['v_caption'];
                    $data['ar'][$arindex]['player_key'] = $value['player_key'];
                    $arindex++;
                }
            }

        }

        $total_created_list = FantasyTeam::where('match_key', $request->id)->where('user_id', auth()->user()->_id)->get();

        $tlist = array();
        foreach ($total_created_list as $ind => $totallist) {
            $tlist[$ind]['created_at'] = $totallist->created_at;
            $tlist[$ind]['match_key'] = $totallist->match_key;
            $tlist[$ind]['team_id'] = $totallist->_id;
            foreach ($totallist->players as $payer) {
                if ($payer['caption'] == 1) {
                    $tlist[$ind]['cname'] = $payer['name'];
                } elseif ($payer['v_caption'] == 1) {
                    $tlist[$ind]['vcname'] = $payer['name'];
                }
            }
        }

        $response = array('details' => $data, 'tlist' => $tlist);

        $yourData = ['status' => true, 'response' => $response, 'message' => ''];

        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function selectviewteam(Request $request)
    {
        $details = FantasyTeam::where('_id', $request->team_id)->where('match_key', $request->match_key)->where('user_id', auth()->user()->_id)->first();

        $wkindex = $bowlindex = $batindex = $arindex = '0';
        $data = array();

        if (is_object($details)) {

            foreach ($details->players as $key => $value) {
                if ($value['role'] == 'wk') {
                    $data['wk'][$wkindex]['name'] = $value['name'];
                    $data['wk'][$wkindex]['credit'] = $value['credit'];
                    $data['wk'][$wkindex]['caption'] = $value['caption'];
                    $data['wk'][$wkindex]['visecaption'] = $value['v_caption'];
                    $wkindex++;
                } elseif ($value['role'] == 'bat') {
                    $data['bat'][$batindex]['name'] = $value['name'];
                    $data['bat'][$batindex]['credit'] = $value['credit'];
                    $data['bat'][$batindex]['caption'] = $value['name'];
                    $data['bat'][$batindex]['visecaption'] = $value['v_caption'];
                    $batindex++;
                } elseif ($value['role'] == 'bowl') {
                    $data['bowl'][$bowlindex]['name'] = $value['name'];
                    $data['bowl'][$bowlindex]['credit'] = $value['credit'];
                    $data['bowl'][$bowlindex]['caption'] = $value['caption'];
                    $data['bowl'][$bowlindex]['visecaption'] = $value['v_caption'];
                    $bowlindex++;
                } elseif ($value['role'] == 'ar') {
                    $data['ar'][$arindex]['name'] = $value['name'];
                    $data['ar'][$arindex]['credit'] = $value['credit'];
                    $data['ar'][$arindex]['caption'] = $value['caption'];
                    $data['ar'][$arindex]['visecaption'] = $value['v_caption'];
                    $arindex++;
                }
            }

        }

        $yourData = ['status' => true, 'response' => $data];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function editTeam(Request $request)
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

        $lists = MatchPlayers::where('match_key', $match_key)->with('schedule')->with('player')->get();

        $data = array();
        $batindex = $bowlindex = $arindex = $wkindex = '0';
        $data['player_count'] = 0;

        $data['selectteal2count'] = 5;
        $data['selectteal1count'] = 6;

        $data['wk_player_count'] = 0;
        $data['bat_player_count'] = 0;
        $data['ar_player_count'] = 0;
        $data['bowl_player_count'] = 0;
        $data['total_selected_credit_score'] = 100;

        $details = Schedule::where('key', $match_key)->first();

        $team_short = explode('vs', $details->short_name);

        $check_selected_player = FantasyTeam::where('match_key', $match_key)
            ->where('_id', $request->team_id)
            ->first();

        $array_data = array();
        $tempplayer = array();

        foreach ($check_selected_player->players as $vv) {

            $array_data[] = $vv['player_key'];
            if (str_replace(' ', '', $vv['team_name']) == str_replace(' ', '', strtolower($team_short[0]))) {
                $data['selectteal1count']--;
            } elseif (str_replace(' ', '', $vv['team_name']) == str_replace(' ', '', strtolower($team_short[1]))) {
                $data['selectteal2count']--;
            }

            if ($vv['role'] == 'wk') {
                $data['wk_player_count']++;
            } elseif ($vv['role'] == 'bat') {
                $data['bat_player_count']++;
            } elseif ($vv['role'] == 'bowl') {
                $data['bowl_player_count']++;
            } elseif ($vv['role'] == 'ar') {
                $data['ar_player_count']++;
            }
        }

        foreach ($lists as $key => $value) {

            $selected = 0;

            if (in_array($value->player_key, $array_data)) {
                $selected = 1;

                $data['total_selected_credit_score'] -= $value->credit_value;

                if ($value->player->role == 'keeper') {
                    $role = 'wk';
                } else if ($value->player->role == 'batsman') {
                    $role = 'bat';
                } else if ($value->player->role == 'all rounder') {
                    $role = 'ar';
                } else if ($value->player->role == 'bowler') {
                    $role = 'bowl';
                }

                $data['tempplayer'][$data['player_count']] = array(
                    'name' => $value->player->name,
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
                $player_team_name = $value->schedule->team1_key;
            } else if ($value->teamlevel == 'team2') {
                $player_team_name = $value->schedule->team2_kay;
            }

            $data['short_name'] = $value->schedule->team1_key . ' vs ' . $value->schedule->team2_kay;
            $date = explode('T', $value->schedule->start_date);
            $data['left_time'] = strtotime($date[0]);
            $data['start_date'] = $value->schedule->start_date;
            $start = $value->schedule->start_date;

            $dt = new DateTime($value->schedule->start_date);
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
            $data['start_indian_date'] = $dt->format('d-m-Y H:i:s');

            if ($value->player->role == 'batsman') {

                $data['bat'][$batindex]['name'] = $value->player->name;
                $data['bat'][$batindex]['credit_value'] = $value->credit_value;
                $data['bat'][$batindex]['player_key'] = $value->player_key;
                $data['bat'][$batindex]['team'] = $value->teamlevel;
                $data['bat'][$batindex]['player_team_name'] = $player_team_name;
                $data['bat'][$batindex]['selected'] = $selected;
                $batindex++;

            } elseif ($value->player->role == 'bowler') {
                $data['bowl'][$bowlindex]['name'] = $value->player->name;
                $data['bowl'][$bowlindex]['credit_value'] = $value->credit_value;
                $data['bowl'][$bowlindex]['player_key'] = $value->player_key;
                $data['bowl'][$bowlindex]['team'] = $value->teamlevel;
                $data['bowl'][$bowlindex]['player_team_name'] = $player_team_name;
                $data['bowl'][$bowlindex]['selected'] = $selected;
                $bowlindex++;

            } elseif ($value->player->role == 'all rounder') {
                $data['ar'][$arindex]['name'] = $value->player->name;
                $data['ar'][$arindex]['credit_value'] = $value->credit_value;
                $data['ar'][$arindex]['player_key'] = $value->player_key;
                $data['ar'][$arindex]['team'] = $value->teamlevel;
                $data['ar'][$arindex]['player_team_name'] = $player_team_name;
                $data['ar'][$arindex]['selected'] = $selected;
                $arindex++;

            } elseif ($value->player->role == 'keeper') {
                $data['wk'][$wkindex]['name'] = $value->player->name;
                $data['wk'][$wkindex]['credit_value'] = $value->credit_value;
                $data['wk'][$wkindex]['player_key'] = $value->player_key;
                $data['wk'][$wkindex]['team'] = $value->teamlevel;
                $data['wk'][$wkindex]['player_team_name'] = $player_team_name;
                $data['wk'][$wkindex]['selected'] = $selected;
                $wkindex++;

            }
        }

        $yourData = ['status' => true, 'response' => $data, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function updateTeam(Request $request)
    {

        //print_r($request->all()); exit;
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

    public function check_selected_player1($match_key, $team_id, $player_key)
    {
        $check_selected_player = FantasyTeam::where('match_key', $match_key)
            ->where('_id', $team_id)
            ->get();

        $selected = 0;

        if (is_object($check_selected_player)[0]) {

            echo $player_key . '<br>';
            foreach ($check_selected_player[0]->players as $chckplayer) {
                if ($chckplayer['player_key'] == $player_key) {

                    echo $chckplayer['player_key'], ' = ' . $player_key;
                    $selected = 1;
                } else {
                    $selected = 0;
                }
            }
        }
        return $selected;
    }

    public function rank_details(Request $request)
    {
        $data = [];

        if (isset($request->offset) && $request->offset != '') {
            $details = FantasyTeam::with('match_details')
                ->where('user_id', auth()->user()->_id)
                ->orderBy('created_at', 'desc')
                ->offset((int) $request->offset)
                ->limit((int) $request->limit)
                ->get();

        } else {
            $details = FantasyTeam::with('match_details')
                ->where('user_id', auth()->user()->_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        if (isset($details)) {
            foreach ($details as $key => $value) {
                if ($value['match_details']['status'] == 'completed') {
                    $temp = [];
                    $temp['date'] = date("d-M-Y h:i A", strtotime($value->match_details->start_date));
                    $temp['time'] = '';
                    $temp['match_name'] = $value->match_details->short_name;
                    $temp['name'] = $value->match_details->name;
                    $temp['username'] = $value->user_name;
                    $temp['fantasy_points'] = $value->fantasy_points;
                    $temp['rank'] = $value->rank;
                    $temp['price_winning_amount'] = $value->price_winning_amount;
                    $temp['created_at'] = $value->created_at;
                    $temp['series_name'] = $value->match_details->season_name;
                    $data[] = $temp;
                }
            }
        } 

        $response = array('details' => $data);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function check_selected_player($match_key, $team_id, $player_key, $type)
    {
        $check_selected_player = FantasyTeam::where('match_key', $match_key)
            ->where('_id', $team_id)
            ->first();

        $selected = 0;

        $array_data = array();

        foreach ($check_selected_player->players as $vv) {
            $array_data[] = $vv['player_key'];
        }

        if (in_array($player_key, $array_data)) {
            $selected = 1;
        }

        return $selected;
    }

    public function getplayerdetail(Request $request)
    {

        $player_details = MatchPlayers::with('schedule')->with('player')
            ->where('match_key', $request->match_id)
            ->where('player_key', $request->playerid)
            ->first();

        $getSelectePercent = $this->getSelectePercent($request->match_id);
        $data['player_percent'] = isset($getSelectePercent[$player_details->player_key]) ? $getSelectePercent[$player_details->player_key] : 0;
        $data['short_name'] = $player_details->schedule->short_name;
        $data['credits'] = $player_details->credit_value;
        $data['total_points'] = '-';
        $data['batting_style'] = $player_details->player['batting_style'];
        $data['bowling_style'] = $player_details->player['bowling_style'];
        $data['name'] = $player_details->player['name'];
        $data['role'] = $player_details->player['role'];
        $data['nationality'] = $player_details->player['teams'];
        $data['dob'] = '-';

        $response = array('details' => $data);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function test1()
    {
        //  yesterday match complted
        $schedule_details = Schedule::where('status','!=','completed')
        ->where('start_date','<', '2020-09-02')
        ->get();
        // dd(Carbon::yesterday()->format('Y-m-d'));
        dd($schedule_details);

        foreach($schedule_details as $key => $data){

            $data->status = 'completed';
            $data->save();
        }
    }

   public function test2()
    {
        // delete duplicate records.

        $match_key = 'cplt20_2020_g23';
         $player_details = MatchPlayers::where('match_key', $match_key)
         // ->where('player_key', $player_key)
            ->get();

            foreach ($player_details as $key => $value) {
                $player_count = MatchPlayers::where('match_key',$match_key)->where('player_key',$value->player_key)->count();
                
                if($player_count > 1){
                    MatchPlayers::where('_id',$value->_id)->delete();
                }
            }
    }

    public function column_update()
    {

$record = Schedule::groupBy('format')->get();
dd($record);


        // delete duplicate records.
         $record = Schedule::where('status', '!=' ,'completed')
                ->where('type','cricket')                
                ->get();

                foreach ($record as $key => $value) {
$status = (isset($value->match_player_updated))?$value->match_player_updated:0;
                    $value->match_player_updated  = $status;
                    $value->save();
                }
    }

    public function getSelectePercent($matchkey)
    {
        $teams = FantasyTeam::where('match_key', $matchkey)->get();
        $player_sel_count = [];
        if (count($teams) > 0):
            foreach ($teams as $team):
                foreach ($team->players as $player):
                    if (isset($player_sel_count[$player['player_key']])) {
                        $player_sel_count[$player['player_key']]++;
                    } else {
                        $player_sel_count[$player['player_key']] = 1;
                    }
                endforeach;
            endforeach;
            $player_percent = [];
            $teamcount = count($teams);
            foreach ($player_sel_count as $player => $count) {
                $player_percent[$player] = $count / $teamcount * 100;
            }
        endif;
        return $player_sel_count;
    }
    public function allmatchlimit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',  
            'offset' => 'required',  
        ]); 

        $type = (isset($request->type) && $request->type!='')?$request->type:"cricket";

        if ($validator->fails()) {        
            $yourData =['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
        if($type == "cricket")
            $details = Schedule::where('status', '!=', 'completed')->where('status', '!=', 'started')->where('type','cricket')->offset((int)$request->offset)->limit((int)($request->limit))->get();
        else
            $details = Schedule::where('status', '!=', 'completed')->where('status', '!=', 'started')->where('type','football')->offset((int)$request->offset)->limit((int)($request->limit))->orderBy('start_date')->get();
        foreach ($details as $data) {
            $end = Carbon::parse($data->start_date)->setTimezone('Asia/Kolkata');
            //$start = Carbon::now(); 
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';
            $data->left_time =  $day . $hour . $diff->i . ' minutes';

            $dt = new DateTime($data->start_date); 
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
            $data->start_indian_date =  $dt->format('d-m-Y H:i:s');

            $data->team1_flag = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team1_key.'.svg';
            $data->team2_flag = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team2_kay.'.svg';

            $footlogo1 = SockerTeam::where('short_code',$data->team1_key)->first();
            $footlogo2 = SockerTeam::where('short_code',$data->team2_kay)->first();
            if(is_object($footlogo1))
                $data->footteamlogo1 = $footlogo1->logo_path;
            else
            $data->footteamlogo1 = "";

            if(is_object($footlogo2))
                $data->footteamlogo2 = $footlogo2->logo_path;
            else
                $data->footteamlogo2 = "";
            if(@$data->score_team1 != '' && @$data->score_team1 != '') 
            {
                $data->score_team1 = $data->score_team1;
                $data->score_team2 = $data->score_team2;
            } else {
                $data->score_team1 = 'null';
                $data->score_team2 = 'null';
            }
        }

        $response = array('matchdetails' => $details);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

}