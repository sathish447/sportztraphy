<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\User;
use App\Commission;
use App\Models\Supportticket;
use App\Models\Supportchat;
use Auth;

class SupportController extends Controller
{
    // public function __construct()
    // {
    //      $this->middleware(['auth','twofa']);
    // }

   	public function support()
   	{
   		$user = Auth::user();
   		$tickets = Supportticket::where('uid', $user->id)->orderBy('id','desc')->paginate(15);
   		$ticket_count = Supportchat::where([['uid', '=', $user->id], ['user_status', '=', '0']])->count();
   		$yourData =['status' => true, 'response' => $tickets, 'message' => 'Ticket generate successfully'];              
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
   	}
   	
   	public function newticket()
   	{
   		$user = Auth::user();
   		// dd($user);
	    $tickets = Supportticket::where('uid', $user->id)->orderBy('id','desc')->get();
	    $yourData =['status' => true, 'response' => $tickets, 'message' => 'Ticket generate successfully'];              
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

   	    // return view('/userpanel/newticket', ['user' => $user, 'ticket_count' => $ticket_count]);
   	}

   	public function submitNewTicket(Request $request)
	{
		// dd($request->all());
		$user = Auth::user();

		$this->validate($request, [
			'subject' => 'required',
			'message' => 'required| regex:/(^[A-Za-z0-9 ]+$)+/'
		]);

		$ticket_id = "BT".rand(1000, 9999)."ST";

		$new_ticket = new Supportticket();
		$new_ticket->uid = $user->id;
		$new_ticket->ticket_id = $ticket_id;
		$new_ticket->subject = $request->subject;
		$new_ticket->message = $request->message;
		$new_ticket->status = 0;
		$save_record = $new_ticket->save();
		if($save_record)
		{
			$chat_msg = new Supportchat();
			$chat_msg->uid = $user->id;
			$chat_msg->ticketid = $ticket_id;
			$chat_msg->message = $request->message;
			$chat_msg->reply = NULL;
			$chat_msg->user_status = 1;
			$chat_msg->admin_status = 0;
			$chat_msg->save();
			$tickets = Supportticket::where('uid', $user->id)->orderBy('id','desc')->paginate(15);
			\Session::flash('raised_new_ticket', 'Thank you! your ticket has been sent successfully. we will reply soon.');
		}
		$yourData =['status' => true, 'response' => $tickets, 'message' => 'Ticket generate successfully'];              
        return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
	}
	
	public function viewTicket(Request $request)
	{
		// dd($request->id();
		$user = Auth::user();
		// $ticket_id = Crypt::decrypt($request->id);
		$user_support = Supportticket::where('uid', $user->id)->get();
		$tickets = Supportticket::where('uid', $user->id)->orderBy('id','desc')->paginate(15);
		$ticket_count = Supportchat::where([['uid', '=', $user->id], ['user_status', '=', '0']])->count();

		if($request->id && $request->id!='')
		{
			$user_support = Supportchat::where('ticketid', $request->id)->get();
			$update = Supportchat::where('ticketid', $request->id)->update(['user_status' => 1]);
			if($user_support && $user_support->count() > 0)
			{
				$yourData =['status' => true, 'response' => $user_support, 'message' => 'chat'];              
        		return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
			}
		}
		else
		{
			$yourData =['status' => false, 'response' => $user_support, 'message' => 'chat'];              
        	return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
		}
	}
	
	public function sendMessage(Request $request)
	{
		$user = Auth::user();
		
		$this->validate($request, [
			'ticket_id' => 'required| regex:/(^[A-Za-z0-9 ]+$)+/',
			'message' 	=> 'required| regex:/(^[A-Za-z0-9 ]+$)+/'
		]);

		$chat_msg = new Supportchat();
		$chat_msg->uid = $user->id;
		$chat_msg->ticketid = $request->ticket_id;
		$chat_msg->message = $request->message;
		$chat_msg->reply = NULL;
		$chat_msg->user_status = 1;
		$chat_msg->admin_status = 0;
		$chat_msg->save();

		$support_data = '';
		$user_support = Supportticket::where('uid', $user->id)->get();
		if($user_support && $user_support->count() > 0)
		{
			$support_data = $user_support;
		}
		$ticket_id = Crypt::encrypt($request->ticket_id);
		$yourData =['status' => true, 'response' => $user_support, 'message' => 'Message Sended.'];              
    	return response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);
    }
}
