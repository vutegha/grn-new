@extends('layouts.admin')

@section('title', 'Configuration des emails')

@section('content')
<div class="container mx-auto px-4">
    <!-- En-tête avec breadcrumbs -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    @foreach($breadcrumbs as $breadcrumb)
                        @if($loop->last)
                            <li class="text-sm text-gray-500">{{ $breadcrumb['name'] }}</li>
                        @else
                            <li class="text-sm">
                                <a href="{{ $breadcrumb['url'] }}" class="text-iri-primary hover:text-iri-secondary">{{ $breadcrumb['name'] }}</a>
                                <span class="mx-2 text-gray-500">/</span>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-envelope-open-text mr-3 text-iri-primary"></i>
                Configuration des emails
            </h1>
            <p class="text-gray-600 mt-1">Gérez les adresses email de réception et de copie pour les différentes fonctionnalités</p>
        </div>
    </div>

    <!-- Messages de statut -->
    <div id="message-container" class="mb-6"></div>

    <!-- Cartes de configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @foreach($emailSettings as $setting)
        <div class="bg-white rounded-lg shadow-sm border {{ $setting->active ? 'border-l-4 border-l-green-500' : 'border-l-4 border-l-yellow-500' }}">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold {{ $setting->active ? 'text-green-700' : 'text-yellow-700' }} flex items-center">
                    <i class="fas fa-{{ $setting->active ? 'check-circle' : 'exclamation-triangle' }} mr-3"></i>
                    {{ $setting->label }}
                    @if($setting->required)
                        <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Obligatoire</span>
                    @endif
                </h3>
                <div class="relative">
                    <button class="text-gray-400 hover:text-gray-600 focus:outline-none" onclick="toggleDropdown({{ $setting->id }})">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div id="dropdown-{{ $setting->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                        <div class="py-1">
                            <button onclick="testEmailConfiguration({{ $setting->id }})" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Tester la configuration
                            </button>
                            <button onclick="toggleConfiguration({{ $setting->id }})" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-{{ $setting->active ? 'toggle-off' : 'toggle-on' }} mr-2"></i>
                                {{ $setting->active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if($setting->description)
                    <p class="text-gray-600 text-sm mb-4">{{ $setting->description }}</p>
                @endif

                <!-- Liste des emails configurés -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Adresses email configurées ({{ count($setting->emails) }})
                    </label>
                    <div id="email-list-{{ $setting->id }}" class="space-y-2">
                        @foreach($setting->emails as $email)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-800 flex items-center">
                                    <i class="fas fa-envelope text-iri-primary mr-2"></i>
                                    {{ $email }}
                                </span>
                                @if(!$setting->required || count($setting->emails) > 1)
                                    <button type="button" 
                                            onclick="removeEmail({{ $setting->id }}, '{{ $email }}')"
                                            class="text-red-500 hover:text-red-700 p-1"
                                            title="Supprimer cette adresse">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Formulaire d'ajout d'email -->
                <form id="add-email-form-{{ $setting->id }}" onsubmit="addEmail(event, {{ $setting->id }})" class="mb-4">
                    <div class="flex gap-2">
                        <input type="email" 
                               name="email" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary" 
                               placeholder="Ajouter une adresse email..."
                               required>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </form>

                <!-- Statut de la configuration -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <small class="text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Clé: <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $setting->key }}</code>
                    </small>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="active-{{ $setting->id }}"
                               {{ $setting->active ? 'checked' : '' }}
                               onchange="toggleConfiguration({{ $setting->id }})"
                               class="mr-2 h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                        <label for="active-{{ $setting->id }}" class="text-sm text-gray-700">
                            {{ $setting->active ? 'Actif' : 'Inactif' }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Section d'aide -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-iri-primary flex items-center">
                <i class="fas fa-question-circle mr-3"></i>
                Guide d'utilisation
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Types de configuration</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-iri-primary"></i>
                            <div>
                                <strong>Adresse principale:</strong>
                                <span class="text-gray-600">Reçoit tous les messages en premier</span>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-copy mr-3 text-green-600"></i>
                            <div>
                                <strong>Adresses de copie:</strong>
                                <span class="text-gray-600">Reçoivent une copie de chaque message</span>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-bell mr-3 text-blue-600"></i>
                            <div>
                                <strong>Notifications:</strong>
                                <span class="text-gray-600">Pour les inscriptions et événements</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Actions disponibles</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-plus mr-3 text-green-600"></i>
                            <div>
                                <strong>Ajouter:</strong>
                                <span class="text-gray-600">Nouvelle adresse email à une configuration</span>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-times mr-3 text-red-600"></i>
                            <div>
                                <strong>Supprimer:</strong>
                                <span class="text-gray-600">Retirer une adresse (si non obligatoire)</span>
                            </div>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-paper-plane mr-3 text-iri-primary"></i>
                            <div>
                                <strong>Tester:</strong>
                                <span class="text-gray-600">Vérifier le bon fonctionnement</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de test d'email -->
<div id="testEmailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-paper-plane mr-3"></i>
                Tester la configuration email
            </h3>
            <button type="button" onclick="closeTestModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="test-email-form" onsubmit="sendTestEmail(event)">
            <div class="p-6">
                <div class="mb-4">
                    <label for="test-email-address" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse email de test
                    </label>
                    <input type="email" 
                           id="test-email-address" 
                           name="test_email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary" 
                           placeholder="votre@email.com"
                           required>
                    <p class="text-sm text-gray-500 mt-2">
                        Cette adresse recevra également l'email de test, en plus des adresses configurées.
                    </p>
                </div>
                <input type="hidden" id="test-email-setting-id" name="email_setting_id">
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeTestModal()" 
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-iri-primary text-white rounded-md hover:bg-iri-secondary flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Envoyer le test
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Fonctions utilitaires
function showMessage(message, type = 'success') {
    const container = document.getElementById('message-container');
    const alertClass = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
    const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    container.innerHTML = `
        <div class="border-l-4 p-4 ${alertClass} rounded-md mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas ${iconClass}"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Gestion des dropdowns
function toggleDropdown(settingId) {
    const dropdown = document.getElementById(`dropdown-${settingId}`);
    // Fermer tous les autres dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
        if (d.id !== `dropdown-${settingId}`) {
            d.classList.add('hidden');
        }
    });
    dropdown.classList.toggle('hidden');
}

// Fermer les dropdowns quand on clique ailleurs
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*="toggleDropdown"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            d.classList.add('hidden');
        });
    }
});

// Ajouter une adresse email
async function addEmail(event, settingId) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const email = formData.get('email');

    try {
        const response = await fetch(`/admin/email-settings/${settingId}/add-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        });

        const data = await response.json();

        if (data.success) {
            showMessage(data.message, 'success');
            
            // Ajouter l'email à la liste
            const emailList = document.getElementById(`email-list-${settingId}`);
            const newEmailDiv = document.createElement('div');
            newEmailDiv.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-lg';
            newEmailDiv.innerHTML = `
                <span class="text-gray-800 flex items-center">
                    <i class="fas fa-envelope text-iri-primary mr-2"></i>
                    ${email}
                </span>
                <button type="button" 
                        onclick="removeEmail(${settingId}, '${email}')"
                        class="text-red-500 hover:text-red-700 p-1"
                        title="Supprimer cette adresse">
                    <i class="fas fa-times"></i>
                </button>
            `;
            emailList.appendChild(newEmailDiv);
            
            // Réinitialiser le formulaire
            form.reset();
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        showMessage('Erreur lors de l\'ajout de l\'adresse email', 'error');
    }
}

// Supprimer une adresse email
async function removeEmail(settingId, email) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette adresse email ?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/email-settings/${settingId}/remove-email`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        });

        const data = await response.json();

        if (data.success) {
            showMessage(data.message, 'success');
            
            // Supprimer l'email de la liste
            const emailList = document.getElementById(`email-list-${settingId}`);
            const emailItems = emailList.querySelectorAll('div');
            emailItems.forEach(item => {
                if (item.textContent.includes(email)) {
                    item.remove();
                }
            });
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        showMessage('Erreur lors de la suppression de l\'adresse email', 'error');
    }
}

// Activer/désactiver une configuration
async function toggleConfiguration(settingId) {
    const checkbox = document.getElementById(`active-${settingId}`);
    const isActive = checkbox.checked;

    try {
        const response = await fetch(`/admin/email-settings/${settingId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ active: isActive })
        });

        const data = await response.json();

        if (data.success) {
            showMessage(data.message, 'success');
            
            // Mettre à jour l'interface
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showMessage(data.message, 'error');
            // Remettre l'état précédent
            checkbox.checked = !isActive;
        }
    } catch (error) {
        showMessage('Erreur lors de la mise à jour de la configuration', 'error');
        checkbox.checked = !isActive;
    }
}

