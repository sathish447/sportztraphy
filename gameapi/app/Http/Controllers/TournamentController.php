<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\FantasyTeam;
use App\Models\MatchPlayers;
use App\Models\SockerTeam;
use App\Models\Player;
use App\Models\Schedule;
use App\Models\TournmentTeam;
use App\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TournamentController extends Controller
{
    public function alltournament()
    {
        $details = Schedule::where('status', '!=', 'completed')
        ->where('status', '!=', 'started')
        ->where('type', '!=', 'football')
        ->select('short_name','format','team1_key','team2_kay','start_date')
        ->groupBy('season_name')
        ->orderBy('start_date')
        ->get();


        $tornument = array();

        foreach ($details as $key => $data) {

            $end = Carbon::parse($data->start_date)->setTimezone('Asia/Kolkata');
//$start = Carbon::now();
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';    

            $tornument[$key]['session_name'] = $data->season_name;
            $tornument[$key]['format'] = $data->format;
            $tornument[$key]['left_time'] = $day . $hour . $diff->i . ' minutes+';
            $tornument[$key]['start_date'] = strtotime($end);

            $tornument[$key]['team1_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team1_key.'.svg';
            $tornument[$key]['team2_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team2_kay.'.svg';

            $dt = new DateTime($data->start_date); 
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));

            $tornument[$key]['start_indian_date'] = $dt->format('d-m-Y H:i:s');;

        }

        $response = array('matchdetails' => $tornument);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }


    public function alltournamentnew()
    {
        $details = Schedule::where('status', '!=', 'completed')
        ->where('status', '!=', 'started')
        ->where('type', '!=', 'football')
        ->select('short_name','format','team1_key','team2_kay','start_date')
        ->groupBy('season_name')
        ->orderBy('start_date')
        ->get();


        $tornument = array();
        $odindex = $onedayindex = $testindex = $t20index = $t10index = 0;

            $tornument = array();

        foreach ($details as $key => $data) {

            $end = Carbon::parse($data->start_date)->setTimezone('Asia/Kolkata');
//$start = Carbon::now();
            $diff = $end->diff(now());
            $day = ($diff->d > 0) ? $diff->d . ' days, ' : '';
            $hour = ($diff->d = 0 && ($diff->h = 0)) ? '' : $diff->h . ' hours, ';    

            $tornument[$key]['session_name'] = $data->season_name;
            $tornument[$key]['format'] = $data->format;
            $tornument[$key]['left_time'] = $day . $hour . $diff->i . ' minutes+';
            $tornument[$key]['start_date'] = strtotime($end);

            $tornument[$key]['team1_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team1_key.'.svg';
            $tornument[$key]['team2_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team2_kay.'.svg';

            $dt = new DateTime($data->start_date); 
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));

            $tornument[$key]['start_indian_date'] = $dt->format('d-m-Y H:i:s');;

        }      

            // if($data->format == 'od'){
            //     $tornument['od'][$odindex]['session_name'] = $data->season_name;
            //     $tornument['od'][$odindex]['format'] = $data->format;
            //     $tornument['od'][$odindex]['left_time'] = $day . $hour . $diff->i . ' minutes+';
            //     $tornument['od'][$odindex]['start_date'] = strtotime($end);
            //     $tornument['od'][$odindex]['team1_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team1_key.'.svg';
            //     $tornument['od'][$odindex]['team2_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team2_kay.'.svg';
            //     $tornument['od'][$odindex]['start_indian_date'] = $dt->format('d-m-Y H:i:s');

            //     $odindex++;

            // }

            // elseif($data->format == 'one-day'){
            //     $tornument['oneday'][$onedayindex]['session_name'] = $data->season_name;
            //     $tornument['oneday'][$onedayindex]['format'] = $data->format;
            //     $tornument['oneday'][$onedayindex]['left_time'] = $day . $hour . $diff->i . ' minutes+';
            //     $tornument['oneday'][$onedayindex]['start_date'] = strtotime($end);
            //     $tornument['oneday'][$onedayindex]['team1_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team1_key.'.svg';
            //     $tornument['oneday'][$onedayindex]['team2_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team2_kay.'.svg';
            //     $tornument['oneday'][$onedayindex]['start_indian_date'] = $dt->format('d-m-Y H:i:s');

            //     $onedayindex++;
            // }            

            // elseif($data->format == 'test'){
            //     $tornument['test'][$testindex]['session_name'] = $data->season_name;
            //     $tornument['test'][$testindex]['format'] = $data->format;
            //     $tornument['test'][$testindex]['left_time'] = $day . $hour . $diff->i . ' minutes+';
            //     $tornument['test'][$testindex]['start_date'] = strtotime($end);
            //     $tornument['test'][$testindex]['team1_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team1_key.'.svg';
            //     $tornument['test'][$testindex]['team2_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team2_kay.'.svg';
            //     $tornument['test'][$testindex]['start_indian_date'] = $dt->format('d-m-Y H:i:s');
            //     $testindex++;
            // }

            // elseif($data->format == 't20'){
            //     $tornument['t20'][$t20index]['session_name'] = $data->season_name;
            //     $tornument['t20'][$t20index]['format'] = $data->format;
            //     $tornument['t20'][$t20index]['left_time'] = $day . $hour . $diff->i . ' minutes+';
            //     $tornument['t20'][$t20index]['start_date'] = strtotime($end);
            //     $tornument['t20'][$t20index]['team1_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team1_key.'.svg';
            //     $tornument['t20'][$t20index]['team2_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team2_kay.'.svg';
            //     $tornument['t20'][$t20index]['start_indian_date'] = $dt->format('d-m-Y H:i:s');

            //     $t20index++;

            // }

            // elseif($data->format == 't10'){
            //     $tornument['t10'][$t10index]['session_name'] = $data->season_name;
            //     $tornument['t10'][$t10index]['format'] = $data->format;
            //     $tornument['t10'][$t10index]['left_time'] = $day . $hour . $diff->i . ' minutes+';
            //     $tornument['t10'][$t10index]['start_date'] = strtotime($end);
            //     $tornument['t10'][$t10index]['team1_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team1_key.'.svg';
            //     $tornument['t10'][$t10index]['team2_flag'] = 'https://fantasy.demozab.com/assets/images/flag/'.$data->team2_kay.'.svg';
            //     $tornument['t10'][$t10index]['start_indian_date'] = $dt->format('d-m-Y H:i:s');

            //     $t10index++;

            // }    
        

        $cat_det = Schedule::groupBy('format')->get();

        $cat_details = array();

        foreach ($cat_det as $cat_key => $cat_value) {

            $cat_details[$cat_key]['name'] = $cat_value->format;

        }

        $response = array('matchdetails' => $tornument, 'category' => $cat_details);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }


    public function mytournament(Request $request)
    {
        $type = (isset($request->type) && $request->type!='') ? $request->type : "cricket";

        $lists = TournmentTeam::with('match_details')
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

    public function mytournamentdetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_name' => 'required',
            'format' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }


        $plist = TournmentTeam::where('session_name',$request->session_name)
        ->where('user_id',auth()->user()->_id)
        ->where('format',$request->format)
        ->with('match_players')
        ->with('foot_match_details')
        ->get();

        $data = array();
        $profile = 'player.jpg';
        $common_index = 0;

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

        $auth_id = auth()->user()->_id;
        $yourData = ['status' => true, 'response' => $response, 'authid' => $auth_id, 'message' => ''];
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

    public function createTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sessionname' => 'required',
            'format' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $match_key = array();
// $match_key = $request->id;
        $sessionname = $request->sessionname;
        $format = $request->format;

        $schedule_details = Schedule::where('season_name',$sessionname)->where('format',$format)->get();

        foreach ($schedule_details as $key => $value) {

            $match_key[] = $value->key;
        }

        $lists = MatchPlayers::whereIn('match_key', $match_key)
        ->with('schedule')
        ->with('player')
        ->where('type','cricket')
// ->groupBy('name')
        ->get();    

        $data = array();
        $batindex = $bowlindex = $arindex = $wkindex = $playerindx = '0';
        $data['selectteal1count'] = 6;
        $data['selectteal2count'] = 5;


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

    public function saveTournmentTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sessionname' => 'required',
            'format' => 'required',
            'player' => 'required',
        ]);

        $type = (isset($request->type) && $request->type!='')?$request->type:"cricket";

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
/// Update or create team based on the team_id in param list
        if (isset($request->team_id) && $request->team_id != '') {
            $team = TournmentTeam::find($request->team_id);
if (isset($request->contest_id) && $request->contest_id != '') // update contest_id if provided
{
    $team->contest_id = $request->contest_id;
}
} else {
    $team = new TournmentTeam();
    $team->contest_id = '';
}

$team->session_name = $request->sessionname;
$team->format = $request->format;
$team->user_id = auth()->user()->_id;
$team->user_name = auth()->user()->teamname;
$team->players = (isset($request->request_type) && $request->request_type == 'mobile') ? json_decode($request->player) : $request->player;
$team->match_type = 'cricket';
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