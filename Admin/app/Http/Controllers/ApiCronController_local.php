<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\FantasyTeam;
use App\Models\MatchPlayers;
use App\Models\Player;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Cashfree;

class ApiCronController extends Controller
{
    use Cashfree;
    public $access_token = "";

    public function __construct()
    {
        $setting = Setting::first();
        $this->access_token = $setting->access_token;
    }

    public function authAPI()
    {

        $curl = curl_init();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rest.cricketapi.com/rest/v2/auth/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"access_key\"\r\n\r\na475fc1b71c202e0213b68b9e7ce7657\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"app_id\"\r\n\r\ncom.filo.app\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"secret_key\"\r\n\r\n992461b712752a5c4f88d104a856099d\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"device_id\"\r\n\r\ndevelopernew\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Authorization: Basic c2Itb3M0N2duMjA3NDEwQGJ1c2luZXNzLmV4YW1wbGUuY29tOlN0SkVwZ0VV",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: 575",
                "Content-Type: multipart/form-data; boundary=--------------------------018561532196227813026236",
                "Host: rest.cricketapi.com",
                "Postman-Token: e9e8286e-14f3-4c54-8e28-ddb3e2a64772,31b76637-8560-48c0-96ad-bdae1b1ace72",
                "User-Agent: PostmanRuntime/7.17.1",
                "access_key: 2647497cb76a33067206405a8ca37e2e",
                "app_id: com.onlycric.lst",
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "device_id: developernew",
                "secret_key: 913be57d674422d3dd40216a7aeff578",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        /// Log update
        $query = \DB::table('api_log')->where('_id', '5df4e2931723935a209756a8');
        $getlog = $query->first();
        $logcount = isset($getlog['auth_api']) ? $getlog['auth_api'] + 1 : 1;
        $updatelog = $query->update(['auth_api' => $logcount]);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            if (!is_null($response->auth)):
                $this->access_token = $response->auth->access_token;
                $setting = Setting::first();
                $setting->access_token = $response->auth->access_token;
                $setting->save();

            endif;
        }
    }

    public function matchShedule()
    {

        $url = "https://rest.cricketapi.com/rest/v2/schedule/?access_token=" . $this->access_token . "&date=2020-02";
        $response = $this->curlCall($url);
        $response = json_decode($response);
        if (!$response->status && $response->status_msg == 'Invalid Access Token') {
            $this->authAPI();
            $url = "https://rest.cricketapi.com/rest/v2/schedule/?access_token=" . $this->access_token;
            $response = json_decode($this->curlCall($url));
            $query = \DB::table('api_log')->where('_id', '5df4e2931723935a209756a8');
            $getlog = $query->first();
            $logcount = isset($getlog['auth_api']) ? $getlog['auth_api'] + 1 : 1;
            $updatelog = $query->update(['auth_api' => $logcount]);
        }
        // Log update
        $query = \DB::table('api_log')->where('_id', '5df4e2931723935a209756a8');
        $getlog = $query->first();
        $logcount = $getlog['schedule_api'] + 1;
        $updatelog = $query->update(['schedule_api' => $logcount]);

        $insert = [];
        if (!is_null($response->data)) {
            foreach ($response->data->months as $month):
                foreach ($month as $days):
                    if (is_array($days) && count($days) > 0):
                        foreach ($days as $dy):
                            if (count($dy->matches) > 0):
                                foreach ($dy->matches as $match):
                                    $mkey = $match->key;
                                    $temp = [];
                                    $temp['format'] = $match->format;
                                    $temp['key'] = $mkey;
                                    $temp['result'] = $match->msgs->result;
                                    $temp['name'] = $match->name;
                                    $temp['related_name'] = $match->related_name;
                                    $temp['short_name'] = $match->short_name;
                                    $temp['start_date'] = $match->start_date->iso;
                                    $temp['status'] = $match->status;
                                    $temp['title'] = $match->title;
                                    $temp['venue'] = $match->venue;
                                    if ($match->winner_team == 'a') {
                                        $temp['winner'] = 'team1';
                                    } else {
                                        $temp['winner'] = 'team2';
                                    }
                                    $temp['team1'] = $match->teams->a->name;
                                    $temp['team1_key'] = $match->teams->a->key;
                                    $temp['team1_season_key'] = $match->teams->a->match->season_team_key;
                                    $temp['team2'] = $match->teams->b->name;
                                    $temp['team2_kay'] = $match->teams->b->key;
                                    $temp['team2_season_key'] = $match->teams->b->match->season_team_key;
                                    $temp['msg_info'] = isset($match->msgs->info) ? $match->msgs->info : '';
                                    $temp['msg_completed'] = isset($match->msgs->completed) ? $match->msgs->completed : '';
                                    $temp['venue'] = $match->venue;
                                    $temp['season_name'] = $match->season->name;
                                    $temp['season_key'] = $match->season->key;
                                    $insert[] = $temp;
                                endforeach;
                            endif;
                        endforeach;
                    endif;
                endforeach;
            endforeach;
        }
        if (count($insert) > 0) {
            Schedule::insert($insert);
        }
    }

    public function updateMatch()
    {
        $date = date('Y-m-d');
        $schedule = Schedule::where('start_date', 'like', '%' . $date . '%')->where('status', '!=', 'completed')->get(); //where('start_date','like','%'.$date.'%')->
        $insert = [];
        $insertp = [];
        foreach ($schedule as $sch):
            if ($sch->key != "1"):
                /// Need to call match api for every match and update the player details for the match ///
                $url = "https://rest.cricketapi.com/rest/v2/match/" . $sch->key . "/?access_token=" . $this->access_token;
                $response = $this->curlCall($url);
                $response = json_decode($response);
                if (!$response->status && $response->status_msg == 'Invalid Access Token') {
                    $this->authAPI();
                    $url = "https://rest.cricketapi.com/rest/v2/match/" . $sch->key . "/?access_token=" . $this->access_token;
                    $response = json_decode($this->curlCall($url));
                    // log update //
                    $query = \DB::table('api_log')->where('_id', '5df4e2931723935a209756a8');
                    $getlog = $query->first();
                    $logcount = isset($getlog['auth_api']) ? $getlog['auth_api'] + 1 : 1;
                    $updatelog = $query->update(['auth_api' => $logcount]);
                }
                // Update match status completed or cancelled etc. //
                if (!is_null($response->data)) {
                    // log update //
                    $query = \DB::table('api_log')->where('_id', '5df4e2931723935a209756a8');
                    $getlog = $query->first();
                    $logcount = $getlog['Match_api'] + 1;
                    $updatelog = $query->update(['Match_api' => $logcount]);

                    $msgs_info = isset($response->data->card->msgs->info) ? $response->data->card->msgs->info : '';
                    $msgs_complete = isset($response->data->card->msgs->completed) ? $response->data->card->msgs->completed : '';
                    $sch->msg_info = $msgs_info;
                    $sch->msg_completed = $msgs_complete;
                    $sch->status = $response->data->card->status;
                    $sch->save();

                    if ($response->data->card->status == 'completed') {
                        $this->updateRank($sch->key);
                    }
                    if ($response->data->card->status == 'started') {
                        $this->cancelContest($sch['key']);
                    }
                    $mplayer = MatchPlayers::where('match_key', $sch['key'])->first();
                    if (!is_object($mplayer)) {
                        $temp = [];
                        $temp1 = [];
                        $temp['match_key'] = $sch->key;
                        foreach ($response->data->card->teams->a->match->players as $plyer) {
                            $temp1['player_key'] = $plyer;
                            $temp['player_key'] = $plyer;
                            $temp['team_key'] = $sch['team1_season_key'];
                            $temp['team_name'] = $response->data->card->teams->a->short_name;
                            $temp['teamlevel'] = 'team1';
                            if ($response->data->card->teams->a->match->playing_xi != '' && in_array($plyer, $response->data->card->teams->a->match->playing_xi)) {
                                $temp['playing_11'] = 1;
                            } else {
                                $temp['playing_11'] = 0;
                            }
                            $insert[] = $temp;
                            $insertp[] = $temp1;
                        }
                        foreach ($response->data->card->teams->b->match->players as $plyer) {
                            $temp1['player_key'] = $plyer;
                            $temp['player_key'] = $plyer;
                            $temp['team_key'] = $sch['team2_season_key'];
                            $temp['team_name'] = $response->data->card->teams->b->short_name;
                            $temp['teamlevel'] = 'team2';
                            if ($response->data->card->teams->b->match->playing_xi != '' && in_array($plyer, $response->data->card->teams->b->match->playing_xi)) {
                                $temp['playing_11'] = 1;
                            } else {
                                $temp['playing_11'] = 0;
                            }
                            $insert[] = $temp;
                            $insertp[] = $temp1;
                        }
                    } else {
                        if ($response->data->card->teams->a->match->playing_xi != '') {
                            foreach ($response->data->card->teams->a->match->playing_xi as $p11) {
                                $matchplayer = MatchPlayers::where('player_key', $p11)->first();
                                if (is_object($matchplayer)):
                                    $matchplayer->playing_11 = 1;
                                    $matchplayer->teamlevel = 'team1';
                                    $matchplayer->save();
                                endif;
                            }
                        }
                        if ($response->data->card->teams->b->match->playing_xi != '') {
                            foreach ($response->data->card->teams->b->match->playing_xi as $p11) {
                                $matchplayer = MatchPlayers::where('player_key', $p11)->first();
                                if (is_object($matchplayer)):
                                    $matchplayer->playing_11 = 1;
                                    $matchplayer->teamlevel = 'team2';
                                    $matchplayer->save();
                                endif;
                            }
                        }
                    }

                } else {
                    echo "<pre>";
                    print_r($response);
                    echo $url;
                }
            endif;
        endforeach;
        if (count($insert) > 0) {
            MatchPlayers::insert($insert);
        }
        if (count($insertp) > 0) {
            Player::insert($insertp);
        }
        echo "done";
    }

    public function cancelContest($matchKey)
    {
        $contestids = Contest::select('_id,contest_size,type')->where('type', 0)->where('status', 1)->get();
        foreach ($contestids as $cids) {
            $fteams = FantasyTeam::where('contest_id', $cids->_id)->where('match_key', $matchKey)->get();
            if (count($fteams) < $cids->contest_size) {
                foreach ($fteams as $fteam):
                    /// Cancel the team when the contest not filled with full slots.
                    $fteam->cancelled = 1;
                    $fteam->save();
                    $refundAmount = $fteam->contestinfo->entry_fee;
                    $user_id = $fteam->user_id;
                    $contest_id = $fteam->contest_id;
                    $users = User::find($user_id);

                    if (is_object($users)) {
                        /// Update account with refund amount
                        $winnings = $users->wallet['winnings'];
                        $bonus = $users->wallet['bonus'];
                        $deposit = $users->wallet['deposit'] + $refundAmount;
                        $currency = $users->wallet['currency'];
                        $total = $users->wallet['total'] + $refundAmount;

                        $users->wallet = ['deposit' => $deposit,
                            'total' => $total,
                            'bonus' => $bonus,
                            'currency' => $currency,
                            'winnings' => $winnings,
                        ];
                        /// Add transaction for refund
                        if ($users->save()) {
                            $temp = [];
                            $temp['uid'] = $user_id;
                            $temp['orderAmount'] = $refundAmount;
                            $temp['txStatus'] = 'SUCCESS';
                            $temp['txMsg'] = 'Transaction Successful';
                            $temp['type'] = 'cancle-refund';
                            $temp['referenceId'] = $contest_id;
                            $transaction[] = $temp;
                        }
                    }
                endforeach;
                if (count($transaction) > 0) {
                    Transaction::insert($transaction);
                }
            }
        }
    }

    public function updatePlayer()
    {
        $players = Player::all();
        foreach ($players as $player):
            $url = "https://rest.cricketapi.com/rest/v2/player/" . $player->player_key . "/league/icc/stats/?access_token=" . $this->access_token;
            $response = $this->curlCall($url);
            $response = json_decode($response);
            /// Log update
            $query = \DB::table('api_log')->where('_id', '5df4e2931723935a209756a8');
            $getlog = $query->first();
            $logcount = isset($getlog['player_api']) ? $getlog['player_api'] + 1 : 1;
            $updatelog = $query->update(['player_api' => $logcount]);
            if (!is_null($response->data)) {
                $player->name = $response->data->player->name;
                $teamArr = [];
                foreach ($response->data->player->recent_teams as $teams):
                    $teamArr[] = $teams->name;
                endforeach;
                $player->teams = $teamArr;
                $player->batting_style = isset($response->data->player->batting_styles[0]) ? $response->data->player->batting_styles[0] : '';
                $player->bowling_style = isset($response->data->player->bowling_styles[0]) ? $response->data->player->bowling_styles[0] : '';
                $player->batting_style = isset($response->data->player->batting_styles[0]) ? $response->data->player->batting_styles[0] : '';
                if ($response->data->player->identified_roles->batsman && $response->data->player->identified_roles->bowler) {
                    $player->role = 'all rounder';
                } elseif ($response->data->player->identified_roles->batsman) {
                $player->role = 'batsman';
            } elseif ($response->data->player->identified_roles->bowler) {
                $player->role = 'bowler';
            }
            if ($response->data->player->identified_roles->keeper) {
                $player->role = 'keeper';
            }
            $player->save();

        }

        endforeach;
        echo "done";
    }

    public function updateCredit()
    {
        $date = date('Y-m-d');
        $schedule = Schedule::where('start_date', 'like', '%' . $date . '%')->where('status', '!=', 'completed')->get();
        foreach ($schedule as $match):
            $url = "https://rest.cricketapi.com/rest/v3/fantasy-match-credits/" . $match->key . "/?access_token=" . $this->access_token;
            $response = $this->curlCall($url);
            $response = json_decode($response);
            $query = \DB::table('api_log')->where('_id', '5df4e2931723935a209756a8');
            $getlog = $query->first();
            $logcount = isset($getlog['player_credit_api']) ? $getlog['player_credit_api'] + 1 : 1;
            $updatelog = $query->update(['player_credit_api' => $logcount]);
            if (!is_null($response->data)) {
                foreach ($response->data->fantasy_points as $fp):
                    $mplayer = MatchPlayers::where('match_key', $match->key)->where('player_key', $fp->player)->first();
                    if (is_object($mplayer)) {
                        $mplayer->credit_value = $fp->credit_value;
                        $mplayer->save();
                    }
                endforeach;
            } else {
                print_r($response);exit;
            }

        endforeach;
        echo "done";
    }

    public function updateFantasyPoints()
    {
        $schedule = Schedule::where('status', 'started')->get(); ///where('key','indwi_2019_one-day_02')->
        if ($schedule->count() > 0):
            foreach ($schedule as $match):
                $url = "https://rest.cricketapi.com/rest/v3/fantasy-match-points/" . $match->key . "/?access_token=" . $this->access_token;
                $response = $this->curlCall($url);
                $response = json_decode($response);
                if (!is_null($response->data)) {
                    // log update //
                    $query = \DB::table('api_log')->where('_id', '5df4e2931723935a209756a8');
                    $getlog = $query->first();
                    $logcount = $getlog['fantasy_api'] + 1;
                    $updatelog = $query->update(['fantasy_api' => $logcount]);

                    foreach ($response->data->fantasy as $fp):
                        $mplayer = MatchPlayers::where('match_key', $match->key)->where('player_key', $fp->player)->first();
                        if (is_object($mplayer)) {
                            $mplayer->match_points = $fp->match_points;
                            $mplayer->save();
                        }
                        $this->updateIndividualPoints($match->key, $fp->player, $fp->match_points);
                    endforeach;
                    echo "<script>
																																																																																																																																		                   var conn = new WebSocket('wss://fantasy.demozab.com:8100');
																																																																																																																																		                  conn.onopen = function (e) {
																																																																																																																																		                    console.log('Connected to websocket');
																																																																																																																																		                    conn.send(JSON.stringify({'type':'Leaderboard','match_key':'" . $match->key . "'}));
																																																																																																																																		                  };
																																																																																																																																		                  </script>
																																																																																																																																		                ";
                } else {
                    echo "<pre>";
                    print_r($response);
                }
            endforeach;
        endif;
        echo "done";
    }

    public function updateLeaderboardPoints()
    {

        // $date = date('Y-m-d');
        // $Schedules = Schedule::where('status','=','started')->where('start_date','like','%'.$date.'%')->get();
        // if($Schedules->count() > 0){
        //     foreach ($Schedules as $s) {
        //        $match_key = $s->key;
        //        // $fantasy_points = MatchPlayers::where('match_key',$match_key)->groupBy('team1')->sum('match_points');

        //        $fantasy = \DB::table('fantasy_teams')->where(['match_key' => $match_key]);
        //        if($fantasy->count() > 0){
        //          $updatefantasy_points = $fantasy->update(['fantasy_points'=>$fantasy_points]);
        //        }
        //     }
        //  }

        // echo "
        //   <script>
        //    var conn = new WebSocket('wss://fantasy.demozab.com:8100');
        //   conn.onopen = function (e) {
        //     console.log('Connected to websocket');
        //     conn.send(JSON.stringify({'type':'Leaderboard','match_key':'paksl_2019_02_test_01'}));
        //   };
        //   </script>
        // " ;

    }

    public function html_email()
    {

        // $to = "amirtha.pixelweb@gmail.com";
        // $subject = "Test mail";
        // $txt = "Hello mail!";
        // $headers = "From: amirtha.pixelweb@gmail.comm" . "\r\n" ;

        // @mail($to,$subject,$txt,$headers);
        // echo "HTML Email Sent. Check your inbox.";
    }

    public function curlCall($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Host: rest.cricketapi.com",
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function getplayers($match_key, $response)
    {
        // $match_key ='indwi_2019_t20_03';
        // $date = date('Y-m-d');
        // foreach ($schedule as $match):
        // $url = "https://rest.cricketapi.com/rest/v3/fantasy-match-points/".$match_key."/?access_token=".$this->access_token;
        // $response = $this->curlCall($url);
        // $response = json_decode($response);
        if (!is_null($response->data)) {
            $pointsArr = [];
            foreach ($response->data->fantasy as $fp):
                //  $this->updateIndividualPoints($match_key,$fp->player,$fp->match_points);
                $mplayer = MatchPlayers::where('match_key', $match_key)->where('player_key', $fp->player)->first();
                if (is_object($mplayer)) {
                    $playerArr = $this->players($match_key, $fp->player);
                    if (count($playerArr) > 0):
                        foreach ($playerArr as $k => $pArr) {
                            if (in_array($fp->player, $pArr)) {
                                if (isset($pointsArr[$k])) {
                                    $pointsArr[$k] = $pointsArr[$k] + $fp->match_points;
                                } else {
                                    $pointsArr[$k] = $fp->match_points;
                                }
                            }
                        }
                    endif;
                }
            endforeach;
            foreach ($pointsArr as $key => $value) {
                $where = explode("*", $key);
                $matchkey = $where[0];
                $fantasy = \DB::table('fantasy_teams')->where(['match_key' => $where[0], 'contest_id' => $where[1], 'user_id' => $where[2]])->update(['fantasy_points' => $value]);
                // Need to update individual player fantasy points //
            }
        }
    }

    public function updateIndividualPoints($match_key = 'qtrlt10_2019_g10', $player_key = 'a_alam', $match_points = 30)
    {
        $fantasy = FantasyTeam::where(['match_key' => $match_key])->get();
        if ($fantasy->count() > 0) {
            foreach ($fantasy as $fteam) {
                $players = $fteam['players'];
                $plp = [];
                foreach ($players as $k => $pl) {
                    if (isset($player_key) && isset($pl['player_key'])) {
                        if ($player_key == $pl['player_key']):
                            if (isset($pl['caption']) && $pl['caption'] == 1) {
                                $pl['fantasy_points'] = $match_points * 2;
                            } elseif (isset($pl['v_caption']) && $pl['v_caption'] == 1) {
                            $pl['fantasy_points'] = $match_points * 1.5;
                        } else {
                            $pl['fantasy_points'] = $match_points;
                        }
                        $plp[$k] = $pl;
                        // $update = \DB::table('fantasy_teams')->where(['match_key' => $match_key])->update(['fantasy_points'=>$pl['fantasy_points'] ]);
                        else:
                            $plp[$k] = $pl;
                        endif;
                    }
                }
                $fteam['players'] = $plp;
                $tot = 0;
                foreach ($plp as $tplp):
                    $tot = $tot + $tplp['fantasy_points'];
                endforeach;
                $fteam->fantasy_points = $tot;
                $fteam->save();
            }
        }
        echo "done";
    }

    public function updateWinner($match_key = 'uaetriseries_2019_g3')
    {
        $contests = Contest::all();
        foreach ($contests as $contest):
            $wteam = FantasyTeam::where('match_key', $match_key)->where('contest_key', $contest->_id)->orderBy('fantasy_points', 'desc')->get();
            dd($wteam);
        endforeach;

    }

    public function players($match_key, $player_key)
    {
        $fantasy = \DB::table('fantasy_teams')->where(['match_key' => $match_key])->get();
        $ind_players = [];
        $count = [];
        if ($fantasy->count() > 0) {
            foreach ($fantasy as $key => $player) {
                $players = $player['players'];
                $contest_id = $player['contest_id'];
                $user_id = $player['user_id'];

                $keyvalue = $match_key . '*' . $contest_id . '*' . $user_id;
                foreach ($players as $key1 => $pl) {
                    if (isset($pl['player_key'])) {
                        $ind_players[$keyvalue][] = $pl['player_key'];
                    }
                }
            }
            return $ind_players;
        }

    }

    public function deleteSchedule()
    {
        $tobeDeleted = Schedule::where('key', true)->get();
        foreach ($tobeDeleted as $tbd) {
            $tbd->delete();
        }
    }
    public function updateWithdrawRequest()
    {

        $transaction = Transaction::where('txStatus', 100)->get();

        if ($transaction->count() > 0) {
            foreach ($transaction as $txn) {
                $batchTransferId = $txn->batchTransferId;
                if ($batchTransferId != '') {
                    $checkstatus = $this->cashfree_getcurl('/payout/v1/getBatchTransferStatus?batchTransferId=' . $batchTransferId);

                    if ($checkstatus != '' && $checkstatus['status'] == 'ERROR' && $checkstatus['message'] == 'Token is not valid') {
                        $checkstatus = $this->cashfree_getcurl('/payout/v1/getBatchTransferStatus?batchTransferId=' . $transferId);
                    } else if ($checkstatus != '' && $checkstatus['status'] == 'SUCCESS' && $checkstatus['subCode'] == 200) {

                        if (isset($checkstatus['data']['transfers']) && count($checkstatus['data']['transfers']) > 0) {
                            foreach ($checkstatus['data']['transfers'] as $transfer) {
                                $transferId = $transfer->transferId;
                                $update = Transaction::where(['txStatus' => 100, 'transferId' => $transferId])->update(['utr' => $transfer->utr, 'txStatus' => 1]);
                            }
                            echo "send";
                        }
                    }

                }
            }
        }
    }

    public function updateRank($matchkey)
    {
        //$matchkey='sst20_2019_g15';
        $schedule = FantasyTeam::where(['match_key' => $matchkey, 'winner_status' => 0])->get();
        $contestids = [];
        if (!empty($schedule)):
            foreach ($schedule as $fantasy):
                if ($fantasy->winner_status == 0):
                    $total_fp = 0;
                    if (!empty($fantasy->players)):
                        foreach ($fantasy->players as $fp):
                            $fantasy_points = isset($fp['fantasy_points']) ? $fp['fantasy_points'] : 0;
                            $total_fp = $total_fp + $fantasy_points;

                        endforeach;
                    endif;
                    $fantasy->fantasy_points = $total_fp;
                    $fantasy->save();
                    $contestids[] = ['contest_id' => $fantasy->contest_id];
                endif;
            endforeach;
        endif;
        $this->rankupdate($matchkey, $contestids);

    }

    public function rankupdate($matchkey, $contestids)
    {
        if (count($contestids) > 0) {
            foreach ($contestids as $value) {

                $contest_id = $value['contest_id'];
                $fantasy = FantasyTeam::where(['match_key' => $matchkey, 'contest_id' => $contest_id, 'winner_status' => 0])->orderBy('fantasy_points', 'desc')->with('contestinfo')->get();

                if ($fantasy->count() > 0) {
                    foreach ($fantasy as $key => $fp) {
                        $winners = [];
                        $rank = $key + 1;
                        $fp->rank = $rank;
                        $fp->save();
                        $ranks = '#' . $rank;
                        $winners = $fp->contestinfo['winners'];
                        $price_type = $fp->contestinfo['price_type'];

                        if (count($winners) > 0) {
                            foreach ($winners as $keys => $value) {

                                if ($price_type == 'range') {
                                    $split_rank_key = ltrim($keys, '#');
                                    $rank_array = explode('-', $split_rank_key);
                                    $fromrange = $rank_array[0];
                                    $torange = $rank_array[1];
                                    if (($fromrange != '' && $torange != '') && $rank > $fromrange && $rank <= $torange) {
                                        $fp->price_winning_amount = $value;
                                        $fp->winner_status = 1;
                                        $fp->save();
                                        break;
                                    }
                                }
                                if ($price_type == 'individual') {

                                    if ($keys == $ranks):
                                        $fp->price_winning_amount = $value;
                                        $fp->winner_status = 1;
                                        $fp->save();
                                        break;
                                    endif;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function updateWinnerPrice()
    {
        $fantasy = FantasyTeam::where(['winner_status' => 1, 'price_update_status' => 0])->get();

        if ($fantasy->count() > 0) {

            foreach ($fantasy as $key => $fp) {
                $prize_amount = $fp->price_winning_amount;
                $user_id = $fp->user_id;
                $contest_id = $fp->contest_id;
                $users = User::find($user_id);

                if ($users != '') {
                    $wallet = $users->wallet;
                    if (isset($wallet['winnings'])) {
                        $winnings = $users->wallet['winnings'] + $prize_amount;
                        $bonus = $users->wallet['bonus'];
                        $deposit = $users->wallet['deposit'];
                        $currency = $users->wallet['currency'];
                        $total = $users->wallet['total'] + $winnings;

                        $users->wallet = ['deposit' => $deposit,
                            'total' => $total,
                            'bonus' => $bonus,
                            'currency' => $currency,
                            'winnings' => $winnings,
                        ];

                        if ($users->save()) {
                            $Transaction = new Transaction;
                            $Transaction->uid = $user_id;
                            $Transaction->orderAmount = $prize_amount;
                            $Transaction->txStatus = 'SUCCESS';
                            $Transaction->txMsg = 'Transaction Successful';
                            $Transaction->type = 'winning';
                            $Transaction->referenceId = $contest_id;
                            if ($Transaction->save()) {
                                $fp->price_update_status = 1;
                                $fp->save();
                            }
                        }
                    }
                }
            }
        }
    }

}