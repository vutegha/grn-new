<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfThumbnailServiceFallback
{
    /**
     * Génère une miniature pour un PDF donné
     * Version fallback qui crée une image placeholder stylisée
     * 
     * @param string $pdfPath Chemin vers le PDF
     * @param string $filename Nom du fichier de sortie (sans extension)
     * @return string|false Chemin de la miniature générée ou false en cas d'erreur
     */
    public static function generateThumbnail($pdfPath, $filename)
    {
        try {
            // Créer le répertoire des miniatures s'il n'existe pas
            $thumbnailDir = 'thumbnails';
            if (!Storage::disk('public')->exists($thumbnailDir)) {
                Storage::disk('public')->makeDirectory($thumbnailDir);
            }

            // Vérifier si le PDF existe
            if (!file_exists($pdfPath)) {
                // Essayer avec le chemin storage si c'est un chemin relatif
                if (strpos($pdfPath, 'storage') === false && strpos($pdfPath, ':\\') === false) {
                    $altPath = storage_path('app/public/' . $pdfPath);
                    if (file_exists($altPath)) {
                        $pdfPath = $altPath;
                    } else {
                        Log::warning("PDF non trouvé: {$pdfPath} ni {$altPath}");
                        return false;
                    }
                } else {
                    Log::warning("PDF non trouvé: {$pdfPath}");
                    return false;
                }
            }

            // Générer le chemin de sortie
            $outputPath = $thumbnailDir . '/' . $filename . '.jpg';
            $fullOutputPath = storage_path('app/public/' . $outputPath);

            // Méthode 1: Tentative avec ImageMagick si disponible
            if (extension_loaded('imagick')) {
                try {
                    $imagick = new \Imagick();
                    $imagick->setResolution(150, 150);
                    $imagick->readImage($pdfPath . '[0]'); // Première page
                    $imagick->setImageFormat('jpeg');
                    $imagick->setImageCompressionQuality(80);
                    $imagick->thumbnailImage(300, 400, true, true);
                    
                    if ($imagick->writeImage($fullOutputPath)) {
                        $imagick->clear();
                        $imagick->destroy();
                        Log::info("Miniature générée avec ImageMagick: {$outputPath}");
                        return $outputPath;
                    }
                } catch (\Exception $e) {
                    Log::warning("Échec ImageMagick: " . $e->getMessage());
                }
            }

            // Méthode 2: Génération d'une image placeholder stylisée
            $image = imagecreatetruecolor(300, 400);
            
            // Couleurs
            $background = imagecolorallocate($image, 245, 245, 245); // Gris clair
            $border = imagecolorallocate($image, 59, 130, 246); // Bleu
            $textColor = imagecolorallocate($image, 55, 65, 81); // Gris foncé
            $pdfIcon = imagecolorallocate($image, 239, 68, 68); // Rouge PDF
            
            // Remplir le fond
            imagefill($image, 0, 0, $background);
            
            // Bordure
            imagerectangle($image, 0, 0, 299, 399, $border);
            imagerectangle($image, 1, 1, 298, 398, $border);
            
            // Icône PDF stylisée
            imagefilledrectangle($image, 120, 120, 180, 140, $pdfIcon);
            imagestring($image, 5, 135, 125, 'PDF', imagecolorallocate($image, 255, 255, 255));
            
            // Lignes simulant du texte
            for ($i = 0; $i < 8; $i++) {
                $y = 180 + ($i * 20);
                $width = rand(150, 250);
                imagefilledrectangle($image, 25, $y, 25 + $width, $y + 3, $textColor);
            }
            
            // Sauvegarder l'image
            if (imagejpeg($image, $fullOutputPath, 80)) {
                imagedestroy($image);
                Log::info("Miniature placeholder générée: {$outputPath}");
                return $outputPath;
            } else {
                imagedestroy($image);
                Log::error("Impossible de sauvegarder la miniature placeholder");
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Erreur génération miniature: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retourne le chemin de la miniature pour un fichier PDF
     * 
     * @param string $pdfPath Chemin vers le PDF
     * @param int $publicationId ID de la publication
     * @return string|null
     */
    public static function getThumbnailPath($pdfPath, $publicationId)
    {
        $filename = 'thumb_pub_' . $publicationId;
        $thumbnailPath = 'thumbnails/' . $filename . '.jpg';
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return $thumbnailPath;
        }
        
        return null;
    }

    /**
     * Vérifie si une miniature existe pour une publication
     * 
     * @param int $publicationId
     * @return bool
     */
    public static function thumbnailExists($publicationId)
    {
        $filename = 'thumb_pub_' . $publicationId;
        $thumbnailPath = 'thumbnails/' . $filename . '.jpg';
        
        return Storage::disk('public')->exists($thumbnailPath);
    }

    /**
     * Génère les miniatures pour toutes les publications sans miniature
     * 
     * @param bool $force Regénérer même si la miniature existe
     * @return array Statistiques de génération
     */
    public static function generateAllThumbnails($force = false)
    {
        $stats = [
            'processed' => 0,
            'success' => 0,
            'errors' => 0,
            'skipped' => 0
        ];

        $publications = \App\Models\Publication::whereNotNull('fichier_pdf')->get();

        foreach ($publications as $publication) {
            $stats['processed']++;

            // Vérifier si la miniature existe déjà
            if (!$force && self::thumbnailExists($publication->id)) {
                $stats['skipped']++;
                continue;
            }

            $pdfPath = storage_path('app/public/' . $publication->fichier_pdf);
            $filename = 'thumb_pub_' . $publication->id . '_' . time();

            if (self::generateThumbnail($pdfPath, $filename)) {
                $stats['success']++;
            } else {
                $stats['errors']++;
            }
        }

        return $stats;
    }
}
