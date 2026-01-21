@props([
    'title' => '',
    'subtitle' => '',
    'createRoute' => null,
    'createLabel' => 'Nouveau',
    'searchPlaceholder' => 'Rechercher...',
    'showSearch' => true,
    'showFilters' => true,
    'filters' => [],
    'stats' => []
])

<div class="space-y-6">
    
    <!-- Header avec titre et bouton d'action -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        @if($createRoute)
            <a href="{{ $createRoute }}" 
               class="inline-flex items-center px-4 py-2 bg-coral hover:bg-coral/90 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                <i class="bi bi-plus mr-2"></i>
                {{ $createLabel }}
            </a>
        @endif
    </div>

    <!-- Statistiques -->
    @if(count($stats) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($stats as $stat)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 {{ $stat['bg_color'] ?? 'bg-coral' }} bg-opacity-10 rounded-lg flex items-center justify-center">
                                <i class="bi {{ $stat['icon'] ?? 'bi-bar-chart' }} {{ $stat['icon_color'] ?? 'text-coral' }} text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Formulaire de recherche et filtres -->
    @if($showSearch || $showFilters)
        <form method="GET" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                <!-- Champ de recherche -->
                @if($showSearch)
                    <div class="lg:col-span-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}" 
                                   placeholder="{{ $searchPlaceholder }}"
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200">
                        </div>
                    </div>
                @endif

                <!-- Filtres dynamiques -->
                @if($showFilters && count($filters) > 0)
                    @foreach($filters as $filter)
                        <div class="lg:col-span-2">
                            <label for="{{ $filter['name'] }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $filter['label'] }}
                            </label>
                            @if($filter['type'] === 'select')
                                <select name="{{ $filter['name'] }}" 
                                        id="{{ $filter['name'] }}"
                                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200">
                                    @foreach($filter['options'] as $value => $label)
                                        <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif($filter['type'] === 'date')
                                <input type="date" 
                                       name="{{ $filter['name'] }}" 
                                       id="{{ $filter['name'] }}"
                                       value="{{ request($filter['name']) }}"
                                       class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200">
                            @endif
                        </div>
                    @endforeach
                @endif

                <!-- Boutons d'action -->
                <div class="lg:col-span-2 flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 inline-flex justify-center items-center px-4 py-2.5 bg-coral hover:bg-coral/90 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="bi bi-search mr-2"></i>
                        Filtrer
                    </button>
                    @if(request()->hasAny(['search', ...(collect($filters)->pluck('name')->toArray())]))
                        <a href="{{ url()->current() }}" 
                           class="inline-flex items-center px-3 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    @endif

    <!-- Contenu principal -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        {{ $slot }}
    </div>

</div>

<style>
/* Styles pour les inputs harmonisés */
.form-input {
    @apply block w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200;
}

.form-select {
    @apply block w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200;
}

.form-textarea {
    @apply block w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200 resize-none;
}

.form-label {
    @apply block text-sm font-medium text-gray-700 mb-2;
}

.btn-primary {
    @apply inline-flex items-center px-4 py-2.5 bg-coral hover:bg-coral/90 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md;
}

.btn-secondary {
    @apply inline-flex items-center px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200;
}

.btn-success {
    @apply inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200;
}

.btn-warning {
    @apply inline-flex items-center px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200;
}

.btn-danger {
    @apply inline-flex items-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200;
}
</style>
