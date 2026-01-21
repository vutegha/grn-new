{{-- Composant table admin réutilisable --}}
@props([
    'title' => '',
    'createRoute' => '',
    'createLabel' => 'Nouveau',
    'searchPlaceholder' => 'Rechercher...',
    'filters' => [],
    'columns' => [],
    'data' => collect(),
    'pagination' => null,
    'emptyMessage' => 'Aucun élément trouvé',
    'emptyDescription' => 'Commencez par créer votre premier élément.'
])

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    
    <!-- Header avec titre et actions -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                {{ $title }}
            </h3>
            @if($createRoute)
                <div class="mt-4 sm:mt-0">
                    <a href="{{ $createRoute }}" 
                       class="inline-flex items-center justify-center w-10 h-10 bg-coral text-white rounded-full hover:bg-coral-dark transition-colors duration-200 shadow-sm"
                       title="{{ $createLabel }}">
                        <i class="fas fa-plus text-sm"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Filtres et recherche -->
    @if(!empty($filters) || $searchPlaceholder)
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            
            <!-- Barre de recherche -->
            @if($searchPlaceholder)
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="{{ $searchPlaceholder }}"
                               class="w-full pl-10 pr-4 py-2 border-2 border-gray-300 rounded-md focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200"
                               aria-label="Recherche">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filtres supplémentaires -->
            @foreach($filters as $filter)
                <div class="min-w-0 sm:w-48">
                    <select name="{{ $filter['name'] }}" 
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200"
                            aria-label="{{ $filter['label'] }}">
                        <option value="">{{ $filter['label'] }}</option>
                        @foreach($filter['options'] as $value => $label)
                            <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach

            <!-- Bouton de recherche -->
            <div>
                <button type="submit" 
                        class="px-6 py-2 bg-coral text-white rounded-md hover:bg-coral-dark focus:outline-none focus:ring-2 focus:ring-coral focus:ring-offset-2 transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Filtrer
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Table responsive -->
    <div class="overflow-x-auto max-h-[calc(100vh-16rem)] overflow-y-auto">
        <table class="min-w-full">
            
            <!-- En-têtes fixes -->
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr class="border-b border-gray-200">
                    @foreach($columns as $column)
                        <th scope="col" 
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $column['class'] ?? '' }}">
                            {{ $column['label'] }}
                        </th>
                    @endforeach
                    <th scope="col" 
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>

            <!-- Corps de la table -->
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($data as $index => $item)
                    <tr class="hover:bg-gray-50 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-25' }} transition-colors duration-150">
                        @foreach($columns as $column)
                            <td class="px-6 py-4 {{ $column['class'] ?? 'whitespace-nowrap' }}">
                                @if(isset($column['component']))
                                    {{ $column['component']($item) }}
                                @else
                                    {{ data_get($item, $column['field']) }}
                                @endif
                            </td>
                        @endforeach
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-1">
                                {{ $actions($item) ?? '' }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 1 }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $emptyMessage }}</h3>
                                <p class="text-gray-500 mb-4">{{ $emptyDescription }}</p>
                                @if($createRoute)
                                    <a href="{{ $createRoute }}" 
                                       class="inline-flex items-center px-4 py-2 bg-coral text-white text-sm font-medium rounded-md hover:bg-coral-dark transition-colors duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        {{ $createLabel }}
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($pagination && $pagination->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de {{ $pagination->firstItem() }} à {{ $pagination->lastItem() }} 
                sur {{ $pagination->total() }} résultats
            </div>
            <div>
                {{ $pagination->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Boutons d'action standardisés --}}
@php
    function actionButton($url, $icon, $color, $title) {
        return "<a href='{$url}' class='inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-{$color}-100 text-{$color}-600 hover:text-{$color}-800 transition-colors duration-200' title='{$title}'><i class='fas fa-{$icon} text-sm'></i></a>";
    }
    
    function deleteButton($action, $title = 'Supprimer', $confirmMessage = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
        return "<form method='POST' action='{$action}' class='inline' onsubmit='return confirm(\"{$confirmMessage}\")'>" .
               csrf_field() . method_field('DELETE') .
               "<button type='submit' class='inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-red-100 text-red-600 hover:text-red-800 transition-colors duration-200' title='{$title}'><i class='fas fa-trash text-sm'></i></button>" .
               "</form>";
    }
@endphp
