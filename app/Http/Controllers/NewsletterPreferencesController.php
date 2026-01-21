<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterPreferencesController extends Controller
{
    protected $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Affiche la page de gestion des préférences
     */
    public function preferences($token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return view('newsletter.preferences-error', [
                'error' => 'Token invalide ou abonnement non trouvé.'
            ]);
        }
        
        return view('newsletter.preferences', [
            'newsletter' => $newsletter
        ]);
    }

    /**
     * Met à jour les préférences
     */
    public function updatePreferences(Request $request, $token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Token invalide.');
        }
        
        $validator = Validator::make($request->all(), [
            'nom' => 'nullable|string|max:255',
            'preferences' => 'nullable|array',
            'preferences.*' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Mettre à jour le nom (garder null si vide)
        $newsletter->update([
            'nom' => $request->nom ?: null
        ]);
        
        // Traiter les préférences
        $preferences = [
            'actualites' => $request->has('preferences.actualites'),
            'publications' => $request->has('preferences.publications'),
            'rapports' => $request->has('preferences.rapports'),
            'evenements' => $request->has('preferences.evenements'),
            'projets' => $request->has('preferences.projets')
        ];
        
        if ($this->newsletterService->updatePreferences($newsletter, $preferences)) {
            return redirect()->back()->with('success', 'Vos informations et préférences ont été mises à jour avec succès.');
        }
        
        return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour.');
    }

    /**
     * Affiche la page de désabonnement
     */
    public function unsubscribe($token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return view('newsletter.unsubscribe-error', [
                'error' => 'Token invalide ou abonnement non trouvé.'
            ]);
        }
        
        if (!$newsletter->actif) {
            return view('newsletter.already-unsubscribed', [
                'newsletter' => $newsletter
            ]);
        }
        
        return view('newsletter.unsubscribe', [
            'newsletter' => $newsletter
        ]);
    }

    /**
     * Traite le désabonnement
     */
    public function processUnsubscribe(Request $request, $token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return redirect()->route('newsletter.unsubscribe', $token)
                ->with('error', 'Token invalide.');
        }
        
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $reason = $request->input('reason');
        
        if ($this->newsletterService->unsubscribe($newsletter, $reason)) {
            return view('newsletter.unsubscribe-success', [
                'newsletter' => $newsletter
            ]);
        }
        
        return redirect()->back()->with('error', 'Une erreur est survenue lors du désabonnement.');
    }

    /**
     * Réabonne un utilisateur
     */
    public function resubscribe($token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return view('newsletter.preferences-error', [
                'error' => 'Token invalide ou abonnement non trouvé.'
            ]);
        }
        
        if ($newsletter->actif) {
            return redirect()->route('newsletter.preferences', $token)
                ->with('info', 'Vous êtes déjà abonné à notre newsletter.');
        }
        
        $newsletter->update([
            'actif' => true,
            'unsubscribe_reason' => null
        ]);
        
        return redirect()->route('newsletter.preferences', $token)
            ->with('success', 'Vous êtes de nouveau abonné à notre newsletter !');
    }
}
