<?php

namespace App\Mail;

use App\Models\Publication;
use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PublicationNewsletterSimple extends Mailable
{
    use Queueable, SerializesModels;

    public $publication;
    public $subscriber;

    public function __construct(Publication $publication, Newsletter $subscriber)
    {
        $this->publication = $publication;
        $this->subscriber = $subscriber;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📚 Nouvelle Publication : ' . $this->publication->titre,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: $this->buildHtml()
        );
    }

    private function buildHtml(): string
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Nouvelle Publication</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
                .header { background: #ee6751; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .button { background: #ee6751; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>📚 Nouvelle Publication</h1>
                <p>Centre de Gouvernance des Ressources Naturelles</p>
            </div>
            <div class="content">
                <p>Bonjour ' . ($this->subscriber->nom ?? 'Cher abonné') . ',</p>
                <p>Nous avons le plaisir de vous annoncer la publication de :</p>
                <h2>' . htmlspecialchars($this->publication->titre) . '</h2>';
        
        if ($this->publication->description) {
            $html .= '<p>' . nl2br(htmlspecialchars(substr($this->publication->description, 0, 300))) . '</p>';
        }
        
        $html .= '
                <p>Cordialement,<br>L\'équipe IRI-UCBC</p>
                <hr>
                <p style="font-size: 12px; color: #666;">
                    Vous recevez cet email car vous êtes abonné à notre newsletter.
                </p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    public function attachments(): array
    {
        return [];
    }
}
