<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\ContestsCategory;
use App\Models\FantasyTeam;
use App\Models\Schedule;
use App\Models\Transaction;
use App\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ContestController extends Controller
{
    public function Contest_list(Request $request)
    {
        $details = Contest::where('created_by', 'admin')
            ->where('status', 1)
            ->get();

        $team_details = FantasyTeam::select('_id')
            ->where('match_key', $request->id)
            ->where('user_id', auth()->user()->_id)
            ->get();

        $cat_details = array();

        $contest_cat_details = ContestsCategory::all();
        foreach ($contest_cat_details as $cat_key => $cat_value) {

            $count_contest = Contest::where('created_by', 'admin')
                ->where('status', 1)
                ->where('cat_id', $cat_value->_id)
                ->count();

            $cat_details[$cat_key]['name'] = $cat_value->cat_name;
            $cat_details[$cat_key]['show_status'] = $count_contest;
        }

        $temp = [];
        $temp1 = [];

        foreach ($details as $cat => $value) {

            $listcount = FantasyTeam::where('contest_id', $value->_id)
                ->where('match_key', $request->id)
                ->where('user_id', auth()->user()->_id)
            // ->where('paid_status',1)
                ->count();

            $team_create_check = FantasyTeam::where('contest_id', $value->_id)
                ->where('match_key', $request
                    ->id) 
                ->where('user_id', auth()->user()->_id)
                ->first();

            $team_create_count = 0;
            if (is_object($team_create_check)) {
                $team_create_count = $team_create_check->count();
                $create_team_status = 1;
                $paid_status = $team_create_check->paid_status;
            } else {
                $create_team_status = 0;
                $paid_status = 0;
            }

            $joinedlistcount = FantasyTeam::where('contest_id', $value->_id)
                ->where('match_key', $request->id)
            // ->where('user_id',auth()->user()->_id)
                ->where('paid_status', 1)
                ->count();

            if (isset($joinedlistcount)) {
                $teamcount = $value->contest_size - $joinedlistcount;
                $range = $joinedlistcount / $value->contest_size * 100;
                $range = 100 - $range;
            } else {
                $teamcount = $value->contest_size;
                $range = $joinedlistcount / $value->contest_size * 100;
                $range = 100 - $range;
            }

            $value->total_contest = $teamcount;
            $value->range = $range;
            $value->rank = 0;
            $value->winning_prize = 0;
            $value->paid_status = $paid_status;
            $value->create_team_status = $create_team_status;
            // $value = $team_details->_id;

            $winners = $value->winners;
            $con_details = array();
            $con_details['contest_id'] = $value->_id;
            $con_details['contest_name'] = $value->contest_name;
            $con_details['contest_size'] = $value->contest_size;
            $con_details['prize_pool'] = $value->prize_pool;
            $con_details['entry_fee'] = $value->entry_fee;
            $con_details['multiple'] = $value->multiple;
            $con_details['win_percent'] = round($value->win_percent);
            $con_details['create_team_status'] = $value->create_team_status;
            if (is_object($value->paid_status)) {
                $con_details['paid_status'] = $value->paid_status;
            } else {
                $con_details['paid_status'] = 0;
            }

            $con_details['range'] = $value->range;
            $con_details['total_contest'] = $value->total_contest;
            $con_details['type'] = $value->type;
            $con_details['status'] = $value->status;
            $newdata = [];

            foreach ($winners as $key => $val) {
                $newdata[] = $key . ' - ' . $val;
            }

            $con_details['total_contest'] = $teamcount;
            $con_details['range'] = $range;
            $con_details['winners'] = $newdata;
            $con_details['cat_id'] = $value->cat_id;
            $con_details['cat_name'] = $value->category->cat_name;
            $con_details['join_status'] = $listcount;
            $temp[] = $con_details;
            // $temp1[$value->cat_id][] = $con_details;
        }

        $response = array('contestdetails' => $temp, 'team_create_count' => count($team_details), 'teamdetails' => $team_details, 'category_details' => $cat_details);

        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    /**
     * Football contest list along with teamlist created, joined contest list
     *
     * @param Request $request
     * @return void
     */
    public function footcontest_list(Request $request)
    {
        $details = Contest::where('created_by', 'admin')
            ->where('status', 1)
            ->get();
            
        $team_details = FantasyTeam::select('_id')
            ->where('match_key',$request->id)
            ->where('user_id', auth()->user()->_id)
            ->get();
        $cat_details = array();

        $contest_cat_details = ContestsCategory::all();
        foreach ($contest_cat_details as $cat_key => $cat_value) {

            $count_contest = Contest::where('created_by', 'admin')
                ->where('status', 1)
                ->where('cat_id', $cat_value->_id)
                ->count();

            $cat_details[$cat_key]['name'] = $cat_value->cat_name;
            $cat_details[$cat_key]['show_status'] = $count_contest;
        }

        $temp = [];
        $temp1 = [];

        foreach ($details as $cat => $value) {

            $listcount = FantasyTeam::where('contest_id', $value->_id)
                ->where('match_key', $request->id)
                ->where('user_id', auth()->user()->_id)
            // ->where('paid_status',1)
                ->count();

            $team_create_check = FantasyTeam::where('contest_id', $value->_id)
                ->where('match_key', $request->id) 
                ->where('user_id', auth()->user()->_id)
                ->first();

            $team_create_count = 0;
            if (is_object($team_create_check)) {
                $team_create_count = $team_create_check->count();
                $create_team_status = 1;
                $paid_status = $team_create_check->paid_status;
            } else {
                $create_team_status = 0;
                $paid_status = 0;
            }

            $joinedlistcount = FantasyTeam::where('contest_id', $value->_id)
                ->where('match_key', $request->id)
            // ->where('user_id',auth()->user()->_id)
                ->where('paid_status', 1)
                ->count();

            if (isset($joinedlistcount)) {
                $teamcount = $value->contest_size - $joinedlistcount;
                $range = $joinedlistcount / $value->contest_size * 100;
                $range = 100 - $range;
            } else {
                $teamcount = $value->contest_size;
                $range = $joinedlistcount / $value->contest_size * 100;
                $range = 100 - $range;
            }

            $value->total_contest = $teamcount;
            $value->range = $range;
            $value->rank = 0;
            $value->winning_prize = 0;
            $value->paid_status = $paid_status;
            $value->create_team_status = $create_team_status;
            // $value = $team_details->_id;

            $winners = $value->winners;
            $con_details = array();
            $con_details['contest_id'] = $value->_id;
            $con_details['contest_name'] = $value->contest_name;
            $con_details['contest_size'] = $value->contest_size;
            $con_details['prize_pool'] = $value->prize_pool;
            $con_details['entry_fee'] = $value->entry_fee;
            $con_details['multiple'] = $value->multiple;
            $con_details['win_percent'] = round($value->win_percent);
            $con_details['create_team_status'] = $value->create_team_status;
            if (is_object($value->paid_status)) {
                $con_details['paid_status'] = $value->paid_status;
            } else {
                $con_details['paid_status'] = 0;
            }

            $con_details['range'] = $value->range;
            $con_details['total_contest'] = $value->total_contest;
            $con_details['type'] = $value->type;
            $con_details['status'] = $value->status;
            $newdata = [];

            foreach ($winners as $key => $val) {
                $newdata[] = $key . ' - ' . $val;
            }

            $con_details['total_contest'] = $teamcount;
            $con_details['range'] = $range;
            $con_details['winners'] = $newdata;
            $con_details['cat_id'] = $value->cat_id;
            $con_details['cat_name'] = $value->category->cat_name;
            $con_details['join_status'] = $listcount;
            $temp[] = $con_details;
            // $temp1[$value->cat_id][] = $con_details;

        }

        $response = array('contestdetails' => $temp, 'team_create_count' => count($team_details), 'teamdetails' => $team_details, 'category_details' => $cat_details);

        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function entryteamdetails(Request $request)
    {
        $teamdetailscount = FantasyTeam::where('match_key', $request['id'])->where('user_id', auth()->user()->_id)->get();

        $team_player_details = array();
        foreach ($teamdetailscount as $index => $value) {
            $team_player_details[$index]['teamid'] = $value->_id;
            foreach ($value->players as $key => $players) {

                if ($players['caption'] == '1') {
                    $team_player_details[$index]['caption'] = $players['name'];
                } else if ($players['v_caption'] == '1') {
                    $team_player_details[$index]['v_caption'] = $players['name'];
                }
            }
        }
        $response = array('teamdetailscount' => count($teamdetailscount), 'team_details_count' => $team_player_details);

        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function userContest(Request $request)
    {
        $type = (isset($request->type) && $request->type!='') ? $request->type : "cricket";
        $user_contest_details = FantasyTeam::with('contests')->with('match_details')
            ->where('match_key', $request->match_key)
            ->where('user_id', auth()->user()->_id)
            ->where('match_type', $type)
            ->orderBy('fantasy_team.created_at', 'desc')
            ->get();

        $user_contest_count = FantasyTeam::with('contests')
            ->where('match_key', $request->match_key)
            ->where('user_id', auth()->user()->_id)
            ->where('paid_status', 1)
            ->get();

        if (isset($user_contest_details)) {
            foreach ($user_contest_details as $value) {
                if (isset($value->contests->_id)) {
                    $end = Carbon::parse($value->match_details->start_date)->setTimezone('Asia/Kolkata');

                    $listcount = FantasyTeam::where('contest_id', $value->contests->_id)
                        ->where('match_key', $request->match_key)
                        ->where('paid_status', 1)
                        ->count();

                    $text_status = '';

                    if ($value->cancelled == '1') {
                        $text_status = 'Cancelled';
                    } else {
                        if ($value->paid_status == '1') {
                            $text_status = 'Joined';
                        } else {
                            $text_status = 'Not Joined';
                        }
                    }

                    $data = [];

                    // dd($value->players);

                    foreach ($value->players as $key => $pvalue) {

                        if (isset($pvalue['credit']) || array_key_exists('credit', $pvalue)) {
                            $data[$key] = $pvalue;
                        } else {
                            $pvalue['credit'] = '0';
                            $data[$key] = $pvalue;
                        }
                    }

                    $teamcount = $value->contests->contest_size - $listcount;
                    $range = $listcount / $value->contests->contest_size * 100;
                    $range = 100 - $range;
                    $range = round($range);
                    $value->contests->total_contest = $teamcount;
                    $value->contests->range = $range;
                    $value->rank = 0;
                    $value->winning_prize = 0;
                    $value->points = 0;
                    $value->text_status = $text_status;
                    $value->players = $data;

                    $value->multiple = $value->contests->multiple;
                    $value->win_percent = round($value->contests->win_percent);

                    $date = explode('T', $value->match_details->start_date);
                    $value['contests']['left_time'] = strtotime($date[0]);
                    $value['contests']['start_date'] = $value->match_details->start_date;
                    $dt = new DateTime($value->match_details->start_date);
                    $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
                    $value['contests']['start_indian_date'] = $dt->format('d-m-Y H:i:s');
                }
            }

            $response = array('user_contest_details' => $user_contest_details, 'user_contest_count' => $user_contest_count->count());
            $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        } else {
            $response = array('user_contest_details' => null, 'user_contest_count' => '0');
            $yourData = ['status' => false, 'response' => $response, 'message' => ''];

        }
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
        public function footuserContest(Request $request)
    {
        $type = (isset($request->type) && $request->type!='') ? $request->type : "cricket";
        $user_contest_details = FantasyTeam::with('contests')->with('foot_match_details')
            ->where('match_key', $request->match_key)
            ->where('user_id', auth()->user()->_id)
            ->where('match_type', $type)
            ->orderBy('fantasy_team.created_at', 'desc')
            ->get();

        $user_contest_count = FantasyTeam::with('contests')
            ->where('match_key', $request->match_key)
            ->where('user_id', auth()->user()->_id)
            ->where('paid_status', 1)
            ->get();

        if (isset($user_contest_details)) {
            foreach ($user_contest_details as $value) {
                if (isset($value->contests->_id)) {
                    $end = Carbon::parse($value->foot_match_details->start_date)->setTimezone('Asia/Kolkata');

                    $listcount = FantasyTeam::where('contest_id', $value->contests->_id)
                        ->where('match_key', $request->match_key)
                        ->where('paid_status', 1)
                        ->count();

                    $text_status = '';

                    if ($value->cancelled == '1') {
                        $text_status = 'Cancelled';
                    } else {
                        if ($value->paid_status == '1') {
                            $text_status = 'Joined';
                        } else {
                            $text_status = 'Not Joined';
                        }
                    }

                    $data = [];

                    // dd($value->players);

                    foreach ($value->players as $key => $pvalue) {

                        if (isset($pvalue['credit']) || array_key_exists('credit', $pvalue)) {
                            $data[$key] = $pvalue;
                        } else {
                            $pvalue['credit'] = '0';
                            $data[$key] = $pvalue;
                        }
                    }

                    $teamcount = $value->contests->contest_size - $listcount;
                    $range = $listcount / $value->contests->contest_size * 100;
                    $range = 100 - $range;
                    $range = round($range);
                    $value->contests->total_contest = $teamcount;
                    $value->contests->range = $range;
                    $value->rank = 0;
                    $value->winning_prize = 0;
                    $value->points = 0;
                    $value->text_status = $text_status;
                    $value->players = $data;

                    $value->multiple = $value->contests->multiple;
                    $value->win_percent = round($value->contests->win_percent);

                    $date = explode('T', $value->foot_match_details->start_date);
                    $value['contests']['left_time'] = strtotime($date[0]);
                    $value['contests']['start_date'] = $value->foot_match_details->start_date;
                    $dt = new DateTime($value->foot_match_details->start_date);
                    $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
                    $value['contests']['start_indian_date'] = $dt->format('d-m-Y H:i:s');
                }
            }
            $auth_id = auth()->user()->_id;

            $response = array('user_contest_details' => $user_contest_details, 'user_contest_count' => $user_contest_count->count(), 'authid' => $auth_id);
            $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        } else {
            $response = array('user_contest_details' => null, 'user_contest_count' => '0');
            $yourData = ['status' => false, 'response' => $response, 'message' => ''];

        }
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
    public function feecalculation(Request $request)
    {
        $user_details = User::find(auth()->user()->_id);
        $fee_detect = $user_details['wallet']['total'] - $request->entry_fees;
        $bonus_detect = '';
        if($request->bonus != 0){
            // dd($request->all());
            $user_entry_fees = $request->entry_fees/10;
            $user_total      = $user_details['wallet']['total'];
            $user_bonus      = $user_details['wallet']['bonus'];
            $fee_detect      = ($user_total - $request->entry_fees) + $user_entry_fees;
            $bonus_detect    = $user_bonus - $user_entry_fees;
             // dd($bonus_detect);
        }
       
        $yourData = ['status' => true,'response' => $user_details,'fee_detect' => $fee_detect,'bonus_detect' => $bonus_detect,'message' => 'Fee detected'];

        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function joincontest(Request $request)
    {
        $user_details = User::find(auth()->user()->_id);
        if ($user_details->wallet['total'] != 0 && $user_details->wallet['deposit'] >= $request->entryfee || $user_details->wallet['winnings'] >= $request->entryfee) {

            if (isset($request->teamid)) {

                $listcount = FantasyTeam::where('match_key', $request['id'])
                    ->where('_id', $request['teamid'])
                    ->where('user_id', auth()->user()->_id)
                    ->first();

            } else {
                $listcount = FantasyTeam::where('match_key', $request['id'])
                    ->where('user_id', auth()->user()->_id)
                    ->first();
            }

            if (is_object($listcount)) {

                if ($listcount->paid_status == 1) {

                    $yourData = ['status' => false, 'create_status' => false, 'message' => 'You already joined a contest.please create another team and join again'];

                    return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
                }

                $cat_details = Contest::where('_id', $request->cat_id)->first();

                if (is_object($cat_details)) {

                    $joinedlistcount = FantasyTeam::where('contest_id', $request->cat_id)
                        ->where('match_key', $request->id)
                        ->where('paid_status', 1)
                        ->count();

                    if ($joinedlistcount >= $cat_details->contest_size) {

                        $yourData = ['status' => false, 'create_status' => false, 'message' => 'Contest Full.'];

                        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

                    }

                    $listcount->paid_status = 1;
                    // $listcount->join_contest = 1;
                    $listcount->contest_id = $request->cat_id;
                    $listcount->save();

                }
            } else {
                $yourData = ['status' => false, 'create_status' => false, 'message' => 'First create your team.'];
                return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
            }
            $bonus = isset($request->bonus)?$request->bonus:0;
            $deposit = $user_details->wallet['deposit'];
            if ($user_details->wallet['deposit'] >= $request->entryfee) {
                $deposit = $user_details->wallet['deposit'] - $request->entryfee;
            }
            $winnings = $user_details->wallet['winnings'];
            if ($user_details->wallet['deposit'] <= $request->entryfee) {
                $winnings = $user_details->wallet['winnings'] - $request->entryfee;
            }
            $currency = @$user_details->wallet['currency'];
            $total = (isset($request->fee_detect) && $request->fee_detect > 0 )?$request->fee_detect:$deposit+$winnings + $bonus;
            $user_details->wallet = ['deposit' => $deposit,
                'total' => $total,
                'bonus' => $bonus,
                'currency' => $currency,
                'winnings' => $winnings,
            ];
            $user_details->paid_status = 1;
            $user_details->save();

            $this->updateTransaction($request->entryfee, $request['catid'], 'join contest');

            $yourData = ['status' => true, 'response' => $listcount, 'message' => 'Match fee deducted from your wallet for joining the contest.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

        } else {

            $yourData = ['status' => false, 'message' => 'Not enough balance.Please deposit and try.'];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }
        // } else {
        //     $response = array('teamdetailscount' => count($teamdetailscount),'team_details_count' => $teamdetailscount);

        //     $yourData =['status' => false,'team_status' => false,'response' => $response , 'message' => ''];
        //     return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        // }

    }

    public function updateTransaction($amount, $contest_id, $type)
    {
        $user = new Transaction();
        $user->orderAmount = $amount;
        $user->uid = auth()->user()->_id;
        $user->txStatus = "SUCCESS";
        $user->paymentMode = 'Wallet';
        $user->txTime = Carbon::now();
        $user->type = $type;
        $user->referenceId = $contest_id;
        $user->save();
    }

    public function Contest_win_price_update()
    {
        $details = User::where('_id', "5ddbf6d86dfec747714f10ea")->first();

        $details->winners = ['currency' => "₹",
            'total' => 650,
            'winnings' => 150,
            'bonus' => 0,
            'deposit' => 500,
        ];
        $details->save();
    }

    public function selectcontest(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $details = Contest::where('_id', $request->id)->first();
        $auth_id = auth()->user()->_id;
        // $data['upcomming'][$upindex]['format'] = $value->match_details->format;
        $yourData = ['status' => true, 'response' => $details, 'authid' => $auth_id, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function participatedetails(Request $request)
    {
        $myteam = FantasyTeam::where('user_id', auth()->user()->_id)->where('match_key', $request->contestid)->get();
        $team_create_status = 0;

        if (count($myteam) > 0) {
            $team_create_status = 1;
            $myteam = $myteam->count();
        } else {
            $myteam = 0;
        }

        $response = array('team_create_status' => $team_create_status, 'myteam' => $myteam);
        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    public function Contest_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_key' => 'required',
            'contest_id' => 'required',
        ]);

        if ($validator->fails()) {
            $yourData = ['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
            return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
        }

        $contest_details = Contest::find($request->contest_id);
        $con_details = array();

        if (is_object($contest_details)) {

            // $listcount = FantasyTeam::where('contest_id',$contest_details->_id)->where('user_id',auth()->user()->_id)->where('match_key',$request->match_key)->count();
            // $teamcount = $value->contest_size - $listcount;
            // $range = $listcount / $value->contest_size * 100;
            // $range = 100 - $range;

            // $teamcount = $contest_details->contest_size - $listcount;
            // $range = $listcount / $contest_details->contest_size * 100;
            // $range = 100 - $range;

            $user_contest_details = FantasyTeam::with('contests')
                ->where('match_key', $request->match_key)
                ->where('contest_id', $request->contest_id)
            // ->where('user_id',auth()->user()->_id)
                ->get();

            $teamcount = $contest_details->contest_size;
            $range = 100;
            $joinstatus = 0;

            if (isset($user_contest_details)) {
                $con_details = array();
                foreach ($user_contest_details as $value) {

                    $listcount = FantasyTeam::where('contest_id', $value->contests->_id)
                        ->where('match_key', $request->match_key)
                        ->where('paid_status', 1)
                        ->count();

                    $joinstatus = $listcount;

                    $teamcount = $value->contests->contest_size - $listcount;
                    $range = $listcount / $value->contests->contest_size * 100;
                    $range = 100 - $range;
                    // $range = round($range);
                    $con_details['contest_size'] = $value->contests->contest_size;

                }
            }

            $winners = $contest_details->winners;
            $newdata = [];

            foreach ($winners as $match_key => $value) {
                $newdata[] = ['rank' => $match_key, 'price' => $value];
            }

            $con_details['_id'] = $contest_details->_id;
            $con_details['contest_name'] = $contest_details->contest_name;
            $con_details['contest_size'] = $contest_details->contest_size;
            $con_details['prize_pool'] = $contest_details->prize_pool;
            $con_details['entry_fee'] = $contest_details->entry_fee;
            $con_details['multiple'] = $contest_details->mutiple;
            $con_details['win_percent'] = round($contest_details->win_percent);

            $con_details['total_contest'] = $teamcount;
            $con_details['range'] = $range;
            $con_details['join_status'] = $joinstatus;
            $con_details['winners'] = $newdata;
        }
        $type = (isset($request->type) && $request->type!='')?$request->type:'football';
        if($type=='football'){
            $mkey = intval($request->match_key);
        } else {
            $mkey = $request->match_key;
        }
            
        $match_details = Schedule::where('key', $mkey)->get();
        $m_details = array();
        foreach ($match_details as $data) {
            $end = Carbon::parse($data->start_date)->setTimezone('Asia/Kolkata');

            $m_details['_id'] = $data->_id;
            $m_details['format'] = $data->format;
            $m_details['matck_key'] = $data->key;
            $m_details['short_name'] = $data->short_name;
            $m_details['status'] = $data->status;

            $date = explode('T', $data->start_date);
            $m_details['left_time'] = strtotime($date[0]);
            $m_details['start_date'] = $data->start_date;

            $dt = new DateTime($data->start_date);
            $dt->setTimezone(new DateTimezone('Asia/Kolkata'));
            $m_details['start_indian_date'] = $dt->format('d-m-Y H:i:s');

            $m_details['team1_flag'] = 'https://fantasy.demozab.com/assets/images/flag/' . $data->team1_key . '.svg';
            $m_details['team2_flag'] = 'https://fantasy.demozab.com/assets/images/flag/' . $data->team2_kay . '.svg';
        }

        $response = array('contest_details' => $con_details, 'match_details' => $m_details);

        $yourData = ['status' => true, 'response' => $response, 'message' => ''];
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }

    

}