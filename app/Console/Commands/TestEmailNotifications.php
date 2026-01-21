<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Publication;
use App\Notifications\ContentPublished;
use Illuminate\Support\Facades\Mail;

class TestEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-notifications {--email= : Email de test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Teste les notifications email du système de modération';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Test des Notifications Email IRI-UCBC ===');

        // Test 1: Configuration SMTP
        $this->info("\n1. Vérification de la configuration SMTP...");
        $this->checkMailConfig();

        // Test 2: Test de notification de publication
        $this->info("\n2. Test de notification de publication...");
        $this->testPublicationNotification();

        // Test 3: Test d'email simple
        if ($this->option('email')) {
            $this->info("\n3. Test d'email simple...");
            $this->testSimpleEmail();
        }

        $this->info("\n=== Tests terminés ===");
    }

    private function checkMailConfig()
    {
        $config = [
            'Mailer' => config('mail.default'),
            'Host' => config('mail.mailers.smtp.host'),
            'Port' => config('mail.mailers.smtp.port'),
            'From' => config('mail.from.address'),
            'Name' => config('mail.from.name'),
        ];

        foreach ($config as $key => $value) {
            $this->line("  • {$key}: " . ($value ?: 'Non configuré'));
        }
    }

    private function testPublicationNotification()
    {
        try {
            // Récupérer un utilisateur de test
            $user = User::first();
            if (!$user) {
                $this->error('Aucun utilisateur trouvé pour le test');
                return;
            }

            // Récupérer une publication de test
            $publication = Publication::first();
            if (!$publication) {
                $this->error('Aucune publication trouvée pour le test');
                return;
            }

            $this->info("  • Utilisateur: {$user->name} ({$user->email})");
            $this->info("  • Publication: {$publication->titre}");

            // Créer et tester la notification
            $notification = new ContentPublished($publication, 'publication');
            
            // Test en mode log pour éviter l'envoi réel
            $originalMailer = config('mail.default');
            config(['mail.default' => 'log']);
            
            $user->notify($notification);
            
            // Restaurer la configuration
            config(['mail.default' => $originalMailer]);
            
            $this->info("  ✓ Notification créée et enregistrée en log");
            
        } catch (\Exception $e) {
            $this->error("  ✗ Erreur: " . $e->getMessage());
        }
    }

    private function testSimpleEmail()
    {
        $email = $this->option('email');
        
        try {
            Mail::raw('Ceci est un test du système de notification IRI-UCBC.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Notification IRI-UCBC');
            });
            
            $this->info("  ✓ Email de test envoyé à: {$email}");
            
        } catch (\Exception $e) {
            $this->error("  ✗ Erreur d'envoi: " . $e->getMessage());
        }
    }
}
