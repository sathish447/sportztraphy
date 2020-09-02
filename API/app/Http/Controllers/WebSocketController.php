<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\MatchPlayers;
use App\Models\FantasyTeam;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketController extends Controller implements MessageComponentInterface
{

    private $connections = [];
    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections[$conn->resourceId] = compact('conn') + ['uid' => null];
        // $this->clients->attach($conn);
        echo "Connection Established! \n";
        echo " new conncection " . $conn->resourceId . "\n";
    }
    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    public function OnClose(ConnectionInterface $conn)
    {
        $disconnectedId = $conn->resourceId;
        unset($this->connections[$disconnectedId]);
        // foreach($this->connections as &$connection)
        //     $connection['conn']->send(json_encode([
        //         'offline_user' => $disconnectedId,
        //         'from_user_id' => 'server control',
        //         'from_resource_id' => null
        //     ]));
        echo "Connection Closed! \n";
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $userId = $this->connections[$conn->resourceId]['uid'];
        echo " {$e->getMessage()}\n";
        unset($this->connections[$conn->resourceId]);
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */

    public function onMessage(ConnectionInterface $conn, $data)
    {
        $messageObj = json_decode($data);
        $data = null;
        if (isset($messageObj->type) && $messageObj->type == 'Leaderboard' && $messageObj->match_key != '' && $messageObj->contest_id != '' && $messageObj->user_id != '') {
            $Leaderboard = $this->Leaderboard($messageObj);
            $data = ['status' => true, 'response' => $Leaderboard['alldata'], 'loginresponse' => '', 'match_key' => $Leaderboard['match_key'], 'contest_id' => $Leaderboard['contest_id'], 'leaderboard' => $Leaderboard['json'],'player_details' => isset($Leaderboard['json'][0]['players_stat'])?$Leaderboard['json'][0]['players_stat']:[], 'score' => $Leaderboard['score'], 'loginLeaderboard' => $Leaderboard['loginjson'], 'message' => 'Success', 'match_status' => $Leaderboard['match_status']];
        }
        foreach ($this->connections as $resourceId => &$connection) {
            $connection['conn']->send(json_encode($data));
        }
    }

    public function Leaderboard($obj)
    {
        $match_key = $obj->match_key;
        $userid = isset($obj->user_id) ? $obj->user_id : '';
        $contest_id = isset($obj->contest_id) ? $obj->contest_id : '';
        $json = [];
        $alldata = $this->getHtmldata($match_key, $contest_id, $userid, 'all');
        $contest = \DB::table('fantasy_teams')
            ->where(['match_key' => $match_key, 'contest_id' => $contest_id, 'paid_status' => 1])
            ->orderBy('fantasy_points', 'desc')
            ->get();
        $match_type = $obj->match_type; 
        if (count($contest) > 0) {
            foreach ($contest as $key => $ci) { 
                $fpoints = $ci['fantasy_points'];
                $rank = $fpoints == 0 ? '-' : $key + 1;
                $players1 = $ci['players'];
                $batsman = [];
                $bowler = [];
                $keeper = [];
                $allrounder = [];
               
                $batsman = [];
                $pl_fantasy= [];
                foreach ($players1 as $pl) {
                    $pl_fantasy[$pl['player_key']] = $pl['fantasy_points'];
                    $players_role = isset($pl['role']) ? $pl['role'] : '';
                    if($match_type == 'cricket'){
                        if ($players_role != '' && $pl['role'] == 'wk') {
                            $keeper[] = $pl;
                        } else if ($players_role != '' && $pl['role'] == 'bowl') {
                            $bowler[] = $pl;
                        } else if ($players_role != '' && $pl['role'] == 'bat') {
                            $batsman[] = $pl;
                        } else if ($players_role != '' && $pl['role'] == 'ar') {
                            $allrounder[] = $pl;
                        } 
                    } else {
                        if ($players_role != '' && $pl['role'] == 'gk') {
                            $keeper[] = $pl;
                        } else if ($players_role != '' && $pl['role'] == 'mid') {
                            $bowler[] = $pl;
                        } else if ($players_role != '' && $pl['role'] == 'def') {
                            $batsman[] = $pl;
                        } else if ($players_role != '' && $pl['role'] == 'st') {
                            $allrounder[] = $pl;
                        } 
                    }
                }
                $player_stats=[];
                $playerstats = MatchPlayers::where('match_key', (int)$match_key)->get();
                foreach ($playerstats as $key => $player_match_stats) {
                    $temp = [];
                    $temp['name'] = $player_match_stats['name'];
                    $temp['playing_11'] = $player_match_stats['playing_11'];
                    $temp['credit_value'] = $player_match_stats['credit_value'];
                    $temp['fantasy_points'] = (isset($pl_fantasy[strval($player_match_stats['player_key'])]))?$pl_fantasy[strval($player_match_stats['player_key'])]:"0";
                    /*$temp['match_key'] = $player_match_stats['name'];
                    $temp['type'] = $player_match_stats['type'];
                    $temp['team_key'] = $player_match_stats['team_key'];
                    $temp['teamlevel'] = $player_match_stats['teamlevel'];
                    $temp['player_key'] = $player_match_stats['player_key'];
                    $temp['role'] = $player_match_stats['role'];
                    $temp['match_id'] = $player_match_stats['match_id'];*/                    
                    $player_stats[] = $temp;
                }

                $json[] = ['id' => $ci['_id'], 'match_key' => $ci['match_key'], 'contest_id' => $ci['contest_id'],
                    'user_name' => $ci['user_name'], 'user_id' => $ci['user_id'],
                    'fantasy_points' => $ci['fantasy_points'], 'rank' => $rank,
                    'players' => ['keeper' => $keeper, 'bowler' => $bowler, 'batsman' => $batsman,'allrounder' => $allrounder],
                    'players_stat' => $player_stats
                ];
            }
        } else {
            $json = [];
        }   
        if($match_type == 'football'){
            $mdetails = Schedule::where('match_id', $match_key)->first();
            $team1['innings'] = "";
            $team1['runs'] = @$mdetails->score_team1->first_half + @$mdetails->score_team1->second_half;
            $team1['run_str'] = "";
            $team1['wickets'] = 0;
            $team1['balls'] =  0;
            $team1['run_rate'] = "";
            $team1['req_run_str'] = "";
            $team1['target_runs'] = 0;
            $team1['target_balls'] = 0;
            $team1['team_name'] = $mdetails->team1_key;
            $datas['score']['score_team1'] = $team1;
            //-------------------------//
            $team2['innings'] = "";
            $team2['runs'] = @$mdetails->score_team2->first_half + @$mdetails->score_team2->second_half;
            $team2['run_str'] = "";
            $team2['wickets'] = 0;
            $team2['balls'] =  0;
            $team2['run_rate'] = "";
            $team2['req_run_str'] = "";
            $team2['target_runs'] = 0;
            $team2['target_balls'] = 0;
            $team2['team_name'] = $mdetails->team2_kay;            
            $datas['score']['score_team2'] = $team1;
            $datas['score']['football_result'] = @$mdetails->result;
          
        } else {
            $mdetails = Schedule::where('key', $match_key)->first();
            

            $datas['score'] = ['team1_name' => $mdetails->team1_key, "team2_name" => $mdetails->team2_kay,
            'first_batting' => $mdetails->first_batting, 
            'score_team2' => isset($mdetails->score_team2) ? $mdetails->score_team2->first_half + @$mdetails->score_team2->second_half  : (object) [],
            'score_team1' => isset($mdetails->score_team1) ? $mdetails->score_team1->first_half + @$mdetails->score_team1->second_half : (object) [],
            'football_result' => @$mdetails->result];
        }
       
        $datas['alldata'] = $alldata;
        $datas['json'] = $json;
        $datas['loginjson'] = [];
        $datas['contest_id'] = $contest_id;
        $datas['match_key'] = $match_key;
        $datas['match_status'] = $mdetails->status;
        return $datas;
    }

    public function getHtmldata($match_key, $contest_id, $userid, $type)
    {
        if ($type == 'all') {
            $allLists = \DB::table('fantasy_teams')->where(['match_key' => $match_key, 'contest_id' => $contest_id, 'paid_status' => 1])->orderBy('fantasy_points', 'desc')->get();
        } else {
            $allLists = \DB::table('fantasy_teams')->where(['match_key' => $match_key, 'contest_id' => $contest_id, 'user_id' => $userid])->orderBy('fantasy_points', 'desc')->get();
        }

        $alldata = '';
        if ($allLists->count() > 0) {
            foreach ($allLists as $key => $li) {

                $user_name = isset($li['user_name']) ? $li['user_name'] : '';
                $fpoints = $li['fantasy_points'];
                $rank = ($fpoints == 0) ? '-' : $key + 1;
                $paid_status = isset($li['paid_status']) ? $li['paid_status'] : 0;
                $keyid = $li['_id'];

                //if ($type == 'all' && $paid_status == 1) {
                $alldata .= ' <div data-target=#open-' . $keyid . ' data-toggle="collapse" class="leader-board-div white-box showteam"> <div class="leader-div1">' . $user_name . '</div> <div class="leader-div2">' . $li['fantasy_points'] . '</div> <div class="leader-div3">' . $rank . '</div></div>';
                // } else {
                //     $alldata .= ' <div data-target=#open-' . $keyid . ' data-toggle="collapse" class="leader-board-div white-box"> <div class="leader-div1">' . $user_name . '</div>          <div class="leader-div2">' . $li['fantasy_points'] . '</div> <div class="leader-div3">' . $rank . '</div></div>';
                // }

                $players = $li['players'];
                $bowler1 = [];
                $keeper1 = [];
                $allrounder1 = [];
                $batsman1 = [];
                foreach ($players as $pl) {
                    $players_role = isset($pl['role']) ? $pl['role'] : '';
                    if ($players_role != '' && $pl['role'] == 'wk') {
                        $keeper1[] = $pl;
                    } else if ($players_role != '' && $pl['role'] == 'bowl') {
                        $bowler1[] = $pl;
                    } else if ($players_role != '' && $pl['role'] == 'bat') {
                        $batsman1[] = $pl;
                    } else if ($players_role != '' && $pl['role'] == 'ar') {
                        $allrounder1[] = $pl;
                    }
                    $players_role = isset($pl['role']) ? $pl['role'] : '';
                    if ($players_role != '' && $pl['role'] == 'gk') {
                        $keeper1[] = $pl;
                    } else if ($players_role != '' && $pl['role'] == 'mid') {
                        $batsman1[] = $pl;
                    } else if ($players_role != '' && $pl['role'] == 'def') {
                        $allrounder1[] = $pl;
                    } else if ($players_role != '' && $pl['role'] == 'st') {
                        $bowler1[] = $pl;
                    }
                    $player_stats = $pl;
                }
                $img = "https://fantasy.demozab.com/assets/images/ground-bg1.jpg";
                $alldata .= '<div class="collapse" data-parent="#accordionExample" id="open-' . $keyid . '" ><div class="socket white-box v-ground white-t" style="background-image:url(' . $img . '); background-size: 100%; background-position: center center;"><table class="select-table-player white-t"><tbody><tr><td><h6> Wicket-Keepers </h6><table>
<tbody>';
                if (isset($keeper1) && count($keeper1) > 0):
                    foreach ($keeper1 as $key => $wk) {
                        if ($wk['caption'] == 1) {
                            $chigh = "captain";
                        } elseif ($wk['v_caption'] == 1) {
                        $chigh = " vcaptain";
                    } else {
                        $chigh = "";
                    }

                    $alldata .= '
<tr class="player-data-td ' . $chigh . '">
<td class="player-pic"><img src="assets/images/player.svg"></td>
<td class="player-name h6">' . $wk['name'] . '<br><span class="grey-t s-t"> WK - ' . $wk['team_name'] . '</span></td>
<td>' . $wk["fantasy_points"] . '<br><span class="grey-t s-t">Points</span></td>
</tr> ';
                } else :
                    $alldata .= ' <tr> <td colspan="4"> - </td> </tr> ';
                endif;
                $alldata .= '
</tbody>
</table>
                            </td>
                            <td>
                            <h6>Batsmen</h6>
                            <table>
                            <tbody>';
                if (isset($batsman1) && count($batsman1) > 0):
                    foreach ($batsman1 as $wk) {
                        if ($wk['caption'] == 1) {
                            $chigh = " captain";
                        } elseif ($wk['v_caption'] == 1) {
                        $chigh = " vcaptain";
                    } else {
                        $chigh = "";
                    }
                    $alldata .= '
<tr class="player-data-td' . $chigh . '">
<td class="player-pic"><img src="assets/images/player.svg"></td>
<td class="player-name h6">' . $wk['name'] . '<br><span class="grey-t s-t"> BAT - ' . $wk['team_name'] . '</span></td>
<td>' . $wk["fantasy_points"] . '<br>
<span class="grey-t s-t">Points</span>
</td>
</tr> ';
                } else :
                    $alldata .= ' <tr> <td colspan="4">-</td> </tr> ';
                endif;

                $alldata .= ' </tbody>
                            </table>
                            </td>

                            <td>
                            <h6>All-Rounders</h6>
                            <table>
                            <tbody>';
                if (isset($allrounder1) && count($allrounder1) > 0):
                    foreach ($allrounder1 as $wk) {
                        if ($wk['caption'] == 1) {
                            $chigh = " captain";
                        } elseif ($wk['v_caption'] == 1) {
                        $chigh = " vcaptain";
                    } else {
                        $chigh = "";
                    }
                    $alldata .= '
<tr class="player-data-td' . $chigh . '">
<td class="player-pic"><img src="assets/images/player.svg"></td>
<td class="player-name h6">' . $wk['name'] . '<br><span class="grey-t s-t"> AR - ' . $wk['team_name'] . '</span></td>
<td>' . $wk["fantasy_points"] . '<br>
<span class="grey-t s-t">Points</span>
</td>
</tr> ';
                } else :
                    $alldata .= ' <tr> <td colspan="4">-</td> </tr> ';
                endif;
                $alldata .= ' </tbody>
                            </table>
                            </td>
                            <td>
                            <h6>Bowlers</h6>
                            <table>
                            <tbody>
                            ';

                if (isset($bowler1) && count($bowler1) > 0):
                    foreach ($bowler1 as $wk) {
                        if ($wk['caption'] == 1) {
                            $chigh = " captain";
                        } elseif ($wk['v_caption'] == 1) {
                        $chigh = " vcaptain";
                    } else {
                        $chigh = "";
                    }
                    $alldata .= '
<tr class="player-data-td' . $chigh . '">
<td class="player-pic"><img src="assets/images/player.svg"></td>
<td class="player-name h6">' . $wk['name'] . '<br><span class="grey-t s-t"> BOWL - ' . $wk['team_name'] . '</span></td>
<td>' . $wk["fantasy_points"] . '<br>
<span class="grey-t s-t">Points</span>
</td>
</tr> ';
                } else :
                    $alldata .= ' <tr> <td colspan="4"> - </td> </tr> ';
                endif;
                $alldata .= '</tbody>
                            </table>
                            </td>
                            </tr>
                            </tbody>
                            </table>
                            </div></div>';
            }

        } else {
            $alldata = '';
        }
        return $alldata;
    }

}