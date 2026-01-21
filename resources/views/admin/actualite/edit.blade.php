@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.actualite.index') }}" class="text-white/70 hover:text-white transition-colors duration-200">Actualités</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white font-medium">Modifier l'actualité</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Modifier l\'actualité')
@section('subtitle', 'Modifiez les informations de cette actualité')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête avec design IRI -->
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-iri-primary mb-8">
            <div class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-iri-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-poppins font-bold text-iri-primary">Modifier l'actualité</h1>
                        <p class="text-iri-gray">Modifiez les informations de cette actualité</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-red-800 font-medium">Erreurs détectées</h3>
                        <div class="mt-2 text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('alert'))
            <div class="mb-6">
                {!! session('alert') !!}
            </div>
        @endif

        @php
            $formAction = route('admin.actualite.update', $actualite);
        @endphp
        
        @include('admin.actualite._form')
    </div>
</div>
@endsection

