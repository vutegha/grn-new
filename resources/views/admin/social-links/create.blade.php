@extends('layouts.admin')

@section('title', 'Créer un Lien Social')
@section('subtitle', 'Ajouter un nouveau lien vers les réseaux sociaux')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.social-links.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-share mr-2 group-hover:rotate-12 transition-transform duration-200"></i>
            Liens sociaux
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium flex items-center">
            <i class="fas fa-plus mr-2 text-iri-accent"></i>
            Créer
        </span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Créer un nouveau lien social</h1>
            <p class="text-gray-600">Ajoutez un lien vers un réseau social ou une plateforme</p>
        </div>

        <!-- Formulaire -->
        @include('admin.social-links._form', ['socialLink' => null])
    </div>
</div>
@endsection
