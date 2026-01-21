<?php

namespace App\Listeners;

use App\Events\PublicationFeaturedCreated;
use App\Events\ActualiteFeaturedCreated;
use App\Events\ProjectCreated;
use App\Events\RapportCreated;
use App\Events\EvenementFeaturedCreated;
use App\Mail\PublicationNewsletter;
use App\Mail\ActualiteNewsletter;
use App\Mail\ProjectNewsletter;
use App\Mail\RapportNewsletter;
use App\Mail\EvenementNewsletter;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewsletterEmail
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        try {
            switch (true) {
                case $event instanceof PublicationFeaturedCreated:
                    $this->sendPublicationNewsletter($event->publication);
                    break;
                case $event instanceof ActualiteFeaturedCreated:
                    $this->sendActualiteNewsletter($event->actualite);
                    break;
                case $event instanceof ProjectCreated:
                    $this->sendProjectNewsletter($event->projet);
                    break;
                case $event instanceof RapportCreated:
                    $this->sendRapportNewsletter($event->rapport);
                    break;
                case $event instanceof EvenementFeaturedCreated:
                    $this->sendEvenementNewsletter($event->evenement);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi newsletter: ' . $e->getMessage(), [
                'event' => get_class($event),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Envoyer newsletter pour publication
     */
    private function sendPublicationNewsletter($publication)
    {
        $subscribers = Newsletter::active()
            ->whereJsonContains('preferences->publications', true)
            ->get();

        $emailCount = 0;
        $errorCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                // Essayer d'abord avec Laravel Mail
                Mail::to($subscriber->email)
                    ->send(new PublicationNewsletter($publication, $subscriber));
                
                $emailCount++;
                Log::info("Email Laravel envoyé à: " . $subscriber->email);
                
            } catch (\Exception $e) {
                Log::warning("Échec Laravel Mail pour {$subscriber->email}, essai direct: " . $e->getMessage());
                
                try {
                    // En cas d'échec, utiliser le service direct
                    $directService = new \App\Services\DirectEmailService();
                    $directService->sendPublicationNewsletter($publication, $subscriber);
                    
                    $emailCount++;
                    Log::info("Email direct envoyé à: " . $subscriber->email);
                    
                } catch (\Exception $e2) {
                    $errorCount++;
                    Log::error("Échec envoi direct à {$subscriber->email}: " . $e2->getMessage());
                }
            }
        }

        Log::info("Newsletter publication envoyée à {$subscribers->count()} abonnés", [
            'publication_id' => $publication->id,
            'publication_titre' => $publication->titre,
            'emails_sent' => $emailCount,
            'errors' => $errorCount
        ]);
    }

    /**
     * Envoyer newsletter pour actualité
     */
    private function sendActualiteNewsletter($actualite)
    {
        $subscribers = Newsletter::active()
            ->whereJsonContains('preferences->actualites', true)
            ->get();

        $emailCount = 0;
        $errorCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                // Essayer d'abord avec Laravel Mail
                Mail::to($subscriber->email)
                    ->send(new ActualiteNewsletter($actualite, $subscriber));
                
                $emailCount++;
                Log::info("Email actualité Laravel envoyé à: " . $subscriber->email);
                
            } catch (\Exception $e) {
                Log::warning("Échec Laravel Mail actualité pour {$subscriber->email}, essai direct: " . $e->getMessage());
                
                try {
                    // En cas d'échec, utiliser le service direct
                    $directService = new \App\Services\DirectEmailService();
                    $directService->sendActualiteNewsletter($actualite, $subscriber);
                    
                    $emailCount++;
                    Log::info("Email actualité direct envoyé à: " . $subscriber->email);
                    
                } catch (\Exception $e2) {
                    $errorCount++;
                    Log::error("Échec envoi actualité direct à {$subscriber->email}: " . $e2->getMessage());
                }
            }
        }

        Log::info("Newsletter actualité envoyée à {$subscribers->count()} abonnés", [
            'actualite_id' => $actualite->id,
            'actualite_titre' => $actualite->titre,
            'emails_sent' => $emailCount,
            'errors' => $errorCount
        ]);
    }

    /**
     * Envoyer newsletter pour projet
     */
    private function sendProjectNewsletter($projet)
    {
        $subscribers = Newsletter::active()
            ->whereJsonContains('preferences->projets', true)
            ->get();

        $emailCount = 0;
        $errorCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                // Essayer d'abord avec Laravel Mail
                Mail::to($subscriber->email)
                    ->send(new ProjectNewsletter($projet, $subscriber));
                
                $emailCount++;
                Log::info("Email projet Laravel envoyé à: " . $subscriber->email);
                
            } catch (\Exception $e) {
                Log::warning("Échec Laravel Mail projet pour {$subscriber->email}, essai direct: " . $e->getMessage());
                
                try {
                    // En cas d'échec, utiliser le service direct
                    $directService = new \App\Services\DirectEmailService();
                    $directService->sendProjectNewsletter($projet, $subscriber);
                    
                    $emailCount++;
                    Log::info("Email projet direct envoyé à: " . $subscriber->email);
                    
                } catch (\Exception $e2) {
                    $errorCount++;
                    Log::error("Échec envoi projet direct à {$subscriber->email}: " . $e2->getMessage());
                }
            }
        }

        Log::info("Newsletter projet envoyée à {$subscribers->count()} abonnés", [
            'projet_id' => $projet->id,
            'projet_titre' => $projet->titre,
            'emails_sent' => $emailCount,
            'errors' => $errorCount
        ]);
    }

    /**
     * Envoyer newsletter pour rapport
     */
    private function sendRapportNewsletter($rapport)
    {
        $subscribers = Newsletter::active()
            ->whereJsonContains('preferences->rapports', true)
            ->get();

        $emailCount = 0;
        $errorCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                // Essayer d'abord avec Laravel Mail
                Mail::to($subscriber->email)
                    ->send(new RapportNewsletter($rapport, $subscriber));
                
                $emailCount++;
                Log::info("Email rapport Laravel envoyé à: " . $subscriber->email);
                
            } catch (\Exception $e) {
                Log::warning("Échec Laravel Mail rapport pour {$subscriber->email}, essai direct: " . $e->getMessage());
                
                try {
                    // En cas d'échec, utiliser le service direct
                    $directService = new \App\Services\DirectEmailService();
                    $directService->sendRapportNewsletter($rapport, $subscriber);
                    
                    $emailCount++;
                    Log::info("Email rapport direct envoyé à: " . $subscriber->email);
                    
                } catch (\Exception $e2) {
                    $errorCount++;
                    Log::error("Échec envoi rapport direct à {$subscriber->email}: " . $e2->getMessage());
                }
            }
        }

        Log::info("Newsletter rapport envoyée à {$subscribers->count()} abonnés", [
            'rapport_id' => $rapport->id,
            'rapport_titre' => $rapport->titre,
            'emails_sent' => $emailCount,
            'errors' => $errorCount
        ]);
    }

    /**
     * Envoyer newsletter pour événement
     */
    private function sendEvenementNewsletter($evenement)
    {
        $subscribers = Newsletter::active()
            ->whereJsonContains('preferences->evenements', true)
            ->get();

        $emailCount = 0;
        $errorCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                // Essayer d'abord avec Laravel Mail
                Mail::to($subscriber->email)
                    ->send(new EvenementNewsletter($evenement, $subscriber));
                
                $emailCount++;
                Log::info("Email événement Laravel envoyé à: " . $subscriber->email);
                
            } catch (\Exception $e) {
                Log::warning("Échec Laravel Mail événement pour {$subscriber->email}, essai direct: " . $e->getMessage());
                
                try {
                    // En cas d'échec, utiliser le service direct
                    $directService = new \App\Services\DirectEmailService();
                    $directService->sendEvenementNewsletter($evenement, $subscriber);
                    
                    $emailCount++;
                    Log::info("Email événement direct envoyé à: " . $subscriber->email);
                    
                } catch (\Exception $e2) {
                    $errorCount++;
                    Log::error("Échec envoi événement direct à {$subscriber->email}: " . $e2->getMessage());
                }
            }
        }

        Log::info("Newsletter événement envoyée à {$subscribers->count()} abonnés", [
            'evenement_id' => $evenement->id,
            'evenement_titre' => $evenement->titre,
            'emails_sent' => $emailCount,
            'errors' => $errorCount
        ]);
    }
}
