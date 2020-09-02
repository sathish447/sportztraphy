<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class Invite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        // dd($email['invitecode']);
        $this->email = $email;
        $this->invite_code = $email['invitecode'];
        $this->username = $email['username'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.invitefriends')->with(['email' => $this->email,'invite_code' => $this->invite_code,'username' => $this->username]);
    }
}
