<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\ContestsCategory;
use Illuminate\Support\Facades\DB;
class ContestController extends Controller
{
    public function createContest() {
        $cat = DB::table('contests_categories')->lists('cat_name','_id')->toArray(); 
        return view('contest.add_contest', ['cat' => $cat]);
    }

    public function updateContest(Request $request){
		$niceNames = array(	
            'cat' => 'Category' , 
            'prize_type' => 'Prize type',
            'prize_count' => 'Prize count' , 
            'prize_pool' => 'Prize pool' , 
            'entry_fee' => 'Entry fee' , 
            'contest_name' => 'Contest name' , 
            'contest_size' => 'Contest size' , 
            ); 

        $this->validate($request, [   
            'cat' => 'required' ,                       
            'prize_type' => 'required',
            'multiple' => 'required',
            'type' => 'required',
            'prize_count' => 'required|numeric',
            'prize_pool' => 'required|numeric',
            'contest_name' => 'required',
            'entry_fee' => 'required|numeric',
            'contest_size' => 'required|numeric',

			],[],$niceNames);
			 
        $fromrange = $request->from;
        $torange = $request->to;
        $percentage = $request->percentage;
        $price_type = $request->prize_type;
        $prize_count = $request->prize_count;
        $contest_size = $request->contest_size;
		$prize_pool = $request->prize_pool;
		$win_percent = round($prize_count/$contest_size*100);
		$total_torange=0;
		$total_percentage=0;
		$newdata=[];
		$flag=0; 	 

		if($price_type == 1){
	 	 
		  $splitup = $request->splitup;
		  if(count($splitup) > 0) {
		   $flag=1;	
	 	   foreach($splitup as $key=>$price){
	      	     $keys = $key+1;
		     if($price != '') {	
		      $newdata['#'.$keys] =$price;
		      $total_percentage=$total_percentage+$price;
		     }
		   }
		  } 
		}
		else {
		 if(($torange != '' || $torange != null) && count($torange) > 0) {
	 	  $flag=1; 	 
		  foreach($torange as $key=>$value){
	 		// dd($value);
		     $frange = $fromrange[$key];

		     $trange = $torange[$key];
		     $percent = $percentage[$key];	
		        if($frange !='' && $trange !='' && $percent !='') {		  
				 
			      $diffrange = $trange-$frange; 
			      $calc_percent = round($prize_pool*($percent/100));
			      $frange_key = ($frange == 0) ? 1:$frange;
			      $keys = ($frange_key == $trange) ? '#'.$frange_key : '#'.$frange_key.'-'.$trange;
			      $newdata[$keys] = $calc_percent;  
			      $total_torange = $total_torange+$diffrange;  
			      $diffrange = $trange-$frange;
			      $multiple_percent = $calc_percent*$diffrange;
			      $total_percentage = $total_percentage+$multiple_percent; 
		        }	 
		    }		
		  }
		}
	  
		$price_type = $price_type == 1 ? 'individual' : 'range'; 
		$cat = DB::table('contests_categories')->lists('cat_name','_id')->toArray(); 

		if($flag == 0){
			// \Session::flash('fail', 'No values for Prize type');
			return redirect()->back()->with('fail', 'No values for Prize type');		
		}
		if($price_type == 'range' && $total_torange > $prize_count){
		   // \Session::flash('fail', 'Prize count does not match.');
		   return redirect()->back()->with('fail', 'Prize count does not match');		
	        	
		}
		// if($price_type == 'range' && $total_percentage < $prize_pool){
		//   // \Session::flash('fail', 'Prize pool limit not reached.'); 
		//   return redirect()->back()->with('fail', 'Prize pool limit not reached.');		
		// }
		// if($price_type == 'range' && $total_percentage > $prize_pool){
		//    // \Session::flash('fail', 'Prize pool limit reached.');
	 //       return redirect()->back()->with('fail', 'Prize pool limit not reached.');		
		// }
		// if($price_type == 'individual' && ($total_percentage > $prize_pool || $total_percentage < $prize_pool)) {
		// 	// \Session::flash('fail', 'Prize pool limit not satisfied !!!');
	 //        return redirect()->back()->with('fail', 'Prize pool limit not satisfied.');
		// } 

		$contest = new Contest();
        $contest->cat_id = $request->cat;
        $contest->contest_name = $request->contest_name;
        $contest->contest_size = $request->contest_size;
        $contest->prize_pool = $prize_pool;
        $contest->prize_count = $prize_count;
        $contest->entry_fee = $request->entry_fee;
		$contest->price_type=$price_type;
		$contest->winners=$newdata;
		if($request->multiple == 1){
			$contest->multiple = 1;
		} elseif ($request->multiple == 0) {
			$contest->multiple = 0;
		}
		if($request->type == 1){
			$contest->type = 1;
		} elseif ($request->type == 0) {
			$contest->type = 0;
		}
		$contest->win_percent = $win_percent;
		$contest->created_by='admin';
		$contest->status=0;
		$contest->view_status=1;

		if($contest->save()){
		  \Session::flash('status', 'Contest created successfully!');
	            return redirect()->back();		
		}
		else{
		 \Session::flash('fail', 'Contest could not be created!');
	            return redirect()->back();		
		}  
    }
    
