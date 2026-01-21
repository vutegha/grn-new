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
        Schema::table('actualites', function (Blueprint $table) {
            // Ajouter les colonnes manquantes référencées dans le modèle
            $table->string('social_image_path')->nullable()->after('image');
            $table->string('image_alt')->nullable()->after('social_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actualites', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn(['social_image_path', 'image_alt']);
        });
    }
};
