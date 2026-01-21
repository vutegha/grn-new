<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        
        $this->authorize('viewAny', User::class);
$query = User::with('roles');
        
        // Filtre par recherche (nom ou email)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Filtre par rôle
        if ($request->filled('role')) {
            $query->role($request->get('role'));
        }
        
        $users = $query->latest()->paginate(10);
        $totalUsers = User::count();
        $activeUsers = User::where('active', true)->count();
        $adminUsers = User::role('admin')->count();
        $roles = Role::all();
        
        // Statistiques par rôle
        $roleStats = [];
        foreach ($roles as $role) {
            $roleStats[$role->name] = User::role($role->name)->count();
        }
        
        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'adminUsers', 'roles', 'roleStats'));
    }

    /**
     * Afficher le formulaire de création d'utilisateur
     */
    public function create()
    {
        
        $this->authorize('create', User::class);
$roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        
        $this->authorize('create', User::class);
$validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'permissions' => 'array'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(), // Vérification automatique pour les admins
        ]);


        // Assigner les rôles (convertir ID en nom)
        if (isset($validated['roles'])) {
            $roleIds = $validated['roles'];
            $roleNames = Role::whereIn('id', (array)$roleIds)->pluck('name')->toArray();
            $user->assignRole($roleNames);
        }

        // Assigner les permissions directes (convertir ID en nom)
        if (isset($validated['permissions'])) {
            $permissionIds = $validated['permissions'];
            $permissionNames = Permission::whereIn('id', (array)$permissionIds)->pluck('name')->toArray();
            $user->givePermissionTo($permissionNames);
        }

        return redirect()->route('admin.users.index')
                       ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        
        $this->authorize('view', $user);
        $user->load('roles', 'permissions');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $user)
    {
        
        $this->authorize('update', $user);
        $roles = Role::all();
        $permissions = Permission::all();
        $user->load('roles', 'permissions');
        
        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        
        $this->authorize('update', $user);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'permissions' => 'array'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);


        // Synchroniser les rôles (convertir ID en nom)
        $roleIds = $validated['roles'] ?? [];
        $roleNames = [];
        if (!empty($roleIds)) {
            $roleNames = Role::whereIn('id', (array)$roleIds)->pluck('name')->toArray();
        }
        $user->syncRoles($roleNames);


        // Synchroniser les permissions directes (convertir ID en nom)
        $permissionIds = $validated['permissions'] ?? [];
        $permissionNames = [];
        if (!empty($permissionIds)) {
            $permissionNames = Permission::whereIn('id', (array)$permissionIds)->pluck('name')->toArray();
        }
        $user->syncPermissions($permissionNames);

        return redirect()->route('admin.users.index')
                       ->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        
        $this->authorize('delete', $user);
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()->back()
                           ->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                       ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Gérer les rôles et permissions
     */
    public function managePermissions(User $user)
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $user->load('roles', 'permissions');

        return view('admin.users.manage_permissions', compact('user', 'roles', 'permissions'));
    }

    /**
     * Mettre à jour les permissions d'un utilisateur
     */
    public function updatePermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'array',
            'permissions' => 'array'
        ]);


        // Synchroniser les rôles (convertir ID en nom)
        $roleIds = $validated['roles'] ?? [];
        $roleNames = [];
        if (!empty($roleIds)) {
            $roleNames = Role::whereIn('id', (array)$roleIds)->pluck('name')->toArray();
        }
        $user->syncRoles($roleNames);


        // Synchroniser les permissions directes (convertir ID en nom)
        $permissionIds = $validated['permissions'] ?? [];
        $permissionNames = [];
        if (!empty($permissionIds)) {
            $permissionNames = Permission::whereIn('id', (array)$permissionIds)->pluck('name')->toArray();
        }
        $user->syncPermissions($permissionNames);

        return redirect()->route('admin.users.show', $user)
                       ->with('success', 'Permissions mises à jour avec succès');
    }

    /**
     * Changer le statut d'un utilisateur (actif/inactif) via AJAX
     */
    public function toggleStatus(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        try {
            // Utiliser le champ 'active' comme indicateur de statut
            // false = désactivé, true = activé
            if ($user->active) {
                // Désactiver l'utilisateur
                $user->active = false;
                $status = 'inactive';
                $message = 'Utilisateur désactivé avec succès';
            } else {
                // Activer l'utilisateur
                $user->active = true;
                $status = 'active';
                $message = 'Utilisateur activé avec succès';
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
                'user_id' => $user->id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }
    
    /**
     * Mettre à jour le statut et les rôles d'un utilisateur simultanément via AJAX
     */
    public function updateStatusAndRoles(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        try {
            $validated = $request->validate([
                'active' => 'required|boolean',
                'roles' => 'array',
                'roles.*' => 'exists:roles,id'
            ]);
            
            // Mettre à jour le statut
            $user->active = $validated['active'];
            $user->save();
            
            // Mettre à jour les rôles
            if (isset($validated['roles'])) {
                $roleIds = $validated['roles'];
                $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
                $user->syncRoles($roleNames);
            } else {
                // Si aucun rôle n'est sélectionné, supprimer tous les rôles
                $user->syncRoles([]);
            }
            
            $statusText = $user->active ? 'activé' : 'désactivé';
            $roleCount = count($validated['roles'] ?? []);
            $roleText = $roleCount > 0 ? " avec {$roleCount} rôle(s)" : " sans rôle";
            
            return response()->json([
                'success' => true,
                'message' => "Utilisateur {$statusText}{$roleText} avec succès",
                'user_id' => $user->id,
                'active' => $user->active,
                'roles_count' => $roleCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }
}
