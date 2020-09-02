<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CMS;

class ManagecmsController extends Controller
{
    public function contact_us()
    { 
    	$contact_details = CMS::where('type','contactus')->first();
    	return view('managecms.contactus',['contact_details' => $contact_details]);
    }
    public function term_conditions()
    {
    	$terms_conditions_details = CMS::where('type','contactus')->first();
    	return view('managecms.termconditions',['terms_conditions_details' => $terms_conditions_details]);
    }
    public function privacy_policy()
    {
    	$privacy_policy = CMS::where('type','privacypolicy')->first();
    	return view('managecms.privacy_policy',['privacy_policy' => $privacy_policy]);
    }
    public function about_us()
    {
    	$aboutus = CMS::where('type','aboutus')->first();
    	return view('managecms.about_us',['aboutus' => $aboutus]);
    }
    public function create_contact(Request $request)
    {
    	$check_contact_details = CMS::where('type',$request->type)->first();
    	if($check_contact_details != '')
    	{
    		$contact_details = CMS::where('type',$request->type)->first();
    	} else {
    		$contact_details = new CMS;
    	}
    	$contact_details->content = $request->content;
    	$contact_details->type = 'contactus';
    	if($contact_details->save()){
		  \Session::flash('status', 'CONTACT US UPDATED SUCCESSFULLY!');
	            return redirect()->back();		
		}
    }
    public function create_term_condition(Request $request)
    {
    	// dd(CMS::all());
    	$terms_conditions_details = CMS::where('type',$request->type)->first();
    	if(isset($terms_conditions_details))
    	{
    		$terms_conditions = CMS::where('type',$request->type)->first();
    	} else {
    		$terms_conditions = new CMS;
    	}
    	$terms_conditions->content = $request->content;
    	$terms_conditions->type = 'terms&conditions';
    	if($terms_conditions->save()){
		  \Session::flash('status', 'TERMS & CONDITIONS UPDATED SUCCESSFULLY!');
	            return redirect()->back();		
		}
    }
    public function create_privacy_policy(Request $request)
    {
    	$privacy_policy_details = CMS::where('type',$request->type)->first();
    	if(isset($privacy_policy_details))
    	{
    		// dd('if');
    		$privacy_policy = CMS::where('type',$request->type)->first();
    	} else {
    		// dd('else');
    		$privacy_policy = new CMS;
    	}
    	$privacy_policy->content = $request->content;
    	$privacy_policy->type = 'privacypolicy';
    	$privacy_policy->save();
    	if($privacy_policy->save()){
		  \Session::flash('status', 'PRIVACY POLICY UPDATED SUCCESSFULLY!');
	            return redirect()->back();		
		}
    }
    public function create_about_us(Request $request)
    {
    	// dd(CMS::all());
    	$privacy_policy_details = CMS::where('type',$request->type)->first();
    	if(isset($privacy_policy_details))
    	{
    		// dd('if');
    		$privacy_policy = CMS::where('type',$request->type)->first();
    	} else {
    		// dd('else');
    		$privacy_policy = new CMS;
    	}
    	$privacy_policy->content = $request->content;
    	$privacy_policy->type = 'aboutus';
    	$privacy_policy->save();
    	if($privacy_policy->save()){
		  \Session::flash('status', 'ABOUT US UPDATED SUCCESSFULLY!');
	            return redirect()->back();		
		}
    }

}
