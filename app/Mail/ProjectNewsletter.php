<?php

namespace App\Mail;

use App\Models\Projet;
use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectNewsletter extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $projet;
    public $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct(Projet $projet, Newsletter $subscriber)
    {
        $this->projet = $projet;
        $this->subscriber = $subscriber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚀 Nouveau Projet : ' . $this->projet->nom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.projet',
            with: [
                'projet' => $this->projet,
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
