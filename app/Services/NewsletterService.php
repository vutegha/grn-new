<?php

namespace App\Services;

use App\Models\Newsletter;
use App\Mail\NewsletterWelcomeMail;
use App\Mail\PublicationNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NewsletterService
{
    /**
     * Envoie un email de bienvenue à un nouvel abonné
     */
    public function sendWelcomeEmail(Newsletter $newsletter)
    {
        try {
            Mail::to($newsletter->email)->send(new NewsletterWelcomeMail($newsletter));
            
            // Marquer l'email comme envoyé
            $newsletter->markEmailSent();
            
            Log::info('Email de bienvenue newsletter envoyé', [
                'email' => $newsletter->email,
                'nom' => $newsletter->nom
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email bienvenue newsletter', [
                'email' => $newsletter->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Notifie les abonnés d'une nouvelle publication
     */
    public function notifySubscribersOfPublication($publication, string $contentType)
    {
        try {
            // Récupérer les abonnés intéressés par ce type de contenu
            $subscribers = Newsletter::getSubscribersForContent($contentType);
            
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($subscribers as $subscriber) {
                try {
                    Mail::to($subscriber->email)->send(
                        new PublicationNotificationMail($subscriber, $publication, $contentType)
                    );
                    
                    // Marquer l'email comme envoyé
                    $subscriber->markEmailSent();
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::error('Erreur envoi notification publication', [
                        'email' => $subscriber->email,
                        'publication' => $publication->titre ?? $publication->id,
                        'type' => $contentType,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            Log::info('Notifications de publication envoyées', [
                'publication' => $publication->titre ?? $publication->id,
                'type' => $contentType,
                'total_subscribers' => $subscribers->count(),
                'success' => $successCount,
                'errors' => $errorCount
            ]);
            
            return [
                'success' => true,
                'total' => $subscribers->count(),
                'sent' => $successCount,
                'errors' => $errorCount
            ];
            
        } catch (\Exception $e) {
            Log::error('Erreur générale notification publication', [
                'publication' => $publication->titre ?? $publication->id,
                'type' => $contentType,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifie si un abonné peut recevoir un type de contenu
     */
    public function canReceiveContentType(Newsletter $newsletter, string $contentType): bool
    {
        // Vérifier si l'abonné est actif et confirmé
        if (!$newsletter->actif || !$newsletter->confirme_a) {
            return false;
        }
        
        // Vérifier les préférences
        return $newsletter->hasPreference($contentType);
    }

    /**
     * Met à jour les préférences d'un abonné
     */
    public function updatePreferences(Newsletter $newsletter, array $preferences): bool
    {
        try {
            $newsletter->updatePreferences($preferences);
            
            Log::info('Préférences newsletter mises à jour', [
                'email' => $newsletter->email,
                'preferences' => $preferences
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour préférences newsletter', [
                'email' => $newsletter->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Désabonne un utilisateur
     */
    public function unsubscribe(Newsletter $newsletter, string $reason = null, array $reasons = []): bool
    {
        try {
            $newsletter->update([
                'actif' => false,
                'unsubscribe_reason' => $reason,
                'unsubscribe_reasons' => $reasons,
                'unsubscribed_at' => now()
            ]);
            
            Log::info('Désabonnement newsletter', [
                'email' => $newsletter->email,
                'reason' => $reason,
                'reasons' => $reasons
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur désabonnement newsletter', [
                'email' => $newsletter->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Obtient les statistiques d'envoi pour un abonné
     */
    public function getSubscriberStats(Newsletter $newsletter): array
    {
        return [
            'email' => $newsletter->email,
            'nom' => $newsletter->nom,
            'actif' => $newsletter->actif,
            'confirme_a' => $newsletter->confirme_a,
            'total_emails_sent' => $newsletter->emails_sent_count ?? 0,
            'last_email_sent' => $newsletter->last_email_sent,
            'preferences' => $newsletter->preferences ?? []
        ];
    }
}
