<?php

namespace App\Providers;

use App\Models\Publication;
use App\Models\Actualite;
use App\Models\Service;
use App\Models\Projet;
use App\Models\Evenement;
use App\Models\Rapport;
use App\Models\Media;
use App\Models\Auteur;
use App\Models\Partenaire;
use App\Models\SocialLink;
use App\Models\Contact;
use App\Models\ContactInfo;
use App\Models\Categorie;
use App\Models\Newsletter;
use App\Models\JobOffer;
use App\Models\JobApplication;
use App\Policies\PublicationPolicy;
use App\Policies\ActualitePolicy;
use App\Policies\ServicePolicy;
use App\Policies\ProjetPolicy;
use App\Policies\EvenementPolicy;
use App\Policies\RapportPolicy;
use App\Policies\MediaPolicy;
use App\Policies\AuteurPolicy;
use App\Policies\PartenairePolicy;
use App\Policies\SocialLinkPolicy;
use App\Policies\ContactPolicy;
use App\Policies\ContactInfoPolicy;
use App\Policies\CategoriePolicy;
use App\Policies\NewsletterPolicy;
use App\Policies\JobOfferPolicy;
use App\Policies\JobApplicationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Publication::class => PublicationPolicy::class,
        Actualite::class => ActualitePolicy::class,
        Service::class => ServicePolicy::class,
        Projet::class => ProjetPolicy::class,
        Evenement::class => EvenementPolicy::class,
        Rapport::class => RapportPolicy::class,
        Media::class => MediaPolicy::class,
        Auteur::class => AuteurPolicy::class,
        Partenaire::class => PartenairePolicy::class,
        SocialLink::class => SocialLinkPolicy::class,
        Contact::class => ContactPolicy::class,
        ContactInfo::class => ContactInfoPolicy::class,
        Categorie::class => CategoriePolicy::class,
        Newsletter::class => NewsletterPolicy::class,
        JobOffer::class => JobOfferPolicy::class,
        JobApplication::class => JobApplicationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
