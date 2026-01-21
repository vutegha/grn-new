{{-- resources/views/components/admin-navigation.blade.php --}}
<nav class="bg-gray-800 text-white p-4">
    <div class="container mx-auto">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">Administration IRI</h1>
            
            @auth
                <div class="flex space-x-4">
                    {{-- Dashboard - Accessible à tous les admins --}}
                    @can('view_admin_dashboard')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300">
                            📊 Dashboard
                        </a>
                    @endcan

                    {{-- Super Admin uniquement --}}
                    @role('super admin')
                        <a href="{{ route('admin.system.config') }}" class="hover:text-gray-300 text-red-400">
                            ⚙️ Configuration Système
                        </a>
                        <a href="{{ route('admin.security.audit') }}" class="hover:text-gray-300 text-red-400">
                            🔒 Audit Sécurité
                        </a>
                    @endrole

                    {{-- Gestion des utilisateurs --}}
                    @can('manage_users')
                        <a href="{{ route('admin.users.index') }}" class="hover:text-gray-300">
                            👥 Utilisateurs
                        </a>
                    @endcan

                    {{-- Publications --}}
                    @can('view_publications')
                        <a href="{{ route('admin.publications.index') }}" class="hover:text-gray-300">
                            📄 Publications
                        </a>
                    @endcan

                    {{-- Actualités --}}
                    @if(auth()->user()->canViewActualites())
                        <a href="{{ route('admin.actualites.index') }}" class="hover:text-gray-300">
                            📰 Actualités
                        </a>
                    @endif

                    {{-- Événements --}}
                    @can('view_evenements')
                        <a href="{{ route('admin.evenements.index') }}" class="hover:text-gray-300">
                            📅 Événements
                        </a>
                    @endcan

                    {{-- Services --}}
                    @if(auth()->user()->canViewServices())
                        <a href="{{ route('admin.services.index') }}" class="hover:text-gray-300">
                            🛠️ Services
                        </a>
                    @endif

                    {{-- Projets --}}
                    @if(auth()->user()->canViewProjets())
                        <a href="{{ route('admin.projets.index') }}" class="hover:text-gray-300">
                            🚀 Projets
                        </a>
                    @endif

                    {{-- Modération - Uniquement pour les modérateurs et plus --}}
                    @if(auth()->user()->canModerate())
                        <div class="relative group">
                            <button class="hover:text-gray-300 flex items-center">
                                🛡️ Modération
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-gray-700 rounded-md shadow-lg hidden group-hover:block z-10">
                                @can('moderate_publications')
                                    <a href="{{ route('admin.moderation.publications') }}" class="block px-4 py-2 hover:bg-gray-600">
                                        Publications en attente
                                    </a>
                                @endcan
                                @can('moderate_actualites')
                                    <a href="{{ route('admin.moderation.actualites') }}" class="block px-4 py-2 hover:bg-gray-600">
                                        Actualités en attente
                                    </a>
                                @endcan
                                @if(auth()->user()->canModerateProjets())
                                    <a href="{{ route('admin.moderation.projets') }}" class="block px-4 py-2 hover:bg-gray-600">
                                        Projets en attente
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Paramètres --}}
                    @canany(['manage system', 'manage_users'])
                        <div class="relative group">
                            <button class="hover:text-gray-300 flex items-center">
                                ⚙️ Paramètres
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-gray-700 rounded-md shadow-lg hidden group-hover:block z-10">
                                @can('manage system')
                                    <a href="{{ route('admin.settings.general') }}" class="block px-4 py-2 hover:bg-gray-600">
                                        Paramètres généraux
                                    </a>
                                @endcan
                                @can('manage_users')
                                    <a href="{{ route('admin.roles.index') }}" class="block px-4 py-2 hover:bg-gray-600">
                                        Rôles et permissions
                                    </a>
                                @endcan
                                @role('super admin')
                                    <a href="{{ route('admin.logs') }}" class="block px-4 py-2 hover:bg-gray-600 text-red-400">
                                        Logs système
                                    </a>
                                @endrole
                            </div>
                        </div>
                    @endcanany

                    {{-- Profil utilisateur --}}
                    <div class="relative group">
                        <button class="hover:text-gray-300 flex items-center">
                            👤 {{ auth()->user()->name }}
                            @if(auth()->user()->isSuperAdmin())
                                <span class="ml-1 px-2 py-1 bg-red-600 text-xs rounded">SUPER</span>
                            @endif
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-gray-700 rounded-md shadow-lg hidden group-hover:block z-10">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-600">
                                Profil
                            </a>
                            <hr class="border-gray-600">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-600">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- Affichage des informations de l'utilisateur (debug) --}}
@if(config('app.debug') && auth()->check())
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 text-sm">
        <strong>Debug Info:</strong>
        <ul class="list-disc list-inside">
            <li>Utilisateur: {{ auth()->user()->email }}</li>
            <li>Rôles: {{ auth()->user()->roles->pluck('name')->implode(', ') ?: 'Aucun' }}</li>
            <li>Permissions directes: {{ auth()->user()->permissions->count() }}</li>
            <li>Permissions via rôles: {{ auth()->user()->getPermissionsViaRoles()->count() }}</li>
            <li>Total permissions: {{ auth()->user()->getAllPermissions()->count() }}</li>
            <li>Super Admin: {{ auth()->user()->isSuperAdmin() ? 'Oui' : 'Non' }}</li>
            <li>Email vérifié: {{ auth()->user()->hasVerifiedEmail() ? 'Oui' : 'Non' }}</li>
            <li>Actif: {{ auth()->user()->active ? 'Oui' : 'Non' }}</li>
        </ul>
    </div>
@endif
