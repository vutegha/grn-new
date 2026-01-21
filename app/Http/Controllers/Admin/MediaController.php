<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
public function index(Request $request)
{
    
        $this->authorize('viewAny', Media::class);
// Vérification des permissions
    Gate::authorize('viewAny', Media::class);

    $query = Media::with(['creator', 'moderator', 'projet']);

    // Filtrage par type
    if ($request->has('type') && in_array($request->type, ['image', 'video'])) {
        $query->where('type', $request->type);
    }

    // Filtrage par statut
    if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected', 'published'])) {
        $query->where('status', $request->status);
    }

    // Recherche par titre ou description
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('titre', 'LIKE', '%' . $search . '%')
              ->orWhere('description', 'LIKE', '%' . $search . '%');
        });
    }

    // Si l'utilisateur n'est pas admin, il ne voit que ses médias
    if (!Auth::user()->hasAnyRole(['super-admin', 'admin'])) {
        $query->where('created_by', Auth::id());
    }

    $medias = $query->latest()->paginate(12);

    // Statistiques des médias
    $stats = [
        'total' => Media::count(),
        'images' => Media::where('type', 'image')->count(),
        'videos' => Media::where('type', 'video')->count(),
        'published' => Media::where('status', 'published')->count(),
        'pending' => Media::where('status', 'pending')->count(),
        'approved' => Media::where('status', 'approved')->count(),
        'rejected' => Media::where('status', 'rejected')->count(),
    ];

    // Statistiques par type et statut
    $imageStats = [
        'total' => Media::where('type', 'image')->count(),
        'published' => Media::where('type', 'image')->where('status', 'published')->count(),
        'pending' => Media::where('type', 'image')->where('status', 'pending')->count(),
    ];

    $videoStats = [
        'total' => Media::where('type', 'video')->count(),
        'published' => Media::where('type', 'video')->where('status', 'published')->count(),
        'pending' => Media::where('type', 'video')->where('status', 'pending')->count(),
    ];

    return view('admin.media.index', compact('medias', 'stats', 'imageStats', 'videoStats'));
}

    public function show(Media $media)
    {
        
        $this->authorize('view', $media);
        
        Gate::authorize('view', $media);
        
        $media->load(['creator', 'moderator', 'projet']);
        
        return view('admin.media.show', compact('media'));
    }

    public function create()
    {
        
        $this->authorize('create', Media::class);
Gate::authorize('create', Media::class);
        
        $projets = \App\Models\Projet::all();
        return view('admin.media.create', compact('projets'));
    }

public function store(Request $request)
{
    
        $this->authorize('create', Media::class);
$validated = $request->validate([
        'type' => 'nullable|string|max:255',
        'titre' => 'nullable|string|max:255',
        'medias' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,video/mp4,video/quicktime,video/webm|max:40480',
        'projet_id' => 'nullable|integer|exists:projets,id',
    ]);

    try {
        // Upload du fichier si présent
        if ($request->hasFile('medias')) {
            $path = $request->file('medias')->store('assets/media', 'public');
            $validated['medias'] = $path;
        }

        Media::create($validated);

        return redirect()->route('admin.media.index')->with('success', 'Média enregistré avec succès.');
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Erreur lors de l’enregistrement : ' . $e->getMessage()])
                     ->withInput();
    }
}




    public function edit(Media $media)
    {
        
        $this->authorize('update', $media);
        
        $projets = \App\Models\Projet::all();
        return view('admin.media.edit', ['media' => $media, 'projets' => $projets]);
    }

    public function update(Request $request, Media $media)
    {
        
        $this->authorize('update', $media);
        
        $validated = $request->validate([
        'type' => 'nullable|string|max:255',
        'titre' => 'nullable|string|max:255',
        'medias' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,video/mp4,video/quicktime,video/webm|max:40480',
        'projet_id' => 'nullable|integer|exists:projets,id',
    ]);

    try {
        // Remplacer le média s’il y a un nouveau fichier
        if ($request->hasFile('medias')) {
            // Supprimer l'ancien fichier
            if ($media->medias && Storage::disk('public')->exists($media->medias)) {
                Storage::disk('public')->delete($media->medias);
            }

            // Uploader le nouveau
            $path = $request->file('medias')->store('assets/media', 'public');
            $validated['medias'] = $path;
        }

        $media->update($validated);

        return redirect()->route('admin.media.index')->with('success', 'Média mis à jour avec succès.');
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()])
                     ->withInput();
    }
}




