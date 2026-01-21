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
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            // Note: 'point_focal' est déprécié - les points focaux sont maintenant intégrés dans les bureaux régionaux via les champs responsable_*
            $table->enum('type', ['bureau_principal', 'bureau_regional', 'point_focal', 'autre'])->default('autre');
            $table->string('nom'); // Nom du bureau ou du point focal
            $table->string('titre')->nullable(); // Ex: "Bureau Régional de Goma", "Point Focal Nord-Kivu"
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('province')->nullable();
            $table->string('pays')->default('RDC');
            
            // Coordonnées du bureau (usage interne - NON affichées sur le site public)
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('telephone_secondaire')->nullable();
            $table->text('horaires')->nullable(); // Horaires d'ouverture
            
            // Informations du responsable/point focal (AFFICHÉES sur le site public)
            $table->string('responsable_nom')->nullable(); // Nom du responsable/point focal
            $table->string('responsable_fonction')->nullable(); // Fonction du responsable
            $table->string('responsable_email')->nullable();
            $table->string('responsable_telephone')->nullable();
            $table->string('photo')->nullable(); // Photo du responsable/point focal
            
            $table->text('description')->nullable(); // Description du bureau ou point focal
            $table->decimal('latitude', 10, 7)->nullable(); // Pour la carte
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('ordre')->default(0); // Pour l'ordre d'affichage
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'actif']);
            $table->index('province');
            $table->index('ordre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_infos');
    }
};
