<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;
use App\Models\EmailSetting;

class ContactMessageWithCopy extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $isForAdmin;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, bool $isForAdmin = true)
    {
        $this->contact = $contact;
        $this->isForAdmin = $isForAdmin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isForAdmin 
            ? 'Nouveau message de contact - ' . $this->contact->sujet
            : 'Copie de votre message - ' . $this->contact->sujet;

        return new Envelope(
            subject: $subject,
            replyTo: [
                $this->contact->email,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->isForAdmin 
            ? 'emails.contact-message-admin'
            : 'emails.contact-message-copy';

        return new Content(
            view: $view,
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Envoyer le message à toutes les adresses configurées
     */
    public static function sendToConfiguredEmails(Contact $contact)
    {
        try {
            $totalSent = 0;
            $copyRecipients = [];
            $acknowledgmentRecipients = [];

            // 1. Récupérer la configuration email active
            $emailSetting = EmailSetting::where('active', true)->first();
            
            if (!$emailSetting) {
                throw new \Exception('Aucune configuration email active trouvée');
            }

            // 4. Envoyer aux adresses configurées (pour admins)
            $targetEmails = $emailSetting->emails;
            
            foreach ($targetEmails as $email) {
                $adminMail = new ContactMessageWithCopy($contact, true);
                \Mail::to($email)->send($adminMail);
                
                $copyRecipients[] = $email;
                $totalSent++;
            }

            // 5. Envoyer accusé de réception formaté à l'expéditeur
            $confirmationMail = new ContactMessageWithCopy($contact, false);
            \Mail::to($contact->email)->send($confirmationMail);
            
            $acknowledgmentRecipients[] = $contact->email;
            $totalSent++;

            return [
                'success' => true,
                'total_sent' => $totalSent,
                'copy_recipients' => $copyRecipients,
                'acknowledgment_recipients' => $acknowledgmentRecipients
            ];

        } catch (\Exception $e) {
            \Log::error('Erreur ContactMessageWithCopy: ' . $e->getMessage(), [
                'contact_id' => $contact->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'total_sent' => 0
            ];
        }
    }
}
