<?php

namespace App\Mail;

use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newsletter;
    public $unsubscribeUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
        $this->unsubscribeUrl = route('newsletter.unsubscribe', ['token' => $newsletter->token]);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bienvenue à la newsletter IRI-UCBC')
                    ->view('emails.newsletter.welcome')
                    ->with([
                        'newsletter' => $this->newsletter,
                        'unsubscribeUrl' => $this->unsubscribeUrl,
                        'preferencesUrl' => route('newsletter.preferences', ['token' => $this->newsletter->token])
                    ]);
    }
}
