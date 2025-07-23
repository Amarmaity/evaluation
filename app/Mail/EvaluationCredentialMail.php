<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvaluationCredentialMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $password;
    public function __construct($user, $password)
    {
        //
         $this->user = $user;
        $this->password = $password;
    }


    public function build()
    {
        return $this->subject('Your Evaluation Credentials')
                    ->view('emails.evaluation_credentials')
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                        'loginUrl' => url('https://modest-gagarin.74-208-156-247.plesk.page/'), // adjust if you use a custom login URL
                    ]);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Evaluation Credential Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.evaluation_credentials',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
