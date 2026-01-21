<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RapportController extends Controller
{
    /**
     * Affiche la liste des rapports avec pagination et filtres
     */
    public function index(Request $request)
    {
        $query = Rapport::with('categorie')
            ->where('is_published', true)
            ->orderBy('date_publication', 'desc');

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par année
        if ($request->filled('annee')) {
            $query->whereYear('date_publication', $request->annee);
        }

        $rapports = $query->paginate(12)->appends($request->query());
        
        // Récupérer toutes les catégories pour le filtre
        $categories = Categorie::orderBy('nom')->get();

        return view('rapports.index', compact('rapports', 'categories'));
    }

    /**
     * Affiche un rapport spécifique
     */
    public function show(Rapport $rapport)
    {
        // Vérifier que le rapport est publié
        if (!$rapport->is_published) {
            abort(404);
        }

        return view('rapports.show', compact('rapport'));
    }

    /**
     * Télécharge un rapport
     */
    public function download(Rapport $rapport)
    {
        // Vérifier que le rapport est publié
        if (!$rapport->is_published) {
            abort(404);
        }

        // Vérifier que le fichier existe
        if (!$rapport->fichier || !Storage::disk('public')->exists($rapport->fichier)) {
            abort(404, 'Fichier non trouvé');
        }

        $filePath = Storage::disk('public')->path($rapport->fichier);
        $fileName = $rapport->titre . '.' . pathinfo($rapport->fichier, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }

    /**
     * Affiche le rapport au format PDF dans le navigateur
     */
    public function preview(Rapport $rapport)
    {
        // Vérifier que le rapport est publié
        if (!$rapport->is_published) {
            abort(404);
        }

        // Vérifier que c'est un PDF
        if (!$rapport->isPdf()) {
            return redirect()->route('rapports.download', $rapport);
        }

        // Vérifier que le fichier existe
        if (!$rapport->fichier || !Storage::disk('public')->exists($rapport->fichier)) {
            abort(404, 'Fichier non trouvé');
        }

        $filePath = Storage::disk('public')->path($rapport->fichier);
        
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $rapport->titre . '.pdf"'
        ]);
    }

    /**
     * API pour récupérer les rapports par catégorie (pour AJAX)
     */
    public function byCategory(Request $request, $categorieId = null)
    {
        $query = Rapport::with('categorie')
            ->where('is_published', true)
            ->orderBy('date_publication', 'desc');

        if ($categorieId) {
            $query->where('categorie_id', $categorieId);
        }

        $rapports = $query->get();

        if ($request->expectsJson()) {
            return response()->json([
                'rapports' => $rapports->map(function ($rapport) {
                    return [
                        'id' => $rapport->id,
                        'titre' => $rapport->titre,
                        'description' => $rapport->description,
                        'date_publication' => $rapport->date_publication?->format('d/m/Y'),
                        'categorie' => $rapport->categorie?->nom,
                        'file_type' => $rapport->getFileType(),
                        'file_icon' => $rapport->getFileIcon(),
                        'file_size' => $rapport->getFileSize(),
                        'can_have_thumbnail' => $rapport->canHaveThumbnail(),
                        'download_url' => $rapport->getDownloadUrl(),
                        'preview_url' => $rapport->isPdf() ? route('rapports.preview', $rapport) : null,
                    ];
                })
            ]);
        }

        return view('rapports.category', compact('rapports'));
    }

    /**
     * Recherche de rapports (pour autocomplétion)
     */
    public function search(Request $request)
    {
        $term = $request->get('term', '');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $rapports = Rapport::where('is_published', true)
            ->where(function ($query) use ($term) {
                $query->where('titre', 'LIKE', "%{$term}%")
                      ->orWhere('description', 'LIKE', "%{$term}%");
            })
            ->limit(10)
            ->get(['id', 'titre', 'description']);

        return response()->json([
            'results' => $rapports->map(function ($rapport) {
                return [
                    'id' => $rapport->id,
                    'text' => $rapport->titre,
                    'description' => Str::limit($rapport->description, 100)
                ];
            })
        ]);
    }
}
