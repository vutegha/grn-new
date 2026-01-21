<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EmailSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Créer les configurations par défaut pour les emails de contact
        EmailSetting::create([
            'key' => 'contact_copy_emails',
            'label' => 'Adresses email de copie pour les messages de contact',
            'emails' => ['iri@ucbc.org', 's.vutegha@gmail.com'],
            'description' => 'Liste des adresses email qui recevront une copie de tous les messages de contact envoyés via le formulaire du site web.',
            'active' => true,
            'required' => true
        ]);

        EmailSetting::create([
            'key' => 'contact_main_email',
            'label' => 'Adresse email principale pour les messages de contact',
            'emails' => [config('mail.from.address', 'info@iri.ledinitiatives.com')],
            'description' => 'Adresse email principale qui recevra en premier tous les messages de contact.',
            'active' => true,
            'required' => true
        ]);

        EmailSetting::create([
            'key' => 'newsletter_copy_emails',
            'label' => 'Adresses email de copie pour les inscriptions newsletter',
            'emails' => ['iri@ucbc.org'],
            'description' => 'Liste des adresses email qui recevront une notification lors des nouvelles inscriptions à la newsletter.',
            'active' => true,
            'required' => false
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        EmailSetting::whereIn('key', [
            'contact_copy_emails',
            'contact_main_email', 
            'newsletter_copy_emails'
        ])->delete();
    }
};
