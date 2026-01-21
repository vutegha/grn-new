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
        // Cette migration est redondante - les colonnes ont déjà été ajoutées
        // dans la migration précédente 2025_08_04_021458_add_moderation_fields_to_media_table
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            // Supprimer les contraintes de clés étrangères
            $table->dropForeign(['moderated_by']);
            $table->dropForeign(['created_by']);
            
            // Supprimer l'index
            $table->dropIndex(['created_by']);
            
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'moderated_by',
                'moderated_at',
                'created_by',
                'rejection_reason'
            ]);
        });
    }
};