public function destroy(Media $media)
{
    
        $this->authorize('delete', $media);
try {
        if ($media->medias && Storage::disk('public')->exists($media->medias)) {
            Storage::disk('public')->delete($media->medias);
        }

        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Média supprimé avec succès.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors([
            'message' => 'Erreur lors de la suppression : ' . $e->getMessage(),
        ]);
    }
}

/**
 * Liste les médias pour CKEditor (format JSON)
 */
public function list()
{
    $this->authorize('viewAny', Media::class);
    
    try {
        $medias = Media::where(function($query) {
                           $query->where('type', 'image')
                                 ->orWhere('medias', 'like', '%.jpg')
                                 ->orWhere('medias', 'like', '%.jpeg')
                                 ->orWhere('medias', 'like', '%.png')
                                 ->orWhere('medias', 'like', '%.gif')
                                 ->orWhere('medias', 'like', '%.webp')
                                 ->orWhere('medias', 'like', '%.svg');
                       })
                       ->latest()
                       ->get()
                       ->map(function ($media) {
                           $filePath = storage_path('app/public/' . $media->medias);
                           $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                           
                           return [
                               'id' => $media->id,
                               'url' => asset('storage/' . $media->medias),
                               'name' => $media->titre ?: basename($media->medias),
                               'alt' => $media->titre ?: 'Image',
                               'size' => $fileSize,
                               'created_at' => $media->created_at ? $media->created_at->toISOString() : null,
                               'dimensions' => $this->getImageDimensions($filePath)
                           ];
                       });

        return response()->json([
            'success' => true,
            'images' => $medias,
            'count' => $medias->count(),
            'message' => 'Médias chargés avec succès'
        ]);
    } catch (\Exception $e) {
        Log::error('Erreur lors du chargement des médias: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'images' => [],
            'count' => 0,
            'message' => 'Erreur lors du chargement des médias: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Obtenir les dimensions d'une image
 */
private function getImageDimensions($filePath)
{
    if (!file_exists($filePath) || !in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        return null;
    }
    
    try {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo) {
            return $imageInfo[0] . 'x' . $imageInfo[1];
        }
    } catch (\Exception $e) {
        // Ignorer les erreurs de lecture d'image
    }
    
    return null;
}

/**
 * Upload d'image pour CKEditor (simple et multiple)
 */
public function upload(Request $request)
{
    $this->authorize('create', Media::class);
    
    // Validation pour upload simple ou multiple
    $rules = [
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240'
    ];
    
    $request->validate($rules);

    try {
        $uploadedFiles = [];
        $errors = [];
        
        // Upload simple (compatibilité arrière)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $result = $this->processFileUpload($file);
            
            if ($result['success']) {
                $uploadedFiles[] = $result['data'];
            } else {
                $errors[] = $result['message'];
            }
        }
        
        // Upload multiple
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $result = $this->processFileUpload($file);
                
                if ($result['success']) {
                    $uploadedFiles[] = $result['data'];
                } else {
                    $errors[] = $result['message'];
                }
            }
        }
        
        if (empty($uploadedFiles) && empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun fichier valide fourni'
            ], 400);
        }
        
        $response = [
            'success' => !empty($uploadedFiles),
            'uploaded' => count($uploadedFiles),
            'files' => $uploadedFiles
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
            $response['message'] = count($uploadedFiles) > 0 
                ? 'Upload partiel: ' . count($uploadedFiles) . ' fichier(s) uploadé(s), ' . count($errors) . ' erreur(s)'
                : 'Erreurs lors de l\'upload: ' . implode(', ', $errors);
        } else {
            $response['message'] = count($uploadedFiles) . ' fichier(s) uploadé(s) avec succès';
        }
        
        return response()->json($response);
        
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'upload: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'upload : ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Traiter l'upload d'un fichier individuel
 */
