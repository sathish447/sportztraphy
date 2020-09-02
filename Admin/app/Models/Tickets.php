<?php

namespace App\Models;
 
use Illuminate\Support\Facades\Crypt;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Eloquent
{ 
    protected $connection = 'mongodb';
    protected $collection = 'tickets';

    public static function index()
    {
    	$tickets = Tickets::orderBy('id','desc')->paginate(10);

    	return $tickets;
    }

    public static function view($id)
    {
    	$tickets = Tickets::where('id', '=', $id)->first(); 
        $chats = Supportchat::where('ticket_id', '=', $tickets->id)->orderBy('id', 'asc')->get();

        $update = Supportchat::where('ticket_id', '=', $tickets->id)->update(['admin_status' => 1]);
          
    	return array('tickets' => $tickets,'chats' => $chats);
    }

    public static function addMessage( $request)
    { 

		$ticket_id = $request->ticket_id;
		$enc_id = Crypt::encrypt($ticket_id);

		$user_chat_msg = Tickets::where('id', $ticket_id)->first();
        
		$chat_msg = new Supportchat();
		$chat_msg->setConnection('mysql2');
		$chat_msg->user_id = $user_chat_msg->user_id;
		$chat_msg->ticket_id = $user_chat_msg->id;
		$chat_msg->message = '';
		$chat_msg->reply = $request->message;
		$chat_msg->user_status = 0;
		$chat_msg->admin_status = 1;
		$chat_msg->save(); 
		
        return true;
    }
}
