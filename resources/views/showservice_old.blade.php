@extends('layouts.iri')

@section('title', $service->exists ? 'Service - ' . $service->nom : 'Service introuvable')

@section('content')
<div class="min-h-screen bg-light-gray">

    @if($service->exists)
        <!-- Hero Section avec image principale -->
        <div class="relative h-96 bg-gradient-to-r from-coral to-olive overflow-hidden">
            @if($service->image)
                <img src="{{ asset('storage/'.$service->image) }}" 
                    alt="{{ $service->nom }}" 
                    class="absolute inset-0 w-full h-full object-cover opacity-70">
                <div class="absolute inset-0 bg-black opacity-50"></div>
            @endif
            
            <div class="relative z-10 flex items-center justify-center h-full">
                <div class="text-center text-white px-6">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow-lg text-white">{{ $service->nom }}</h1>
                </div>
            </div>
        </div>

        <!-- Section résumé sous l'image -->
        <div class="bg-white py-8">
            <div class="max-w-4xl mx-auto px-4">
                <div class="text-center">
                    <p class="text-xl md:text-2xl font-light text-olive leading-relaxed max-w-3xl mx-auto">
                        {{ $service->resume ?? $service->description }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Section description détaillée -->
        <div class="max-w-4xl mx-auto px-4 py-16">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-grayish">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-olive mb-6">À propos de ce service</h2>
                </div>

                @if($service->contenu)
                    <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
                        {!! nl2br(e($service->contenu)) !!}
                    </div>
                @else
                    <div class="text-olive text-lg leading-relaxed">
                        <p>{{ $service->resume ?? $service->description }}</p>
                        <p class="mt-4 text-olive">Ce service fait partie intégrante de notre mission d'accompagnement et de développement. Notre équipe expérimentée met tout en œuvre pour fournir des solutions adaptées et de qualité.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section projets -->
        @if($service->projets->count())
            <div class="bg-beige py-16">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold text-olive mb-4">Nos Projets</h2>
                        <p class="text-xl text-olive">Découvrez les réalisations concrètes de ce service</p>
                        <div class="w-24 h-1 bg-coral mx-auto mt-4"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        @foreach($service->projets as $projet)
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                                <!-- En-tête du projet -->
                                <div class="bg-gradient-to-r from-olive to-light-green p-6 text-white">
                                    <h3 class="text-2xl font-bold mb-2 drop-shadow">{{ $projet->nom }}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-white/90">
                                        @if($projet->date_debut)
                                            <span>📅 {{ \Carbon\Carbon::parse($projet->date_debut)->format('M Y') }}</span>
                                        @endif
                                        @if($projet->etat)
                                            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur">{{ ucfirst($projet->etat) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Description du projet -->
                                <div class="p-6">
                                    <p class="text-gray-800 leading-relaxed mb-6 text-base">{{ $projet->description }}</p>
                                    
                                    @if($projet->resume)
                                        <div class="bg-light-green/10 border-l-4 border-light-green p-4 rounded-r-lg mb-6">
                                            <p class="text-sm text-gray-700 italic">{{ $projet->resume }}</p>
                                        </div>
                                    @endif

                                    <!-- Galerie de médias -->
                                    @if($projet->medias && $projet->medias->count())
                                        <div class="space-y-4">
                                            <h4 class="text-lg font-semibold text-olive flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                                Galerie du projet ({{ $projet->medias->count() }} {{ $projet->medias->count() > 1 ? 'médias' : 'média' }})
                                            </h4>
                                            
                                            <div class="grid grid-cols-2 gap-4">
                                                @foreach($projet->medias as $media)
                                                    <div class="group relative">
                                                        <div class="aspect-square overflow-hidden rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-300 border border-gray-200">
                                                            @if($media->type === 'image')
                                                                <img src="{{ asset('storage/'.$media->medias) }}" 
                                                                    alt="{{ $media->titre ?: 'Image du projet' }}" 
                                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                            @elseif($media->type === 'video')
                                                                <video class="w-full h-full object-cover" controls>
                                                                    <source src="{{ asset('storage/'.$media->medias) }}" type="video/mp4">
                                                                    Votre navigateur ne supporte pas la vidéo.
                                                                </video>
                                                            @else
                                                                <div class="w-full h-full bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                                                                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                            
                                                            <!-- Overlay avec informations -->
                                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-300 flex items-end">
                                                                <div class="p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                                    @if($media->titre)
                                                                        <h5 class="font-semibold text-sm mb-1">{{ $media->titre }}</h5>
                                                                    @endif
                                                                    @if($media->description)
                                                                        <p class="text-xs mb-2 opacity-90">{{ Str::limit($media->description, 50) }}</p>
                                                                    @endif
                                                                    <p class="text-xs opacity-75">
                                                                        {{ ucfirst($media->type ?: 'Média') }}
                                                                        @if($media->created_at)
                                                                            • {{ $media->created_at->format('d/m/Y') }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Description du média -->
                                                        <div class="mt-3">
                                                            @if($media->titre)
                                                                <p class="text-sm font-medium text-gray-900 mb-1">{{ $media->titre }}</p>
                                                            @endif
                                                            @if($media->description)
                                                                <p class="text-xs text-gray-600 leading-relaxed">{{ Str::limit($media->description, 80) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-gray-600">
                                            <svg class="w-16 h-16 mx-auto mb-4 opacity-60 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                            <p class="italic text-base">Aucun média disponible pour ce projet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 py-16">
                <div class="max-w-4xl mx-auto px-4 text-center">
                    <div class="bg-white rounded-2xl p-12 shadow-lg border border-gray-100">
                        <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-700 mb-4">Projets à venir</h3>
                        <p class="text-gray-600 text-lg">Ce service ne dispose pas encore de projets réalisés, mais restez connectés pour découvrir nos futures réalisations !</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Section Actualités liées au service -->
        @if($service->actualites->count() > 0)
            <div class="bg-light-green py-16">
                <div class="max-w-6xl mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-olive mb-4">Actualités du service</h2>
                        <p class="text-olive text-lg max-w-3xl mx-auto">
                            Découvrez les dernières nouvelles et événements liés à ce service
                        </p>
                    </div>

                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($service->actualites->take(6) as $actualite)
                            <article class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                                @if($actualite->image)
                                    <div class="relative overflow-hidden">
                                        <img src="{{ asset('storage/' . $actualite->image) }}" 
                                             alt="{{ $actualite->titre }}" 
                                             class="h-48 w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>
                                @endif

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-3 leading-tight">
                                        {{ Str::limit($actualite->titre, 60) }}
                                    </h3>

                                    <p class="text-olive mb-4 leading-relaxed">
                                        {{ Str::limit($actualite->resume, 100) }}
                                    </p>

                                    <div class="flex items-center justify-between pt-4 border-t border-grayish">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-olive" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-olive">
                                                {{ $actualite->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>

                                        <a href="{{ route('site.actualite.id', ['id' => $actualite->id]) }}"
                                           class="inline-flex items-center space-x-1 text-coral hover:text-olive font-semibold text-sm transition-colors">
                                            <span>Lire plus</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($service->actualites->count() > 6)
                        <div class="text-center mt-8">
                            <a href="{{ route('site.actualites') }}?service={{ $service->id }}" 
                               class="btn-coral inline-flex items-center space-x-2 font-semibold px-6 py-3 rounded-lg transition-all duration-200 hover:shadow-md">
                                <span>Voir toutes les actualités de ce service</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Section contenu détaillé en bas de page -->
        @if($service->contenu)
            <div class="bg-white py-16 border-t border-grayish">
                <div class="max-w-4xl mx-auto px-4">
                    <div class="bg-beige rounded-2xl p-8 border border-grayish">
                        <h2 class="text-3xl font-bold text-olive mb-8 text-center">Informations détaillées</h2>
                        <div class="prose prose-lg max-w-none text-olive leading-relaxed">
                            {!! nl2br(e($service->contenu)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif

@else
    <!-- Si $service n'est pas trouvé -->
        <div class="min-h-screen flex items-center justify-center px-4">
            <div class="bg-red-50 border-l-8 border-red-500 text-red-700 p-8 rounded-2xl shadow-xl max-w-2xl mx-auto">
                <div class="flex items-center mb-4">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <h2 class="text-2xl font-bold">Service introuvable</h2>
                </div>
                <p class="mb-6 text-lg">Nous n'avons pas pu trouver les informations pour ce service. Vérifiez l'URL ou retournez à la liste des services.</p>
                <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-coral text-white font-semibold rounded-lg hover:bg-coral/90 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
