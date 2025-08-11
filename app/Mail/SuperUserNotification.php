<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PendingUser;

class SuperUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $pendingUser;

    public function __construct(PendingUser $pendingUser)
    {
        $this->pendingUser = $pendingUser;
    }

    public function build()
    {
        $approvalUrl = env('APP_URL') . '/approve-user/' . $this->pendingUser->token;

        return $this->subject('Approve New User Request')
                    ->view('emails.super_user_notification')
                    ->with([
                        'email' => $this->pendingUser->email,
                        'user_type' => $this->pendingUser->user_type,
                        'link' => $approvalUrl,
                    ]);
    }
}
