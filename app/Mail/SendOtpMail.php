<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;
    public $otp;
    public $user;
    public function __construct($otp, $user) {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function build() {
        return $this->subject('Mã OTP của bạn')
                    ->view('emails.send-otp')
                    ->with(['otp' => $this->otp]);
    }
}
