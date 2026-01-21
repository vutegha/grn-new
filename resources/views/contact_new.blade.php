@extends('layouts.iri')

@section('title', 'Contact')

@section('content')
<!-- Breadcrumb -->
@include('partials.breadcrumb', [
    'breadcrumbs' => [
        ['title' => 'Contact', 'url' => null]
    ]
])

<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 drop-shadow-2xl">
                Contactez-nous
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                N'hésitez pas à nous contacter pour toute question ou demande d'information
            </p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Messages de feedback -->
            @if(session('success'))
                <div class="mb-8 bg-green-50 border-l-4 border-green-500 text-green-700 p-6 rounded-r-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-full mr-4">
                            <i class="fas fa-check text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Message envoyé avec succès !</h3>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-r-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-2 rounded-full mr-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Erreur</h3>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 sticky top-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                            <i class="fas fa-address-book text-iri-primary mr-3"></i>
                            Nos coordonnées
                        </h2>
                        
                        <div class="space-y-6">
                            <!-- Address -->
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-r from-iri-primary to-iri-secondary p-3 rounded-lg flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Adresse</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        GRN-UCBC<br>
                                        Beni, Nord-Kivu<br>
                                        République Démocratique du Congo
                                    </p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-r from-iri-accent to-iri-gold p-3 rounded-lg flex-shrink-0">
                                    <i class="fas fa-envelope text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Email</h3>
                                    <a href="mailto:grn@ucbc.org" class="text-iri-primary hover:text-iri-secondary transition-colors duration-200">
                                        contact@grn-ucbc.org
                                    </a>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-r from-iri-secondary to-iri-primary p-3 rounded-lg flex-shrink-0">
                                    <i class="fas fa-phone text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Téléphone</h3>
                                    <p class="text-gray-600">+243 XXX XXX XXX</p>
                                </div>
                            </div>

                            <!-- Working Hours -->
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-r from-iri-gold to-iri-accent p-3 rounded-lg flex-shrink-0">
                                    <i class="fas fa-clock text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Heures d'ouverture</h3>
                                    <div class="text-gray-600 text-sm">
                                        <p>Lundi - Vendredi : 8h00 - 17h00</p>
                                        <p>Samedi : 8h00 - 12h00</p>
                                        <p>Dimanche : Fermé</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="font-bold text-gray-900 mb-4">Suivez-nous</h3>
                            <div class="flex space-x-3">
                                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg transition-colors duration-200">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="bg-blue-400 hover:bg-blue-500 text-white p-3 rounded-lg transition-colors duration-200">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="bg-blue-800 hover:bg-blue-900 text-white p-3 rounded-lg transition-colors duration-200">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-lg transition-colors duration-200">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center">
                            <i class="fas fa-paper-plane text-iri-primary mr-3"></i>
                            Envoyez-nous un message
                        </h2>
                        
                        <form action="{{ route('site.contact.store') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="nom" class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class="fas fa-user mr-2 text-iri-primary"></i>
                                        Nom complet *
                                    </label>
                                    <input type="text" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom') }}"
                                           class="w-full px-4 py-3 border @error('nom') border-red-500 bg-red-50 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200"
                                           placeholder="Votre nom complet"
                                           required>
                                    @error('nom')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                                        <i class="fas fa-envelope mr-2 text-iri-primary"></i>
                                        Adresse email *
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           class="w-full px-4 py-3 border @error('email') border-red-500 bg-red-50 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200"
                                           placeholder="votre@email.com"
                                           required>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="sujet" class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-iri-primary"></i>
                                    Sujet *
                                </label>
                                <input type="text" 
                                       id="sujet" 
                                       name="sujet" 
                                       value="{{ old('sujet') }}"
                                       class="w-full px-4 py-3 border @error('sujet') border-red-500 bg-red-50 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200"
                                       placeholder="Sujet de votre message"
                                       required>
                                @error('sujet')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-comment mr-2 text-iri-primary"></i>
                                    Message *
                                </label>
                                <textarea id="message" 
                                          name="message" 
                                          rows="6" 
                                          class="w-full px-4 py-3 border @error('message') border-red-500 bg-red-50 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200 resize-none"
                                          placeholder="Votre message détaillé..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Information Notice -->
                            <div class="bg-gradient-to-r from-iri-primary/10 to-iri-secondary/10 border border-iri-primary/20 p-6 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-iri-primary p-2 rounded-full flex-shrink-0">
                                        <i class="fas fa-info text-white text-sm"></i>
                                    </div>
                                    <div class="text-sm text-gray-700">
                                        <h4 class="font-bold text-iri-primary mb-1">Information importante :</h4>
                                        <p>En envoyant ce message, votre adresse email sera automatiquement ajoutée à notre liste de diffusion pour recevoir nos actualités et newsletters. Vous pourrez vous désabonner à tout moment.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" class="w-full bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary text-white font-bold py-4 px-8 rounded-lg transition-all duration-200 transform hover:scale-105 hover:shadow-lg flex items-center justify-center space-x-3">
                                    <i class="fas fa-paper-plane text-lg"></i>
                                    <span class="text-lg">Envoyer le message</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section (Optional) -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Notre localisation
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Retrouvez-nous au cœur de Beni, Nord-Kivu
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 h-96">
                <!-- Placeholder for map - replace with actual map integration -->
                <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <div class="bg-iri-primary p-4 rounded-full inline-block mb-4">
                            <i class="fas fa-map-marked-alt text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">Carte interactive</h3>
                        <p class="text-gray-500">Intégration Google Maps à venir</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
