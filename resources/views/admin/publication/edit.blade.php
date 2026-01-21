@extends('layouts.admin')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.publication.index') }}" 
           class="text-iri-gray hover:text-iri-primary transition-colors duration-200 font-medium">
            Publications
        </a>
    </li>
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">{{ Str::limit($publication->titre, 30) }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
            <strong class="font-semibold">Une erreur est survenue :</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('alert'))
        <div class="mb-6">
            {!! session('alert') !!}
        </div>
    @endif

    <!-- En-tête -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Modifier la Publication
                    </h1>
                    <p class="text-white/80 mt-1">Modification de "{{ $publication->titre }}"</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.publication.show', $publication) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-200">
                        <i class="fas fa-eye mr-2"></i>
                        Voir
                    </a>
                    <a href="{{ route('admin.publication.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-iri-primary rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    @php
        $formAction = route('admin.publication.update', $publication);
    @endphp
    @include('admin.publication._form')
</div>
@endsection

