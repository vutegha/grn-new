@extends('layouts.admin')

@section('title', 'Nouveau Service')
@section('subtitle', 'Créer un nouveau service GRN-UCBC')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.service.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-cogs mr-2 group-hover:rotate-45 transition-transform duration-200"></i>
            Services
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium flex items-center">
            <i class="fas fa-plus-circle mr-2 text-iri-accent"></i>
            Nouveau service
        </span>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Contenu principal -->
    <div class="max-w-4xl mx-auto">
        @include('admin.service._form', [
            'formAction' => route('admin.service.store')
        ])
    </div>
</div>
@endsection
