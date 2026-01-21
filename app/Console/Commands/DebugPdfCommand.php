<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Publication;

class DebugPdfCommand extends Command
{
    protected $signature = 'debug:pdf';
    protected $description = 'Debug PDF files';

    public function handle()
    {
        $this->info('=== DEBUG PDF FILES ===');
        
        $publications = Publication::whereNotNull('fichier_pdf')->get();
        $this->info("Nombre de publications avec fichier PDF: " . $publications->count());
        
        foreach ($publications as $publication) {
            $this->info("Publication ID: {$publication->id}");
            $this->info("Titre: {$publication->titre}");
            $this->info("Fichier PDF: {$publication->fichier_pdf}");
            
            // Vérifier si le fichier existe
            $fullPath = storage_path('app/public/' . $publication->fichier_pdf);
            $publicPath = public_path('storage/' . $publication->fichier_pdf);
            
            $this->info("Chemin complet: {$fullPath}");
            $this->info("Fichier existe (storage): " . (file_exists($fullPath) ? "OUI" : "NON"));
            $this->info("Fichier existe (public): " . (file_exists($publicPath) ? "OUI" : "NON"));
            
            if (file_exists($fullPath)) {
                $this->info("Taille du fichier: " . filesize($fullPath) . " bytes");
                $this->info("Extension: " . pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION));
            }
            
            $this->info("URL générée: " . asset('storage/' . $publication->fichier_pdf));
            $this->info("---");
        }
        
        // Vérifier le lien symbolique
        $this->info("\n=== VERIFICATION LIEN SYMBOLIQUE ===");
        $storageLink = public_path('storage');
        $this->info("Lien storage existe: " . (is_link($storageLink) ? "OUI" : "NON"));
        
        return 0;
    }
}
