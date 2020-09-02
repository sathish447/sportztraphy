<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction;
use App\Models\FantasyTeam;

class ReferalController extends Controller
{
    public function index()
    {
    	$details = User::index();
        $counts = User::count();
        return view('referal.referal')->with(['details' => $details, 'counts' => $counts]);
    }
    public function referal_view(Request $request)
    {
        $user_id = Crypt::decrypt($request->id);
        // $wallet = User::userWalletDetails($user_id);

        if ($user_id) {
            $user = User::find($user_id);
            if ($user->invitecode != '') {
                $wherekyc = array('referral' => $user->invitecode);
                $userRef = User::where($wherekyc)->get();
            } else {
                $userRef = [];
            }
            $deposit_history = Transaction::where('uid', $user_id)->where('type', 'deposit')->get();
            $withdraw_history = Transaction::where('uid', $user_id)->where('type', 'withdraw')->get();
            $contests_joined = FantasyTeam::where('user_id', $user_id)->with('match')->with('contestinfo')->get();

            return view('referal.referal_view',
                ['userdetails' => $user,
                    'wallet' => $user->wallet,
                    'userReferral' => $userRef,
                    'withdraw_history' => $withdraw_history,
                    'deposit_history' => $deposit_history,
                    'contests_joined' => $contests_joined,
                ]);
        }
    }
}
