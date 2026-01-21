@extends('layouts.admin')

@section('title', 'Informations de Contact')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-address-book text-iri-primary mr-3"></i>
                Informations de Contact
            </h1>
            <nav class="text-sm breadcrumbs">
                <ol class="flex space-x-2 text-gray-600">
                    @foreach($breadcrumbs as $breadcrumb)
                        @if($breadcrumb['url'])
                            <li><a href="{{ $breadcrumb['url'] }}" class="hover:text-iri-primary">{{ $breadcrumb['name'] }}</a></li>
                            <li>/</li>
                        @else
                            <li class="text-gray-900 font-medium">{{ $breadcrumb['name'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.contact-info.create') }}" 
           class="bg-iri-primary hover:bg-iri-dark text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Ajouter une information
        </a>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Bureau Principal -->
    @if(isset($contactInfos['bureau_principal']) && $contactInfos['bureau_principal']->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 border-b rounded-t-xl">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-building mr-3"></i>
                Bureau Principal
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-4">
                @foreach($contactInfos['bureau_principal'] as $info)
                    @include('admin.contact-info._card', ['info' => $info])
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Bureaux Régionaux -->
    @if(isset($contactInfos['bureau_regional']) && $contactInfos['bureau_regional']->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 border-b rounded-t-xl">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-map-marked-alt mr-3"></i>
                Bureaux Régionaux / Bureaux de Liaison
            </h2>
        </div>
        <div class="p-6">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded mb-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Les points focaux sont intégrés dans les bureaux régionaux via la section "Responsable / Point Focal"
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($contactInfos['bureau_regional'] as $info)
                    @include('admin.contact-info._card', ['info' => $info])
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Autres -->
    @if(isset($contactInfos['autre']) && $contactInfos['autre']->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-gray-600 to-gray-700 border-b rounded-t-xl">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                Autres
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($contactInfos['autre'] as $info)
                    @include('admin.contact-info._card', ['info' => $info])
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($contactInfos->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-address-book text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune information de contact</h3>
            <p class="text-gray-500 mb-6">Commencez par ajouter votre premier bureau ou point focal.</p>
            <a href="{{ route('admin.contact-info.create') }}" 
               class="bg-iri-primary hover:bg-iri-dark text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Ajouter une information
            </a>
        </div>
    @endif
</div>
@endsection
