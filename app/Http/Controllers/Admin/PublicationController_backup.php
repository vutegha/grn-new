<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Publication;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PublicationController extends Controller
{
public function index(Request $request)
{
    $query = \App\Models\Publication::with('auteur', 'categorie');

    if ($request->filled('auteur')) {
        $query->where('auteur_id', $request->auteur);
    }

    if ($request->filled('categorie')) {
        $query->where('categorie_id', $request->categorie);
    }

    $publications = $query->latest()->paginate(10)->appends($request->query());

    $auteurs = \App\Models\Auteur::all();
    $categories = \App\Models\Categorie::all();

    return view('admin.publication.index', compact('publications', 'auteurs', 'categories', 'request'));
}


   

    public function create()
    {
        $auteurs = \App\Models\Auteur::all();
        $categories = \App\Models\Categorie::all();
        return view('admin.publication.create', compact('auteurs', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'required|string',
                'citation' => 'nullable|string',
                'a_la_une' => 'nullable|boolean',
                'en_vedette' => 'nullable|boolean',
                'auteurs' => 'required|array',
                'auteurs.*' => 'exists:auteurs,id',
                'categorie_id' => 'required|exists:categories,id',
                'fichier_pdf' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,odt,odp|max:40240',
            ], [
                'fichier_pdf.required' => 'Le fichier PDF est obligatoire pour toute publication.',
                'fichier_pdf.file' => 'Le fichier téléchargé doit être un fichier valide.',
                'fichier_pdf.mimes' => 'Le fichier doit être au format PDF, Word, PowerPoint ou LibreOffice.',
                'fichier_pdf.max' => 'Le fichier ne doit pas dépasser 40 MB.',
                'titre.required' => 'Le titre est obligatoire.',
                'resume.required' => 'Le résumé est obligatoire.',
                'auteurs.required' => 'Au moins un auteur doit être sélectionné.',
                'categorie_id.required' => 'Une catégorie doit être sélectionnée.',
            ]);

            // Ensure boolean fields are set to false if not present
            $validated['a_la_une'] = $request->has('a_la_une') ? 1 : 0;
            $validated['en_vedette'] = $request->has('en_vedette') ? 1 : 0;

            // Vérifier que le fichier a été téléchargé
            if (!$request->hasFile('fichier_pdf')) {
                throw new ValidationException(validator([], []), [
                    'fichier_pdf' => 'Le fichier PDF est requis mais n\'a pas été téléchargé.'
                ]);
            }

            $file = $request->file('fichier_pdf');
            
            // Vérifications supplémentaires du fichier
            if (!$file->isValid()) {
                throw new ValidationException(validator([], []), [
                    'fichier_pdf' => 'Le fichier téléchargé est corrompu ou invalide: ' . $file->getErrorMessage()
                ]);
            }

            // Générer le nom du fichier à partir du titre en remplaçant les espaces par des underscores
            $filename = preg_replace('/\s+/', '_', $validated['titre']) . '.' . $file->getClientOriginalExtension();
            
            try {
                // S'assurer que le dossier existe
                $assetsPath = storage_path('app/public/assets');
                if (!is_dir($assetsPath)) {
                    mkdir($assetsPath, 0755, true);
                }
                
                // Tenter l'upload avec Laravel Storage
                $path = $file->storeAs('assets', $filename, 'public');
                
                if (!$path) {
                    // Fallback: essayer avec move()
                    $destinationPath = storage_path('app/public/assets/' . $filename);
                    if ($file->move(dirname($destinationPath), $filename)) {
                        $path = 'assets/' . $filename;
                    } else {
                        throw new \Exception('Impossible de déplacer le fichier vers le dossier de destination');
                    }
                }
                
                $validated['fichier_pdf'] = $path;
                
                // Log pour debug
                \Log::info('Fichier uploadé avec succès', [
                    'nom_original' => $file->getClientOriginalName(),
                    'nom_stockage' => $filename,
                    'chemin' => $path,
                    'taille' => $file->getSize()
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Erreur upload fichier', [
                    'message' => $e->getMessage(),
                    'fichier' => $file->getClientOriginalName(),
                    'taille' => $file->getSize(),
                    'type' => $file->getClientMimeType()
                ]);
                
                throw new \Exception('Erreur lors de l\'upload du fichier : ' . $e->getMessage());
            }

            // Extraire les auteurs avant de créer la publication
            $auteurs = $validated['auteurs'];
            unset($validated['auteurs']);

            // Créer la publication
            $publication = Publication::create($validated);
            
            // Attacher les auteurs avec l'ordre
            $auteursWithOrder = [];
            foreach ($auteurs as $index => $auteurId) {
                $auteursWithOrder[$auteurId] = ['ordre' => $index + 1];
            }
            $publication->auteurs()->attach($auteursWithOrder);

            return redirect()->route('admin.publication.index')
                ->with('alert', '<span class="alert alert-success">Publication enregistrée avec succès. Fichier PDF: ' . basename($path) . '</span>');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur de validation. Veuillez vérifier les champs requis.</span>');
        } catch (\Exception $e) {
            // Nettoyer le fichier si une erreur survient après l'upload
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            return redirect()
                ->back()
                ->with('alert', '<span class="alert alert-danger">Une erreur est survenue lors de l\'enregistrement : ' . e($e->getMessage()) . '</span>')
                ->withInput();
        }
    }

