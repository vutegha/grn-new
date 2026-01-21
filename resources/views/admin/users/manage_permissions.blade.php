@extends('layouts.admin')

@section('title', 'Gérer les Permissions - ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="sr-only">Utilisateurs</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('admin.users.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Utilisateurs</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">Permissions</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gérer les Permissions</h1>
                    <p class="text-gray-600 mt-1">Utilisateur: <span class="font-medium">{{ $user->name }}</span> ({{ $user->email }})</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Voir le Profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations actuelles -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Rôles actuels -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rôles Actuels</h3>
                <div class="space-y-2">
                    @forelse($user->roles as $role)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm font-medium">{{ ucfirst($role->name) }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($role->name === 'admin') bg-red-100 text-red-800
                                @elseif($role->name === 'moderator') bg-yellow-100 text-yellow-800
                                @elseif($role->name === 'editor') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $role->permissions->count() }} permissions
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">Aucun rôle assigné</p>
                    @endforelse
                </div>
            </div>

            <!-- Permissions directes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions Directes</h3>
                <div class="space-y-1">
                    @forelse($user->getDirectPermissions() as $permission)
                        <div class="text-sm bg-green-50 text-green-800 px-2 py-1 rounded">
                            {{ str_replace(['_', '-'], ' ', ucfirst($permission->name)) }}
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">Aucune permission directe</p>
                    @endforelse
                </div>
            </div>

            <!-- Résumé des accès -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Résumé des Accès</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total permissions</span>
                        <span class="text-sm font-medium">{{ $user->getAllPermissions()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Via rôles</span>
                        <span class="text-sm font-medium">{{ $user->getPermissionsViaRoles()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Directes</span>
                        <span class="text-sm font-medium">{{ $user->getDirectPermissions()->count() }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Statut</span>
                            @if($user->hasRole('admin'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Administrateur
                                </span>
                            @elseif($user->hasAnyRole(['moderator', 'editor']))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Privilégié
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Standard
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de gestion -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Gestion des rôles -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Assigner des Rôles</h3>
                        <div class="space-y-3">
                            @foreach($roles as $role)
                            <div class="relative">
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer 
                                    {{ $user->hasRole($role->name) ? 'bg-blue-50 border-blue-200' : '' }}">
                                    <input type="checkbox" 
                                           name="roles[]" 
                                           value="{{ $role->name }}"
                                           {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ ucfirst($role->name) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $role->permissions->count() }} permissions
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($role->name === 'admin')
                                                Accès complet à toutes les fonctionnalités du système
                                            @elseif($role->name === 'moderator')
                                                Modération des contenus, gestion des utilisateurs
                                            @elseif($role->name === 'editor')
                                                Création et édition de contenus, gestion des publications
                                            @else
                                                Utilisateur standard avec accès de base
                                            @endif
                                        </div>
                                        @if($role->permissions->count() > 0)
                                        <div class="mt-2">
                                            <details class="text-xs">
                                                <summary class="text-gray-600 hover:text-gray-800 cursor-pointer">
                                                    Voir les permissions ({{ $role->permissions->count() }})
                                                </summary>
                                                <div class="mt-1 pl-4 space-y-1">
                                                    @foreach($role->permissions->take(5) as $permission)
                                                    <div class="text-gray-500">
                                                        • {{ str_replace(['_', '-'], ' ', ucfirst($permission->name)) }}
                                                    </div>
                                                    @endforeach
                                                    @if($role->permissions->count() > 5)
                                                    <div class="text-gray-400 italic">
                                                        ... et {{ $role->permissions->count() - 5 }} autres
                                                    </div>
                                                    @endif
                                                </div>
                                            </details>
                                        </div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gestion des permissions spécifiques -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions Spécifiques</h3>
                        
                        <!-- Filtre par catégorie -->
                        <div class="mb-4">
                            <select id="permission-filter" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Toutes les permissions</option>
                                <option value="admin">Administration</option>
                                <option value="user">Utilisateurs</option>
                                <option value="service">Services</option>
                                <option value="actualite">Actualités</option>
                                <option value="publication">Publications</option>
                                <option value="evenement">Événements</option>
                                <option value="projet">Projets</option>
                            </select>
                        </div>

                        <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            <div class="space-y-2">
                                @foreach($permissions->groupBy(function($permission) {
                                    return explode('_', $permission->name)[0] ?? 'other';
                                }) as $category => $categoryPermissions)
                                <div class="permission-category" data-category="{{ $category }}">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2 capitalize">
                                        {{ ucfirst($category) }}
                                    </h4>
                                    <div class="pl-4 space-y-1">
                                        @foreach($categoryPermissions as $permission)
                                        <label class="flex items-center py-1 cursor-pointer hover:bg-gray-50 rounded px-2">
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->name }}"
                                                   {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}
                                                   class="h-3 w-3 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ str_replace(['_', '-'], ' ', ucwords(str_replace($category . '_', '', $permission->name))) }}
                                            </span>
                                            @if($user->hasPermissionTo($permission->name) && !$user->hasDirectPermission($permission->name))
                                            <span class="ml-auto text-xs text-green-600 italic">via rôle</span>
                                            @endif
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <p class="mt-2 text-xs text-gray-500">
                            Les permissions cochées seront assignées directement à l'utilisateur.
                            Les permissions "via rôle" sont héritées des rôles assignés.
                        </p>
                        @error('permissions')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-8">
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Mettre à jour les permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filter = document.getElementById('permission-filter');
    const categories = document.querySelectorAll('.permission-category');

    filter.addEventListener('change', function() {
        const selectedCategory = this.value.toLowerCase();
        
        categories.forEach(category => {
            const categoryName = category.dataset.category.toLowerCase();
            if (selectedCategory === '' || categoryName.includes(selectedCategory)) {
                category.style.display = 'block';
            } else {
                category.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
