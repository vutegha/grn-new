<?php

namespace App\Mail;

use App\Models\Evenement;
use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvenementNewsletter extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $evenement;
    public $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct(Evenement $evenement, Newsletter $subscriber)
    {
        $this->evenement = $evenement;
        $this->subscriber = $subscriber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Nouvel Événement : ' . $this->evenement->titre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.evenement',
            with: [
                'evenement' => $this->evenement,
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
