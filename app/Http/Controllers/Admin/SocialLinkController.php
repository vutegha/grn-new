<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', SocialLink::class);

        $socialLinks = SocialLink::query()
            ->ordered()
            ->paginate(20);

        $statistics = [
            'total' => SocialLink::count(),
            'active' => SocialLink::active()->count(),
            'inactive' => SocialLink::where('is_active', false)->count(),
        ];

        return view('admin.social-links.index', compact('socialLinks', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', SocialLink::class);
        return view('admin.social-links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $this->authorize('create', SocialLink::class);
Log::info('=== DÉBUT CRÉATION LIEN SOCIAL ===', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
        ]);

        $this->authorize('create', SocialLink::class);

        $validatedData = $request->validate([
            'platform' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        Log::info('Données validées', $validatedData);

        // Si aucun ordre n'est spécifié, utiliser le prochain ordre disponible
        if (!isset($validatedData['order'])) {
            $validatedData['order'] = SocialLink::max('order') + 1;
        }

        $validatedData['is_active'] = $request->has('is_active');

        Log::info('Données finales avant création', $validatedData);

        try {
            $socialLink = SocialLink::create($validatedData);
            
            Log::info('Lien social créé avec succès', [
                'id' => $socialLink->id,
                'platform' => $socialLink->platform,
                'user_id' => auth()->id(),
            ]);

            return redirect()
                ->route('admin.social-links.index')
                ->with('success', 'Le lien social a été créé avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du lien social', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $validatedData,
                'user_id' => auth()->id(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du lien social: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialLink $socialLink)
    {
        $this->authorize('view', $socialLink);
        return view('admin.social-links.show', compact('socialLink'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialLink $socialLink)
    {
        $this->authorize('update', $socialLink);
        return view('admin.social-links.edit', compact('socialLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialLink $socialLink)
    {
        $this->authorize('update', $socialLink);

        $validatedData = $request->validate([
            'platform' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        try {
            $socialLink->update($validatedData);
            
            Log::info('Lien social modifié', [
                'id' => $socialLink->id,
                'platform' => $socialLink->platform,
                'user_id' => auth()->id(),
            ]);

            return redirect()
                ->route('admin.social-links.index')
                ->with('success', 'Le lien social a été modifié avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la modification du lien social', [
                'error' => $e->getMessage(),
                'id' => $socialLink->id,
                'user_id' => auth()->id(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification du lien social.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialLink $socialLink)
    {
        $this->authorize('delete', $socialLink);

        try {
            $platform = $socialLink->platform;
            $socialLink->delete();
            
            Log::info('Lien social supprimé', [
                'id' => $socialLink->id,
                'platform' => $platform,
                'user_id' => auth()->id(),
            ]);

            return redirect()
                ->route('admin.social-links.index')
                ->with('success', 'Le lien social a été supprimé avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du lien social', [
                'error' => $e->getMessage(),
                'id' => $socialLink->id,
                'user_id' => auth()->id(),
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de la suppression du lien social.');
        }
    }

    /**
     * Basculer l'état actif/inactif d'un lien social (Action de modération)
     */
    public function toggleActive(SocialLink $socialLink)
    {
        // Vérifier les permissions de modération ou de mise à jour
        if (!auth()->user()->can('moderate_social_links') && !auth()->user()->can('update', $socialLink)) {
            abort(403, 'Action non autorisée - Permission de modération requise');
        }

        try {
            $oldStatus = $socialLink->is_active;
            $socialLink->update(['is_active' => !$socialLink->is_active]);
            
            Log::info('Modération lien social - Statut modifié', [
                'id' => $socialLink->id,
                'platform' => $socialLink->platform,
                'ancien_statut' => $oldStatus ? 'actif' : 'inactif',
                'nouveau_statut' => $socialLink->is_active ? 'actif' : 'inactif',
                'action' => $socialLink->is_active ? 'activation' : 'désactivation',
                'moderateur' => auth()->id(),
                'user_name' => auth()->user()->name,
            ]);

            $message = $socialLink->is_active 
                ? 'Le lien social a été activé avec succès.' 
                : 'Le lien social a été désactivé avec succès.';

            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la modération du lien social', [
                'error' => $e->getMessage(),
                'id' => $socialLink->id,
                'platform' => $socialLink->platform,
                'moderateur' => auth()->id(),
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de la modification du statut.');
        }
    }
}
