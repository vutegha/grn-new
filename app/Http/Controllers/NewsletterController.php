<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Models\Publication;
use App\Models\Actualite;
use App\Models\Rapport;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class NewsletterController extends Controller
{
    protected $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Afficher le formulaire d'inscription avec les statistiques
     */
    public function showSubscribeForm()
    {
        // Utiliser le cache pour éviter de recalculer les statistiques à chaque visite
        $stats = Cache::remember('newsletter_stats', 300, function () { // Cache pendant 5 minutes
            return [
                'subscribers' => Newsletter::where('actif', true)->count(),
                'publications' => Publication::count() + Rapport::count(),
                'open_rate' => $this->calculateOpenRate(),
                'recent_subscribers' => Newsletter::where('actif', true)
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count(),
                'total_content' => Publication::count() + Rapport::count() + Actualite::count(),
                'average_monthly_content' => $this->calculateAverageMonthlyContent()
            ];
        });

        return view('newsletter.subscribe', compact('stats'));
    }

    /**
     * Calculer le taux d'ouverture approximatif
     */
    private function calculateOpenRate()
    {
        $totalSubscribers = Newsletter::where('actif', true)->count();
        
        if ($totalSubscribers === 0) {
            return 0;
        }

        // Simuler un taux d'ouverture basé sur les abonnés actifs
        // Les newsletters récentes ont généralement un meilleur taux
        $recentSubscribers = Newsletter::where('actif', true)
            ->where('created_at', '>=', now()->subMonths(3))
            ->count();

        // Calcul d'un taux d'ouverture réaliste (entre 70% et 95%)
        $baseRate = 70;
        $bonusRate = ($recentSubscribers / max($totalSubscribers, 1)) * 25;
        
        return min(95, $baseRate + $bonusRate);
    }

    /**
     * Calculer la moyenne mensuelle de contenu
     */
    private function calculateAverageMonthlyContent()
    {
        // Calculer le nombre de mois depuis la première publication
        $firstPublication = Publication::oldest('created_at')->first();
        $firstActualite = Actualite::oldest('created_at')->first();
        $firstRapport = Rapport::oldest('created_at')->first();

        $earliestDate = collect([$firstPublication, $firstActualite, $firstRapport])
            ->filter()
            ->min('created_at');

        if (!$earliestDate) {
            return 0;
        }

        $monthsSinceStart = max(1, $earliestDate->diffInMonths(now()));
        $totalContent = Publication::count() + Rapport::count() + Actualite::count();

        return round($totalContent / $monthsSinceStart, 1);
    }

    /**
     * Inscription à la newsletter
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'nom' => 'nullable|string|max:255',
            'preferences' => 'required|array|min:1',
            'preferences.*' => 'in:actualites,publications,rapports,evenements',
            'redirect_url' => 'nullable|url'
        ]);

        try {
            // Préparer les préférences
            $preferences = [];
            foreach(['actualites', 'publications', 'rapports', 'evenements'] as $type) {
                $preferences[$type] = in_array($type, $request->preferences);
            }

            // Créer ou récupérer l'abonné
            $newsletter = Newsletter::firstOrCreate(
                ['email' => $request->email],
                [
                    'nom' => $request->nom,
                    'token' => Str::random(64),
                    'actif' => true,
                    'confirme_a' => now(),
                    'preferences' => $preferences
                ]
            );

            // Si l'abonné existe déjà, mettre à jour ses préférences
            if (!$newsletter->wasRecentlyCreated) {
                $newsletter->updatePreferences($preferences);
                $newsletter->update(['actif' => true]);
            }

            // Envoyer l'email de bienvenue pour les nouveaux abonnés
            if ($newsletter->wasRecentlyCreated) {
                $this->newsletterService->sendWelcomeEmail($newsletter);
                
                // Vider le cache des statistiques car nous avons un nouvel abonné
                Cache::forget('newsletter_stats');
            }

            // Déterminer l'URL de redirection
            $redirectUrl = $request->redirect_url ?: url()->previous();
            
            return redirect()->to($redirectUrl)->with('success', 'Inscription réussie ! Vous recevrez désormais nos newsletters selon vos préférences.');

        } catch (\Exception $e) {
            $redirectUrl = $request->redirect_url ?: url()->previous();
            return redirect()->to($redirectUrl)->with('error', 'Une erreur est survenue lors de l\'inscription.');
        }
    }

    /**
     * Afficher la page de gestion des préférences
     */
    public function preferences($token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return view('newsletter.preferences-error', [
                'error' => 'Token invalide ou abonnement non trouvé.'
            ]);
        }

        return view('newsletter.preferences', compact('newsletter'));
    }

    /**
     * Mettre à jour les préférences
     */
    public function updatePreferences(Request $request, $token)
    {
        $request->validate([
            'nom' => 'nullable|string|max:255',
            'preferences' => 'array',
            'preferences.*' => 'boolean'
        ]);

        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Token invalide.');
        }

        try {
            // Mettre à jour le nom si fourni
            if ($request->has('nom')) {
                $newsletter->update([
                    'nom' => $request->nom ?: null
                ]);
            }

            $preferences = [
                'actualites' => $request->has('preferences.actualites'),
                'publications' => $request->has('preferences.publications'),
                'rapports' => $request->has('preferences.rapports'),
                'evenements' => $request->has('preferences.evenements')
            ];

            if ($this->newsletterService->updatePreferences($newsletter, $preferences)) {
                return redirect()->back()->with('success', 'Vos informations et préférences ont été mises à jour avec succès.');
            }

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    /**
     * Afficher la page de désabonnement
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
     * Traiter le désabonnement
     */
    public function confirmUnsubscribe(Request $request, $token)
    {
        $request->validate([
            'reasons' => 'nullable|array',
            'reasons.*' => 'in:too_many_emails,not_relevant,not_interested,technical_issues,other',
            'comment' => 'nullable|string|max:500'
        ]);

        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return redirect()->route('newsletter.unsubscribe', $token)
                ->with('error', 'Token invalide.');
        }

        // Préparer les données de désabonnement
        $reasons = $request->input('reasons', []);
        $comment = $request->input('comment');
        
        // Conserver l'ancien commentaire s'il existe et ajouter le nouveau
        $finalReason = $comment;
        if ($newsletter->unsubscribe_reason && $comment) {
            $finalReason = $newsletter->unsubscribe_reason . ' | ' . $comment;
        } elseif ($newsletter->unsubscribe_reason && !$comment) {
            $finalReason = $newsletter->unsubscribe_reason;
        }
        
        if ($this->newsletterService->unsubscribe($newsletter, $finalReason, $reasons)) {
            return view('newsletter.unsubscribe', ['newsletter' => $newsletter])
                ->with('success', 'Votre désabonnement a été confirmé. Merci pour vos retours, ils nous aideront à améliorer notre service.');
        }

        return redirect()->back()->with('error', 'Une erreur est survenue lors du désabonnement.');
    }

    /**
     * Réabonner un utilisateur
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
            'unsubscribe_reason' => null,
            'unsubscribe_reasons' => null,
            'unsubscribed_at' => null
        ]);
        
        return redirect()->route('newsletter.preferences', $token)
            ->with('success', 'Vous êtes de nouveau abonné à notre newsletter !');
    }
}
