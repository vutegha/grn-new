<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\Categorie;
use App\Events\RapportCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\DatabaseErrorHandler;
use App\Helpers\ErrorContextHelper;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        
        $this->authorize('viewAny', Rapport::class);
$categories = Categorie::all();
        $annees = Rapport::selectRaw('YEAR(date_publication) as annee')->distinct()->orderByDesc('annee')->pluck('annee')->toArray();

        $rapports = Rapport::query()
                            // Exclure les rapports liés à une actualité
                            ->whereDoesntHave('actualites')
                            ->when($request->categorie, fn($q) => $q->where('categorie_id', $request->categorie))
                            ->when($request->annee, fn($q) => $q->whereYear('date_publication', $request->annee))
                            ->latest()
                            ->paginate(10);

        return view('admin.rapports.index', compact('rapports', 'categories', 'annees', 'request'));
    }

    public function create()
    {
        
        $this->authorize('create', Rapport::class);
$categories = Categorie::all();
        return view('admin.rapports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Rapport::class);

        try {
            $validated = $request->validate([
                'titre' => 'required|string|min:5|max:255',
                'description' => 'nullable|string|min:10',
                'date_publication' => 'nullable|date',
                'categorie_id' => 'nullable|exists:categories,id',
                'fichier' => 'required|file|mimes:pdf,doc,docx|max:51200', // max 50MB
                'is_published' => 'nullable|boolean',
            ], [
                'titre.required' => 'Le titre est obligatoire.',
                'titre.min' => 'Le titre doit contenir au moins 5 caractères.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'description.min' => 'Si vous remplissez la description, elle doit contenir au moins 10 caractères.',
                'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
                'fichier.required' => 'Un fichier de rapport est obligatoire.',
                'fichier.mimes' => 'Le fichier doit être au format PDF, DOC ou DOCX.',
                'fichier.max' => 'Le fichier ne peut pas dépasser 50 MB.',
                'date_publication.date' => 'Format de date invalide.',
            ]);

            // Traitement du checkbox is_published
            $validated['is_published'] = $request->boolean('is_published');
            
            // Si publié par un modérateur, définir les timestamps
            if ($validated['is_published'] && auth()->user()->canModerate()) {
                $validated['published_at'] = now();
                $validated['published_by'] = auth()->id();
            }

            // Enregistrement du fichier avec validation supplémentaire
            if ($request->hasFile('fichier')) {
                $file = $request->file('fichier');
                
                // Validation supplémentaire
                if (!$file->isValid()) {
                    throw new \Exception('Le fichier est corrompu ou invalide.');
                }
                
                $filename = uniqid('rapport_') . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $extension = $file->getClientOriginalExtension();
                $path = $file->storeAs('rapports', "$filename.$extension", 'public');
                $validated['fichier'] = $path;
            }

            // Associer l'utilisateur connecté
            $validated['user_id'] = auth()->id();

            $rapport = Rapport::create($validated);

            return redirect()->route('admin.rapports.index')
                ->with('success', 'Rapport créé avec succès.')
                ->with('alert', '<span class="alert alert-success"><strong>Succès !</strong> Le rapport a été enregistré et sera visible après modération.</span>');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.')
                ->with('alert', '<span class="alert alert-warning"><strong>Attention !</strong> Veuillez corriger les erreurs signalées dans le formulaire.</span>');
                
        } catch (\Illuminate\Database\QueryException $e) {
            $errorInfo = DatabaseErrorHandler::handleQueryException($e, 'création de rapport');
            $contextInfo = ErrorContextHelper::enhanceErrorMessage($errorInfo['message']);
            $errorId = ErrorContextHelper::generateErrorId();
            
            // Tracker la fréquence de cette erreur
            ErrorContextHelper::trackErrorFrequency('database_query_rapport', auth()->id());
            
            return back()
                ->withInput()
                ->with('error', $errorInfo['message'])
                ->with('alert', $errorInfo['alert'])
                ->with('error_suggestions', $contextInfo['suggestions'])
                ->with('error_troubleshooting', $contextInfo['troubleshooting'])
                ->with('error_next_steps', $contextInfo['next_steps'])
                ->with('error_id', $errorId);
                
        } catch (\Exception $e) {
            $errorInfo = DatabaseErrorHandler::handleGeneralException($e, 'création de rapport');
            $contextInfo = ErrorContextHelper::enhanceErrorMessage($errorInfo['message']);
            $errorId = ErrorContextHelper::generateErrorId();
            
            // Tracker la fréquence de cette erreur
            ErrorContextHelper::trackErrorFrequency('general_exception_rapport', auth()->id());
            
            return back()
                ->withInput()
                ->with('error', $errorInfo['message'])
                ->with('alert', $errorInfo['alert'])
                ->with('error_suggestions', $contextInfo['suggestions'])
                ->with('error_troubleshooting', $contextInfo['troubleshooting'])
                ->with('error_next_steps', $contextInfo['next_steps'])
                ->with('error_id', $errorId);
        }
    }

    /**
     * Créer plusieurs rapports à partir de fichiers PDF uploadés
     * Le titre sera le nom du fichier (sans extension)
     */
    public function storeMultiple(Request $request)
    {
        $this->authorize('create', Rapport::class);

        try {
            $validated = $request->validate([
                'rapports_pdf' => 'required|array|min:1',
                'rapports_pdf.*' => 'required|file|mimes:pdf|max:51200', // 50 Mo max par fichier
                'actualite_id' => 'nullable|exists:actualites,id',
                'projet_id' => 'nullable|exists:projets,id',
            ], [
                'rapports_pdf.required' => 'Vous devez sélectionner au moins un fichier PDF.',
                'rapports_pdf.*.mimes' => 'Seuls les fichiers PDF sont autorisés.',
                'rapports_pdf.*.max' => 'Chaque fichier ne peut pas dépasser 50 Mo.',
            ]);

            $rapportsCreés = [];
            $erreurs = [];

            foreach ($request->file('rapports_pdf') as $index => $file) {
                try {
                    // Validation du fichier
                    if (!$file->isValid()) {
                        $erreurs[] = "Le fichier {$file->getClientOriginalName()} est corrompu.";
                        continue;
                    }

                    // Extraire le nom du fichier sans extension pour le titre
                    $nomOriginal = $file->getClientOriginalName();
                    $titre = pathinfo($nomOriginal, PATHINFO_FILENAME);
                    $titre = str_replace(['_', '-'], ' ', $titre); // Remplacer _ et - par des espaces
                    $titre = ucfirst(trim($titre)); // Première lettre en majuscule

                    // Sauvegarder le fichier
                    $filename = uniqid('rapport_') . '_' . Str::slug($titre);
                    $extension = $file->getClientOriginalExtension();
                    $path = $file->storeAs('rapports', "$filename.$extension", 'public');

                    // Créer le rapport
                    $rapport = Rapport::create([
                        'titre' => $titre,
                        'fichier' => $path,
                        'user_id' => auth()->id(),
                        'date_publication' => now(),
                        'is_published' => auth()->user()->canModerate() ? true : false,
                        'published_at' => auth()->user()->canModerate() ? now() : null,
                        'published_by' => auth()->user()->canModerate() ? auth()->id() : null,
                    ]);

                    // Si actualite_id est fourni, lier le rapport à l'actualité
                    if ($request->actualite_id) {
                        $rapport->actualites()->attach($request->actualite_id);
                    }

                    // Si projet_id est fourni, lier le rapport au projet
                    if ($request->projet_id) {
                        $rapport->projets()->attach($request->projet_id);
                    }

                    $rapportsCreés[] = $rapport;

                } catch (\Exception $e) {
                    $erreurs[] = "Erreur lors du traitement de {$file->getClientOriginalName()}: " . $e->getMessage();
                }
            }

            // Préparer le message de succès
            $message = count($rapportsCreés) . ' rapport(s) créé(s) avec succès';
            if (count($erreurs) > 0) {
                $message .= ', mais ' . count($erreurs) . ' erreur(s) rencontrée(s).';
            }

            // Rediriger vers le projet si fourni
            if ($request->projet_id) {
                $projet = \App\Models\Projet::findOrFail($request->projet_id);
                return redirect()->route('admin.projets.show', $projet)
                    ->with('success', $message)
                    ->with('rapports_erreurs', $erreurs);
            }

            // Rediriger vers l'actualité si fournie, sinon vers la liste des rapports
            if ($request->actualite_id) {
                // Charger l'actualité pour la redirection
                $actualite = \App\Models\Actualite::findOrFail($request->actualite_id);
                return redirect()->route('admin.actualite.show', $actualite)
                    ->with('success', $message)
                    ->with('rapports_erreurs', $erreurs);
            }

            return redirect()->route('admin.rapports.index')
                ->with('success', $message)
                ->with('rapports_erreurs', $erreurs);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création des rapports: ' . $e->getMessage());
        }
    }

    public function edit(Rapport $rapport)
    {
        
        $this->authorize('update', $rapport);
$categories = Categorie::all();
        return view('admin.rapports.edit', compact('rapport', 'categories'));
    }

    public function update(Request $request, Rapport $rapport)
    {
        $this->authorize('update', $rapport);

        try {
            $validated = $request->validate([
                'titre' => 'required|string|min:5|max:255',
                'description' => 'nullable|string|min:10',
                'date_publication' => 'nullable|date',
                'categorie_id' => 'nullable|exists:categories,id',
                'fichier' => 'nullable|file|mimes:pdf,doc,docx|max:51200', // max 50MB
                'is_published' => 'nullable|boolean',
            ], [
                'titre.required' => 'Le titre est obligatoire.',
                'titre.min' => 'Le titre doit contenir au moins 5 caractères.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'description.min' => 'Si vous remplissez la description, elle doit contenir au moins 10 caractères.',
                'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
                'fichier.mimes' => 'Le fichier doit être au format PDF, DOC ou DOCX.',
                'fichier.max' => 'Le fichier ne peut pas dépasser 50 MB.',
                'date_publication.date' => 'Format de date invalide.',
            ]);

            // Sauvegarder l'ancien fichier pour le rollback en cas d'erreur
            $oldFile = $rapport->fichier;
            
            // Traitement du checkbox is_published
            $validated['is_published'] = $request->boolean('is_published');
            
            // Vérifier les permissions de publication
            if ($validated['is_published'] && !auth()->user()->canModerate() && !$rapport->is_published) {
                throw new \Exception('Vous n\'avez pas les permissions nécessaires pour publier ce rapport.');
            }
            
            // Si publié par un modérateur pour la première fois
            if ($validated['is_published'] && auth()->user()->canModerate() && !$rapport->published_at) {
                $validated['published_at'] = now();
                $validated['published_by'] = auth()->id();
            }

            // Génération du slug unique si le titre a changé
            if ($request->filled('titre') && $request->titre !== $rapport->titre) {
                $baseSlug = Str::slug($validated['titre']);
                $slug = $baseSlug;
                $counter = 1;
                
                while (Rapport::where('slug', $slug)->where('id', '!=', $rapport->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $validated['slug'] = $slug;
            }

            // Traitement du fichier avec gestion d'erreurs améliorée
            if ($request->hasFile('fichier')) {
                $file = $request->file('fichier');
                
                // Validation supplémentaire
                if (!$file->isValid()) {
                    throw new \Exception('Le fichier téléchargé est corrompu ou invalide.');
                }
                
                // Vérifier l'espace disque disponible
                $fileSize = $file->getSize();
                $availableSpace = disk_free_space(storage_path('app/public'));
                
                if ($fileSize > $availableSpace) {
                    throw new \Exception('Espace disque insuffisant pour enregistrer le fichier.');
                }
                
                try {
                    // Supprimer l'ancien fichier uniquement après vérification
                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }

                    $filename = uniqid('rapport_') . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    $extension = $file->getClientOriginalExtension();
                    $path = $file->storeAs('rapports', "$filename.$extension", 'public');
                    
                    // Vérifier que le fichier a bien été enregistré
                    if (!Storage::disk('public')->exists($path)) {
                        throw new \Exception('Échec de l\'enregistrement du fichier sur le serveur.');
                    }
                    
                    $validated['fichier'] = $path;
                    
                } catch (\Exception $e) {
                    // En cas d'erreur, restaurer l'ancien fichier s'il a été supprimé
                    throw new \Exception('Erreur lors de la mise à jour du fichier : ' . $e->getMessage());
                }
            }

            $rapport->update($validated);

            return redirect()->route('admin.rapports.index')
                ->with('success', 'Rapport mis à jour avec succès.')
                ->with('alert', '<span class="alert alert-success"><strong>Succès !</strong> Le rapport a été mis à jour.</span>');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.')
                ->with('alert', '<span class="alert alert-warning"><strong>Attention !</strong> Veuillez corriger les erreurs signalées dans le formulaire.</span>');
                
        } catch (\Illuminate\Database\QueryException $e) {
            $errorInfo = DatabaseErrorHandler::handleQueryException($e, 'mise à jour de rapport');
            $contextInfo = ErrorContextHelper::enhanceErrorMessage($errorInfo['message']);
            $errorId = ErrorContextHelper::generateErrorId();
            
            // Tracker la fréquence de cette erreur
            ErrorContextHelper::trackErrorFrequency('database_query_rapport_update', auth()->id());
            
            return back()
                ->withInput()
                ->with('error', $errorInfo['message'])
                ->with('alert', $errorInfo['alert'])
                ->with('error_suggestions', $contextInfo['suggestions'])
                ->with('error_troubleshooting', $contextInfo['troubleshooting'])
                ->with('error_next_steps', $contextInfo['next_steps'])
                ->with('error_id', $errorId);
                
        } catch (\Exception $e) {
            $errorInfo = DatabaseErrorHandler::handleGeneralException($e, 'mise à jour de rapport');
            $contextInfo = ErrorContextHelper::enhanceErrorMessage($errorInfo['message']);
            $errorId = ErrorContextHelper::generateErrorId();
            
            // Tracker la fréquence de cette erreur
            ErrorContextHelper::trackErrorFrequency('general_exception_rapport_update', auth()->id());
            
            return back()
                ->withInput()
                ->with('error', $errorInfo['message'])
                ->with('alert', $errorInfo['alert'])
                ->with('error_suggestions', $contextInfo['suggestions'])
                ->with('error_troubleshooting', $contextInfo['troubleshooting'])
                ->with('error_next_steps', $contextInfo['next_steps'])
                ->with('error_id', $errorId);
        }
    }

    public function show(Rapport $rapport)
    {
        
        $this->authorize('view', $rapport);
return view('admin.rapports.show', compact('rapport'));
    }

    public function destroy(Rapport $rapport)
    {
        
        $this->authorize('delete', $rapport);
if ($rapport->fichier && Storage::disk('public')->exists($rapport->fichier)) {
            Storage::disk('public')->delete($rapport->fichier);
        }

        $rapport->delete();

        return redirect()->route('admin.rapports.index')
            ->with('alert', '<span class="alert alert-success">Rapport supprimé avec succès.</span>');
    }

    /**
     * Publier un rapport
     */
    public function publish(Request $request, Rapport $rapport)
    {
        try {
            $rapport->publish(auth()->user(), $request->input('comment'));
            
            // Déclencher l'événement newsletter lors de la publication officielle
            try {
                RapportCreated::dispatch($rapport);
                \Log::info('Événement RapportCreated déclenché lors de la publication', [
                    'rapport_id' => $rapport->id,
                    'titre' => $rapport->titre
                ]);
            } catch (\Exception $e) {
                \Log::warning('Erreur lors du déclenchement de l\'événement RapportCreated', [
                    'rapport_id' => $rapport->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Rapport publié avec succès',
                'status' => $rapport->publication_status,
                'published_at' => $rapport->published_at ? $rapport->published_at->format('d/m/Y H:i') : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dépublier un rapport
     */
    public function unpublish(Request $request, Rapport $rapport)
    {
        try {
            $rapport->unpublish($request->input('comment'));
            
            return response()->json([
                'success' => true,
                'message' => 'Rapport dépublié avec succès',
                'status' => $rapport->publication_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Voir les éléments en attente de modération
     */
    public function pendingModeration()
    {
        $rapports = Rapport::pendingModeration()
                          ->with(['categorie'])
                          ->latest()
                          ->paginate(10);

        return view('admin.rapports.pending', compact('rapports'));
    }

    /**
     * Supprimer plusieurs rapports en une fois
     */
    public function deleteMultiple(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:rapports,id'
            ]);

            $deletedCount = 0;
            foreach ($validated['ids'] as $id) {
                $rapport = Rapport::find($id);
                if ($rapport) {
                    // Supprimer le fichier s'il existe
                    if ($rapport->fichier && file_exists(public_path($rapport->fichier))) {
                        unlink(public_path($rapport->fichier));
                    }
                    $rapport->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} rapport(s) supprimé(s) avec succès"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter plusieurs rapports en Excel
     */
    public function export(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids', ''));
            $rapports = Rapport::whereIn('id', $ids)->with('categorie')->get();

            // Créer les données pour l'export
            $exportData = [];
            foreach ($rapports as $rapport) {
                $exportData[] = [
                    'ID' => $rapport->id,
                    'Titre' => $rapport->titre,
                    'Description' => $rapport->description,
                    'Catégorie' => $rapport->categorie->nom ?? 'N/A',
                    'Date de publication' => $rapport->date_publication ? $rapport->date_publication->format('d/m/Y') : 'N/A',
                    'Fichier' => $rapport->fichier ? basename($rapport->fichier) : 'Aucun',
                    'Créé le' => $rapport->created_at->format('d/m/Y H:i'),
                    'Modifié le' => $rapport->updated_at->format('d/m/Y H:i'),
                ];
            }

            // Utiliser une simple réponse CSV pour l'instant
            $filename = 'rapports_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            $output = fopen('php://output', 'w');
            
            // Headers pour le téléchargement
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            // En-têtes CSV
            if (!empty($exportData)) {
                fputcsv($output, array_keys($exportData[0]));
                foreach ($exportData as $row) {
                    fputcsv($output, $row);
                }
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger plusieurs rapports dans un fichier ZIP
     */
    public function downloadZip(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids', ''));
            $rapports = Rapport::whereIn('id', $ids)->get();

            $zipFilename = 'rapports_' . date('Y-m-d_H-i-s') . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFilename);
            
            // Créer le dossier temp s'il n'existe pas
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Impossible de créer le fichier ZIP');
            }

            foreach ($rapports as $rapport) {
                if ($rapport->fichier && file_exists(public_path($rapport->fichier))) {
                    $filename = Str::slug($rapport->titre) . '_' . $rapport->id . '.' . pathinfo($rapport->fichier, PATHINFO_EXTENSION);
                    $zip->addFile(public_path($rapport->fichier), $filename);
                }
            }

            $zip->close();

            // Télécharger et supprimer le fichier temporaire
            return response()->download($zipPath, $zipFilename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du ZIP: ' . $e->getMessage());
        }
    }
}
