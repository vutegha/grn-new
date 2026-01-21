@extends('layouts.admin')

@section('title', 'Détails de l\'auteur')

@section('breadcrumbs')
<li>
    <div class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <a href="{{ route('admin.auteur.index') }}" class="ml-1 text-sm font-medium text-iri-gray hover:text-iri-primary md:ml-2">Auteurs</a>
    </div>
</li>
<li>
    <div class="flex items-center">
        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <span class="ml-1 text-sm font-medium text-iri-gray md:ml-2">{{ Str::limit($auteur->nom, 30) }}</span>
    </div>
</li>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($auteur->photo)
                                    <img class="h-16 w-16 rounded-xl object-cover ring-4 ring-white shadow-lg" 
                                         src="{{ asset('storage/' . $auteur->photo) }}" 
                                         alt="{{ $auteur->nom }}">
                                @else
                                    <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-iri-primary to-iri-secondary flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-xl">
                                            {{ strtoupper(substr($auteur->nom, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-6">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $auteur->nom }} {{ $auteur->prenom }}</h1>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                    @if($auteur->email)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $auteur->email }}
                                        </div>
                                    @endif
                                    @if($auteur->institution)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-6 0H3m2-5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $auteur->institution }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                        @can('update', $auteur)
                        <a href="{{ route('admin.auteur.edit', $auteur) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>
                        @endcan
                        
                        @can('delete', $auteur)
                        <button onclick="confirmDelete()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Publications</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $auteur->publications ? $auteur->publications->count() : 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Membre depuis</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $auteur->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Dernière activité</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $auteur->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biography Section -->
                @if($auteur->biographie)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Biographie</h3>
                    </div>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($auteur->biographie)) !!}
                    </div>
                </div>
                @endif

                <!-- Publications Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Publications ({{ $auteur->publications ? $auteur->publications->count() : 0 }})</h3>
                            </div>
                            <a href="{{ route('admin.publication.create') }}" 
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-iri-primary hover:text-iri-secondary">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Nouvelle publication
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($auteur->publications && $auteur->publications->count() > 0)
                            <div class="space-y-4">
                                @foreach($auteur->publications as $publication)
                                <div class="border border-gray-200 rounded-xl p-4 hover:border-iri-primary/30 hover:bg-iri-primary/5 transition-all duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 mb-2">{{ $publication->titre }}</h4>
                                            @if($publication->resume)
                                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($publication->resume, 200) }}</p>
                                            @endif
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $publication->created_at->format('d/m/Y') }}
                                                </div>
                                                @if($publication->journal)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                                        </svg>
                                                        {{ $publication->journal }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="{{ route('admin.publication.show', $publication) }}" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-iri-primary hover:text-iri-secondary">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune publication</h3>
                                <p class="text-gray-500 mb-6">Cet auteur n'a pas encore de publications enregistrées.</p>
                                <a href="{{ route('admin.publication.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Ajouter une publication
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Author Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informations
                    </h3>
                    <div class="space-y-4">
                        @if($auteur->prenom)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Prénom</dt>
                            <dd class="text-sm text-gray-900">{{ $auteur->prenom }}</dd>
                        </div>
                        @endif
                        
                        @if($auteur->email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">
                                <a href="mailto:{{ $auteur->email }}" class="text-iri-primary hover:text-iri-secondary">
                                    {{ $auteur->email }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        
                        @if($auteur->institution)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Institution</dt>
                            <dd class="text-sm text-gray-900">{{ $auteur->institution }}</dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Profil public</dt>
                            <dd class="text-sm">
                                <div class="flex items-center gap-2">
                                    <input type="text" 
                                           id="publicProfileUrl" 
                                           value="{{ route('site.auteur.show', $auteur->getSlug()) }}" 
                                           readonly 
                                           class="flex-1 px-3 py-2 text-xs border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-iri-primary/50">
                                    <button onclick="copyProfileLink(event)" 
                                            class="inline-flex items-center px-3 py-2 bg-iri-primary text-white text-xs font-medium rounded-lg hover:bg-iri-secondary transition-colors duration-200 flex-shrink-0"
                                            title="Copier le lien">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                                <a href="{{ route('site.auteur.show', $auteur->getSlug()) }}" 
                                   target="_blank"
                                   class="inline-flex items-center mt-2 text-xs text-iri-primary hover:text-iri-secondary">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Ouvrir dans un nouvel onglet
                                </a>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                            <dd class="text-sm text-gray-900">{{ $auteur->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                            <dd class="text-sm text-gray-900">{{ $auteur->updated_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Actions rapides
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.auteur.edit', $auteur) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier le profil
                        </a>
                        
                        <a href="{{ route('admin.publication.create') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nouvelle publication
                        </a>
                        
                        <a href="{{ route('admin.auteur.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="modalContent">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirmer la suppression</h3>
                <p class="text-gray-600">
                    Êtes-vous sûr de vouloir supprimer l'auteur <strong>{{ $auteur->nom }} {{ $auteur->prenom }}</strong> ? 
                </p>
                <p class="text-sm text-red-600 mt-2">Cette action est irréversible et supprimera toutes les données associées.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Annuler
                </button>
                <form action="{{ route('admin.auteur.destroy', $auteur) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyProfileLink(event) {
    const input = document.getElementById('publicProfileUrl');
    const button = event.currentTarget;
    const originalHTML = button.innerHTML;
    
    input.select();
    input.setSelectionRange(0, 99999); // Pour mobile
    
    // Fonction pour afficher le succès
    function showSuccess() {
        button.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        `;
        button.classList.remove('bg-iri-primary', 'hover:bg-iri-secondary');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-iri-primary', 'hover:bg-iri-secondary');
        }, 2000);
    }
    
    // Essayer avec l'API Clipboard moderne
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(input.value).then(() => {
            showSuccess();
        }).catch(err => {
            // Fallback vers la méthode classique
            try {
                document.execCommand('copy');
                showSuccess();
            } catch (e) {
                alert('Impossible de copier le lien. Veuillez le copier manuellement.');
            }
        });
    } else {
        // Méthode classique pour les navigateurs plus anciens
        try {
            document.execCommand('copy');
            showSuccess();
        } catch (err) {
            alert('Impossible de copier le lien. Veuillez le copier manuellement.');
        }
    }
}
</script>

@push('scripts')
<script>
function confirmDelete() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeDeleteModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
});
</script>
@endpush
@endsection
