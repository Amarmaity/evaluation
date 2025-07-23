<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ProbationCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Probation Period Completed',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.probation_completed',
            with: ['user' => $this->user],
        );
    }

    public function attachments()
    {
        return [];
    }
}
