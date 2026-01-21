@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.rapports.index') }}" class="text-white/70 hover:text-white transition-colors duration-200">Rapports</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white font-medium">Nouveau Rapport</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Créer un Rapport')
@section('subtitle', 'Ajouter un nouveau rapport institutionnel à la bibliothèque GRN-UCBC')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Créer un Rapport</h1>
    
    <!-- Messages d'erreur simples -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Erreurs détectées:</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire simple -->
    <form action="{{ route('admin.rapports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Section 1: Informations de base -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
            
            <div class="space-y-4">
                <!-- Titre -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">
                        Titre du rapport *
                    </label>
                    <input type="text" 
                           name="titre" 
                           id="titre" 
                           value="{{ old('titre') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titre') border-red-500 @enderror"
                           placeholder="Entrez le titre du rapport" 
                           required>
                    @error('titre')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Décrivez le contenu du rapport">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Catégorie -->
                <div>
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Catégorie
                    </label>
                    <select name="categorie_id" 
                            id="categorie_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('categorie_id') border-red-500 @enderror">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie_id')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de publication -->
                <div>
                    <label for="date_publication" class="block text-sm font-medium text-gray-700 mb-1">
                        Date de publication *
                    </label>
                    <input type="date" 
                           name="date_publication" 
                           id="date_publication" 
                           value="{{ old('date_publication', now()->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_publication') border-red-500 @enderror"
                           required>
                    @error('date_publication')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Fichier PDF -->
                <div>
                    <label for="fichier" class="block text-sm font-medium text-gray-700 mb-1">
                        Fichier PDF
                    </label>
                    <input type="file" 
                           name="fichier" 
                           id="fichier" 
                           accept=".pdf"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('fichier') border-red-500 @enderror">
                    <p class="text-sm text-gray-500 mt-1">Sélectionnez le fichier PDF du rapport (max 50MB)</p>
                    @error('fichier')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.rapports.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Créer le rapport
            </button>
        </div>
    </form>
</div>
@endsection
