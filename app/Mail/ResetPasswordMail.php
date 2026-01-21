<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ], false));

        return $this->to($this->email)
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Réinitialisation de votre mot de passe - GRN-UCBC')
                    ->view('emails.auth.reset-password')
                    ->with([
                        'resetUrl' => $resetUrl,
                        'email' => $this->email,
                        'token' => $this->token
                    ]);
    }
}
