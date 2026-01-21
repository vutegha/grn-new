@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.projets.index') }}" class="text-white/70 hover:text-white">Projets</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">Modifier</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Modifier le Projet')
@section('subtitle', 'Modification de ' . $projet->nom)

@section('content')
<div class="container-admin px-4 py-6">
    @include('admin.projets._form', [
        'formAction' => route('admin.projets.update', $projet),
        'projet' => $projet,
        'services' => $services
    ])
</div>
@endsection