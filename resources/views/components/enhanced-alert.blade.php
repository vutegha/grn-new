@props([
    'type' => 'error',
    'message' => '',
    'title' => '',
    'suggestions' => [],
    'troubleshooting' => [],
    'nextSteps' => [],
    'errorId' => null,
    'dismissible' => true
])

@php
    $bgColor = match($type) {
        'success' => 'bg-green-50 border-green-200',
        'warning' => 'bg-yellow-50 border-yellow-200',
        'info' => 'bg-blue-50 border-blue-200',
        default => 'bg-red-50 border-red-200'
    };
    
    $textColor = match($type) {
        'success' => 'text-green-800',
        'warning' => 'text-yellow-800',
        'info' => 'text-blue-800',
        default => 'text-red-800'
    };
    
    $iconColor = match($type) {
        'success' => 'text-green-400',
        'warning' => 'text-yellow-400',
        'info' => 'text-blue-400',
        default => 'text-red-400'
    };
    
    $icon = match($type) {
        'success' => 'fa-check-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
        default => 'fa-exclamation-circle'
    };
    
    $defaultTitle = match($type) {
        'success' => 'Succès',
        'warning' => 'Attention',
        'info' => 'Information',
        default => 'Erreur détectée'
    };
@endphp

<div class="enhanced-alert {{ $bgColor }} border rounded-lg p-4 mb-6" x-data="{ 
    expanded: false, 
    dismissed: false,
    errorId: '{{ $errorId ?? 'ERR-' . time() }}' 
}" x-show="!dismissed" x-transition>
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas {{ $icon }} {{ $iconColor }}"></i>
        </div>
        
        <div class="ml-3 flex-1">
            <!-- En-tête avec titre et message principal -->
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-sm font-medium {{ $textColor }}">
                        {{ $title ?: $defaultTitle }}
                    </h3>
                    @if($message)
                    <div class="mt-2 text-sm {{ $textColor }}">
                        {{ $message }}
                    </div>
                    @endif
                </div>
                
                @if($dismissible)
                <button @click="dismissed = true" class="ml-4 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
                @endif
            </div>
            
            <!-- Boutons d'action si des détails sont disponibles -->
            @if(!empty($suggestions) || !empty($troubleshooting) || !empty($nextSteps))
            <div class="mt-3 flex space-x-2">
                <button @click="expanded = !expanded" 
                        class="text-xs px-3 py-1 rounded-full border border-current opacity-75 hover:opacity-100 transition-opacity">
                    <i class="fas fa-lightbulb mr-1"></i>
                    <span x-text="expanded ? 'Masquer l\'aide' : 'Voir l\'aide'"></span>
                </button>
                
                @if($errorId)
                <button @click="copyToClipboard('{{ $errorId }}')" 
                        class="text-xs px-3 py-1 rounded-full border border-current opacity-75 hover:opacity-100 transition-opacity">
                    <i class="fas fa-copy mr-1"></i>
                    ID d'erreur
                </button>
                @endif
            </div>
            @endif
            
            <!-- Contenu étendu -->
            <div x-show="expanded" x-collapse class="mt-4 space-y-4">
                
                @if(!empty($suggestions))
                <div class="bg-white bg-opacity-50 rounded-md p-3">
                    <h4 class="text-xs font-semibold {{ $textColor }} uppercase tracking-wide mb-2">
                        <i class="fas fa-lightbulb mr-1"></i> Suggestions
                    </h4>
                    <ul class="text-sm {{ $textColor }} space-y-1">
                        @foreach($suggestions as $suggestion)
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>{{ $suggestion }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if(!empty($troubleshooting))
                <div class="bg-white bg-opacity-50 rounded-md p-3">
                    <h4 class="text-xs font-semibold {{ $textColor }} uppercase tracking-wide mb-2">
                        <i class="fas fa-cogs mr-1"></i> Diagnostic
                    </h4>
                    <ul class="text-sm {{ $textColor }} space-y-1">
                        @foreach($troubleshooting as $item)
                        <li class="flex items-start">
                            <span class="mr-2">🔍</span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if(!empty($nextSteps))
                <div class="bg-white bg-opacity-50 rounded-md p-3">
                    <h4 class="text-xs font-semibold {{ $textColor }} uppercase tracking-wide mb-2">
                        <i class="fas fa-list-ol mr-1"></i> Prochaines étapes
                    </h4>
                    <ol class="text-sm {{ $textColor }} space-y-1">
                        @foreach($nextSteps as $step)
                        <li class="flex items-start">
                            <span class="mr-2 text-xs bg-white bg-opacity-75 rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                {{ $loop->iteration }}
                            </span>
                            <span>{{ $step }}</span>
                        </li>
                        @endforeach
                    </ol>
                </div>
                @endif
                
                @if($errorId)
                <div class="bg-white bg-opacity-25 rounded-md p-2 text-xs {{ $textColor }} border-t">
                    <strong>ID d'erreur :</strong> 
                    <code class="bg-white bg-opacity-50 px-1 rounded">{{ $errorId }}</code>
                    <em class="ml-2">(à communiquer au support technique si nécessaire)</em>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Optionnel : afficher une notification de copie
        console.log('ID d\'erreur copié dans le presse-papier');
    }).catch(function(err) {
        console.error('Erreur lors de la copie : ', err);
    });
}
</script>
