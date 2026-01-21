<?php  

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    // Constructeur avec middleware d'authentification
    public function __construct()
    {
        $this->middleware('auth');
        // Les permissions seront gérées individuellement dans chaque méthode si nécessaire
    }
    public function index()
    {
        $this->authorize('viewAny', Service::class);
        
        $services = Service::latest()->get();
        return view('admin.service.index', compact('services'));
    }

    public function create()
    {
        $this->authorize('create', Service::class);
        
        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Service::class);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'nom_menu' => 'nullable|string|max:255',
            'resume' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services/images', 'public');
            
            // Copier vers public/storage pour compatibilité Windows
            $this->ensurePublicStorageSync($validated['image']);
        }

        Service::create($validated);

        return redirect()->route('admin.service.index')->with('success', 'Service ajouté.');
    }

    public function edit(Service $service)
    {
        $this->authorize('update', $service);
        
        return view('admin.service.edit', compact('service'));
    }

    public function show(Service $service)
    {
        $this->authorize('view', $service);
        
        return view('admin.service.show', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'nom_menu' => 'nullable|string|max:255',
            'resume' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
                $this->removePublicStorageFile($service->image);
            }
            $validated['image'] = $request->file('image')->store('services/images', 'public');
            
            // Copier vers public/storage pour compatibilité Windows
            $this->ensurePublicStorageSync($validated['image']);
        }

        $service->update($validated);

        return redirect()->route('admin.service.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
            $this->removePublicStorageFile($service->image);
        }
        $service->delete();

        return redirect()->route('admin.service.index')->with('success', 'Service supprimé.');
    }

    /**
     * Synchroniser les fichiers vers public/storage pour compatibilité Windows
     */
    private function ensurePublicStorageSync($imagePath)
    {
        $sourcePath = storage_path('app/public/' . $imagePath);
        $destPath = public_path('storage/' . $imagePath);
        
        // Créer le répertoire de destination si nécessaire
        $destDir = dirname($destPath);
        if (!file_exists($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        // Copier le fichier
        if (file_exists($sourcePath)) {
            copy($sourcePath, $destPath);
        }
    }

    /**
     * Supprimer le fichier de public/storage
     */
    private function removePublicStorageFile($imagePath)
    {
        $destPath = public_path('storage/' . $imagePath);
        if (file_exists($destPath)) {
            unlink($destPath);
        }
    }

    /**
     * Publier un service
     */
    public function publish(Request $request, Service $service)
    {
        // Vérifier les permissions
        $this->authorize('publish', $service);

        try {
            $service->update([
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
                'moderation_comment' => $request->input('comment')
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service publié avec succès.'
                ]);
            }

            return redirect()->back()
                ->with('alert', '<span class="alert alert-success">Service publié avec succès.</span>');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la publication : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la publication : ' . $e->getMessage() . '</span>');
        }
    }

    /**
     * Dépublier un service
     */
    public function unpublish(Request $request, Service $service)
    {
        // Vérifier les permissions
        $this->authorize('unpublish', $service);

        try {
            $service->update([
                'is_published' => false,
                'moderation_comment' => $request->input('comment')
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service dépublié avec succès.'
                ]);
            }

            return redirect()->back()
                ->with('alert', '<span class="alert alert-success">Service dépublié avec succès.</span>');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la dépublication : ' . $e->getMessage() . '</span>');
        }
    }

    /**
     * Modérer un service (ajouter un commentaire et changer le statut)
     */
    public function moderate(Request $request, Service $service)
    {
        $this->authorize('moderate', $service);
        
        $validated = $request->validate([
            'moderation_comment' => 'nullable|string|max:1000',
            'is_published' => 'boolean'
        ]);

        try {
            $updateData = [
                'moderation_comment' => $validated['moderation_comment'] ?? null,
            ];

            if (isset($validated['is_published'])) {
                $updateData['is_published'] = $validated['is_published'];
                if ($validated['is_published']) {
                    $updateData['published_at'] = now();
                    $updateData['published_by'] = auth()->id();
                }
            }

            $service->update($updateData);
            
            return redirect()->back()->with('success', 'Modération mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la modération : ' . $e->getMessage());
        }
    }

    /**
     * Basculer l'affichage dans le menu
     */
    public function toggleMenu(Request $request, Service $service)
    {
        // Vérifier les permissions (temporairement désactivé)
        // if (!auth()->user()->can('moderate-services')) {
        //     return redirect()->back()->with('error', 'Vous n\'avez pas les permissions pour gérer l\'affichage des services dans le menu.');
        // }

        try {
            $showInMenu = $request->input('show_in_menu', $service->show_in_menu ? 0 : 1);
            
            // Vérifier que le service est publié
            if (!$service->is_published && $showInMenu) {
                return redirect()->back()->with('error', 'Le service doit être publié avant d\'être affiché dans le menu.');
            }
            
            $service->update([
                'show_in_menu' => $showInMenu,
                'moderation_comment' => $showInMenu 
                    ? "Service ajouté au menu Programmes par " . auth()->user()->name 
                    : "Service retiré du menu Programmes par " . auth()->user()->name
            ]);
            
            $displayName = $service->menu_display_name;
            
            $message = $showInMenu 
                ? "Service ajouté au menu \"Programmes\" sous le nom : {$displayName}" 
                : 'Service retiré du menu "Programmes" avec succès';
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Voir les éléments en attente de modération
     */
    public function pendingModeration()
    {
        $services = Service::pendingModeration()
                          ->latest()
                          ->paginate(10);

        return view('admin.service.pending', compact('services'));
    }
}
