<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Service;

class ServiceMenuProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les services avec toutes les vues pour le menu dynamique
        View::composer('*', function ($view) {
            $services = Service::select('nom', 'nom_menu', 'slug')
                              ->where('is_published', true)
                              ->where('show_in_menu', true)
                              ->orderBy('nom')
                              ->get()
                              ->map(function ($service) {
                                  // Si nom_menu est vide, utiliser le nom principal
                                  $service->display_name = !empty(trim($service->nom_menu)) 
                                      ? trim($service->nom_menu) 
                                      : $service->nom;
                                  return $service;
                              });
            
            $view->with('menuServices', $services);
        });
    }
}
