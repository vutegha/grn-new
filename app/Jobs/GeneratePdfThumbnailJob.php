<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Publication;
use App\Services\PdfThumbnailServiceFallback;
use Illuminate\Support\Facades\Log;

class GeneratePdfThumbnailJob implements ShouldQueue
{
    use Queueable;

    protected $publicationId;
    protected $pdfPath;

    /**
     * Create a new job instance.
     */
    public function __construct($publicationId, $pdfPath)
    {
        $this->publicationId = $publicationId;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("🔄 Génération miniature PDF pour publication #{$this->publicationId}");

            $publication = Publication::find($this->publicationId);
            
            if (!$publication) {
                Log::warning("❌ Publication #{$this->publicationId} introuvable");
                return;
            }

            // Générer un nom unique pour la miniature
            $filename = 'thumb_pub_' . $this->publicationId . '_' . time();
            
            $thumbnailPath = PdfThumbnailServiceFallback::generateThumbnail($this->pdfPath, $filename);

            if ($thumbnailPath) {
                Log::info("✅ Miniature générée avec succès: {$thumbnailPath}");
            } else {
                Log::warning("⚠️  Échec génération miniature pour publication #{$this->publicationId}");
            }

        } catch (\Exception $e) {
            Log::error("❌ Erreur job miniature PDF publication #{$this->publicationId}: " . $e->getMessage(), [
                'pdf_path' => $this->pdfPath,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
}
