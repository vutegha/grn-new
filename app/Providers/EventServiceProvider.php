<?php

namespace App\Providers;

use App\Events\PublicationFeaturedCreated;
use App\Events\ActualiteFeaturedCreated;
use App\Events\ProjectCreated;
use App\Events\RapportCreated;
use App\Listeners\SendNewsletterEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        PublicationFeaturedCreated::class => [
            SendNewsletterEmail::class,
        ],
        ActualiteFeaturedCreated::class => [
            SendNewsletterEmail::class,
        ],
        ProjectCreated::class => [
            SendNewsletterEmail::class,
        ],
        RapportCreated::class => [
            SendNewsletterEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Enregistrement explicite des listeners pour s'assurer qu'ils sont chargés
        $events = $this->app['events'];
        
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
