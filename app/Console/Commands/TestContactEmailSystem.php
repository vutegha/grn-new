<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\EmailSetting;
use App\Mail\ContactMessageWithCopy;

class TestContactEmailSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:test-email 
                            {--email= : Email de test pour recevoir une copie}
                            {--name=Utilisateur Test : Nom de l\'expéditeur du test}
                            {--subject=Test du système de contact : Sujet du message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester le système d\'envoi d\'emails de contact avec copies configurables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Test du système d\'emails de contact');
        $this->newLine();

        // Vérifier les configurations email
        $this->checkEmailConfigurations();
        
        // Créer un contact de test
        $contact = $this->createTestContact();
        
        // Tester l'envoi
        $this->testEmailSending($contact);
        
        // Nettoyer
        $this->cleanup($contact);

        return Command::SUCCESS;
    }

    /**
     * Vérifier les configurations email
     */
    private function checkEmailConfigurations()
    {
        $this->info('📋 Vérification des configurations email...');
        
        $mainEmails = EmailSetting::getActiveEmails('contact_main_email');
        $copyEmails = EmailSetting::getActiveEmails('contact_copy_emails');
        
        $this->table(['Type', 'Adresses configurées', 'Statut'], [
            [
                'Principal', 
                implode(', ', $mainEmails), 
                count($mainEmails) > 0 ? '✅ Actif' : '❌ Aucune adresse'
            ],
            [
                'Copies', 
                implode(', ', $copyEmails), 
                count($copyEmails) > 0 ? '✅ Actif' : '⚠️ Aucune copie'
            ]
        ]);

        if (count($mainEmails) === 0) {
            $this->error('❌ Aucune adresse email principale configurée !');
            return false;
        }

        $this->info('✅ Configuration email vérifiée');
        $this->newLine();
        return true;
    }

    /**
     * Créer un contact de test
     */
    private function createTestContact()
    {
        $this->info('📝 Création d\'un message de contact de test...');
        
        $testEmail = $this->option('email') ?: $this->ask('Votre email pour recevoir une copie de test');
        $name = $this->option('name');
        $subject = $this->option('subject');
        
        $message = "Ceci est un message de test pour vérifier le bon fonctionnement du système d'envoi d'emails de contact.\n\n" .
                  "Détails du test :\n" .
                  "- Date : " . now()->format('d/m/Y à H:i:s') . "\n" .
                  "- Email testeur : {$testEmail}\n" .
                  "- Système : " . php_uname() . "\n" .
                  "- Version Laravel : " . app()->version() . "\n\n" .
                  "Si vous recevez cet email, le système fonctionne correctement !";

        $contact = Contact::create([
            'nom' => $name,
            'email' => $testEmail,
            'sujet' => $subject,
            'message' => $message,
            'statut' => 'test'
        ]);

        $this->info("✅ Contact de test créé (ID: {$contact->id})");
        $this->line("   👤 Nom: {$contact->nom}");
        $this->line("   📧 Email: {$contact->email}");
        $this->line("   📋 Sujet: {$contact->sujet}");
        $this->newLine();

        return $contact;
    }

    /**
     * Tester l'envoi d'emails
     */
    private function testEmailSending($contact)
    {
        $this->info('📧 Test d\'envoi des emails...');
        
        try {
            // Utiliser une barre de progression
            $this->output->progressStart(4);
            
            // Étape 1: Préparation
            $this->output->progressAdvance();
            sleep(1);
            
            // Étape 2: Envoi des emails
            $result = ContactMessageWithCopy::sendToConfiguredEmails($contact);
            $this->output->progressAdvance();
            sleep(1);
            
            // Étape 3: Vérification
            $this->output->progressAdvance();
            sleep(1);
            
            // Étape 4: Finalisation
            $this->output->progressAdvance();
            
            $this->output->progressFinish();
            $this->newLine();

            if ($result['success']) {
                $this->info('✅ Emails envoyés avec succès !');
                $this->newLine();
                
                $this->table(['Type', 'Nombre d\'emails', 'Détail'], [
                    ['Principal', $result['main_recipients'], 'Adresses principales'],
                    ['Copies', $result['copy_recipients'], 'Adresses de copie'],
                    ['Confirmation', 1, 'Email à l\'expéditeur'],
                    ['TOTAL', $result['total_sent'], 'Emails envoyés']
                ]);
                
                $this->info('📬 Vérifiez vos boîtes de réception pour confirmer la réception.');
                $this->warn('💡 N\'oubliez pas de vérifier le dossier spam/courrier indésirable.');
                
            } else {
                $this->error('❌ Erreur lors de l\'envoi des emails :');
                $this->line("   {$result['error']}");
            }

        } catch (\Exception $e) {
            $this->error('❌ Exception lors du test :');
            $this->line("   {$e->getMessage()}");
            $this->newLine();
            $this->line('🔍 Vérifiez :');
            $this->line('  - La configuration SMTP dans le fichier .env');
            $this->line('  - La connexion Internet');
            $this->line('  - Les paramètres du serveur email');
        }
    }

    /**
     * Nettoyer après le test
     */
    private function cleanup($contact)
    {
        $this->newLine();
        $keep = $this->confirm('💾 Voulez-vous conserver ce message de test dans la base de données ?', false);
        
        if (!$keep) {
            $contact->delete();
            $this->info('🗑️ Message de test supprimé de la base de données');
        } else {
            $this->info('💾 Message de test conservé (ID: ' . $contact->id . ')');
        }
        
        $this->newLine();
        $this->info('🎉 Test terminé !');
    }
}
