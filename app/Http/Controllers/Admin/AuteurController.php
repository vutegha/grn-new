<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuteurRequest;
use App\Models\Auteur;
use App\Traits\SecureImageUpload;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuteurController extends Controller
{
    use SecureImageUpload;
    /**
     * Constructor - Apply middleware and policies
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of authors with advanced search and filtering
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Auteur::class);
        
        try {
            $query = Auteur::query();
            
            // Search functionality
            if ($search = $request->get('search')) {
                $searchTerm = '%' . $search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nom', 'like', $searchTerm)
                      ->orWhere('prenom', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('institution', 'like', $searchTerm);
                });
            }

            // Sort functionality
            $sort = $request->get('sort', 'nom');
            $direction = $request->get('direction', 'asc');
            
            $allowedSorts = ['nom', 'prenom', 'email', 'institution', 'created_at', 'updated_at'];
            if (in_array($sort, $allowedSorts)) {
                $query->orderBy($sort, $direction);
            }

            // Pagination with search persistence
            $auteurs = $query->paginate(15)->withQueryString();

            return view('admin.auteur.index', compact('auteurs'));
            
        } catch (\Exception $e) {
            Log::error('Error in AuteurController@index: ' . $e->getMessage());
            
            // Return empty collection in case of error
            $auteurs = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 15, 1, ['path' => request()->url()]
            );
            
            return view('admin.auteur.index', compact('auteurs'));
        }
    }

    /**
     * Show the form for creating a new author
     */
    public function create(): View
    {
        $this->authorize('create', Auteur::class);
        
        return view('admin.auteur.create');
    }

    /**
     * Store a newly created author with comprehensive validation and security
     */
    public function store(AuteurRequest $request): RedirectResponse
    {
        $this->authorize('create', Auteur::class);
        
        DB::beginTransaction();
        
        try {
            $validated = $request->validatedWithProcessing();
            
            // Handle photo upload with security measures
            if ($request->hasFile('photo')) {
                $validated['photo'] = $this->uploadSecureImage(
                    $request->file('photo'), 
                    'assets/auteurs',
                    ['prefix' => 'auteur']
                );
            }
            
            // Create author with mass assignment protection
            $auteur = Auteur::create($validated);
            
            // Log the creation for audit trail
            Log::info('Author created', [
                'auteur_id' => $auteur->id,
                'nom' => $auteur->nom,
                'created_by' => auth()->id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            DB::commit();
            
            // Success response with different formats
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Auteur créé avec succès.',
                    'auteur' => $auteur->load('publications'),
                    'redirect' => route('admin.auteur.show', $auteur)
                ]);
            }
            
            return redirect()
                ->route('admin.auteur.show', $auteur)
                ->with('success', 'L\'auteur a été créé avec succès.')
                ->with('alert', '<div class="alert alert-success">
                    <strong>Succès!</strong> L\'auteur <strong>' . $auteur->nom . '</strong> a été ajouté à la base de données.
                </div>');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error creating author', [
                'error' => $e->getMessage(),
                'data' => $request->except(['photo']),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la création de l\'auteur.',
                    'errors' => ['general' => [$e->getMessage()]]
                ], 422);
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['general' => 'Une erreur est survenue lors de la création de l\'auteur.'])
                ->with('alert', '<div class="alert alert-danger">
                    <strong>Erreur!</strong> Impossible de créer l\'auteur. Veuillez réessayer.
                </div>');
        }
    }

    /**
     * Display the specified author with their publications
     */
    public function show(Auteur $auteur): View
    {
        $this->authorize('view', $auteur);
        
        try {
            // Load publications with pagination for performance
            $auteur->load(['publications' => function($query) {
                $query->latest()->take(10);
            }]);
            
            return view('admin.auteur.show', compact('auteur'));
            
        } catch (\Exception $e) {
            Log::error('Error showing author', [
                'auteur_id' => $auteur->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()
                ->route('admin.auteur.index')
                ->withErrors(['general' => 'Impossible d\'afficher les détails de cet auteur.']);
        }
    }

    /**
     * Show the form for editing the specified author
     */
    public function edit(Auteur $auteur): View
    {
        $this->authorize('update', $auteur);
        
        return view('admin.auteur.edit', compact('auteur'));
    }

    /**
     * Update the specified author with comprehensive validation
     */
    public function update(AuteurRequest $request, Auteur $auteur): RedirectResponse
    {
        $this->authorize('update', $auteur);
        
        DB::beginTransaction();
        
        try {
            $validated = $request->validatedWithProcessing();
            $oldData = $auteur->toArray();
            
            // Handle photo upload and deletion of old photo
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($auteur->photo) {
                    $this->deleteSecureImage($auteur->photo);
                }
                
                $validated['photo'] = $this->uploadSecureImage(
                    $request->file('photo'), 
                    'assets/auteurs',
                    ['prefix' => 'auteur']
                );
            }
            
            // Update author
            $auteur->update($validated);
            
            // Log the update for audit trail
            Log::info('Author updated', [
                'auteur_id' => $auteur->id,
                'old_data' => $oldData,
                'new_data' => $validated,
                'updated_by' => auth()->id(),
                'ip' => $request->ip()
            ]);
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Auteur modifié avec succès.',
                    'auteur' => $auteur->fresh()->load('publications'),
                    'redirect' => route('admin.auteur.show', $auteur)
                ]);
            }
            
            return redirect()
                ->route('admin.auteur.show', $auteur)
                ->with('success', 'L\'auteur a été modifié avec succès.')
                ->with('alert', '<div class="alert alert-success">
                    <strong>Succès!</strong> Les informations de <strong>' . $auteur->nom . '</strong> ont été mises à jour.
                </div>');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error updating author', [
                'auteur_id' => $auteur->id,
                'error' => $e->getMessage(),
                'data' => $request->except(['photo']),
                'user_id' => auth()->id()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la modification.',
                    'errors' => ['general' => [$e->getMessage()]]
                ], 422);
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['general' => 'Une erreur est survenue lors de la modification.'])
                ->with('alert', '<div class="alert alert-danger">
                    <strong>Erreur!</strong> Impossible de modifier l\'auteur.
                </div>');
        }
    }

    /**
     * Remove the specified author with comprehensive checks
     */
    public function destroy(Auteur $auteur): RedirectResponse
    {
        $this->authorize('delete', $auteur);
        
        DB::beginTransaction();
        
        try {
            // Check if author has publications
            if ($auteur->publications()->count() > 0) {
                return redirect()
                    ->back()
                    ->withErrors(['general' => 'Impossible de supprimer cet auteur car il a des publications associées.'])
                    ->with('alert', '<div class="alert alert-warning">
                        <strong>Attention!</strong> Cet auteur ne peut pas être supprimé car il a des publications associées.
                    </div>');
            }
            
            $auteurData = $auteur->toArray();
            
            // Delete photo if exists
            if ($auteur->photo) {
                $this->deleteSecureImage($auteur->photo);
            }
            
            // Delete author
            $auteur->delete();
            
            // Log deletion for audit trail
            Log::warning('Author deleted', [
                'auteur_data' => $auteurData,
                'deleted_by' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('admin.auteur.index')
                ->with('success', 'L\'auteur a été supprimé avec succès.')
                ->with('alert', '<div class="alert alert-success">
                    <strong>Succès!</strong> L\'auteur <strong>' . $auteurData['nom'] . '</strong> a été supprimé.
                </div>');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error deleting author', [
                'auteur_id' => $auteur->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()
                ->back()
                ->withErrors(['general' => 'Une erreur est survenue lors de la suppression.'])
                ->with('alert', '<div class="alert alert-danger">
                    <strong>Erreur!</strong> Impossible de supprimer l\'auteur.
                </div>');
        }
    }

    /**
     * Export authors data (for admin use)
     */
    public function export(Request $request)
    {
        $this->authorize('export', Auteur::class);
        
        try {
            $auteurs = Auteur::with('publications')->get();
            
            $csvData = [];
            $csvData[] = ['Nom', 'Prénom', 'Email', 'Institution', 'Publications', 'Date création'];
            
            foreach ($auteurs as $auteur) {
                $csvData[] = [
                    $auteur->nom,
                    $auteur->prenom,
                    $auteur->email,
                    $auteur->institution,
                    $auteur->publications->count(),
                    $auteur->created_at->format('d/m/Y')
                ];
            }
            
            $filename = 'auteurs_' . date('Y-m-d_H-i-s') . '.csv';
            
            return response()->streamDownload(function() use ($csvData) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                foreach ($csvData as $row) {
                    fputcsv($file, $row, ';');
                }
                
                fclose($file);
            }, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Export failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()
                ->back()
                ->withErrors(['general' => 'Échec de l\'exportation des données.']);
        }
    }
    
    // ================================================================
    // API METHODS FOR AJAX CALLS (Modal functionality)
    // ================================================================
    
    /**
     * Search authors via AJAX for modal
     */
    /**
     * Search authors via AJAX (alias for searchApi)
     */
    public function search(Request $request)
    {
        return $this->searchApi($request);
    }
    
    public function searchApi(Request $request)
    {
        $this->authorize('viewAny', Auteur::class);
        
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'La recherche nécessite au moins 2 caractères'
                ], 400);
            }
            
            $auteurs = Auteur::where(function($q) use ($query) {
                    $searchTerm = '%' . $query . '%';
                    $q->where('nom', 'like', $searchTerm)
                      ->orWhere('prenom', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('institution', 'like', $searchTerm);
                })
                ->select(['id', 'nom', 'prenom', 'email', 'institution'])
                ->limit(20)
                ->get()
                ->map(function($auteur) {
                    return [
                        'id' => $auteur->id,
                        'nom' => $auteur->nom,
                        'prenom' => $auteur->prenom,
                        'nom_complet' => trim($auteur->prenom . ' ' . $auteur->nom),
                        'email' => $auteur->email,
                        'institution' => $auteur->institution,
                        'display_name' => trim($auteur->prenom . ' ' . $auteur->nom) . 
                                       ($auteur->institution ? ' (' . $auteur->institution . ')' : '')
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $auteurs,
                'count' => $auteurs->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Author search API failed', [
                'error' => $e->getMessage(),
                'query' => $request->get('q'),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche des auteurs'
            ], 500);
        }
    }
    
    /**
     * Create new author via AJAX for modal
     */
    public function storeApi(Request $request)
    {
        $this->authorize('create', Auteur::class);
        
        try {
            // Validation
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255', 
                'email' => 'nullable|email|max:255|unique:auteurs,email',
                'institution' => 'nullable|string|max:255'
            ]);
            
            // Créer l'auteur
            $auteur = Auteur::create($validatedData);
            
            Log::info('Author created via API', [
                'auteur_id' => $auteur->id,
                'created_by' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Auteur créé avec succès',
                'data' => [
                    'id' => $auteur->id,
                    'nom' => $auteur->nom,
                    'prenom' => $auteur->prenom,
                    'nom_complet' => trim($auteur->prenom . ' ' . $auteur->nom),
                    'email' => $auteur->email,
                    'institution' => $auteur->institution,
                    'display_name' => trim($auteur->prenom . ' ' . $auteur->nom) . 
                                   ($auteur->institution ? ' (' . $auteur->institution . ')' : '')
                ]
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Author creation API failed', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'auteur'
            ], 500);
        }
    }
}