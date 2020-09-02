<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\User;
use App\Http\Requests\BonusRequest;

class BonusController extends Controller
{
    public function index()
    {
    	$bonus = Bonus::where('type','referal')->get();
    	return view('bonus.bonus_view',['bonus' => $bonus]);
    }
    public function bonus_create_view()
    {
    	return view('bonus.add_bonus');
    }
    public function bonus_edit_view($id)
    {
    	$bonus = Bonus::where('_id',$id)->first();
    	return view('bonus.add_bonus',['bonus' => $bonus]);
    }
    public function create_bonus(Request $request)
    {
    	if($request->id == null){
	    	$bonus = new Bonus();
	        $bonus->referalbonus = $request->referal_bonus;
	        $bonus->joinedbonus = $request->joined_bonus;
	        $bonus->type = 'referal';
    	} else {
    		$bonus = Bonus::where('_id',$request->id)->first();
    		$bonus->referalbonus = $request->referal_bonus;
	        $bonus->joinedbonus = $request->joined_bonus;
	        $bonus->type = 'referal';
    	}
    	$bonus->save();
        if($request->id == null){
		  \Session::flash('bonus', 'Bonus created successfully!');
	            return redirect()->back();		
		} else {
			\Session::flash('bonus', 'Bonus Updated successfully!');
	            return redirect()->back();
		}
    	return view('bonus.bonus_view');
    }
    public function manage_bonus()
    {
    	$bonus = User::all();
    	return view('bonus.managebonus',['bonus' => $bonus]);
    }
    public function update_manage_bonus($id)
    {
    	$bonus = User::where('_id',$id)->first();
    	return view('bonus.update_manage_bonus',['bonus' => $bonus]);
    }
    public function updatebonus(BonusRequest $request)
    {
		$user = User::where('_id',$request->id)->first();
        $user->wallet = array('currency' => '₹', 'total' => $request->total, 'winnings' =>  $request->winnings, 'bonus' => $request->bonus, 'deposit' => $request->deposit);
    	$user->save();
        if($user->save()){
		  \Session::flash('userbonus', 'User bonus updated successfully!');
	            return redirect()->back();		
		}
    }
    public function bonusSearchList(Request $request)
    {
        // dd($request->all());
        $bonusSearchList = Bonus::searchList($request);
        // $cat = ContestsCategory::paginate();
        // dd($userSearchList);
        $counts = Bonus::count();
        return view('bonus.bonus_view')->with(['bonus' => $bonusSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }
    public function managebonusSearchList(Request $request)
    {
        // dd($request->all());
        $bonusSearchList = Bonus::managesearchList($request);
        // $cat = ContestsCategory::paginate();
        // dd($userSearchList);
        $counts = Bonus::count();
        return view('bonus.managebonus')->with(['bonus' => $bonusSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }
}
