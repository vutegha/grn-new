<div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-3">
        <div class="flex-1">
            <div class="flex items-start gap-2">
                <h3 class="font-semibold text-lg text-gray-900">{{ $info->nom }}</h3>
                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $info->type === 'bureau_principal' ? 'bg-blue-100 text-blue-700' : ($info->type === 'bureau_regional' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ $info->type_libelle }}
                </span>
            </div>
            @if($info->titre)
                <p class="text-sm text-gray-600">{{ $info->titre }}</p>
            @endif
        </div>
        <div class="flex items-center space-x-2">
            <!-- Statut actif/inactif -->
            <button onclick="toggleActive({{ $info->id }})" 
                    class="px-3 py-1 rounded-full text-xs font-semibold transition-colors {{ $info->actif ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                {{ $info->actif ? 'Actif' : 'Inactif' }}
            </button>
        </div>
    </div>

    <div class="space-y-2 text-sm mb-4">
        <!-- Adresse -->
        @if($info->adresse)
            <div class="flex items-start">
                <i class="fas fa-map-marker-alt text-iri-primary mt-1 mr-2 w-4"></i>
                <span class="text-gray-700">{{ $info->adresse_complete }}</span>
            </div>
        @endif

        <!-- Coordonnées du bureau (usage interne - grisées) -->
        @if($info->email || $info->telephone || $info->horaires)
            <div class="bg-gray-100 rounded p-2 mt-2">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1 flex items-center">
                    <i class="fas fa-eye-slash mr-1"></i>
                    Coordonnées bureau (usage interne)
                </p>
                
                @if($info->email)
                    <div class="flex items-center mb-1">
                        <i class="fas fa-envelope text-gray-500 mr-2 w-4"></i>
                        <a href="mailto:{{ $info->email }}" class="text-gray-600 hover:underline text-xs">{{ $info->email }}</a>
                    </div>
                @endif

                @if($info->telephone)
                    <div class="flex items-center mb-1">
                        <i class="fas fa-phone text-gray-500 mr-2 w-4"></i>
                        <a href="tel:{{ $info->telephone }}" class="text-gray-600 hover:text-gray-700 text-xs">{{ $info->telephone }}</a>
                    </div>
                @endif

                @if($info->horaires)
                    <div class="flex items-start">
                        <i class="fas fa-clock text-gray-500 mt-0.5 mr-2 w-4"></i>
                        <span class="text-gray-600 text-xs whitespace-pre-line">{{ $info->horaires }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Responsable / Point Focal (affiché publiquement - mis en évidence) -->
        @if($info->responsable_nom)
            <div class="bg-green-50 border-l-4 border-green-600 rounded p-3 mt-3">
                <p class="text-xs font-semibold text-green-700 uppercase mb-2 flex items-center">
                    <i class="fas fa-eye mr-1"></i>
                    Responsable / Point Focal (affiché publiquement)
                </p>
                <div class="flex items-start space-x-3">
                    <!-- Photo -->
                    @if($info->photo)
                        <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 border-2 border-green-600">
                            <img src="{{ asset('storage/' . $info->photo) }}" alt="{{ $info->responsable_nom }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $info->responsable_nom }}</p>
                        @if($info->responsable_fonction)
                            <p class="text-xs text-gray-600 mb-1">{{ $info->responsable_fonction }}</p>
                        @endif
                        @if($info->responsable_email)
                            <div class="flex items-center mb-1">
                                <i class="fas fa-envelope text-green-600 mr-1 text-xs"></i>
                                <a href="mailto:{{ $info->responsable_email }}" class="text-xs text-green-700 hover:underline">{{ $info->responsable_email }}</a>
                            </div>
                        @endif
                        @if($info->responsable_telephone)
                            <div class="flex items-center">
                                <i class="fas fa-phone text-green-600 mr-1 text-xs"></i>
                                <a href="tel:{{ $info->responsable_telephone }}" class="text-xs text-green-700 hover:underline">{{ $info->responsable_telephone }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="flex justify-end space-x-2 pt-3 border-t border-gray-200">
        <a href="{{ route('admin.contact-info.edit', $info) }}" 
           class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm inline-flex items-center">
            <i class="fas fa-edit mr-2"></i>
            Modifier
        </a>
        <form action="{{ route('admin.contact-info.destroy', $info) }}" method="POST" class="inline-block" 
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette information ?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm inline-flex items-center">
                <i class="fas fa-trash mr-2"></i>
                Supprimer
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleActive(id) {
    fetch(`/admin/contact-info/${id}/toggle-active`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la modification');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la modification');
    });
}
</script>
@endpush