public function edit(Publication $publication)
{
    $auteurs = \App\Models\Auteur::all();
    $categories = \App\Models\Categorie::all();

    return view('admin.publication.edit', compact('publication', 'auteurs', 'categories'));
}



public function update(Request $request, Publication $publication)
{
    try {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'required|string',
            'citation' => 'nullable|string',
            'a_la_une' => 'nullable|boolean',
            'en_vedette' => 'nullable|boolean',
            'auteurs' => 'required|array',
            'auteurs.*' => 'exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'fichier_pdf' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,odt,odp|max:40240',
        ], [
            'fichier_pdf.file' => 'Le fichier téléchargé doit être un fichier valide.',
            'fichier_pdf.mimes' => 'Le fichier doit être au format PDF, Word, PowerPoint ou LibreOffice.',
            'fichier_pdf.max' => 'Le fichier ne doit pas dépasser 40 MB.',
            'titre.required' => 'Le titre est obligatoire.',
            'resume.required' => 'Le résumé est obligatoire.',
            'auteurs.required' => 'Au moins un auteur doit être sélectionné.',
            'categorie_id.required' => 'Une catégorie doit être sélectionnée.',
        ]);

        // Forcer les booléens
        $validated['a_la_une'] = $request->has('a_la_une') ? 1 : 0;
        $validated['en_vedette'] = $request->has('en_vedette') ? 1 : 0;

        // Gérer le fichier PDF/Word/PowerPoint
        if ($request->hasFile('fichier_pdf')) {
            $file = $request->file('fichier_pdf');
            
            // Vérifications supplémentaires du fichier
            if (!$file->isValid()) {
                throw new ValidationException(validator([], []), [
                    'fichier_pdf' => 'Le fichier téléchargé est corrompu ou invalide: ' . $file->getErrorMessage()
                ]);
            }

            // Supprimer l'ancien fichier s'il existe
            if ($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf)) {
                Storage::disk('public')->delete($publication->fichier_pdf);
            }

            // Enregistrer le nouveau fichier
            $filename = preg_replace('/\s+/', '_', $validated['titre']) . '.' . $file->getClientOriginalExtension();
            
            try {
                // S'assurer que le dossier existe
                $assetsPath = storage_path('app/public/assets');
                if (!is_dir($assetsPath)) {
                    mkdir($assetsPath, 0755, true);
                }
                
                // Tenter l'upload avec Laravel Storage
                $path = $file->storeAs('assets', $filename, 'public');
                
                if (!$path) {
                    // Fallback: essayer avec move()
                    $destinationPath = storage_path('app/public/assets/' . $filename);
                    if ($file->move(dirname($destinationPath), $filename)) {
                        $path = 'assets/' . $filename;
                    } else {
                        throw new \Exception('Impossible de déplacer le fichier vers le dossier de destination');
                    }
                }
                
                $validated['fichier_pdf'] = $path;
                
                // Log pour debug
                \Log::info('Fichier mis à jour avec succès', [
                    'publication_id' => $publication->id,
                    'nom_original' => $file->getClientOriginalName(),
                    'nom_stockage' => $filename,
                    'chemin' => $path,
                    'taille' => $file->getSize()
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Erreur mise à jour fichier', [
                    'publication_id' => $publication->id,
                    'message' => $e->getMessage(),
                    'fichier' => $file->getClientOriginalName(),
                    'taille' => $file->getSize(),
                    'type' => $file->getClientMimeType()
                ]);
                
                throw new \Exception('Erreur lors de l\'upload du fichier : ' . $e->getMessage());
            }
        }

        // Extraire les auteurs avant de mettre à jour la publication
        $auteurs = $validated['auteurs'];
        unset($validated['auteurs']);

        // Mise à jour de la publication
        $publication->update($validated);

        // Synchroniser les auteurs avec l'ordre
        $auteursWithOrder = [];
        foreach ($auteurs as $index => $auteurId) {
            $auteursWithOrder[$auteurId] = ['ordre' => $index + 1];
        }
        $publication->auteurs()->sync($auteursWithOrder);

        $message = 'Publication mise à jour avec succès.';
        if (isset($path)) {
            $message .= ' Nouveau fichier: ' . basename($path);
        }

        return redirect()->route('admin.publication.index')
            ->with('alert', '<span class="alert alert-success">' . $message . '</span>');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('alert', '<span class="alert alert-danger">Erreur de validation. Veuillez vérifier les champs.</span>');
    } catch (\Exception $e) {
        // Nettoyer le fichier si une erreur survient après l'upload
        if (isset($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        return redirect()->back()
            ->with('alert', '<span class="alert alert-danger">Une erreur est survenue lors de la mise à jour : ' . e($e->getMessage()) . '</span>')
            ->withInput();
    }
}



    




    
public function updateFeatures(Request $request, Publication $publication)
{
    $validated = $request->validate([
        'en_vedette' => 'required|boolean',
        'a_la_une' => 'required|boolean',
    ]);

    $publication->update([
        'en_vedette' => $validated['en_vedette'],
        'a_la_une' => $validated['a_la_une'],
    ]);

    return redirect()->route('admin.publication.index')
        ->with('alert', '<span class="alert alert-success">Champs mis à jour avec succès.</span>');
}



    public function destroy(Publication $publication)
{
    try {
        // Supprimer le fichier PDF associé s’il existe
        if ($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf)) {
            Storage::disk('public')->delete($publication->fichier_pdf);
        }

        // Supprimer l'enregistrement
        $publication->delete();

        return redirect()->route('admin.publication.index')
            ->with('alert', '<span class="alert alert-success">Publication supprimée avec succès.</span>');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('alert', '<span class="alert alert-danger">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
    }
}


public function show( $id)
    {
         $publication = Publication::with(['auteur', 'categorie'])->findOrFail($id);
        $fichierPath = storage_path('app/public/' . $publication->fichier_pdf);
        $extension = strtolower(pathinfo($fichierPath, PATHINFO_EXTENSION));
        $contenuHtml = null;

    if (in_array($extension, ['doc', 'docx'])) {
        $contenuHtml = $this->convertirDocxEnHtml($fichierPath);
    }
    return view('admin.publication.show', compact('publication', 'contenuHtml', 'extension'));
        // return view('admin.publication.show', compact('item'));
    }

     public function convertirDocxEnHtml($fileUrl)
{
    if (!file_exists($fileUrl)) {
        return '<p>Fichier introuvable.</p>';
    }
    // Vérification du type mime réel
    $expectedMime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    if (mime_content_type($fileUrl) !== $expectedMime) {
        return '<p>Le fichier fourni n’est pas un fichier .docx valide (type mime incorrect).</p>';
    }

    try {
        $phpWord = IOFactory::load($fileUrl);
        $writer = IOFactory::createWriter($phpWord, 'HTML');

        // Capture la sortie HTML générée
        ob_start();
        $writer->save('php://output');
        $contenuHtml = ob_get_clean();

        return $contenuHtml;
    } catch (\Exception $e) {
        return '<p>Erreur lors de la lecture du fichier Word : ' . $e->getMessage() . '</p>';
    }
}

    public function toggleUne(Publication $publication)
    {
        try {
            $publication->a_la_une = !$publication->a_la_une;
            $publication->save();

            return response()->json([
                'success' => true,
                'message' => $publication->a_la_une ? 'Publication mise à la une' : 'Publication retirée de la une',
                'status' => $publication->a_la_une
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Publier une publication
     */
    public function publish(Request $request, Publication $publication)
    {
        try {
            $publication->publish(auth()->user(), $request->input('comment'));
            
            return response()->json([
                'success' => true,
                'message' => 'Publication publiée avec succès',
                'status' => $publication->publication_status,
                'published_at' => $publication->published_at ? $publication->published_at->format('d/m/Y H:i') : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dépublier une publication
     */
    public function unpublish(Request $request, Publication $publication)
    {
        try {
            $publication->unpublish($request->input('comment'));
            
            return response()->json([
                'success' => true,
                'message' => 'Publication dépubliée avec succès',
                'status' => $publication->publication_status
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
        $publications = Publication::pendingModeration()
                                  ->with(['auteurs', 'categorie'])
                                  ->latest()
                                  ->paginate(10);

        return view('admin.publication.pending', compact('publications'));
    }
   
}