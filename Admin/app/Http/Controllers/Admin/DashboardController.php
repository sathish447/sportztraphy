<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\FantasyTeam;
use App\Models\Security;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Dashboard;
use Illuminate\Http\Request;

// use GeoIP as GeoIP;

class DashboardController extends Controller
{
    use Dashboard;

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {

        $dashboard = User::dashboard();
        $logActivity = User::monthweekdata();
        $contestusers = FantasyTeam::distinct('user_id')->groupBy('user_id')->get();
        $trans = Transaction::get();
        $deposit = 0;
        $withdraw = 0;
        $contestfee = 0;
        $winning = 0;
        if (count($trans) > 0):
            foreach ($trans as $transaction):
                
                if ($transaction->type == 'deposit') {
                    $deposit += $transaction->orderAmount;
                }
                if ($transaction->type == 'withdraw') {
                    $withdraw += $transaction->orderAmount;
                }
                if ($transaction->type == 'join contest') {
                    $contestfee += $transaction->orderAmount;
                }
                if ($transaction->type == 'winning') {
                    $winning += $transaction->orderAmount;
                }
            endforeach;
        endif;
        $contests = FantasyTeam::where('cancelled', 0)->get();
        $popular = [];
        foreach ($contests as $contest) {
            if (isset($popular[$contest->contest_id])) {
                $popular[$contest->contest_id]['count']++;
            } else {
                $popular[$contest->contest_id]['count'] = 1;
                $popular[$contest->contest_id]['name'] = $contest->contestinfo['contest_name'];
            }
        }
        $response['details'] = $dashboard;
        $response['deposit'] = $deposit;
        $response['withdraw'] = $withdraw;
        $response['contestfee'] = $contestfee;
        $response['winning'] = $winning;
        $response['contestusers'] = count($contestusers);
        $response['monthweek'] = $logActivity;
        $response['popular'] = $popular;
        $response['sel_cont_count'] = count($contests);

        return view('dashboard')->with('response', $response);
    }

    public function security()
    {
        return view('settings.security');
    }

    public function updateUsername(Request $request)
    {
        $update = Admin::updateUsername($request);

        return back()->with('status', $update);
    }

    public function changepassword(Request $request)
    {
        $update = Admin::changepassword($request);

        return back()->with('status', $update);
    }
}