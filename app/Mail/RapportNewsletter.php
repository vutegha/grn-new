<?php

namespace App\Mail;

use App\Models\Rapport;
use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RapportNewsletter extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $rapport;
    public $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct(Rapport $rapport, Newsletter $subscriber)
    {
        $this->rapport = $rapport;
        $this->subscriber = $subscriber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📊 Nouveau Rapport : ' . $this->rapport->titre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.rapport',
            with: [
                'rapport' => $this->rapport,
                'subscriber' => $this->subscriber,
                'preferencesUrl' => route('newsletter.preferences', $this->subscriber->token),
                'unsubscribeUrl' => route('newsletter.unsubscribe', $this->subscriber->token)
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
