<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tickets;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\SupportRequest;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
    	$support = Tickets::index();
    	return view('support.list',[
    		'tickets' => $support]);
    }

    public function supportdetails($id)
    {
        $ticket_id = Crypt::decrypt($id); 
        $tickets = Tickets::view($ticket_id); 

        return view('support.supportdetails', ['support_data' => $tickets['chats'], 'tickets' => $tickets['tickets'],'ticket_id' => $ticket_id]);
    }

    public function addMessage(SupportRequest $request) 
    {
        $tickets = Tickets::addMessage($request); 
        $ticket_id = $request->ticket_id;
        $enc_id = Crypt::encrypt($ticket_id);
        
        return redirect('/admin/support/'.$enc_id);
    }
}
