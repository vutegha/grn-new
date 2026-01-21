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
                <span class="text-white">Créer</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.alert />
    
    <form action="{{ route('admin.evenements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-calendar-plus mr-3"></i>
                            Créer un Événement
                        </h1>
                        <p class="text-white/80 mt-1">Ajouter un nouvel événement au calendrier</p>
                    </div>
                    <a href="{{ route('admin.evenements.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Inclusion du formulaire moderne -->
        @include('admin.evenements._form')

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-save mr-3"></i>
                    Actions
                </h3>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('admin.evenements.index') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                        <button type="submit" name="statut" value="brouillon"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer en brouillon
                        </button>
                        
                        <button type="submit" name="statut" value="publie"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Créer et publier
                        </button>
                    </div>
                </div>
                
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Aide :</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Brouillon</strong> : L'événement sera sauvegardé mais pas visible publiquement</li>
                                <li><strong>Publier</strong> : L'événement sera immédiatement visible sur le site</li>
                                <li>Vous pouvez modifier le statut à tout moment après création</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
