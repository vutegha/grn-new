@extends('layouts.admin')

@section('title', 'Messages de Contact')

@section('content')
<div class="container-fluid py-4">
    {{-- Header avec titre --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-envelope me-2"></i>Messages de Contact
            </h1>
            <p class="text-muted mb-0">Gérez les messages reçus via le formulaire de contact</p>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Messages</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $contacts->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Nouveaux</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Contact::where('statut', 'nouveau')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Contact::where('statut', 'lu')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Traités</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Contact::where('statut', 'traite')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres et recherche --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-funnel me-2"></i>Filtres et Recherche
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Rechercher</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher par nom, email ou sujet..."
                           class="form-control">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="nouveau" {{ request('statut') == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                        <option value="lu" {{ request('statut') == 'lu' ? 'selected' : '' }}>Lu</option>
                        <option value="traite" {{ request('statut') == 'traite' ? 'selected' : '' }}>Traité</option>
                        <option value="ferme" {{ request('statut') == 'ferme' ? 'selected' : '' }}>Fermé</option>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-fill">
                        <i class="bi bi-search me-1"></i>Rechercher
                    </button>
                    @if(request()->filled(['search', 'statut']))
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table des contacts --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>Liste des Messages
            </h6>
        </div>
        <div class="card-body">
            @if($contacts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Contact</th>
                                <th scope="col">Sujet & Message</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Date</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr class="{{ $contact->statut == 'nouveau' ? 'table-warning' : '' }}">
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $contact->nom }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-envelope me-1"></i>{{ $contact->email }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ Str::limit($contact->sujet, 50) }}</div>
                                            <small class="text-muted">{{ Str::limit($contact->message, 80) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($contact->statut)
                                            @case('nouveau')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-exclamation-circle me-1"></i>Nouveau
                                                </span>
                                                @break
                                            @case('lu')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-eye me-1"></i>Lu
                                                </span>
                                                @break
                                            @case('traite')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Traité
                                                </span>
                                                @break
                                            @case('ferme')
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>Fermé
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($contact->statut) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $contact->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.contacts.show', $contact) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Voir le message">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contact ?')"
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($contacts->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $contacts->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <div class="text-muted">
                        <i class="bi bi-envelope-x fa-3x mb-3 d-block"></i>
                        <h5>Aucun message de contact</h5>
                        <p>Aucun message n'a été reçu pour le moment.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
