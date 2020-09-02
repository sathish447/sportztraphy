<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminBank;
use Illuminate\Support\Facades\Crypt;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $bank = AdminBank::index();

        return view('bank.list',[
            'bank' => $bank]);
    }

    public function addBank()
    {
        return view('bank.add_bank'); 
    }

        public function editBank($id)
    {
    
        $bank = AdminBank::edit(Crypt::decrypt($id));

        return view('bank.edit_bank',[
            'bank' => $bank]); 
    }

    public function updateBank(Request $request)
    {

        $bank = AdminBank::bankUpdate($request);

        return redirect('admin/bank')->with('status','Bank Details Updated Successfully');

    }


    public function deleteBank(Request $request)
    {

        $bank = AdminBank::deleteBank($request->id);

        return \Response::json(array(
                    'status' => true,
                    'msg' => "Bank Details Deleted Successfully!"
                ));

        // return redirect('admin/bank')->with('status','Bank Details Deleted Successfully');
    }
}
