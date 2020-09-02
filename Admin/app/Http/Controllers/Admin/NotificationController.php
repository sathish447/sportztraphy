<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Firebase;
use App\Libraries\Push;
use App\Models\User;
use App\Mail\Notification;

use Mail;

class NotificationController extends Controller
{
    public function _construct()
    {
        $this->middleware('admin');
    }
 
    public function mailTemplateList()
    {

    }

    public function createMailTemplate()
    {

    }

    public function editMailTemplate()
    {

    }

    public function saveMailTemplate()
    {

    }

    public function formBulkmessage()
    {
        $user = User::all();
        return view('noti.choosenoti',['user' => $user]);
    }   

    public function sendBulkMessage()
    {

    }

    public function notify(Request $request)
    {
        // dd($request->content);
        if($request->ntype == '2') {
            foreach ($request->teamname as $key => $value) {
                $user = User::where('teamname',$request->teamname[$key])->get();
                // dd($user);
                foreach ($user as $key => $user_value) {
                    $user_value->content = strip_tags($request->content);
                    Mail::to($user_value->email)->queue(new Notification($user_value));
                    \Session::flash('mail_send', 'Mail send successfully!');
                    return redirect()->back();
                }
            }
        } else if($request->ntype == '1'){
            // dd('else');
            foreach ($request->teamname as $key => $value) {
                $user = User::where('teamname',$request->teamname[$key])->get();
                foreach ($user as $key => $user_value) {
                    $data = array("to" => $user_value->email,"notification" => array( "title" => "please fill the content", "body" => $request->content,"icon" => "icon.png", "click_action" => "https://fantasy.demozab.com")); 
                    $data_string = json_encode($data);   
                    $headers = array
                    (
                         'Authorization: key=AAAATdC7D_o:APA91bGCX_0STz69AQU_a5dHoPW1WAyGxuiBk63d-mO1sRkr1AVCtioApgnVp6wDAgxV615ZdvNXKd09kdIULIrbpE5hDRhbp_ILHjv7xD_a3miPXbaogPNywrGCfeCwWetWbXlnjoIn', 
                         'Content-Type: application/json'
                    );                                                                                 
                    $ch = curl_init();   
                    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
                    curl_setopt( $ch,CURLOPT_POST, true );  
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);           
                    $result = curl_exec($ch); 
                    curl_close ($ch); 
                }
                // \Session::flash('mail_send', 'Push message send successfully!');
                // return redirect()->back();
                return $result;
            }
        }

        // foreach ($request->teamname as $key => $value) {
        //     $user = User::where('teamname',$request->teamname[$key])->get();
        // }
        // $data = json_decode(\request()->getContent());
        // $sender = $data->sender_user;
        // $receiver = $data->receiver_user;
        // $notification_payload = $data->payload;
        // $notification_title = $data->title;
        // $notification_message = $data->message;
        // $notification_push_type = $data->push_type;
        // try {

        //     $sender_id = "";
        //     $receiver_id = "";

        //     $firebase = new Firebase();
        //     $push = new Push();

        //     // optional payload
        //     $payload = $notification_payload;

        //     $title = $notification_title ?? '';

        //     // notification message
        //     $message = $notification_message ?? '';

        //     // push type - single user / topic
        //     $push_type = $notification_push_type ?? '';

        //     $push->setTitle($title);
        //     $push->setMessage($message);
        //     $push->setPayload($payload);

        //     $json = '';
        //     $response = '';

        //     if ($push_type === 'topic') {
        //         $json = $push->getPush();
        //         $response = $firebase->sendTopic('global', $json);
        //     } else if ($push_type === 'individual') {
        //         $json = $push->getPush();
        //         $regId = $receiver_id ?? '';
        //         $response = $firebase->send($regId, $json);

        //         return response()->json([
        //             'response' => $response,
        //         ]);
        //     }

        // } catch (\Exception $ex) {
        //     return response()->json([
        //         'error' => true,
        //         'message' => $ex->getMessage(),
        //     ]);
        // }
    }
}