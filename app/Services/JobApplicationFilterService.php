<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class JobApplicationFilterService
{
    /**
     * Appliquer les filtres à la requête
     */
    public function apply(Builder $query, Request $request): Builder
    {
        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par offre d'emploi (gestion slug et ID)
        if ($request->filled('job_offer')) {
            $this->applyJobOfferFilter($query, $request->job_offer);
        }

        // Support de l'ancien paramètre pour compatibilité
        if ($request->filled('job_offer_id')) {
            $query->where('job_offer_id', $request->job_offer_id);
        }

        // Recherche par nom/email
        if ($request->filled('search')) {
            $this->applySearchFilter($query, $request->search);
        }

        return $query;
    }

    /**
     * Appliquer le filtre par offre d'emploi
     */
    private function applyJobOfferFilter(Builder $query, $jobOfferIdentifier): void
    {
        if (is_numeric($jobOfferIdentifier)) {
            // C'est un ID
            $query->where('job_offer_id', $jobOfferIdentifier);
        } else {
            // C'est un slug, trouver l'ID correspondant
            $jobOffer = JobOffer::where('slug', $jobOfferIdentifier)->first();
            if ($jobOffer) {
                $query->where('job_offer_id', $jobOffer->id);
            }
        }
    }

    /**
     * Appliquer le filtre de recherche
     */
    private function applySearchFilter(Builder $query, string $search): void
    {
        $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