    public function contestStatus(Request $request)
    {
       $id=$request->user;
       $status=$request->status;
       if($id !='' && $status !=''){
          $Contest = Contest::find($id);
	  $stat = ($status == 'disable') ? 0 : 1;
          $Contest->status = $stat; 
          if($Contest->save())  
            echo "<div class='alert alert-success'> Contest was successfully ".$status."d</div>"; 
          else 
            echo "<div class='alert alert-danger'>Contest status was not updated !</div>"; 
       }
       exit;
    }			
	
    public function contestList()
    {
        $contest = Contest::with('category')->paginate();  
        return view('contest.contestlist', ['contest' => $contest]);
    }

    public function contestEdit($id)
    {  
	
       	if($id !=''){
		   $contest =  Contest::with('category')->where('_id',$id)->first();  
		 	
		   return view('contest.contestEdit', ['contest' => $contest,'page'=>'edit']);	
	   	}	
        else{
          return redirect('admin/contestList');	
        }
    }

    public function contestUpdate($id,Request $request)
    { 
        if($id !='' && $request->status !=''){ 
       		$contest = Contest::where('_id',$id)->first();
       		if($contest != '')  {

			 $niceNames = array(
			    'cat' => 'Category' , 
			    'prize_type' => 'Prize type',
			    'prize_count' => 'Prize count' , 
			    'prize_pool' => 'Prize pool' , 
			    'entry_fee' => 'Entry fee' , 
			    'contest_name' => 'Contest name' , 
			    'contest_size' => 'Contest size' , 
			    ); 

			$this->validate($request, [   
			    'cat' => 'required' ,                       
			    'prize_type' => 'required',
			    'prize_count' => 'required|numeric',
			    'prize_pool' => 'required|numeric',
			    'contest_name' => 'required',
			    'entry_fee' => 'required|numeric',
			    'contest_size' => 'required|numeric',

					],[],$niceNames);
			 
			$contest->cat_id = $request->cat;
			$contest->contest_name = $request->contest_name;
			$contest->contest_size = $request->contest_size;
			$contest->prize_pool = $request->prize_pool;
			$contest->prize_count = $request->prize_count;
			$contest->entry_fee = $request->entry_fee;
		
			$fromrange = $request->from;
			$torange = $request->to;
			$percentage = $request->percentage;
			$price_type = $request->prize_type;
			$prize_count = $request->prize_count;
			$prize_pool = $request->prize_pool; 

			$total_torange=0;
			$total_percentage=0;
			$newdata=[];
			 
			if($price_type == 1){
		 	 
			  $splitup = $request->splitup;
			  if(count($splitup) > 0) {
		 	   foreach($splitup as $key=>$price){
		      	     $keys = $key+1;
			     if($price != '') {	
			      $newdata['# '.$keys] =$price;
			     }
			   }
			  }
			}
			else {
			 if($torange != '' && count($torange) > 0) {
		 
			  foreach($torange as $key=>$value){

			     $keys = $key;
			     $frange = $fromrange[$keys];
			     $trange = $torange[$keys];
			     $percent = $percentage[$keys];	
		             if($frange !='' && $trange !='' && $percent !='') {		  
		
			      $diffrange = $trange-$frange; 
			      $calc_percent = $prize_pool*($percent/100);
			      $newdata['# '.$frange] = $calc_percent;  
			      $total_torange = $total_torange+$diffrange;  
			      
			      $multiple_percent = $calc_percent*$trange;
			      $total_percentage = $total_percentage+$multiple_percent;

			     }	 
			   }		
			  }
			}

			$price_type = $price_type == 1 ? 'individual' : 'range';

			$contest->price_type=$price_type;
			$contest->winners=$newdata;
			$contest->created_by='admin';
			$contest->status=0;
			$contest->view_status=1;
	 
       			$contest->status = $request->status; 
       			if($contest->save())
       				return redirect()->back()->with('contestsuccess','Contest was successfully updated');
       			else
       				return redirect()->back()->with('contestfail','Contest was successfully updated');
       		} 
        }	
        else{
          return redirect('admin/contestList');	
        }
	}
	
	public function getContestList(Request $request)
	{
		$contest = Contest::with('category')->where('_id',$request->id)->first();  
        return json_encode($contest);
	}
	public function contestSearchList(Request $request)
    {
        // dd($request->all());
        $userSearchList = Contest::searchList($request);
        $contest = Contest::with('category')->paginate(); 
        // $cat = ContestsCategory::paginate();
        // dd($userSearchList);
        $counts = Contest::count();
        return view('contest.contestlist')->with(['contest' => $userSearchList, 'counts' => $counts, 'term' => $request->searchitem]);
    }

}
	