// Tester la configuration email
function testEmailConfiguration(settingId) {
    document.getElementById('test-email-setting-id').value = settingId;
    document.getElementById('testEmailModal').classList.remove('hidden');
}

// Fermer le modal de test
function closeTestModal() {
    document.getElementById('testEmailModal').classList.add('hidden');
}

// Envoyer un email de test
async function sendTestEmail(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const testEmail = formData.get('test_email');
    const settingId = formData.get('email_setting_id');

    // Debug: vérifier les données
    console.log('Debug sendTestEmail:', {
        testEmail: testEmail,
        settingId: settingId
    });

    // Vérifier le token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    console.log('CSRF Token element:', csrfToken);
    console.log('CSRF Token value:', csrfToken ? csrfToken.getAttribute('content') : 'NOT FOUND');

    if (!csrfToken) {
        showMessage('Token CSRF non trouvé. Veuillez recharger la page.', 'error');
        return;
    }

    try {
        console.log('Sending request to:', '/admin/email-settings/test-email');
        console.log('Request data:', { 
            email_setting_id: settingId,
            test_email: testEmail 
        });

        const response = await fetch('/admin/email-settings/test-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                email_setting_id: settingId,
                test_email: testEmail 
            })
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        const data = await response.json();
        console.log('Response data:', data);

        if (data.success) {
            showMessage(data.message, 'success');
            closeTestModal();
            form.reset();
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        console.error('Error during sendTestEmail:', error);
        showMessage('Erreur lors de l\'envoi de l\'email de test', 'error');
    }
}

// Fermer le modal quand on clique en dehors
document.getElementById('testEmailModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeTestModal();
    }
});
</script>
@endsection
