<?php

namespace App\Mail;

use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublicationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newsletter;
    public $publication;
    public $contentType;
    public $unsubscribeUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Newsletter $newsletter, $publication, string $contentType)
    {
        $this->newsletter = $newsletter;
        $this->publication = $publication;
        $this->contentType = $contentType;
        $this->unsubscribeUrl = route('newsletter.unsubscribe', ['token' => $newsletter->token]);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->getSubjectByType();
        
        return $this->subject($subject)
                    ->view('emails.newsletter.publication-notification')
                    ->with([
                        'newsletter' => $this->newsletter,
                        'publication' => $this->publication,
                        'contentType' => $this->contentType,
                        'unsubscribeUrl' => $this->unsubscribeUrl,
                        'preferencesUrl' => route('newsletter.preferences', ['token' => $this->newsletter->token])
                    ]);
    }

    /**
     * Génère le sujet selon le type de contenu
     */
    private function getSubjectByType(): string
    {
        switch ($this->contentType) {
            case 'actualites':
                return 'Nouvelle actualité IRI-UCBC : ' . $this->publication->titre;
            case 'publications':
                return 'Nouvelle publication IRI-UCBC : ' . $this->publication->titre;
            case 'rapports':
                return 'Nouveau rapport IRI-UCBC : ' . $this->publication->titre;
            case 'evenements':
                return 'Nouvel événement IRI-UCBC : ' . $this->publication->titre;
            default:
                return 'Nouveau contenu IRI-UCBC : ' . $this->publication->titre;
        }
    }
}
