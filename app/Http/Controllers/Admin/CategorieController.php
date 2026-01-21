<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Categorie::class);
        
        $categories = Categorie::latest()->paginate(15);
                              
        return view('admin.categorie.index', compact('categories'));
    }

    public function create()
    {
        
        $this->authorize('create', Categorie::class);
        return view('admin.categorie.create');
    }

    public function show(Categorie $categorie)
    {
        $this->authorize('view', $categorie);
        
        $categorie->loadCount('actualites');
        $categorie->load(['actualites' => function($query) {
            $query->latest()->take(5);
        }]);
        
        return view('admin.categorie.show', compact('categorie'));
    }

    public function store(Request $request)
    {
        
        $this->authorize('create', Categorie::class);
try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255|unique:categories,nom',
                'description' => 'nullable|string|max:1000',
            ]);

            $categorie = Categorie::create($validated);

            // Si c'est une requête AJAX, retourner du JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'categorie' => [
                        'id' => $categorie->id,
                        'nom' => $categorie->nom,
                        'description' => $categorie->description,
                    ],
                    'message' => 'Catégorie créée avec succès!'
                ]);
            }

            return redirect()->route('admin.categorie.index')
                ->with('alert', '<span class="alert alert-success">Catégorie créée avec succès.</span>');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $e->validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur de validation.</span>');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la catégorie: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function edit(Categorie $categorie)
    {
        
        $this->authorize('update', $categorie);
        
        return view('admin.categorie.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        
        $this->authorize('update', $categorie);
        
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($categorie->id)],
            'description' => 'nullable|string|max:1000',
        ]);

        $categorie->update($validated);

        return redirect()->route('admin.categorie.index')
            ->with('alert', '<span class="alert alert-success">Catégorie mise à jour avec succès.</span>');
    }

    public function destroy(Categorie $categorie)
    {
        
        $this->authorize('delete', $categorie);
        
        try {
            $categorie->delete();

            return redirect()->route('admin.categorie.index')
                ->with('alert', '<span class="alert alert-success">Catégorie supprimée avec succès.</span>');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Création de catégorie via AJAX
     */
    public function storeAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255|unique:categories,nom',
                'description' => 'nullable|string|max:1000',
            ]);

            $categorie = Categorie::create($validated);

            return response()->json([
                'success' => true,
                'categorie' => [
                    'id' => $categorie->id,
                    'nom' => $categorie->nom,
                ],
                'message' => 'Catégorie créée avec succès!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la catégorie'
            ], 500);
        }
    }
}
