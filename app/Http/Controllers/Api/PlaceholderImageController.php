<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PlaceholderImageController extends Controller
{
    /**
     * Génère une image placeholder dynamique pour les actualités
     */
    public function generateActualiteImage(Request $request)
    {
        // Validation des paramètres
        $request->validate([
            'title' => 'string|max:100',
            'width' => 'integer|min:200|max:2000',
            'height' => 'integer|min:200|max:2000',
        ]);

        $title = $request->get('title', 'GRN-UCBC');
        $width = $request->get('width', 1200);
        $height = $request->get('height', 630);

        // Vérifier si GD est disponible
        if (!extension_loaded('gd')) {
            abort(500, 'Extension GD non disponible');
        }

        // Créer l'image
        $image = imagecreatetruecolor($width, $height);

        // Couleurs GRN
        $green = imagecolorallocate($image, 34, 139, 34);
        $white = imagecolorallocate($image, 255, 255, 255);
        $lightGreen = imagecolorallocate($image, 144, 238, 144);
        $darkGreen = imagecolorallocate($image, 0, 100, 0);

        // Fond dégradé
        imagefill($image, 0, 0, $green);

        // Effet dégradé simplifié
        for ($i = 0; $i < $height; $i += 10) {
            $ratio = $i / $height;
            $alpha = (int)(60 * $ratio);
            $gradientColor = imagecolorallocatealpha($image, 255, 255, 255, 127 - $alpha);
            imagefilledrectangle($image, 0, $i, $width, $i + 10, $gradientColor);
        }

        // Texte principal
        $siteName = "GRN-UCBC";
        $subtitle = "Groupement des Ressources Naturelles";
        
        // Tronquer le titre si trop long
        $displayTitle = strlen($title) > 50 ? substr($title, 0, 47) . '...' : $title;

        // Centrer les textes
        $siteNameLen = strlen($siteName);
        $siteX = ($width - ($siteNameLen * 15)) / 2;
        $siteY = ($height / 2) - 60;
        imagestring($image, 5, $siteX, $siteY, $siteName, $white);

        // Sous-titre
        $subLen = strlen($subtitle);
        $subX = ($width - ($subLen * 11)) / 2;
        $subY = $siteY + 40;
        imagestring($image, 4, $subX, $subY, $subtitle, $white);

        // Titre de l'article
        if ($displayTitle !== 'GRN-UCBC') {
            $titleLen = strlen($displayTitle);
            $titleX = ($width - ($titleLen * 9)) / 2;
            $titleY = $subY + 40;
            imagestring($image, 3, $titleX, $titleY, $displayTitle, $lightGreen);
        }

        // Logo GRN (rectangle simple)
        $logoWidth = 100;
        $logoHeight = 50;
        $logoX = $width - $logoWidth - 20;
        $logoY = 20;
        imagefilledrectangle($image, $logoX, $logoY, $logoX + $logoWidth, $logoY + $logoHeight, $white);
        imagerectangle($image, $logoX, $logoY, $logoX + $logoWidth, $logoY + $logoHeight, $darkGreen);

        // Texte du logo
        $initX = $logoX + 25;
        $initY = $logoY + 15;
        imagestring($image, 4, $initX, $initY, "GRN", $darkGreen);

        // Envoyer l'image
        ob_start();
        imagejpeg($image, null, 90);
        $imageData = ob_get_contents();
        ob_end_clean();

        // Nettoyer la mémoire
        imagedestroy($image);

        // Headers pour le cache
        $headers = [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400', // Cache 24h
            'Expires' => gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
        ];

        return response($imageData, 200, $headers);
    }

    /**
     * Génère une image placeholder générique
     */
    public function generateGenericImage(Request $request)
    {
        $request->validate([
            'width' => 'integer|min:100|max:2000',
            'height' => 'integer|min:100|max:2000',
            'text' => 'string|max:50',
            'bg' => 'string|regex:/^[0-9A-Fa-f]{6}$/',
            'color' => 'string|regex:/^[0-9A-Fa-f]{6}$/',
        ]);

        $width = $request->get('width', 400);
        $height = $request->get('height', 300);
        $text = $request->get('text', 'GRN');
        $bgHex = $request->get('bg', '228B22'); // Green par défaut
        $colorHex = $request->get('color', 'FFFFFF'); // White par défaut

        // Convertir hex en RGB
        $bgRgb = $this->hexToRgb($bgHex);
        $colorRgb = $this->hexToRgb($colorHex);

        // Créer l'image
        $image = imagecreatetruecolor($width, $height);
        $bgColor = imagecolorallocate($image, $bgRgb[0], $bgRgb[1], $bgRgb[2]);
        $textColor = imagecolorallocate($image, $colorRgb[0], $colorRgb[1], $colorRgb[2]);

        // Fond
        imagefill($image, 0, 0, $bgColor);

        // Centrer le texte
        $textLen = strlen($text);
        $fontSize = min(5, max(1, (int)($width / ($textLen * 12))));
        $textWidth = $textLen * (10 + $fontSize * 2);
        $textHeight = 15 + $fontSize * 3;
        
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $text, $textColor);

        // Dimensions en bas à droite
        $dimensions = $width . 'x' . $height;
        $dimX = $width - strlen($dimensions) * 8 - 10;
        $dimY = $height - 20;
        imagestring($image, 2, $dimX, $dimY, $dimensions, $textColor);

        // Sortie
        ob_start();
        imagejpeg($image, null, 85);
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);

        return response($imageData, 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Convertit une couleur hex en RGB
     */
    private function hexToRgb($hex)
    {
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }
}
