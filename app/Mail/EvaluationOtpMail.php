<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationOtpMail extends Mailable
{
    use Queueable, SerializesModels;
    public $otp; 

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp)
    {
        //
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        return $this->subject('Your OTP Code') // Email Subject
                    ->view('emails.EvaluationOtp') // Email Template
                    ->with(['otp' => $this->otp]);
    }
    
}
