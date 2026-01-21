<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\PdfToImage\Pdf;

class PdfThumbnailService
{
    /**
     * Génère une miniature de la première page d'un PDF
     *
     * @param string $pdfPath Chemin vers le fichier PDF
     * @param string $filename Nom du fichier de sortie (sans extension)
     * @return string|null Chemin vers la miniature générée ou null si erreur
     */
    public static function generateThumbnail($pdfPath, $filename = null)
    {
        try {
            // Vérifier si le fichier PDF existe
            if (!Storage::exists($pdfPath)) {
                Log::warning("PDF non trouvé : {$pdfPath}");
                return null;
            }

            // Générer un nom de fichier si non fourni
            if (!$filename) {
                $filename = 'thumb_' . pathinfo($pdfPath, PATHINFO_FILENAME) . '_' . time();
            }

            // Chemin complet du PDF
            $fullPdfPath = Storage::path($pdfPath);
            
            // Dossier de destination pour les miniatures
            $thumbnailDir = 'public/thumbnails';
            $thumbnailPath = $thumbnailDir . '/' . $filename . '.jpg';
            
            // Créer le dossier s'il n'existe pas
            if (!Storage::exists($thumbnailDir)) {
                Storage::makeDirectory($thumbnailDir);
            }

            // Chemin complet de destination
            $fullThumbnailPath = Storage::path($thumbnailPath);

            // Vérifier si la miniature existe déjà
            if (Storage::exists($thumbnailPath)) {
                return str_replace('public/', '', $thumbnailPath);
            }

            // Vérifier si ImageMagick est disponible
            if (!extension_loaded('imagick')) {
                Log::warning("Extension ImageMagick non disponible pour générer les miniatures PDF");
                return null;
            }

            // Générer la miniature avec Imagick
            $imagick = new \Imagick();
            $imagick->setResolution(150, 150); // Résolution pour une bonne qualité
            $imagick->readImage($fullPdfPath . '[0]'); // Lire seulement la première page
            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompressionQuality(85);
            
            // Redimensionner pour optimiser
            $imagick->thumbnailImage(400, 600, true);
            
            // Sauvegarder
            $imagick->writeImage($fullThumbnailPath);
            $imagick->clear();
            $imagick->destroy();

            Log::info("Miniature PDF générée : {$thumbnailPath}");
            
            // Retourner le chemin relatif pour Storage::url()
            return str_replace('public/', '', $thumbnailPath);

        } catch (\Exception $e) {
            Log::error("Erreur lors de la génération de miniature PDF : " . $e->getMessage(), [
                'pdf_path' => $pdfPath,
                'filename' => $filename
            ]);
            return null;
        }
    }

    /**
     * Génère une miniature en utilisant une approche alternative (sans ImageMagick)
     */
    public static function generateThumbnailFallback($pdfPath, $filename = null)
    {
        try {
            // Cette méthode peut être étendue pour utiliser d'autres outils
            // comme pdf2pic, ghostscript, etc.
            
            Log::info("Génération de miniature en mode fallback pour : {$pdfPath}");
            return null;
            
        } catch (\Exception $e) {
            Log::error("Erreur fallback miniature PDF : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtient le chemin de la miniature pour un PDF donné
     */
    public static function getThumbnailPath($pdfPath)
    {
        if (!$pdfPath) {
            return null;
        }

        $filename = 'thumb_' . pathinfo($pdfPath, PATHINFO_FILENAME);
        $thumbnailPath = 'thumbnails/' . $filename . '.jpg';
        
        if (Storage::exists('public/' . $thumbnailPath)) {
            return $thumbnailPath;
        }

        // Générer la miniature si elle n'existe pas
        return self::generateThumbnail($pdfPath, $filename);
    }
}
