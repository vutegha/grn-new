<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GenerateSocialImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $modelType;
    protected $modelId;

    /**
     * Create a new job instance.
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->modelType = class_basename($model);
        $this->modelId = $model->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Vérifier que le modèle existe toujours
        $model = $this->model->fresh();
        if (!$model) {
            return;
        }

        // Configuration
        $width = config('share.social_image.width', 1200);
        $height = config('share.social_image.height', 630);
        $quality = config('share.social_image.quality', 85);
        $format = config('share.social_image.format', 'jpg');

        // Créer le manager d'image
        $manager = new ImageManager(new Driver());

        // Image source
        $sourceImage = null;
        if ($model->image && Storage::disk('public')->exists($model->image)) {
            $sourceImage = Storage::disk('public')->path($model->image);
        } else {
            // Utiliser l'image par défaut
            $defaultImagePath = config('share.default_image');
            if ($defaultImagePath && Storage::disk('public')->exists($defaultImagePath)) {
                $sourceImage = Storage::disk('public')->path($defaultImagePath);
            } else {
                // Créer une image de fallback simple
                $sourceImage = $this->createFallbackImage($manager, $width, $height);
            }
        }

        if (!$sourceImage) {
            return;
        }

        try {
            // Traiter l'image
            if (is_string($sourceImage)) {
                $image = $manager->read($sourceImage);
            } else {
                $image = $sourceImage; // Image créée par fallback
            }

            // Redimensionner et cropper au centre
            $image = $image->cover($width, $height);

            // Optionnel : Ajouter un bandeau avec le titre
            $this->addTextOverlay($image, $model, $width, $height);

            // Chemin de destination
            $filename = strtolower($this->modelType) . '/' . $this->modelId . '.' . $format;
            $destinationPath = config('share.storage.path') . '/' . $filename;

            // S'assurer que le dossier existe
            $directory = dirname($destinationPath);
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Sauvegarder l'image
            $imageData = $image->toJpeg($quality);
            Storage::disk('public')->put($destinationPath, $imageData);

            // Mettre à jour le modèle avec le chemin de l'image sociale
            $model->update(['social_image_path' => $destinationPath]);

        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer le job
            \Log::error('Erreur lors de la génération de l\'image sociale', [
                'model' => $this->modelType,
                'id' => $this->modelId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Créer une image de fallback simple
     */
    private function createFallbackImage($manager, $width, $height)
    {
        // Créer une image avec un gradient simple
        $image = $manager->create($width, $height);
        
        // Fond dégradé bleu
        $image = $image->fill([67, 126, 234]); // Bleu GRN
        
        return $image;
    }

    /**
     * Ajouter un overlay de texte (optionnel)
     */
    private function addTextOverlay($image, $model, $width, $height)
    {
        // Cette fonctionnalité nécessite une police et est optionnelle
        // Pour l'instant, on peut l'ignorer ou implémenter une version basique
        
        // Si vous voulez ajouter le titre en overlay :
        // $image->text($model->titre, $width/2, $height - 100, function($font) {
        //     $font->size(36);
        //     $font->color('#ffffff');
        //     $font->align('center');
        //     $font->valign('middle');
        // });
        
        return $image;
    }
}
