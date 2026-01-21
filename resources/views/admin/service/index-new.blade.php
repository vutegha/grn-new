@extends('layouts.admin')

@section('title', 'Services')
@section('subtitle', 'Gestion des services GRN-UCBC')

@section('content')

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Services -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-coral bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-gear text-coral text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $services->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Services Actifs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Actifs</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $services->where('statut', 'actif')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Services Inactifs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-pause-circle text-gray-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Inactifs</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $services->where('statut', 'inactif')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Services cette semaine -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Cette semaine</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $services->where('created_at', '>=', now()->subWeek())->count() }}</p>
            </div>
        </div>
    </div>

</div>

<!-- Composant Table Admin -->
<x-admin-table 
    :title="'Services'"
    :subtitle="'Gestion des services GRN-UCBC'"
    :items="$services"
    :create-route="route('admin.service.create')"
    :create-label="'Nouveau Service'"
    :search-placeholder="'Rechercher par nom...'"
    :filters="[
        [
            'name' => 'statut',
            'label' => 'Statut',
            'type' => 'select',
            'options' => [
                'actif' => 'Actif',
                'inactif' => 'Inactif'
            ],
            'selected' => request('statut')
        ]
    ]"
    :headers="['Service', 'Description', 'Statut', 'Date', 'Actions']"
    :empty-message="'Aucun service trouvé'"
    :empty-description="'Commencez par créer votre premier service'"
>
    @foreach($services as $service)
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    @if($service->image)
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->nom }}">
                        </div>
                    @else
                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="bi bi-gear text-gray-500"></i>
                        </div>
                    @endif
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ $service->nom }}</div>
                        <div class="text-sm text-gray-500">{{ $service->slug }}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ Str::limit($service->description, 100) }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($service->statut == 'actif')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="bi bi-check-circle-fill mr-1"></i>
                        Actif
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="bi bi-pause-circle-fill mr-1"></i>
                        Inactif
                    </span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div class="flex flex-col">
                    <span class="font-medium">{{ $service->created_at->format('d/m/Y') }}</span>
                    <span class="text-xs text-gray-400">{{ $service->created_at->format('H:i') }}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end space-x-2">
                    <!-- Voir le service public -->
                    <a href="{{ route('site.service.show', $service->slug) }}" 
                       target="_blank"
                       class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                       title="Voir le service">
                        <i class="bi bi-eye"></i>
                    </a>
                    
                    <!-- Modifier -->
                    <a href="{{ route('admin.service.edit', $service) }}" 
                       class="text-olive hover:text-olive-dark p-1 rounded-md hover:bg-olive-50 transition-colors duration-200"
                       title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    
                    <!-- Supprimer -->
                    <form action="{{ route('admin.service.destroy', $service) }}" 
                          method="POST" 
                          class="inline-block"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
</x-admin-table>

@endsection

@push('scripts')
<script>
    // Script pour les filtres en temps réel
    document.addEventListener('DOMContentLoaded', function() {
        // Soumission automatique du formulaire de filtre
        const filterForm = document.querySelector('#filter-form');
        if (filterForm) {
            const filterInputs = filterForm.querySelectorAll('select, input[type="search"]');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        }
    });
</script>
@endpush
