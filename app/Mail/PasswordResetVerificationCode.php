<?php

// app/Mail/PasswordResetVerificationCode.php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetVerificationCode extends Mailable
{
    use SerializesModels;

    public $verificationCode;

    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    public function build()
    {
        return $this->view('emails.password_reset')
                    ->with(['verificationCode' => $this->verificationCode]);
    }
}
