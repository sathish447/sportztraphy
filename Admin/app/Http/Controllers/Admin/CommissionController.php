<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\CommissionRequest;
use App\Http\Requests\WithdrawCommissionRequest;


class CommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
 
    public function withdrawCommission(Request $request)
    {               
        $setting = Setting::paginate(10); 
        return view('commission.withdraw_commission')->with('details',$setting);
    }

    public function withdrawCommissionedit($id)
    {
        $commission = Setting::where('_id',Crypt::decrypt($id))->first();

        return view('commission.withdraw_commission_edit')->with('commission',$commission);
    }

    public function withdrawCommissionUpdate(WithdrawCommissionRequest $request)
    { 
        $commission = Setting::commissionUpdate($request); 
        return back()->with('status','Commission Updated Successfully');
    }

    
}
