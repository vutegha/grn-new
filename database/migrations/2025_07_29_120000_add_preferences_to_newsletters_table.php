<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('newsletters', function (Blueprint $table) {
            // Ajouter les préférences de contenu
            $table->json('preferences')->nullable()->after('confirme_a')
                ->comment('Préférences de contenu: actualites, publications, rapports, evenements');
            
            // Champs pour tracking
            $table->timestamp('last_email_sent')->nullable()->after('preferences')
                ->comment('Dernière fois qu\'un email a été envoyé');
            
            $table->integer('emails_sent_count')->default(0)->after('last_email_sent')
                ->comment('Nombre total d\'emails envoyés');
            
            // Raison de désabonnement
            $table->text('unsubscribe_reason')->nullable()->after('emails_sent_count')
                ->comment('Raison du désabonnement si applicable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->dropColumn([
                'preferences',
                'last_email_sent',
                'emails_sent_count',
                'unsubscribe_reason'
            ]);
        });
    }
};
