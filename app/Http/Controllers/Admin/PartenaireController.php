<?php  

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partenaire;
use Illuminate\Support\Facades\Storage;

class PartenaireController extends Controller
{
    // Constructeur avec middleware d'authentification
    public function __construct()
    {
        $this->middleware('auth');
        // Les permissions seront gérées individuellement dans chaque méthode si nécessaire
    }

    public function index()
    {
        $this->authorize('viewAny', Partenaire::class);
        
        $partenaires = Partenaire::ordonnes()->get();
        
        // Statistiques pour les cartes
        $stats = [
            'total' => $partenaires->count(),
            'actifs' => $partenaires->where('statut', 'actif')->count(),
            'publies' => $partenaires->filter(function($partenaire) {
                return $partenaire->published_at !== null;
            })->count(),
            'universites' => $partenaires->where('type', 'universite')->count(),
            'organisations' => $partenaires->where('type', 'organisation_internationale')->count(),
        ];
        
        return view('admin.partenaires.index', compact('partenaires', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Partenaire::class);
        
        return view('admin.partenaires.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Partenaire::class);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:universite,centre_recherche,organisation_internationale,ong,entreprise,autre',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:5120',
            'site_web' => 'nullable|url|max:255',
            'email_contact' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string',
            'pays' => 'nullable|string|max:100',
            'statut' => 'required|in:actif,inactif,en_negociation',
        ]);

        // Gérer l'upload du logo
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partenaires/logos', 'public');
            
            // Copier vers public/storage pour compatibilité Windows
            $this->ensurePublicStorageSync($validated['logo']);
        }

        Partenaire::create($validated);

        return redirect()->route('admin.partenaires.index')->with('success', 'Partenaire ajouté avec succès.');
    }

    public function show(Partenaire $partenaire)
    {
        $this->authorize('view', $partenaire);
        
        return view('admin.partenaires.show', compact('partenaire'));
    }

    public function edit(Partenaire $partenaire)
    {
        $this->authorize('update', $partenaire);
        
        return view('admin.partenaires.edit', compact('partenaire'));
    }

    public function update(Request $request, Partenaire $partenaire)
    {
        $this->authorize('update', $partenaire);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:universite,centre_recherche,organisation_internationale,ong,entreprise,autre',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:5120',
            'site_web' => 'nullable|url|max:255',
            'email_contact' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string',
            'pays' => 'nullable|string|max:100',
            'statut' => 'required|in:actif,inactif,en_negociation',
        ]);

        // Gérer l'upload du logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si il existe
            if ($partenaire->logo) {
                Storage::disk('public')->delete($partenaire->logo);
                $this->removePublicStorageFile($partenaire->logo);
            }
            
            $validated['logo'] = $request->file('logo')->store('partenaires/logos', 'public');
            
            // Copier vers public/storage pour compatibilité Windows
            $this->ensurePublicStorageSync($validated['logo']);
        }

        $partenaire->update($validated);

        return redirect()->route('admin.partenaires.index')->with('success', 'Partenaire mis à jour avec succès.');
    }

    public function destroy(Partenaire $partenaire)
    {
        $this->authorize('delete', $partenaire);
        
        // Supprimer le logo si il existe
        if ($partenaire->logo) {
            Storage::disk('public')->delete($partenaire->logo);
            $this->removePublicStorageFile($partenaire->logo);
        }
        
        $partenaire->delete();

        return redirect()->route('admin.partenaires.index')->with('success', 'Partenaire supprimé avec succès.');
    }

    /**
     * Publier/Dépublier un partenaire
     */
    public function togglePublication(Partenaire $partenaire)
    {
        $this->authorize('moderate', $partenaire);

        $partenaire->statut = $partenaire->statut === 'actif' ? 'inactif' : 'actif';
        $partenaire->save();

        $status = $partenaire->statut === 'actif' ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "Partenaire {$status} avec succès.");
    }

    /**
     * Assurer la synchronisation avec public/storage (pour compatibilité Windows)
     */
    private function ensurePublicStorageSync($filePath)
    {
        $storagePath = storage_path('app/public/' . $filePath);
        $publicPath = public_path('storage/' . $filePath);
        
        if (file_exists($storagePath)) {
            $publicDir = dirname($publicPath);
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            copy($storagePath, $publicPath);
        }
    }

    /**
     * Supprimer le fichier de public/storage
     */
    private function removePublicStorageFile($filePath)
    {
        $publicPath = public_path('storage/' . $filePath);
        if (file_exists($publicPath)) {
            unlink($publicPath);
        }
    }
}
