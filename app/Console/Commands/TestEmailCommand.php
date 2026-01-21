<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : Email address to send test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('À quelle adresse email voulez-vous envoyer le test ?');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Adresse email invalide !');
            return 1;
        }

        $this->info('Test de la configuration email...');
        $this->info('Host: ' . config('mail.mailers.smtp.host'));
        $this->info('Port: ' . config('mail.mailers.smtp.port'));
        $this->info('Encryption: ' . config('mail.mailers.smtp.encryption'));
        $this->info('Username: ' . config('mail.mailers.smtp.username'));
        $this->newLine();

        try {
            // Test de connexion SMTP
            $this->info('Test de connexion SMTP...');
            
            Mail::raw('Ceci est un email de test envoyé depuis l\'application IRI-UCBC.
            
Configuration testée :
- Serveur SMTP : ' . config('mail.mailers.smtp.host') . '
- Port : ' . config('mail.mailers.smtp.port') . '
- Encryption : ' . config('mail.mailers.smtp.encryption') . '
- De : ' . config('mail.from.address') . '

Si vous recevez cet email, la configuration fonctionne correctement !

Date/Heure : ' . now()->format('d/m/Y H:i:s'), function (Message $message) use ($email) {
                $message->to($email)
                        ->subject('Test de Configuration Email - IRI-UCBC')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $this->info('✅ Email de test envoyé avec succès !');
            $this->info("📧 Vérifiez votre boîte de réception : {$email}");
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'envoi de l\'email :');
            $this->error($e->getMessage());
            
            // Conseils de dépannage
            $this->newLine();
            $this->warn('💡 Conseils de dépannage :');
            $this->line('1. Vérifiez que le serveur SMTP est accessible');
            $this->line('2. Vérifiez les identifiants de connexion');
            $this->line('3. Vérifiez que le port 465 est ouvert');
            $this->line('4. Vérifiez que SSL est bien supporté');
            
            return 1;
        }

        return 0;
    }
}
