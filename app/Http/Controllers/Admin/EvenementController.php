<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Events\EvenementFeaturedCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EvenementController extends Controller
{
    public function index(Request $request)
    {
        
        $this->authorize('viewAny', Evenement::class);
$query = Evenement::query();

        // Filtres
        if ($request->filled('etat')) {
            switch ($request->etat) {
                case 'a_venir':
                    $query->aVenir();
                    break;
                case 'en_cours':
                    $query->enCours();
                    break;
                case 'passe':
                    $query->passe();
                    break;
            }
        }

        if ($request->filled('annee')) {
            $query->whereYear('date_evenement', $request->annee);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('lieu', 'like', '%' . $request->search . '%');
            });
        }

        $evenements = $query->latest('date_evenement')->paginate(10);

        // Pour les filtres
        $anneesDisponibles = Evenement::selectRaw('YEAR(date_evenement) as annee')
                                    ->whereNotNull('date_evenement')
                                    ->distinct()
                                    ->orderBy('annee', 'desc')
                                    ->pluck('annee')
                                    ->toArray();

        return view('admin.evenements.index', compact('evenements', 'anneesDisponibles'));
    }

    public function create()
    {
        
        $this->authorize('create', Evenement::class);
return view('admin.evenements.create');
    }

    public function store(Request $request)
    {
        
        $this->authorize('create', Evenement::class);
try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string|max:500',
                'description' => 'required|string',
                'date_evenement' => 'required|date|after_or_equal:today',
                'lieu' => 'nullable|string|max:255',
                'organisateur' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'contact_telephone' => 'nullable|string|max:20',
                'type' => 'required|string|in:conference,seminaire,atelier,formation,table-ronde,colloque,autre',
                'en_vedette' => 'nullable|boolean',
                'a_la_une' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'rapport_url' => 'nullable|url|max:255',
            ], [
                'titre.required' => 'Le titre de l\'événement est obligatoire.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'resume.max' => 'Le résumé ne peut pas dépasser 500 caractères.',
                'description.required' => 'La description de l\'événement est obligatoire.',
                'date_evenement.required' => 'La date de l\'événement est obligatoire.',
                'date_evenement.date' => 'La date de l\'événement doit être une date valide.',
                'date_evenement.after_or_equal' => 'La date de l\'événement ne peut pas être dans le passé.',
                'lieu.max' => 'Le lieu ne peut pas dépasser 255 caractères.',
                'organisateur.max' => 'Le nom de l\'organisateur ne peut pas dépasser 255 caractères.',
                'contact_email.email' => 'L\'email de contact doit être une adresse email valide.',
                'contact_email.max' => 'L\'email de contact ne peut pas dépasser 255 caractères.',
                'contact_telephone.max' => 'Le téléphone de contact ne peut pas dépasser 20 caractères.',
                'type.required' => 'Le type d\'événement est obligatoire.',
                'type.in' => 'Le type d\'événement sélectionné n\'est pas valide.',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format JPG, JPEG, PNG ou WebP.',
                'image.max' => 'L\'image ne peut pas dépasser 5 Mo.',
                'rapport_url.url' => 'L\'URL du rapport doit être une URL valide.',
                'rapport_url.max' => 'L\'URL du rapport ne peut pas dépasser 255 caractères.',
            ]);

            // Convertir en_vedette et a_la_une en boolean
            $validated['en_vedette'] = $request->has('en_vedette');
            $validated['a_la_une'] = $request->has('a_la_une');

            // Générer un slug unique
            $validated['slug'] = \Str::slug($validated['titre']);
            $originalSlug = $validated['slug'];
            $counter = 1;
            
            while (Evenement::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            if ($request->hasFile('image')) {
                $filename = 'evenements/' . uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
                $path = $request->file('image')->storeAs('images', $filename, 'public');
                $validated['image'] = $path;
            }

            Evenement::create($validated);

            return redirect()->route('admin.evenements.index')
                ->with('alert', '<span class="text-green-600"><i class="fas fa-check-circle mr-2"></i>Événement créé avec succès.</span>');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>Erreur de validation. Veuillez corriger les champs indiqués.</span>');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de l\'événement: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('alert', '<span class="text-red-600"><i class="fas fa-times-circle mr-2"></i>Une erreur inattendue est survenue. Veuillez réessayer.</span>');
        }
    }

    public function show(Evenement $evenement)
    {
        
        $this->authorize('view', $evenement);
        
        return view('admin.evenements.show', compact('evenement'));
    }

    public function edit(Evenement $evenement)
    {
        
        $this->authorize('update', $evenement);
        
        return view('admin.evenements.edit', compact('evenement'));
    }

    public function update(Request $request, Evenement $evenement)
    {
        
        $this->authorize('update', $evenement);
        
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string|max:500',
                'description' => 'required|string',
                'date_evenement' => 'required|date',
                'lieu' => 'nullable|string|max:255',
                'organisateur' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'contact_telephone' => 'nullable|string|max:20',
                'type' => 'required|string|in:conference,seminaire,atelier,formation,table-ronde,colloque,autre',
                'en_vedette' => 'nullable|boolean',
                'a_la_une' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'rapport_url' => 'nullable|url|max:255',
            ], [
                'titre.required' => 'Le titre de l\'événement est obligatoire.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'resume.max' => 'Le résumé ne peut pas dépasser 500 caractères.',
                'description.required' => 'La description de l\'événement est obligatoire.',
                'date_evenement.required' => 'La date de l\'événement est obligatoire.',
                'date_evenement.date' => 'La date de l\'événement doit être une date valide.',
                'lieu.max' => 'Le lieu ne peut pas dépasser 255 caractères.',
                'organisateur.max' => 'Le nom de l\'organisateur ne peut pas dépasser 255 caractères.',
                'contact_email.email' => 'L\'email de contact doit être une adresse email valide.',
                'contact_email.max' => 'L\'email de contact ne peut pas dépasser 255 caractères.',
                'contact_telephone.max' => 'Le téléphone de contact ne peut pas dépasser 20 caractères.',
                'type.required' => 'Le type d\'événement est obligatoire.',
                'type.in' => 'Le type d\'événement sélectionné n\'est pas valide.',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format JPG, JPEG, PNG ou WebP.',
                'image.max' => 'L\'image ne peut pas dépasser 5 Mo.',
                'rapport_url.url' => 'L\'URL du rapport doit être une URL valide.',
                'rapport_url.max' => 'L\'URL du rapport ne peut pas dépasser 255 caractères.',
            ]);

            // Convertir en_vedette et a_la_une en boolean
            $validated['en_vedette'] = $request->has('en_vedette');
            $validated['a_la_une'] = $request->has('a_la_une');

            // Mettre à jour le slug si le titre a changé
            if ($validated['titre'] !== $evenement->titre) {
                $validated['slug'] = \Str::slug($validated['titre']);
                $originalSlug = $validated['slug'];
                $counter = 1;
                
                while (Evenement::where('slug', $validated['slug'])->where('id', '!=', $evenement->id)->exists()) {
                    $validated['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($evenement->image && Storage::disk('public')->exists($evenement->image)) {
                    Storage::disk('public')->delete($evenement->image);
                }

                $filename = 'evenements/' . uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
                $path = $request->file('image')->storeAs('images', $filename, 'public');
                $validated['image'] = $path;
            }

            $evenement->update($validated);

            return redirect()->route('admin.evenements.index')
                ->with('alert', '<span class="text-green-600"><i class="fas fa-check-circle mr-2"></i>Événement mis à jour avec succès.</span>');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>Erreur de validation. Veuillez corriger les champs indiqués.</span>');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour de l\'événement: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('alert', '<span class="text-red-600"><i class="fas fa-times-circle mr-2"></i>Une erreur inattendue est survenue lors de la mise à jour.</span>');
        }
    }

    public function destroy(Evenement $evenement)
    {
        
        $this->authorize('delete', $evenement);
        
        try {
            // Supprimer l'image associée
            if ($evenement->image && Storage::disk('public')->exists($evenement->image)) {
                Storage::disk('public')->delete($evenement->image);
            }

            $evenement->delete();

            return redirect()->route('admin.evenements.index')
                ->with('alert', '<span class="text-green-600">Événement supprimé avec succès.</span>');

        } catch (\Exception $e) {
            return back()
                ->with('alert', '<span class="text-red-600">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Basculer le statut "en vedette" d'un événement
     */
    public function toggleFeatured(Evenement $evenement)
    {
        $this->authorize('update', $evenement);
        
        try {
            $evenement->update([
                'en_vedette' => !$evenement->en_vedette
            ]);

            $message = $evenement->en_vedette 
                ? 'Événement mis en vedette avec succès !'
                : 'Événement retiré de la vedette !';

            return back()->with('alert', '<span class="text-green-600">✅ ' . $message . '</span>');
        } catch (\Exception $e) {
            return back()
                ->with('alert', '<span class="text-red-600">Erreur lors de la modification : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Basculer le statut "publié" d'un événement
     */
    public function togglePublished(Evenement $evenement)
    {
        $this->authorize('moderate', $evenement);
        
        try {
            if ($evenement->isPublished()) {
                $evenement->unpublish();
                $message = 'Événement dépublié avec succès !';
            } else {
                $evenement->publish();
                $message = 'Événement publié avec succès !';
                
                // Déclencher l'événement newsletter uniquement lors de la publication officielle
                // si l'événement est en vedette ET à la une
                if ($evenement->en_vedette && $evenement->a_la_une) {
                    try {
                        EvenementFeaturedCreated::dispatch($evenement);
                        \Log::info('Événement EvenementFeaturedCreated déclenché lors de la publication', [
                            'evenement_id' => $evenement->id,
                            'titre' => $evenement->titre
                        ]);
                    } catch (\Exception $e) {
                        \Log::warning('Erreur lors du déclenchement de l\'événement EvenementFeaturedCreated', [
                            'evenement_id' => $evenement->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            return back()->with('alert', '<span class="text-green-600">✅ ' . $message . '</span>');
        } catch (\Exception $e) {
            return back()
                ->with('alert', '<span class="text-red-600">Erreur lors de la modification : ' . e($e->getMessage()) . '</span>');
        }
    }
}
