@extends('layouts.admin')

@section('title', 'Nouveau Partenaire')
@section('subtitle', 'Ajouter un nouveau partenaire au réseau GRN-UCBC')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.partenaires.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-handshake mr-2 group-hover:rotate-12 transition-transform duration-200"></i>
            Partenaires
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium flex items-center">
            <i class="fas fa-plus-circle mr-2 text-iri-accent"></i>
            Nouveau partenaire
        </span>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Contenu principal -->
    <div class="max-w-4xl mx-auto">
        @include('admin.partenaires._form', [
            'formAction' => route('admin.partenaires.store')
        ])
    </div>
</div>
@endsection
