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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['temps_plein', 'temps_partiel', 'contrat', 'stage', 'freelance'])->default('temps_plein');
            $table->string('location')->nullable();
            $table->string('department')->nullable();
            $table->enum('source', ['interne', 'partenaire', 'externe'])->default('interne');
            $table->string('partner_name')->nullable();
            $table->enum('status', ['draft', 'active', 'paused', 'expired', 'closed'])->default('draft');
            $table->date('application_deadline')->nullable();
            $table->json('requirements')->nullable();
            $table->json('criteria')->nullable();
            $table->text('benefits')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->boolean('salary_negotiable')->default(false);
            $table->integer('positions_available')->default(1);
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('document_appel_offre')->nullable();
            $table->string('document_appel_offre_nom')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'application_deadline']);
            $table->index(['is_featured', 'created_at']);
            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
