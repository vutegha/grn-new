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
            // Ajouter le champ pour les raisons de désabonnement multiples (JSON)
            $table->json('unsubscribe_reasons')->nullable()->after('unsubscribe_reason');
            // Ajouter la date de désabonnement
            $table->timestamp('unsubscribed_at')->nullable()->after('unsubscribe_reasons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->dropColumn(['unsubscribe_reasons', 'unsubscribed_at']);
        });
    }
};
