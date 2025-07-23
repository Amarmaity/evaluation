<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SuperAddUser;

class AnniversaryEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(SuperAddUser $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('🎉 Congratulations on Your 1-Year Anniversary!')
                    ->view('emails.anniversary');
    }
}
