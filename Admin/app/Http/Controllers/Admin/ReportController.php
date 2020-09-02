<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FantasyTeam;
use App\Models\Schedule;
use App\Models\Contest;

class ReportController extends Controller
{
    public function reports_view()
    {
    	$user_reports = User::all();
    	$contestinfo = Contest::get();
    	// dd($contestinfo);
    	$user_contest_details = FantasyTeam::with('contestinfo')->with('match')->where('paid_status',1)->get();
     //    $report_array = array();
        
    	// foreach ($user_contest_details as $key => $value) {
     //        foreach ($value->match as $key => $match_detail) {
     //            // $match_detail->short_name = $match_detail['short_name'];
        
     //    		$contest_count = count($user_contest_details);
     //    		if($contest_count == $value->contestinfo->contest_size)
     //    		{
     //                $details = array(
     //                    'prize_given' => $value->contestinfo->entry_fee * $value->contestinfo->contest_size,
     //                    'fee_received' => $contest_count * $value->contestinfo->entry_fee,
     //                    'profit_true' => $value->contestinfo->prize_pool - $value->details['fee_received'],
     //                );
        			
     //    		} else {
     //                $details = array(
     //                    'prize_given' => $value->contestinfo->entry_fee * $value->contestinfo->contest_size,
     //                    'fee_received' => $contest_count * $value->contestinfo->entry_fee,
     //                    'profit_true' => $value->details['fee_received'] - $value->contestinfo->prize_pool,
     //                );
     //    		}
     //    		$value->match_name = $value->match->short_name;
     //    		$value->prize_pool = $value->contestinfo->prize_pool;
     //        }

     //    }
    return view('reports.reports_view', ['match_details' => $user_contest_details]);

                            // dd($match_detail);

        // dd($value->details['fee_received']);
    }
}
