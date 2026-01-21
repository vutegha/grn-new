<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        try {
            // Validation des données
            $validator = Validator::make($request->all(), [
                'titre' => ['required', 'string', 'min:5', 'max:255'],
                'description' => ['nullable', 'string'],
                'categorie_id' => ['required', 'exists:categories,id'],
                'fichier' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:10240'], // 10MB max
                'is_published' => ['boolean'],
            ], [
                'titre.required' => 'Le titre est obligatoire.',
                'titre.min' => 'Le titre doit contenir au moins 5 caractères.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'categorie_id.required' => 'Veuillez sélectionner une catégorie.',
                'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
                'fichier.required' => 'Veuillez sélectionner un fichier.',
                'fichier.mimes' => 'Le fichier doit être au format PDF, DOC, DOCX, XLS ou XLSX.',
                'fichier.max' => 'Le fichier ne peut pas dépasser 10 MB.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Gestion du fichier
            if ($request->hasFile('fichier')) {
                $file = $request->file('fichier');
                $filename = uniqid('rapport_') . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('rapports', $filename, 'public');
                $validated['fichier'] = $path;
            }

            // Créer le slug
            $validated['slug'] = \Str::slug($validated['titre']);

            // Gérer les doublons de slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Rapport::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Gestion du boolean pour is_published
            $validated['is_published'] = $request->boolean('is_published', false);

            $rapport = Rapport::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Rapport créé avec succès',
                'rapport' => [
                    'id' => $rapport->id,
                    'titre' => $rapport->titre,
                    'categorie_nom' => $rapport->categorie->nom ?? 'N/A'
                ]
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de base de données lors de la création du rapport.',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur inattendue s\'est produite.',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne'
            ], 500);
        }
    }

    public function index()
    {
        try {
            $rapports = Rapport::with('categorie')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($rapport) {
                    return [
                        'id' => $rapport->id,
                        'titre' => $rapport->titre,
                        'categorie_nom' => $rapport->categorie->nom ?? 'N/A',
                        'is_published' => $rapport->is_published,
                    ];
                });

            return response()->json([
                'success' => true,
                'rapports' => $rapports
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des rapports.',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne'
            ], 500);
        }
    }
}
