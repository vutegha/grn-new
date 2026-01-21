@extends('layouts.admin')

@section('breadcrumbs')
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm font-medium">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/40 text-xs"></i>
                <a href="{{ route('admin.job-applications.index') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm font-medium">
                    Candidatures
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/40 text-xs"></i>
                <span class="text-white font-medium text-sm">{{ $application->first_name }} {{ $application->last_name }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Candidature - ' . $application->first_name . ' ' . $application->last_name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header avec actions -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <div class="flex items-center space-x-4 mb-3">
                    <div class="w-16 h-16 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-full flex items-center justify-center">
                        <span class="text-white text-xl font-bold">
                            {{ strtoupper(substr($application->first_name, 0, 1) . substr($application->last_name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $application->first_name }} {{ $application->last_name }}</h1>
                        <p class="text-gray-600 text-lg">{{ $application->email }}</p>
                        <p class="text-gray-500">Candidature pour: <a href="{{ route('admin.job-offers.show', $application->jobOffer->slug) }}" class="text-iri-primary hover:text-iri-secondary font-medium">{{ $application->jobOffer->title }}</a></p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.job-applications.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
                @if($application->cv_path)
                    <a href="{{ route('admin.job-applications.download-cv', $application) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Télécharger CV
                    </a>
                @endif
                @if($application->portfolio_path)
                    <a href="{{ route('admin.job-applications.download-portfolio', $application) }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        <i class="fas fa-briefcase mr-2"></i>
                        Télécharger Portfolio
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Statut et actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Statut de la candidature</h3>
                    <span class="text-sm text-gray-500">{{ $application->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                
                <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Changer le statut</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200">
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>⏳ En attente</option>
                                <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>👀 Révisée</option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>⭐ Présélectionnée</option>
                                <option value="interviewed" {{ $application->status == 'interviewed' ? 'selected' : '' }}>💬 Entretien passé</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>✅ Acceptée</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>❌ Rejetée</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                <i class="fas fa-save mr-2"></i>
                                Mettre à jour
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes internes (optionnel)</label>
                        <textarea name="admin_notes" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200"
                                  placeholder="Ajoutez des notes sur cette candidature...">{{ $application->admin_notes }}</textarea>
                    </div>
                </form>
            </div>

            <!-- Lettre de motivation -->
            @if($application->cover_letter)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            Lettre de motivation
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($application->cover_letter)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Expérience professionnelle -->
            @if($application->experience)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-iri-secondary to-iri-primary px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-briefcase mr-3"></i>
                            Expérience professionnelle
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($application->experience)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Compétences -->
            @if($application->skills)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-tools mr-3"></i>
                            Compétences
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($application->skills)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Motivation -->
            @if($application->motivation)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-heart mr-3"></i>
                            Motivation
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($application->motivation)) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut actuel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Statut actuel
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $statusConfig = [
                            'pending' => ['color' => 'yellow', 'icon' => 'fa-clock', 'text' => 'En attente'],
                            'reviewed' => ['color' => 'blue', 'icon' => 'fa-eye', 'text' => 'Révisée'],
                            'shortlisted' => ['color' => 'purple', 'icon' => 'fa-star', 'text' => 'Présélectionnée'],
                            'interviewed' => ['color' => 'indigo', 'icon' => 'fa-comments', 'text' => 'Entretien passé'],
                            'accepted' => ['color' => 'green', 'icon' => 'fa-check-circle', 'text' => 'Acceptée'],
                            'rejected' => ['color' => 'red', 'icon' => 'fa-times-circle', 'text' => 'Rejetée']
                        ];
                        $config = $statusConfig[$application->status] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => $application->status];
                    @endphp
                    <div class="text-center">
                        <div class="w-16 h-16 bg-{{ $config['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }}-600 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $config['text'] }}</h4>
                        <p class="text-sm text-gray-500">
                            Dernière mise à jour : {{ $application->updated_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informations personnelles -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        Informations
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900 font-medium">{{ $application->email }}</p>
                    </div>
                    
                    @if($application->phone)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="text-gray-900 font-medium">{{ $application->phone }}</p>
                        </div>
                    @endif
                    
                    @if($application->address)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Adresse</label>
                            <p class="text-gray-900">{{ $application->address }}</p>
                        </div>
                    @endif
                    
                    @if($application->linkedin_profile)
                        <div>
                            <label class="text-sm font-medium text-gray-500">LinkedIn</label>
                            <a href="{{ $application->linkedin_profile }}" 
                               target="_blank" 
                               class="text-iri-primary hover:text-iri-secondary font-medium flex items-center">
                                <i class="fab fa-linkedin mr-1"></i>
                                Voir le profil
                            </a>
                        </div>
                    @endif
                    
                    @if($application->website)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Site web</label>
                            <a href="{{ $application->website }}" 
                               target="_blank" 
                               class="text-iri-primary hover:text-iri-secondary font-medium flex items-center">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Visiter
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Offre d'emploi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-iri-accent to-iri-gold px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-briefcase mr-2"></i>
                        Offre d'emploi
                    </h3>
                </div>
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 mb-2">{{ $application->jobOffer->title }}</h4>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $application->jobOffer->location }}</p>
                        <p><i class="fas fa-clock mr-2"></i>{{ ucfirst(str_replace('-', ' ', $application->jobOffer->type)) }}</p>
                        @if($application->jobOffer->salary_min || $application->jobOffer->salary_max)
                            <p><i class="fas fa-euro-sign mr-2"></i>
                                @if($application->jobOffer->salary_min && $application->jobOffer->salary_max)
                                    {{ number_format($application->jobOffer->salary_min, 0, ',', ' ') }} - {{ number_format($application->jobOffer->salary_max, 0, ',', ' ') }} €/an
                                @elseif($application->jobOffer->salary_min)
                                    À partir de {{ number_format($application->jobOffer->salary_min, 0, ',', ' ') }} €/an
                                @else
                                    Jusqu'à {{ number_format($application->jobOffer->salary_max, 0, ',', ' ') }} €/an
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.job-offers.show', $application->jobOffer->slug) }}" 
                           class="text-iri-primary hover:text-iri-secondary font-medium text-sm">
                            Voir les détails de l'offre →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>
                        Documents
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($application->cv_path)
                        <a href="{{ route('admin.job-applications.download-cv', $application) }}" 
                           class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                            <i class="fas fa-file-pdf text-blue-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-blue-900">Curriculum Vitae</p>
                                <p class="text-xs text-blue-600">PDF - Cliquez pour télécharger</p>
                            </div>
                        </a>
                    @endif
                    
                    @if($application->portfolio_path)
                        <a href="{{ route('admin.job-applications.download-portfolio', $application) }}" 
                           class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                            <i class="fas fa-briefcase text-purple-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-purple-900">Portfolio</p>
                                <p class="text-xs text-purple-600">PDF - Cliquez pour télécharger</p>
                            </div>
                        </a>
                    @endif
                    
                    @if(!$application->cv_path && !$application->portfolio_path)
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-file-alt text-2xl mb-2 opacity-50"></i>
                            <p class="text-sm">Aucun document joint</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-tools mr-2"></i>
                        Actions
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <button onclick="window.print()" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-print mr-2"></i>
                        Imprimer la candidature
                    </button>
                    
                    <form method="POST" action="{{ route('admin.job-applications.destroy', $application) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette candidature ? Cette action est irréversible.')"
                          class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer la candidature
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .bg-gradient-to-r {
            background: #1e472f !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endpush
