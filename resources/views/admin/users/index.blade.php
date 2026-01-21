@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Utilisateurs</h1>
            <p class="text-gray-600 mt-1">Gérer les comptes utilisateurs, rôles et permissions</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouvel Utilisateur
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Rechercher par nom ou email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="role" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Filtrer
            </button>
            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('admin.users.index') }}" 
                   class="text-gray-600 hover:text-gray-800 px-3 py-2">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Utilisateurs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->total() }}</p>
                </div>
            </div>
        </div>

        @foreach($roleStats as $roleName => $count)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ ucfirst($roleName) }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $count }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Table des utilisateurs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rôles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Inscription
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($role->name === 'admin') bg-red-100 text-red-800
                                        @elseif($role->name === 'moderator') bg-yellow-100 text-yellow-800
                                        @elseif($role->name === 'editor') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-500 italic">Aucun rôle</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 user-status" data-user-id="{{ $user->id }}">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 user-status" data-user-id="{{ $user->id }}">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Bouton toggle status avec modal -->
                                <button type="button" 
                                        onclick="openStatusModal({{ $user->id }}, {{ json_encode($user->name) }}, {{ $user->active ? 'true' : 'false' }}, {{ json_encode($user->roles->pluck('id')->toArray()) }})"
                                        class="toggle-status-btn {{ $user->active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        data-user-active="{{ $user->active ? '1' : '0' }}"
                                        data-user-roles="{{ json_encode($user->roles->pluck('id')->toArray()) }}"
                                        title="{{ $user->active ? 'Gérer le statut' : 'Activer et attribuer un rôle' }}">
                                    @if($user->active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </button>
                                
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Voir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.users.manage-permissions', $user) }}" 
                                   class="text-green-600 hover:text-green-900" title="Gérer les permissions">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900" 
                                            title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun utilisateur</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouvel utilisateur.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.users.create') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Nouvel Utilisateur
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50" 
     x-data="{ show: true }" 
     x-show="show" 
     x-transition 
     x-init="setTimeout(() => show = false, 5000)">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50" 
     x-data="{ show: true }" 
     x-show="show" 
     x-transition 
     x-init="setTimeout(() => show = false, 5000)">
    {{ session('error') }}
</div>
@endif

<!-- Modal pour gérer le statut et les rôles -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header du modal -->
            <div class="flex items-center justify-between pb-3">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">
                    Gérer le statut utilisateur
                </h3>
                <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Corps du modal -->
            <form id="statusForm" onsubmit="submitStatusChange(event)">
                <div class="mb-4">
                    <p class="text-sm text-gray-700 mb-3" id="modalMessage">
                        Voulez-vous modifier le statut de cet utilisateur ?
                    </p>
                    
                    <!-- Sélection du statut -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="active" value="1" class="mr-2">
                                <span class="text-sm text-green-700">Actif</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="active" value="0" class="mr-2">
                                <span class="text-sm text-red-700">Inactif</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Sélection des rôles -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rôles</label>
                        <div class="max-h-32 overflow-y-auto border border-gray-200 rounded p-2">
                            @foreach($roles as $role)
                            <label class="flex items-center mb-1">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mr-2">
                                <span class="text-sm">{{ ucfirst($role->name) }}</span>
                            </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Sélectionnez un ou plusieurs rôles pour cet utilisateur</p>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeStatusModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            id="submitButton">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Variables globales pour le modal
let currentUserId = null;

// Fonction pour ouvrir le modal de gestion du statut
function openStatusModal(userId, userName, isActive, currentRoles) {
    console.log('openStatusModal appelée avec:', {
        userId: userId,
        userName: userName,
        isActive: isActive,
        currentRoles: currentRoles
    });
    
    try {
        currentUserId = userId;
        
        // Vérifier que le modal existe
        const modal = document.getElementById('statusModal');
        if (!modal) {
            console.error('Modal statusModal non trouvé dans le DOM');
            alert('Erreur: Modal non trouvé');
            return;
        }
        
        // Mettre à jour le titre et message du modal
        const titleElement = document.getElementById('modalTitle');
        const messageElement = document.getElementById('modalMessage');
        
        if (titleElement) {
            titleElement.textContent = `Gérer le statut de ${userName}`;
        }
        
        if (messageElement) {
            if (isActive) {
                messageElement.textContent = 
                    `${userName} est actuellement actif. Vous pouvez désactiver ce compte ou modifier ses rôles.`;
            } else {
                messageElement.textContent = 
                    `${userName} est actuellement inactif. Vous pouvez activer ce compte et lui attribuer des rôles.`;
            }
        }
        
        // Pré-sélectionner le statut actuel
        const activeRadio = document.querySelector('input[name="active"][value="1"]');
        const inactiveRadio = document.querySelector('input[name="active"][value="0"]');
        
        if (activeRadio && inactiveRadio) {
            if (isActive) {
                activeRadio.checked = true;
            } else {
                inactiveRadio.checked = true;
            }
        } else {
            console.error('Radio buttons pour le statut non trouvés');
        }
        
        // Pré-sélectionner les rôles actuels
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
        console.log('Checkboxes trouvées:', roleCheckboxes.length);
        console.log('Rôles actuels:', currentRoles);
        
        roleCheckboxes.forEach(checkbox => {
            const roleId = parseInt(checkbox.value);
            checkbox.checked = currentRoles.includes(roleId);
            console.log(`Rôle ${roleId}: ${checkbox.checked ? 'coché' : 'décoché'}`);
        });
        
        // Afficher le modal
        modal.classList.remove('hidden');
        console.log('Modal affiché');
        
    } catch (error) {
        console.error('Erreur dans openStatusModal:', error);
        alert('Erreur lors de l\'ouverture du modal: ' + error.message);
    }
}

// Version alternative utilisant les data attributes si onclick ne fonctionne pas
function openStatusModalFromButton(button) {
    const userId = parseInt(button.getAttribute('data-user-id'));
    const userName = button.getAttribute('data-user-name');
    const isActive = button.getAttribute('data-user-active') === '1';
    const currentRoles = JSON.parse(button.getAttribute('data-user-roles') || '[]');
    
    openStatusModal(userId, userName, isActive, currentRoles);
}

// Fonction pour fermer le modal
function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    currentUserId = null;
    
    // Réinitialiser le formulaire
    const form = document.getElementById('statusForm');
    if (form) {
        form.reset();
    }
}

