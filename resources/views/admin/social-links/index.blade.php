@extends('layouts.admin')

@section('title', 'Gestion des Liens Sociaux')
@section('subtitle', 'Administration des liens sociaux du site')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium flex items-center">
            <i class="fas fa-share mr-2 text-iri-accent"></i>
            Liens sociaux
        </span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header avec actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Gestion des liens sociaux</h1>
                <p class="text-gray-600">Gérez les liens vers les réseaux sociaux affichés sur le site</p>
            </div>
            
            @can('create', App\Models\SocialLink::class)
            <div class="flex space-x-3">
                <a href="{{ route('admin.social-links.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un lien
                </a>
            </div>
            @endcan
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-share text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['total'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Actifs</p>
                        <p class="text-2xl font-bold text-green-600">{{ $statistics['active'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Inactifs</p>
                        <p class="text-2xl font-bold text-red-600">{{ $statistics['inactive'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des liens sociaux -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($socialLinks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Plateforme
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom d'affichage
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                URL
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ordre
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($socialLinks as $socialLink)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="{{ $socialLink->icon }} text-lg mr-3 {{ $socialLink->color }}"></i>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $socialLink->platform }}</div>
                                        <div class="text-xs text-gray-500">
                                            Créé {{ $socialLink->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $socialLink->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-blue-600 hover:text-blue-800">
                                    <a href="{{ $socialLink->url }}" 
                                       target="_blank" 
                                       class="inline-flex items-center">
                                        {{ Str::limit($socialLink->url, 40) }}
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $socialLink->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-{{ $socialLink->is_active ? 'check' : 'times' }} mr-1"></i>
                                    {{ $socialLink->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $socialLink->order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @can('view', $socialLink)
                                    <a href="{{ route('admin.social-links.show', $socialLink) }}" 
                                       class="text-iri-primary hover:text-iri-secondary transition-colors duration-200"
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('update', $socialLink)
                                    <a href="{{ route('admin.social-links.edit', $socialLink) }}" 
                                       class="text-iri-accent hover:text-iri-gold transition-colors duration-200"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('update', $socialLink)
                                    <form action="{{ route('admin.social-links.toggle-active', $socialLink) }}" 
                                          method="POST" 
                                          class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-{{ $socialLink->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $socialLink->is_active ? 'yellow' : 'green' }}-800 transition-colors duration-200"
                                                title="{{ $socialLink->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-{{ $socialLink->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    @endcan
                                    
                                    @can('delete', $socialLink)
                                    <form action="{{ route('admin.social-links.destroy', $socialLink) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce lien social ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($socialLinks->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $socialLinks->links() }}
            </div>
            @endif
            @else
            <!-- État vide -->
            <div class="text-center py-12">
                <div class="max-w-sm mx-auto">
                    <div class="mb-4">
                        <i class="fas fa-share text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun lien social</h3>
                    <p class="text-gray-500 mb-6">Commencez par ajouter votre premier lien social.</p>
                    @can('create', App\Models\SocialLink::class)
                    <a href="{{ route('admin.social-links.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200 font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un lien
                    </a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
