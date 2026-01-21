<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\Newsletter;
use App\Models\RapportDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RapportDownloadController extends Controller
{
    /**
     * Valider l'email et préparer le téléchargement
     */
    public function validateEmail(Request $request, $rapportId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'actualite_id' => 'nullable|exists:actualites,id'
            ], [
                'email.required' => 'Veuillez saisir votre adresse email.',
                'email.email' => 'Veuillez saisir une adresse email valide.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $rapport = Rapport::findOrFail($rapportId);
            
            // Vérifier que le rapport est publié
            if (!$rapport->is_published) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce rapport n\'est pas disponible au téléchargement.'
                ], 403);
            }

            // Vérifier que le fichier existe
            if (!$rapport->fichier || !Storage::disk('public')->exists($rapport->fichier)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le fichier du rapport n\'est pas disponible.'
                ], 404);
            }

            $email = $request->input('email');
            $actualiteId = $request->input('actualite_id');

            // Ajouter l'email à la liste de diffusion s'il n'existe pas
            $newsletter = Newsletter::where('email', $email)->first();
            $isNewSubscriber = false;
            
            if (!$newsletter) {
                $newsletter = Newsletter::create([
                    'email' => $email,
                    'actif' => true,
                    'confirme_a' => now(), // Auto-confirmation pour les téléchargements
                ]);
                $isNewSubscriber = true;
            }

            // Enregistrer le téléchargement
            try {
                RapportDownload::recordDownload($rapportId, $email, $actualiteId);
            } catch (\Exception $e) {
                // Log l'erreur mais ne bloque pas le téléchargement
                \Log::warning('Erreur enregistrement téléchargement: ' . $e->getMessage());
            }

            // Générer l'URL de téléchargement
            $downloadUrl = route('rapport.download', ['id' => $rapportId]);

            return response()->json([
                'success' => true,
                'message' => 'Email validé avec succès.',
                'download_url' => $downloadUrl,
                'newsletter_subscribed' => $isNewSubscriber
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur validation email téléchargement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Télécharger le rapport
     */
    public function download($rapportId)
    {
        $rapport = Rapport::findOrFail($rapportId);
        
        // Vérifier que le rapport est publié
        if (!$rapport->is_published) {
            abort(403, 'Ce rapport n\'est pas disponible au téléchargement.');
        }

        // Vérifier que le fichier existe
        if (!$rapport->fichier || !Storage::disk('public')->exists($rapport->fichier)) {
            abort(404, 'Le fichier du rapport n\'est pas disponible.');
        }

        $filePath = Storage::disk('public')->path($rapport->fichier);
        $fileName = basename($rapport->fichier);
        
        // Nettoyer le nom du fichier pour le téléchargement
        $downloadName = $rapport->titre ? 
            \Illuminate\Support\Str::slug($rapport->titre) . '.' . pathinfo($fileName, PATHINFO_EXTENSION) : 
            $fileName;

        return response()->download($filePath, $downloadName);
    }
}
