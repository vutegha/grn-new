<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Publication;
use App\Models\Newsletter;
use App\Events\PublicationFeaturedCreated;
use App\Mail\PublicationNewsletter;
use Illuminate\Support\Facades\Mail;

class TestNewsletterCommand extends Command
{
    protected $signature = 'test:newsletter';
    protected $description = 'Test newsletter functionality';

    public function handle()
    {
        $this->info('=== TEST NEWSLETTER COMMAND ===');

        try {
            // 1. Vérifier les données
            $this->info('📊 Vérification des données...');
            $publication = Publication::first();
            $newsletters = Newsletter::active()->get();

            $this->info("   Publications: " . Publication::count());
            $this->info("   Newsletters actives: " . $newsletters->count());

            if (!$publication || $newsletters->count() === 0) {
                $this->error('❌ Données manquantes');
                return 1;
            }

            // 2. Test envoi direct
            $this->info('📧 Test envoi direct...');
            $newsletter = $newsletters->first();

            try {
                Mail::to($newsletter->email)->send(new PublicationNewsletter($publication, $newsletter));
                $this->info('   ✅ Email direct envoyé à ' . $newsletter->email);
            } catch (\Exception $e) {
                $this->error('   ❌ Erreur envoi direct: ' . $e->getMessage());
            }

            // 3. Test événement
            $this->info('🎯 Test événement...');
            try {
                event(new PublicationFeaturedCreated($publication));
                $this->info('   ✅ Événement déclenché');
            } catch (\Exception $e) {
                $this->error('   ❌ Erreur événement: ' . $e->getMessage());
            }

            $this->info('✅ Test terminé');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erreur générale: ' . $e->getMessage());
            return 1;
        }
    }
}
