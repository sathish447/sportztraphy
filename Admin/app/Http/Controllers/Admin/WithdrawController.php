<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction;
use App\Models\Withdraw; 
use App\Traits\Cashfree; 

class WithdrawController extends Controller
{
    use Cashfree;

    public function __construct()
    {
        $this->middleware('admin');
    }
     
    public function withdrawRequest()
    {
    	$transaction = Transaction::where('uid','5deb904454959570db4f9ff2')->where(['type'=>'withdraw','txStatus'=>3])->orderBy('_id','desc')->with('users')->paginate();  
        // dd($transaction);
        return view('userwithdraw.withdraw', ['transaction' => $transaction,'type'=>'pending']);
    }
    public function withdrawHistory()
    {
        $transaction = Transaction::where('type','withdraw')->where('txStatus','!=',3)->orderBy('_id','desc')->with('users')->paginate();
        // dd($transaction);
        return view('userwithdraw.withdraw', ['transaction' => $transaction,'type'=>'complete']);
    }
    public function depositHistory()
    {
        $transaction = Transaction::where('type','deposit')->where('txStatus','SUCCESS')->orderBy('_id','desc')->with('users')->paginate();  
        return view('userwithdraw.deposit', ['transaction' => $transaction]);
    }

    public function withdrawEdit($id)
    {   
        if($id!='') { 
            $decrypt_id = Crypt::decrypt($id);
            $withdraw = Transaction::where('_id',$decrypt_id)->with('users')->first();
           
            return view('userwithdraw.withdraw_edit',[
                'withdraw' => $withdraw
            ]);
        }
    }

    public function withdrawUpdate(Request $request)
    {    
       $transactions = $request->txns; 
      
       $ids='';
       $batch = [];
       if(count($transactions) > 0){
         foreach($transactions as $key=>$txn){
            $id =  strval($txn); 
            $tran = $request->txn;
			$ids .= "$txn,";
            if(isset($tran["'amount'"][$txn]) && isset($tran["'beneid'"][$txn])){
                $amount = $tran["'amount'"][$txn];     
                $beneId = $tran["'beneid'"][$txn];
                $transid = $tran["'transferId'"][$txn]; 
                $batch[] = ['transferId'=>$transid,'amount'=>$amount,'beneId'=>$beneId,'remarks'=>'working'];
            }  
         }  
       } 
        
       $ids = trim($ids, ',');
       if(count($batch) > 0) {
       	   $batchTransferId = 'batch_'.rand(1000,10000);
           $newdata=['batchTransferId'=>$batchTransferId,'batchFormat'=>'BENEFICIARY_ID','batch'=>$batch]; 
	       $send = $this->cashfree_curl('/payout/v1/requestBatchTransfer', $newdata);  
	       if($send !=''  && $send['status'] == 'ERROR' && $send['message'] == 'Token is not valid'){
	       	  $send = $this->cashfree_curl('/payout/v1/requestBatchTransfer', $newdata);  
	       } 
	       if($send != '' && $send['status'] == 'SUCCESS' && $send['subCode'] == 200) { 
	       
	          $referenceId = $send['data']['referenceId']; 
	          $update = Transaction::whereIn('_id',$transactions)->update(['txStatus'=>100,'referenceId'=>$referenceId,'batchTransferId'=>$batchTransferId]);
	          
	          if($update){ 
	            return redirect()->back()->with('status','Updated Successfully');
	          }
	          else{ 
	             return redirect()->back()->with('errorstatus','Not Updated !');
	          } 
	       }
	       else{  
	          return redirect()->back()->with('errorstatus','Updated Failed!.');
	        } 
	    }
    }
    public function withdrawSearchList(Request $request)
    {
        $withdrawSearchList = Transaction::searchList($request);
        // dd($withdrawSearchList);
        $counts = Transaction::count();
        return view('userwithdraw.withdraw')->with(['transaction' => $withdrawSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }
    public function depositSearchList(Request $request)
    {
        // dd($request->all());
        $userSearchList = Transaction::searchList($request);
        // $cat = ContestsCategory::paginate();
        // dd($userSearchList);
        $counts = Transaction::count();
        return view('userwithdraw.deposit')->with(['transaction' => $userSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }
     
}
