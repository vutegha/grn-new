@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.evenements.index') }}" class="text-white/70 hover:text-white">Événements</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ Str::limit($evenement->titre, 30) }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.alert />
    
    <form action="{{ route('admin.evenements.update', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-edit mr-3"></i>
                            Modifier l'Événement
                        </h1>
                        <p class="text-white/80 mt-1">Modification : "{{ Str::limit($evenement->titre, 60) }}"</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.evenements.show', $evenement) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            Voir
                        </a>
                        <a href="{{ route('admin.evenements.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inclusion du formulaire moderne -->
        @include('admin.evenements._form')

        <!-- Actions finales -->
        <div class="flex items-center justify-between p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <a href="{{ route('admin.evenements.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
            
            <div class="flex space-x-4">
                <a href="{{ route('admin.evenements.show', $evenement) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                    <i class="fas fa-eye mr-2"></i>
                    Voir les détails
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Mettre à jour l'événement
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
