@extends('layouts.admin')

@section('title', 'Partenaires')
@section('subtitle', 'Gestion des partenaires')

@section('content')
<div class="container-fluid px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Gestion des Partenaires</h1>
    
    <!-- Statistiques simples -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-600">Total</h3>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-600">Actifs</h3>
            <p class="text-2xl font-bold">{{ $stats['actifs'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-600">Publiés</h3>
            <p class="text-2xl font-bold">{{ $stats['publies'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-600">Universités</h3>
            <p class="text-2xl font-bold">{{ $stats['universites'] }}</p>
        </div>
    </div>

    <!-- Bouton d'ajout -->
    @can('create', App\Models\Partenaire::class)
    <div class="mb-6">
        <a href="{{ route('admin.partenaires.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Ajouter un partenaire
        </a>
    </div>
    @endcan

    <!-- Liste simple -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Liste des partenaires</h2>
        </div>
        <div class="p-4">
            @if($partenaires->count() > 0)
                <div class="space-y-4">
                @foreach($partenaires as $partenaire)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div>
                            <h3 class="font-medium">{{ $partenaire->nom }}</h3>
                            <p class="text-sm text-gray-600">{{ $partenaire->type }} - {{ $partenaire->statut }}</p>
                        </div>
                        <div class="space-x-2">
                            @can('view', $partenaire)
                            <a href="{{ route('admin.partenaires.show', $partenaire) }}" 
                               class="text-blue-600 hover:text-blue-800">Voir</a>
                            @endcan
                            @can('update', $partenaire)
                            <a href="{{ route('admin.partenaires.edit', $partenaire) }}" 
                               class="text-green-600 hover:text-green-800">Modifier</a>
                            @endcan
                        </div>
                    </div>
                @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucun partenaire enregistré.</p>
            @endif
        </div>
    </div>
</div>
@endsection
