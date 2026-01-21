@extends('layouts.admin')

@section('title', 'Projets')

@section('content')
<div class="container-fluid py-4">
    {{-- Header avec titre et bouton d'ajout --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-folder me-2"></i>Projets
            </h1>
            <p class="text-muted mb-0">Gérez vos projets et leurs statuts</p>
        </div>
        <a href="{{ route('admin.projets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nouveau projet
        </a>
    </div>

    {{-- Statistiques --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Projets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-folder fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Publiés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->where('is_published', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En Attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->where('is_published', false)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">En Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->where('etat', 'en cours')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-play-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Terminés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->where('etat', 'terminé')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Suspendus</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projets->where('etat', 'suspendu')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-pause-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-funnel me-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.projets.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="etat" class="form-label">Filtrer par état</label>
                    <select name="etat" id="etat" class="form-select">
                        <option value="">-- Tous les états --</option>
                        @foreach(['en cours', 'terminé', 'suspendu'] as $option)
                            <option value="{{ $option }}" {{ request('etat') == $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="annee" class="form-label">Filtrer par année</label>
                    <select name="annee" id="annee" class="form-select">
                        <option value="">-- Toutes les années --</option>
                        @foreach ($anneesDisponibles as $annee)
                            <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>
                                {{ $annee }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-fill">
                        <i class="bi bi-search me-1"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.projets.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table des projets --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>Liste des Projets
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Projet</th>
                            <th scope="col">Période</th>
                            <th scope="col">État</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projets as $projet)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($projet->image)
                                            <img src="{{ asset('storage/' . $projet->image) }}" 
                                                 alt="Image du projet"
                                                 class="rounded me-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-secondary d-flex align-items-center justify-content-center me-3 text-white fw-bold" 
                                                 style="width: 50px; height: 50px;">
                                                {{ strtoupper(substr($projet->nom, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $projet->nom }}</div>
                                            @if($projet->description)
                                                <small class="text-muted">{{ Str::limit($projet->description, 60) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if($projet->date_debut)
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                Début: {{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}
                                            </small>
                                        @endif
                                        @if($projet->date_fin)
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                Fin: {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}
                                            </small>
                                        @endif
                                        @if(!$projet->date_debut && !$projet->date_fin)
                                            <span class="text-muted"><em>Non définie</em></span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @switch($projet->etat)
                                        @case('en cours')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock me-1"></i>En cours
                                            </span>
                                            @break
                                        @case('terminé')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Terminé
                                            </span>
                                            @break
                                        @case('suspendu')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-pause-circle me-1"></i>Suspendu
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($projet->etat) }}</span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        {{-- Contrôles de modération --}}
                                        @if(auth()->check() && auth()->user()->canModerate())
                                            @if($projet->is_published)
                                                <button onclick="unpublishProjet('{{ $projet->slug }}')" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        title="Dépublier">
                                                    <i class="bi bi-pause-circle"></i>
                                                </button>
                                            @else
                                                <button onclick="publishProjet('{{ $projet->slug }}')" 
                                                        class="btn btn-sm btn-outline-success" 
                                                        title="Publier">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @endif
                                        @endif
                                        
                                        <a href="{{ route('admin.projets.edit', $projet) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.projets.destroy', $projet) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Supprimer ce projet ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-folder-x fa-3x mb-3 d-block"></i>
                                        <p>Aucun projet trouvé.</p>
                                        <a href="{{ route('admin.projets.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i>Créer le premier projet
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($projets->hasPages())
        <div class="d-flex justify-content-center">
            {{ $projets->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Fonctions de modération pour les projets
    function publishProjet(slug) {
        if (confirm('Êtes-vous sûr de vouloir publier ce projet ?')) {
            fetch(`/admin/projets/${slug}/publish`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erreur lors de la publication');
                console.error('Error:', error);
            });
        }
    }

    function unpublishProjet(slug) {
        if (confirm('Êtes-vous sûr de vouloir dépublier ce projet ?')) {
            fetch(`/admin/projets/${slug}/unpublish`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erreur lors de la dépublication');
                console.error('Error:', error);
            });
        }
    }
</script>
@endpush
