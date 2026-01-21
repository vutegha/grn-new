@extends('layouts.iri')

@section('title', 'Contact')

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section - Compact -->
    <section class="relative bg-gradient-to-r from-iri-primary to-iri-secondary py-12">
@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Contact', 'url' => null]
    ]" />
@endsection
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                <i class="fas fa-envelope mr-3"></i>Contactez-nous
            </h1>
            <p class="text-white/90">Une question ? Nous sommes à votre écoute</p>
        </div>
    </section>

    <!-- Contact Content - Compact Design -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Messages de feedback -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-3"></i>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-10 gap-6">
                <!-- Informations de contact - 30% (3/10) -->
                <div class="lg:col-span-3 space-y-4">
                    <!-- Bureau Principal -->
                    @if(isset($contactInfos['bureau_principal']) && $contactInfos['bureau_principal']->count() > 0)
                        @foreach($contactInfos['bureau_principal'] as $info)
                        <div class="bg-white rounded-lg shadow-md p-5">
                            <h3 class="font-bold text-gray-900 mb-3 flex items-center text-lg">
                                <i class="fas fa-building text-iri-primary mr-2"></i>
                                {{ $info->nom }}
                            </h3>
                            
                            <div class="space-y-3 text-sm">
                                @if($info->adresse)
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt text-iri-primary mt-1 mr-2"></i>
                                    <p class="text-gray-600">{{ $info->adresse_complete }}</p>
                                </div>
                                @endif

                                @if($info->email)
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-iri-primary mr-2"></i>
                                    <a href="mailto:{{ $info->email }}" class="text-iri-primary hover:underline">{{ $info->email }}</a>
                                </div>
                                @endif

                                @if($info->telephone)
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-iri-primary mr-2"></i>
                                    <div>
                                        <a href="tel:{{ $info->telephone }}" class="text-gray-700 hover:text-iri-primary">{{ $info->telephone }}</a>
                                        @if($info->telephone_secondaire)
                                            <br><a href="tel:{{ $info->telephone_secondaire }}" class="text-gray-700 hover:text-iri-primary">{{ $info->telephone_secondaire }}</a>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($info->horaires)
                                <div class="flex items-start">
                                    <i class="fas fa-clock text-iri-primary mt-1 mr-2"></i>
                                    <div class="text-gray-600 whitespace-pre-line">{{ $info->horaires }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif

                    <!-- Réseaux sociaux -->
                    <div class="bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg shadow-md p-5 text-white">
                        <h3 class="font-bold mb-3 flex items-center">
                            <i class="fas fa-share-alt mr-2"></i>
                            Suivez-nous
                        </h3>
                        
                        @php
                            $socialLinks = \App\Models\SocialLink::active()->ordered()->get();
                        @endphp
                        
                        @if($socialLinks->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($socialLinks as $socialLink)
                            <a href="{{ $socialLink->url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               title="{{ $socialLink->name }}"
                               class="bg-white/20 hover:bg-white/30 p-2 rounded transition-all">
                                <i class="{{ $socialLink->icon }}"></i>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Formulaire de contact - 70% (7/10) -->
                <div class="lg:col-span-7 bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-paper-plane text-iri-primary mr-2"></i>
                        Envoyez-nous un message
                    </h2>
                    
                    <form action="{{ route('site.contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom complet *
                                </label>
                                <input type="text" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom') }}"
                                       class="w-full px-3 py-2 border @error('nom') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent"
                                       placeholder="Votre nom"
                                       required>
                                @error('nom')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Adresse email *
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       class="w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent"
                                       placeholder="votre@email.com"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="sujet" class="block text-sm font-medium text-gray-700 mb-1">
                                Sujet *
                            </label>
                            <input type="text" 
                                   id="sujet" 
                                   name="sujet" 
                                   value="{{ old('sujet') }}"
                                   class="w-full px-3 py-2 border @error('sujet') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent"
                                   placeholder="Sujet de votre message"
                                   required>
                            @error('sujet')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                                Message *
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border @error('message') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent resize-none"
                                      placeholder="Votre message..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 hover:shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Envoyer le message
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            En envoyant ce message, vous acceptez de recevoir nos actualités
                        </p>
                    </form>
                </div>
            </div>

            <!-- Bureaux Régionaux avec Points Focaux intégrés -->
            @if(isset($contactInfos['bureau_regional']) && $contactInfos['bureau_regional']->count() > 0)
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-network-wired text-green-800 mr-2"></i>
                    Notre Réseau Régional
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Bureaux Régionaux avec toutes les infos -->
                    @if(isset($contactInfos['bureau_regional']) && $contactInfos['bureau_regional']->count() > 0)
                        @foreach($contactInfos['bureau_regional'] as $bureau)
                        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <!-- En-tête Bureau -->
                            <div class="bg-gradient-to-r from-green-600 to-green-700 p-5">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-white/20 p-3 rounded-lg flex-shrink-0">
                                        <i class="fas fa-building text-white text-xl"></i>
                                    </div>
                                    <div class="text-white flex-1">
                                        <h3 class="font-bold text-lg leading-tight mb-1">{{ $bureau->nom }}</h3>
                                        @if($bureau->ville)
                                            <p class="text-sm text-green-100 mb-2">{{ $bureau->ville }}, {{ $bureau->province }}</p>
                                        @endif
                                        <div class="inline-flex items-center bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">
                                            <i class="fas fa-map-marker-alt text-xs mr-1"></i>
                                            <span class="text-xs font-medium">Bureau Régional</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations du Bureau -->
                            <div class="p-5 space-y-3">
                                <!-- Point Focal du bureau (en premier si renseigné) -->
                                @if($bureau->responsable_nom)
                                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-600">
                                    <div class="flex items-center space-x-2 mb-3">
                                        <div class="bg-green-700 p-2 rounded-full">
                                            <i class="fas fa-user-tie text-white text-sm"></i>
                                        </div>
                                        <h4 class="font-bold text-green-900">Point Focal</h4>
                                    </div>
                                    
                                    <div class="flex items-start space-x-3">
                                        <!-- Photo du responsable -->
                                        @if($bureau->photo)
                                        <div class="w-16 h-16 rounded-full border-3 border-green-600 overflow-hidden flex-shrink-0 shadow-lg">
                                            <img src="{{ asset('storage/' . $bureau->photo) }}" alt="{{ $bureau->responsable_nom }}" class="w-full h-full object-cover">
                                        </div>
                                        @else
                                        <div class="w-16 h-16 rounded-full border-3 border-green-600 overflow-hidden flex-shrink-0 shadow-lg bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                            <i class="fas fa-user text-green-500 text-2xl"></i>
                                        </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <p class="font-bold text-gray-900 mb-1">{{ $bureau->responsable_nom }}</p>
                                            @if($bureau->responsable_fonction)
                                                <p class="text-sm text-gray-600 mb-2">{{ $bureau->responsable_fonction }}</p>
                                            @endif
                                            
                                            @if($bureau->responsable_email)
                                            <div class="flex items-center space-x-2 mb-1">
                                                <i class="fas fa-envelope text-green-600 text-xs"></i>
                                                <a href="mailto:{{ $bureau->responsable_email }}" class="text-xs text-green-700 hover:underline break-all">{{ $bureau->responsable_email }}</a>
                                            </div>
                                            @endif

                                            @if($bureau->responsable_telephone)
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-phone text-green-600 text-xs"></i>
                                                <a href="tel:{{ $bureau->responsable_telephone }}" class="text-xs text-green-700 hover:underline">{{ $bureau->responsable_telephone }}</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($bureau->adresse)
                                <div class="flex items-start space-x-3 group">
                                    <div class="bg-green-100 group-hover:bg-green-200 p-2 rounded-lg flex-shrink-0 transition-colors">
                                        <i class="fas fa-map-marker-alt text-green-700 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Adresse du bureau</p>
                                        <p class="text-sm text-gray-700">{{ $bureau->adresse_complete }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Map Section - Compact -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-map-marked-alt text-iri-primary mr-2"></i>
                Notre Localisation
            </h2>

            <div class="bg-gray-100 rounded-lg shadow-md overflow-hidden">
                <div class="w-full h-64 md:h-80">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d498.5!2d29.4920442!3d0.5299809!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x176100a3749b3145%3A0x7a8c3762f7d03179!2sUniversite%20Chretienne%20Bilingue%20du%20Congo!5e1!3m2!1sfr!2scd!4v1733567890123!5m2!1sfr!2scd"
                        class="w-full h-full"
                        style="border: 0;"
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Localisation UCBC">
                    </iframe>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
