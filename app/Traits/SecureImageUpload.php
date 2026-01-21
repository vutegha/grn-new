<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

trait SecureImageUpload
{
    /**
     * Handle secure image upload with validation and processing
     */
    public function uploadSecureImage(UploadedFile $file, string $directory = 'images', array $options = []): string
    {
        $options = array_merge([
            'max_width' => 2000,
            'max_height' => 2000,
            'quality' => 85,
            'prefix' => 'img',
            'create_thumbnails' => false,
            'thumbnail_sizes' => [150, 300, 600]
        ], $options);

        try {
            // Validate file
            $this->validateImageFile($file);
            
            // Generate secure filename
            $filename = $this->generateSecureFilename($file, $options['prefix']);
            
            // Create directory structure with date
            $fullDirectory = $directory . '/' . date('Y/m');
            if (!Storage::disk('public')->exists($fullDirectory)) {
                Storage::disk('public')->makeDirectory($fullDirectory, 0755, true);
            }
            
            $path = $fullDirectory . '/' . $filename;
            
            // Process and store image
            $this->processAndStoreImage($file, $path, $options);
            
            // Create thumbnails if requested
            if ($options['create_thumbnails']) {
                $this->createThumbnails($path, $options['thumbnail_sizes']);
            }
            
            Log::info('Image uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'user_id' => auth()->id() ?? 'system'
            ]);
            
            return $path;
            
        } catch (Exception $e) {
            Log::error('Image upload failed', [
                'error' => $e->getMessage(),
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'user_id' => auth()->id() ?? 'system'
            ]);
            
            throw new Exception('Échec du téléchargement de l\'image: ' . $e->getMessage());
        }
    }

    /**
     * Validate uploaded image file
     */
    private function validateImageFile(UploadedFile $file): void
    {
        // Check if file is valid
        if (!$file->isValid()) {
            throw new Exception('Fichier invalide ou corrompu.');
        }

        // Validate MIME type
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new Exception('Type de fichier non autorisé. Seuls les formats JPEG, PNG, GIF et WebP sont acceptés.');
        }

        // Validate file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception('Extension de fichier non autorisée.');
        }

        // Check file size (2MB max)
        if ($file->getSize() > 2 * 1024 * 1024) {
            throw new Exception('La taille du fichier ne peut pas dépasser 2MB.');
        }

        // Validate image dimensions
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new Exception('Impossible de lire les informations de l\'image.');
        }

        [$width, $height] = $imageInfo;
        
        if ($width < 50 || $height < 50) {
            throw new Exception('L\'image doit faire au minimum 50x50 pixels.');
        }

        if ($width > 4000 || $height > 4000) {
            throw new Exception('L\'image ne peut pas dépasser 4000x4000 pixels.');
        }

        // Check for malicious content
        $this->scanForMaliciousContent($file);
    }

    /**
     * Generate secure filename
     */
    private function generateSecureFilename(UploadedFile $file, string $prefix = 'file'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $hash = hash('sha256', $file->getClientOriginalName() . time() . Str::random(10));
        
        return $prefix . '_' . substr($hash, 0, 16) . '_' . time() . '.' . $extension;
    }

    /**
     * Process and store image with optimization
     */
    private function processAndStoreImage(UploadedFile $file, string $path, array $options): void
    {
        // For now, just store the file directly
        // In a production environment, you would use an image library like Intervention Image
        Storage::disk('public')->putFileAs(
            dirname($path),
            $file,
            basename($path),
            'public'
        );

        // Optional: Add image optimization here
        // This would require installing intervention/image or similar package
        /*
        $image = Image::make($file->getPathname());
        
        // Resize if too large
        if ($image->width() > $options['max_width'] || $image->height() > $options['max_height']) {
            $image->resize($options['max_width'], $options['max_height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        // Optimize quality
        $image->save(storage_path('app/public/' . $path), $options['quality']);
        */
    }

    /**
     * Create thumbnails for the uploaded image
     */
    private function createThumbnails(string $originalPath, array $sizes): array
    {
        $thumbnails = [];
        
        // This would require image processing library
        // For now, just return empty array
        
        /*
        foreach ($sizes as $size) {
            $thumbnailPath = $this->generateThumbnailPath($originalPath, $size);
            
            $image = Image::make(storage_path('app/public/' . $originalPath));
            $image->resize($size, $size, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $image->save(storage_path('app/public/' . $thumbnailPath), 85);
            $thumbnails[$size] = $thumbnailPath;
        }
        */
        
        return $thumbnails;
    }

    /**
     * Generate thumbnail path
     */
    private function generateThumbnailPath(string $originalPath, int $size): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/thumb_' . $size . '_' . $pathInfo['basename'];
    }

    /**
     * Scan file for malicious content
     */
    private function scanForMaliciousContent(UploadedFile $file): void
    {
        $fileContents = file_get_contents($file->getPathname());
        
        $maliciousPatterns = [
            '/<\?php/i',
            '/<script/i',
            '/javascript:/i',
            '/eval\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i',
            '/shell_exec/i',
            '/passthru/i',
            '/base64_decode/i',
            '/file_get_contents/i',
            '/file_put_contents/i',
            '/fopen/i',
            '/fwrite/i',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $fileContents)) {
                throw new Exception('Contenu malveillant détecté dans le fichier.');
            }
        }
    }

    /**
     * Delete image and its thumbnails
     */
    public function deleteSecureImage(string $path): bool
    {
        try {
            $deleted = false;
            
            // Delete main image
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                $deleted = true;
            }
            
            // Delete thumbnails
            $pathInfo = pathinfo($path);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['filename'];
            
            $thumbnailPattern = 'thumb_*_' . $filename . '.*';
            $thumbnails = Storage::disk('public')->glob($directory . '/' . $thumbnailPattern);
            
            foreach ($thumbnails as $thumbnail) {
                Storage::disk('public')->delete($thumbnail);
            }
            
            if ($deleted) {
                Log::info('Image deleted successfully', [
                    'path' => $path,
                    'thumbnails_count' => count($thumbnails),
                    'user_id' => auth()->id() ?? 'system'
                ]);
            }
            
            return $deleted;
            
        } catch (Exception $e) {
            Log::error('Image deletion failed', [
                'path' => $path,
                'error' => $e->getMessage(),
                'user_id' => auth()->id() ?? 'system'
            ]);
            
            return false;
        }
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrl(?string $path, string $fallback = '/images/default-avatar.png'): string
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return asset($fallback);
        }
        
        return Storage::disk('public')->url($path);
    }
}
