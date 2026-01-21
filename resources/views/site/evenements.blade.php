@extends('layouts.site')

@section('title', 'Événements - GRN-UCBC')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- En-tête de la page -->
    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">
                    Événements GRN-UCBC
                </h1>
                <p class="text-xl text-white/90 max-w-3xl mx-auto">
                    Découvrez nos prochains événements et restez informé de nos activités
                </p>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($evenements->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($evenements as $evenement)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        @if($evenement->image)
                            <div class="h-48 bg-gray-200 overflow-hidden">
                                <img src="{{ asset('storage/' . $evenement->image) }}" 
                                     alt="{{ $evenement->titre }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <x-evenement-badge :evenement="$evenement" />
                                @if($evenement->date_evenement)
                                    <time class="text-sm text-gray-500">
                                        {{ $evenement->date_evenement->format('d/m/Y') }}
                                    </time>
                                @endif
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
                                {{ $evenement->titre }}
                            </h3>
                            
                            @if($evenement->resume)
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ $evenement->resume }}
                                </p>
                            @elseif($evenement->description)
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ Str::limit($evenement->description, 150) }}
                                </p>
                            @endif
                            
                            @if($evenement->lieu)
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $evenement->lieu }}
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ route('site.evenement.show', $evenement->slug) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white text-sm font-medium rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                                    <i class="fas fa-calendar-plus mr-2"></i>
                                    Voir les détails
                                </a>
                                
                                @if($evenement->organisateur)
                                    <span class="text-xs text-gray-500">
                                        Par {{ $evenement->organisateur }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($evenements->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $evenements->links() }}
                </div>
            @endif
        @else
            <!-- État vide -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-iri-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-alt text-4xl text-iri-primary"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                        Aucun événement disponible
                    </h3>
                    <p class="text-gray-600">
                        Nous n'avons actuellement aucun événement programmé. 
                        Revenez bientôt pour découvrir nos prochaines activités.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
