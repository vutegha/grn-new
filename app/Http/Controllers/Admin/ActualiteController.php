<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\ActualiteFeaturedCreated;
use Illuminate\Support\Facades\Storage;
use App\Services\DatabaseErrorHandler;
use App\Helpers\ErrorContextHelper;

class ActualiteController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Actualite::class);

        $query = Actualite::with(['user']);

        // Appliquer la recherche globale
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('titre', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('resume', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('texte', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('user', function($q) use ($searchTerm) {
                      $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Appliquer le filtre par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'published':
                    $query->where('is_published', true);
                    break;
                case 'pending':
                    $query->where('is_published', false);
                    break;
                case 'featured':
                    $query->where('a_la_une', true);
                    break;
                case 'urgent':
                    $query->where('en_vedette', true);
                    break;
            }
        }

        $actualites = $query->latest()->paginate(10)->withQueryString();

        // Calculer les statistiques sur TOUTE la base de données (pas seulement la pagination)
        $stats = [
            'total' => Actualite::count(),
            'published' => Actualite::where('is_published', true)->count(),
            'pending' => Actualite::where('is_published', false)->count(),
            'this_week' => Actualite::where('created_at', '>=', now()->startOfWeek())->count(),
            'featured' => Actualite::where('a_la_une', true)->count(),
        ];

        return view('admin.actualite.index', compact('actualites', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Actualite::class);

        $categories = Categorie::orderBy('nom')->get();
        $rapports = \App\Models\Rapport::where('is_published', true)->orderBy('titre')->get();
        
        return view('admin.actualite.create', compact('categories', 'rapports'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Actualite::class);

        try {
            $validated = $request->validate([
                'titre' => 'required|string|min:5|max:255',
                'resume' => 'nullable|string|min:10',
                'texte' => 'required|string|min:20',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'categorie_id' => 'required|exists:categories,id',
                'service_id' => 'nullable|exists:services,id',
            ], [
                'titre.required' => 'Le titre est obligatoire.',
                'titre.min' => 'Le titre doit contenir au moins 5 caractères.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'resume.min' => 'Si vous remplissez le résumé, il doit contenir au moins 10 caractères.',
                'texte.required' => 'Le contenu de l\'actualité est obligatoire.',
                'texte.min' => 'Le contenu doit être plus détaillé (au moins 20 caractères).',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format JPG, JPEG, PNG ou WebP.',
                'image.max' => 'L\'image ne peut pas dépasser 5 MB.',
                'categorie_id.required' => 'Veuillez sélectionner une catégorie.',
                'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
                'service_id.exists' => 'Le service sélectionné n\'existe pas.',
            ]);

            // Gestion des checkboxes (Boolean)  
            $validated['a_la_une'] = $request->boolean('a_la_une');
            $validated['en_vedette'] = $request->boolean('en_vedette');
            $validated['is_published'] = $request->boolean('is_published');

            // Traitement de l'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Validation supplémentaire
                if (!$image->isValid()) {
                    throw new \Exception('Le fichier image est corrompu ou invalide.');
                }
                
                $filename = uniqid('img_') . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('images', $filename, 'public');
                $validated['image'] = $path;
            }

            // Associer l'utilisateur connecté
            $validated['user_id'] = auth()->id();

            $actualite = Actualite::create($validated);

            // Gérer l'association des rapports
            if ($request->has('rapports') && is_array($request->rapports)) {
                $rapportIds = array_filter($request->rapports); // Supprimer les valeurs vides
                if (!empty($rapportIds)) {
                    $actualite->rapports()->sync($rapportIds);
                }
            }

            // NOTE: L'événement ActualiteFeaturedCreated sera déclenché uniquement 
            // lors de l'action de modération "publier" pour respecter le workflow

            return redirect()->route('admin.actualite.index')
                ->with('success', 'Actualité créée avec succès.')
                ->with('alert', '<span class="alert alert-success"><strong>Succès !</strong> Votre actualité a été enregistrée et sera visible après modération.</span>');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.')
                ->with('alert', '<span class="alert alert-warning"><strong>Attention !</strong> Veuillez corriger les erreurs signalées dans le formulaire.</span>');
                
        } catch (\Illuminate\Database\QueryException $e) {
            $errorInfo = DatabaseErrorHandler::handleQueryException($e, 'création d\'actualité');
            $contextInfo = ErrorContextHelper::enhanceErrorMessage($errorInfo['message']);
            $errorId = ErrorContextHelper::generateErrorId();
            
            // Tracker la fréquence de cette erreur
            ErrorContextHelper::trackErrorFrequency('database_query', auth()->id());
            
            return back()
                ->withInput()
                ->with('error', $errorInfo['message'])
                ->with('alert', $errorInfo['alert'])
                ->with('error_suggestions', $contextInfo['suggestions'])
                ->with('error_troubleshooting', $contextInfo['troubleshooting'])
                ->with('error_next_steps', $contextInfo['next_steps'])
                ->with('error_id', $errorId);
                
        } catch (\Exception $e) {
            $errorInfo = DatabaseErrorHandler::handleGeneralException($e, 'création d\'actualité');
            $contextInfo = ErrorContextHelper::enhanceErrorMessage($errorInfo['message']);
            $errorId = ErrorContextHelper::generateErrorId();
            
            // Tracker la fréquence de cette erreur
            ErrorContextHelper::trackErrorFrequency('general_exception', auth()->id());
            
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

    public function show(Actualite $actualite)
    {
        $this->authorize('view', $actualite);

        // S'assurer que l'actualité a un slug seulement si elle a un titre
        if (empty($actualite->slug) && !empty($actualite->titre)) {
            $actualite->slug = now()->format('Ymd') . '-' . \Illuminate\Support\Str::slug($actualite->titre);
            $actualite->save();
        }
        
        return view('admin.actualite.show', compact('actualite'));
    }

    public function edit(Actualite $actualite)
    {
        $this->authorize('update', $actualite);
        
        $categories = Categorie::orderBy('nom')->get();
        $rapports = Rapport::orderBy('titre')->get();
        return view('admin.actualite.edit', compact('actualite', 'categories', 'rapports'));
    }

    public function update(Request $request, Actualite $actualite)
    {
        $this->authorize('update', $actualite);
        
        try {
            $validated = $request->validate([
                'titre' => 'required|string|min:5|max:255',
                'resume' => 'nullable|string|min:10',
                'texte' => 'required|string|min:20',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'categorie_id' => 'required|exists:categories,id',
                'service_id' => 'nullable|exists:services,id',
            ], [
                'titre.required' => 'Le titre est obligatoire.',
                'titre.min' => 'Le titre doit contenir au moins 5 caractères.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'resume.min' => 'Si vous remplissez le résumé, il doit contenir au moins 10 caractères.',
                'texte.required' => 'Le contenu de l\'actualité est obligatoire.',
                'texte.min' => 'Le contenu doit être plus détaillé (au moins 20 caractères).',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format JPG, JPEG, PNG ou WebP.',
                'image.max' => 'L\'image ne peut pas dépasser 5 MB.',
                'categorie_id.required' => 'Veuillez sélectionner une catégorie.',
                'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
                'service_id.exists' => 'Le service sélectionné n\'existe pas.',
            ]);
            
            // Préparer les données pour la mise à jour
            $updateData = $validated;
            
            // Gestion des checkboxes (Boolean) - Identique à create
            $updateData['a_la_une'] = $request->boolean('a_la_une');
            $updateData['en_vedette'] = $request->boolean('en_vedette');
            $updateData['is_published'] = $request->boolean('is_published');

            // Traitement de l'image
            if ($request->hasFile('image')) {
                // Validation supplémentaire
                $image = $request->file('image');
                if (!$image->isValid()) {
                    throw new \Exception('Le fichier image est corrompu ou invalide.');
                }

                // Supprimer l'ancienne image
                if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                    Storage::disk('public')->delete($actualite->image);
                }

                $filename = uniqid('img_') . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('images', $filename, 'public');
                $updateData['image'] = $path;
            }

            $actualite->update($updateData);

            // Gérer l'association des rapports
            if ($request->has('rapports') && is_array($request->rapports)) {
                $rapportIds = array_filter($request->rapports); // Supprimer les valeurs vides
                $actualite->rapports()->sync($rapportIds);
            } else {
                // Si pas de rapports sélectionnés, désassocier tous
                $actualite->rapports()->detach();
            }

            return redirect()->route('admin.actualite.index')
                ->with('success', 'Actualité mise à jour avec succès.')
                ->with('alert', '<span class="alert alert-success"><strong>Succès !</strong> Les modifications ont été enregistrées.</span>');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.')
                ->with('alert', '<span class="alert alert-warning"><strong>Attention !</strong> Veuillez corriger les erreurs signalées dans le formulaire.</span>');
                
        } catch (\Illuminate\Database\QueryException $e) {
            $errorInfo = DatabaseErrorHandler::handleQueryException($e, 'mise à jour d\'actualité');
            $contextInfo = ErrorContextHelper::enhanceErrorMessage($errorInfo['message']);
            $errorId = ErrorContextHelper::generateErrorId();
            
            // Tracker la fréquence de cette erreur
            ErrorContextHelper::trackErrorFrequency('database_query_update', auth()->id());
            
            return back()
                ->withInput()
                ->with('error', $errorInfo['message'])
                ->with('alert', $errorInfo['alert'])
                ->with('error_suggestions', $contextInfo['suggestions'])
                ->with('error_troubleshooting', $contextInfo['troubleshooting'])
                ->with('error_next_steps', $contextInfo['next_steps'])
                ->with('error_id', $errorId);
                
        } catch (\Exception $e) {
            $errorInfo = DatabaseErrorHandler::handleGeneralException($e, 'mise à jour d\'actualité');
            $contextInfo = ErrorContextHelper::enhanceErrorMessage($errorInfo['message']);
            $errorId = ErrorContextHelper::generateErrorId();
            
            // Tracker la fréquence de cette erreur
            ErrorContextHelper::trackErrorFrequency('general_exception_update', auth()->id());
            
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

    public function destroy(Actualite $actualite)
    {
        $this->authorize('delete', $actualite);

        try {
            // Supprimer l'image
            if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                Storage::disk('public')->delete($actualite->image);
            }

            $actualite->delete();

            // Réponse en JSON pour les appels AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Actualité supprimée avec succès.'
                ]);
            }

            return redirect()->route('admin.actualite.index')
                ->with('alert', '<span class="alert alert-success">Actualité supprimée avec succès.</span>');
        } catch (\Exception $e) {
            // Réponse en JSON pour les appels AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function publish(Request $request, Actualite $actualite)
    {
        $this->authorize('moderate', $actualite);

        try {
            $actualite->update([
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
                'moderation_comment' => $request->input('comment')
            ]);
            
            // Déclencher l'événement newsletter uniquement lors de la publication officielle
            // si l'actualité est en vedette ET à la une
            if ($actualite->en_vedette && $actualite->a_la_une) {
                try {
                    ActualiteFeaturedCreated::dispatch($actualite);
                    \Log::info('Événement ActualiteFeaturedCreated déclenché lors de la publication', [
                        'actualite_id' => $actualite->id,
                        'titre' => $actualite->titre
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Erreur lors du déclenchement de l\'événement ActualiteFeaturedCreated', [
                        'actualite_id' => $actualite->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Actualité publiée avec succès'
                ]);
            }
            
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('success', 'Actualité publiée avec succès');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la publication : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('error', 'Erreur lors de la publication : ' . $e->getMessage());
        }
    }

    public function unpublish(Request $request, Actualite $actualite)
    {
        $this->authorize('moderate', $actualite);

        try {
            $actualite->update([
                'is_published' => false,
                'published_at' => null,
                'published_by' => null,
                'moderation_comment' => $request->input('comment')
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Actualité dépubliée avec succès'
                ]);
            }
            
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('success', 'Actualité dépubliée avec succès');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('error', 'Erreur lors de la dépublication : ' . $e->getMessage());
        }
    }

    public function moderate(Request $request, Actualite $actualite)
    {
        $this->authorize('moderate', $actualite);

        try {
            $validated = $request->validate([
                'action' => 'required|in:approve,reject',
                'moderation_comment' => 'nullable|string|max:1000',
            ]);

            $actualite->moderation_comment = $validated['moderation_comment'] ?? null;

            if ($validated['action'] === 'approve') {
                if ($actualite->moderation_status !== 'published') {
                    $actualite->publish(auth()->user(), $validated['moderation_comment']);
                }
            } else {
                if ($actualite->moderation_status === 'published') {
                    $actualite->unpublish($validated['moderation_comment']);
                } else {
                    $actualite->moderation_status = 'rejected';
                    $actualite->save();
                }
            }

            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('success', 'Modération mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('error', 'Erreur lors de la modération : ' . $e->getMessage());
        }
    }

    /**
     * Upload d'images pour CKEditor
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'upload' => [
                    'required',
                    'image',
                    'mimes:jpeg,png,jpg,gif,webp',
                    'max:2048', // 2MB max
                    'dimensions:max_width=2000,max_height=2000' // Limite résolution
                ]
            ]);

            $file = $request->file('upload');
            
            // Vérification supplémentaire du type MIME pour la sécurité
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return response()->json([
                    'error' => [
                        'message' => 'Type de fichier non autorisé'
                    ]
                ], 422);
            }
            
            // Génération d'un nom de fichier sécurisé
            $extension = $file->getClientOriginalExtension();
            $filename = 'actualite_' . time() . '_' . uniqid() . '.' . $extension;
            
            // Stockage du fichier
            $path = $file->storeAs('actualites/images', $filename, 'public');
            
            // URL publique
            $url = asset('storage/' . $path);
            
            return response()->json([
                'url' => $url,
                'filename' => $filename
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Erreur de validation: ' . implode(', ', $e->validator->errors()->all())
                ]
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur upload image actualité: ' . $e->getMessage());
            
            return response()->json([
                'error' => [
                    'message' => 'Erreur lors de l\'upload du fichier'
                ]
            ], 500);
        }
    }

    /**
     * Attacher des rapports à une actualité
     */
    public function attachRapports(Request $request, Actualite $actualite)
    {
        $this->authorize('update', $actualite);

        $request->validate([
            'rapports' => 'required|array',
            'rapports.*' => 'exists:rapports,id'
        ]);

        try {
            // Attacher les rapports sélectionnés
            $actualite->rapports()->attach($request->rapports);

            return redirect()
                ->route('admin.actualite.show', $actualite)
                ->with('success', 'Rapport(s) lié(s) avec succès à cette actualité.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'attachement des rapports: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la liaison des rapports.');
        }
    }

    /**
     * Détacher un rapport d'une actualité
     */
    public function detachRapport(Actualite $actualite, $rapportId)
    {
        $this->authorize('update', $actualite);

        try {
            $actualite->rapports()->detach($rapportId);

            return redirect()
                ->route('admin.actualite.show', $actualite)
                ->with('success', 'Rapport détaché avec succès de cette actualité.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors du détachement du rapport: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors du détachement du rapport.');
        }
    }
}
