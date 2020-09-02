<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Transaction;
use App\Models\User;

class TransactionsController extends Controller
{
    public function transactions()
    {
        $transaction = Transaction::where('txStatus', '!=', 3)->orderBy('_id', 'desc')->with('users')->paginate();
        return view('transactions.transaction_history', ['transaction' => $transaction]);
    }
    public function earnings()
    {
        $page_contest = Contest::where('view_status', 1)->with('fantasyteam')->paginate();
        $contest = Contest::where('view_status', 1)->with('fantasyteam')->get();
        foreach ($contest as $key => $value) {
            if ($value['price_winning_amount'] != 0) {
                $winning_amount = $value->entry_fee - $value['fantasyteam']['price_winning_amount'];
            } else {
                $winning_amount = $value['fantasyteam']['price_winning_amount'];
            }
        }
        return view('earning.earning_history', ['contest' => $contest, 'winning_amount' => $winning_amount, 'page_contest' => $page_contest]);
    }
    public function bonus()
    {
        $bonus = User::where('txStatus', '!=', 3)->orderBy('_id', 'desc')->with('transactions')->paginate();
        dd($bonus);
    }
    public function earningsSearchList(Request $request)
    {
        $userSearchList = Contest::searchList($request);
        $contest = Contest::where('view_status', 1)->with('fantasyteam')->get();
        foreach ($contest as $key => $value) {
            if ($value['price_winning_amount'] != 0) {
                $winning_amount = $value->entry_fee - $value['fantasyteam']['price_winning_amount'];
            } else {
                $winning_amount = $value['fantasyteam']['price_winning_amount'];
            }
        }
        $page_contest = Contest::where('view_status', 1)->with('fantasyteam')->paginate();

        // $cat = ContestsCategory::paginate();
        // dd($userSearchList);
        $counts = Contest::count();
        return view('earning.earning_history')->with(['contest' => $userSearchList, 'counts' => $counts, 'term' => $request->searchitem, 'winning_amount' => $winning_amount, 'page_contest' => $page_contest]);
    }
    public function TransactionsSearchList(Request $request)
    {
        // dd($request->all());
        $userSearchList = Transaction::searchList($request);
        // $transaction = Transaction::where('txStatus', '!=', 3)->orderBy('_id', 'desc')->with('users')->paginate();
        // $cat = ContestsCategory::paginate();
        // dd($userSearchList);
        $counts = Transaction::count();
        return view('transactions.transaction_history')->with(['transaction' => $userSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }
}