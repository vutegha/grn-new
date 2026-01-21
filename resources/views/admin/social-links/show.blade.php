@extends('layouts.admin')

@section('title', 'Lien Social - ' . $socialLink->platform)
@section('subtitle', $socialLink->name)

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.social-links.index') }}" 
           class="text-iri-gray hover:text-iri-primary transition-colors duration-200 font-medium">
            Liens sociaux
        </a>
    </li>
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">{{ Str::limit($socialLink->name, 30) }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- En-tête du lien social -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <div class="flex items-center">
                        <i class="{{ $socialLink->icon }} text-white text-3xl mr-4"></i>
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $socialLink->platform }}</h1>
                            <p class="text-iri-light/90">{{ $socialLink->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- URL -->
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-link text-iri-primary text-lg"></i>
                            <a href="{{ $socialLink->url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium flex items-center">
                                {{ $socialLink->url }}
                                <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                            </a>
                        </div>
                        
                        <!-- Statut -->
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-toggle-on text-iri-secondary text-lg"></i>
                            <span class="text-gray-700">Statut :</span>
                            @if($socialLink->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    Inactif
                                </span>
                            @endif
                        </div>
                        
                        <!-- Ordre d'affichage -->
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-sort-numeric-down text-purple-500 text-lg"></i>
                            <span class="text-gray-700">Ordre d'affichage :</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                #{{ $socialLink->order }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-code mr-3"></i>
                        Informations techniques
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Classes CSS -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-palette text-iri-primary mr-2"></i>
                                Classes CSS générées
                            </label>
                            <div class="bg-gray-900 rounded-lg p-4 font-mono text-sm">
                                <div class="text-green-400 mb-2">
                                    <span class="text-gray-500">Icône:</span> {{ $socialLink->icon }}
                                </div>
                                <div class="text-blue-400">
                                    <span class="text-gray-500">Couleur:</span> {{ $socialLink->color }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Aperçu visuel -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-eye text-iri-secondary mr-2"></i>
                                Aperçu visuel
                            </label>
                            <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-200">
                                <div class="flex items-center justify-center space-x-4">
                                    <i class="{{ $socialLink->icon }} {{ $socialLink->color }} text-4xl"></i>
                                    <div class="text-center">
                                        <div class="font-medium text-gray-900">{{ $socialLink->platform }}</div>
                                        <div class="text-sm text-gray-500">{{ $socialLink->name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique système -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-clock mr-3"></i>
                        Historique système
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-iri-gray flex items-center mb-2">
                                <i class="fas fa-calendar-plus text-green-500 mr-2"></i>
                                Créé le {{ $socialLink->created_at->format('d/m/Y à H:i') }}
                            </div>
                            <div class="text-xs text-gray-500 ml-6">
                                {{ $socialLink->created_at->diffForHumans() }}
                            </div>
                        </div>
                        
                        @if($socialLink->updated_at && $socialLink->updated_at != $socialLink->created_at)
                        <div>
                            <div class="text-sm text-iri-gray flex items-center mb-2">
                                <i class="fas fa-calendar-edit text-blue-500 mr-2"></i>
                                Modifié le {{ $socialLink->updated_at->format('d/m/Y à H:i') }}
                            </div>
                            <div class="text-xs text-gray-500 ml-6">
                                {{ $socialLink->updated_at->diffForHumans() }}
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="text-sm text-iri-gray flex items-center">
                            <i class="fas fa-hashtag text-gray-500 mr-2"></i>
                            Identifiant système: <span class="font-mono ml-1">#{{ $socialLink->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Actions principales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-tools mr-3"></i>
                        Actions
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Retour à la liste -->
                    <a href="{{ route('admin.social-links.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à la liste
                    </a>
                    
                    @can('update', $socialLink)
                    <!-- Modifier -->
                    <a href="{{ route('admin.social-links.edit', $socialLink) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-iri-secondary text-white rounded-lg hover:bg-iri-primary transition-colors duration-200 font-medium">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier
                    </a>
                    @endcan
                    
                    <!-- Tester le lien -->
                    <a href="{{ $socialLink->url }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Tester le lien
                    </a>
                </div>
            </div>

            <!-- Actions de modération -->
            @canany(['moderate_social_links', 'update'], $socialLink)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-3"></i>
                        Modération
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Gérer la visibilité de ce lien social sur le site public.
                    </p>
                    
                    <!-- Toggle activation -->
                    <form action="{{ route('admin.social-links.toggle-active', $socialLink) }}" 
                          method="POST" 
                          class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105
                                       {{ $socialLink->is_active 
                                          ? 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700' 
                                          : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700' }}">
                            <i class="fas fa-{{ $socialLink->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                            {{ $socialLink->is_active ? 'Masquer du public' : 'Afficher au public' }}
                        </button>
                    </form>
                    
                    <div class="text-xs text-gray-500 mt-2">
                        @if($socialLink->is_active)
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            Ce lien est actuellement visible sur le site public.
                        @else
                            <i class="fas fa-times-circle text-red-500 mr-1"></i>
                            Ce lien est actuellement masqué du public.
                        @endif
                    </div>
                </div>
            </div>
            @endcanany

            <!-- Informations de statut -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Statut actuel
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Visibilité :</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $socialLink->is_active 
                                           ? 'bg-green-100 text-green-800' 
                                           : 'bg-red-100 text-red-800' }}">
                                {{ $socialLink->is_active ? 'Public' : 'Masqué' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Plateforme :</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $socialLink->platform }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Position :</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Ordre {{ $socialLink->order }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zone de danger -->
            @can('delete', $socialLink)
            <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-red-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Zone dangereuse
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-warning mr-2 text-red-500"></i>
                        La suppression de ce lien social est <strong>irréversible</strong>. 
                        Cette action ne peut pas être annulée.
                    </p>
                    
                    <form action="{{ route('admin.social-links.destroy', $socialLink) }}" 
                          method="POST" 
                          onsubmit="return confirm('⚠️ ATTENTION ⚠️\n\nÊtes-vous absolument sûr de vouloir supprimer définitivement ce lien social ?\n\n• Plateforme: {{ $socialLink->platform }}\n• Nom: {{ $socialLink->name }}\n• URL: {{ $socialLink->url }}\n\nCette action est IRRÉVERSIBLE !')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
