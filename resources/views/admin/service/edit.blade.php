@extends('layouts.admin')

@section('title', 'Modifier le Service')
@section('subtitle', 'Modifier les informations du service')

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
            <i class="fas fa-edit mr-2 text-iri-accent"></i>
            Modifier "{{ Str::limit($service->nom, 30) }}"
        </span>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Contenu principal -->
    <div class="max-w-4xl mx-auto">
        @include('admin.service._form', [
            'service' => $service,
            'formAction' => route('admin.service.update', $service)
        ])
    </div>
</div>
@endsection