// Fonction pour soumettre les changements
function submitStatusChange(event) {
    event.preventDefault();
    console.log('submitStatusChange appelée');
    
    if (!currentUserId) {
        console.error('currentUserId est null');
        return;
    }
    
    const formData = new FormData(event.target);
    const submitButton = document.getElementById('submitButton');
    
    // Désactiver le bouton pendant la requête
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Traitement...';
    }
    
    // Préparer les données à envoyer
    const requestData = {
        active: formData.get('active') === '1',
        roles: formData.getAll('roles[]')
    };
    
    console.log('Données à envoyer:', requestData);
    
    // Faire la requête AJAX
    fetch(`/admin/users/${currentUserId}/update-status-roles`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('Réponse reçue:', response);
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            // Fermer le modal
            closeStatusModal();
            
            // Recharger la page pour refléter les changements
            showNotification(data.message, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur de communication avec le serveur', 'error');
    })
    .finally(() => {
        // Réactiver le bouton
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Confirmer';
        }
    });
}

// Fonction pour afficher les notifications
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    
    notification.className = `fixed bottom-4 right-4 ${bgColor} border px-4 py-3 rounded z-50 transition-all duration-300`;
    notification.innerHTML = message;
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(10px)';
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    // Suppression automatique après 4 secondes
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(10px)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé, initialisation des event listeners');
    
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeStatusModal();
            }
        });
    }
    
    // Fermer le modal avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeStatusModal();
        }
    });
    
    // Ajouter des event listeners aux boutons si onclick ne fonctionne pas
    const toggleButtons = document.querySelectorAll('.toggle-status-btn');
    console.log('Boutons toggle trouvés:', toggleButtons.length);
    
    toggleButtons.forEach((button, index) => {
        console.log(`Bouton ${index + 1}:`, button);
        
        // Ajouter un event listener de secours
        button.addEventListener('click', function(e) {
            console.log('Event listener click déclenché sur bouton', index + 1);
            openStatusModalFromButton(this);
        });
    });
});
</script>
@endsection
