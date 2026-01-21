@props([
    'action' => '',
    'method' => 'POST',
    'title' => '',
    'subtitle' => '',
    'cancelRoute' => null,
    'submitLabel' => 'Enregistrer',
    'showCancel' => true
])

<div class="space-y-6">
    
    <!-- Header -->
    @if($title)
        <div class="border-b border-gray-200 pb-4">
            <h1 class="text-2xl font-semibold text-gray-900">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" enctype="multipart/form-data" class="space-y-6">
        @if($method !== 'GET')
            @csrf
        @endif
        
        @if(!in_array($method, ['GET', 'POST']))
            @method($method)
        @endif

        <!-- Contenu du formulaire -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            {{ $slot }}
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            @if($showCancel && $cancelRoute)
                <a href="{{ $cancelRoute }}" 
                   class="btn-secondary">
                    <i class="bi bi-x-lg mr-2"></i>
                    Annuler
                </a>
            @endif
            <button type="submit" 
                    class="btn-primary">
                <i class="bi bi-check-lg mr-2"></i>
                {{ $submitLabel }}
            </button>
        </div>
    </form>

</div>