private function processFileUpload($file)
{
    try {
        // Générer un nom unique pour éviter les conflits
        $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $path = $file->storeAs('media', $filename, 'public');
        
        // Créer l'entrée en base
        $media = Media::create([
            'type' => 'image',
            'titre' => $file->getClientOriginalName(),
            'medias' => $path,
            'created_by' => auth()->id(),
            'status' => 'approved' // Approuver automatiquement pour les uploads CKEditor
        ]);
        
        $filePath = storage_path('app/public/' . $path);
        $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
        
        return [
            'success' => true,
            'data' => [
                'id' => $media->id,
                'url' => asset('storage/' . $path),
                'name' => $file->getClientOriginalName(),
                'size' => $fileSize,
                'dimensions' => $this->getImageDimensions($filePath),
                'created_at' => $media->created_at->toISOString()
            ]
        ];
        
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'Erreur upload ' . $file->getClientOriginalName() . ': ' . $e->getMessage()
        ];
    }
}

    /**
     * Actions de modération
     */
    public function approve(Media $media)
    {
        Gate::authorize('approve', $media);
        
        $media->update([
            'status' => Media::STATUS_APPROVED,
            'moderated_by' => Auth::id(),
            'moderated_at' => now()
        ]);
        
        Log::info('Média approuvé', [
            'media_id' => $media->id,
            'moderator_id' => Auth::id()
        ]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média approuvé avec succès.'
            ]);
        }
        
        return back()->with('success', 'Média approuvé avec succès.');
    }
    
    public function reject(Request $request, Media $media)
    {
        Gate::authorize('reject', $media);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        $media->update([
            'status' => Media::STATUS_REJECTED,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);
        
        Log::info('Média rejeté', [
            'media_id' => $media->id,
            'moderator_id' => Auth::id(),
            'reason' => $request->rejection_reason
        ]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média rejeté.'
            ]);
        }
        
        return back()->with('success', 'Média rejeté.');
    }
    
    public function publish(Media $media)
    {
        Gate::authorize('publish', $media);
        
        $media->update([
            'status' => Media::STATUS_PUBLISHED,
            'is_public' => true
        ]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média publié avec succès.'
            ]);
        }
        
        return back()->with('success', 'Média publié avec succès.');
    }
    
    public function unpublish(Media $media)
    {
        Gate::authorize('publish', $media);
        
        $media->update([
            'status' => Media::STATUS_APPROVED,
            'is_public' => false
        ]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média dépublié.'
            ]);
        }
        
        return back()->with('success', 'Média dépublié.');
    }
    
    /**
     * Copier le lien du média
     */
    public function copyLink(Media $media)
    {
        Gate::authorize('copyLink', $media);
        
        $url = asset('storage/' . $media->medias);
        
        return response()->json([
            'success' => true,
            'url' => $url,
            'message' => 'Lien copié dans le presse-papiers'
        ]);
    }
    
    /**
     * Téléchargement sécurisé
     */
    public function download(Media $media)
    {
        Gate::authorize('download', $media);
        
        $filePath = storage_path('app/public/' . $media->medias);
        
        if (!file_exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }
        
        Log::info('Téléchargement média', [
            'media_id' => $media->id,
            'user_id' => Auth::id()
        ]);
        
        return response()->download($filePath, $media->titre . '.' . $media->file_extension);
    }

    /**
     * API pour la médiathèque dans les formulaires
     */
    public function apiList(Request $request)
    {
        $this->authorize('viewAny', Media::class);

        $query = Media::where('status', 'published')
                     ->where('is_public', true);

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        $medias = $query->orderBy('created_at', 'desc')
                       ->limit(50)
                       ->get()
                       ->map(function ($media) {
                           return [
                               'id' => $media->id,
                               'titre' => $media->titre,
                               'description' => $media->description,
                               'type' => $media->type,
                               'alt_text' => $media->alt_text,
                               'public_url' => $media->public_url,
                               'file_size' => $media->file_size,
                               'mime_type' => $media->mime_type,
                               'created_at' => $media->created_at->format('d/m/Y'),
                           ];
                       });

        return response()->json($medias);
    }

}