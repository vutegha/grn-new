@extends('layouts.admin')

@section('title', 'Modifier le Média')

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
                <a href="{{ route('admin.media.index') }}" class="text-white/70 hover:text-white">Médias</a>
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

@section('content')
<div class="min-h-screen bg-gradient-to-br from-iri-light via-white to-iri-light/50">
    <div class="max-w-4xl mx-auto px-4 py-8">
        
        {{-- En-tête --}}
        <div class="mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-r from-iri-primary to-iri-accent rounded-xl flex items-center justify-center">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-iri-primary to-iri-accent bg-clip-text text-transparent">
                        Modifier le média
                    </h1>
                    <p class="text-iri-gray">{{ $media->titre ?? 'Média sans titre' }}</p>
                </div>
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            @include('admin.media._form', [
                'formAction' => route('admin.media.update', $media),
                'method' => 'PUT'
            ])
        </div>
    </div>
</div>
@endsection

