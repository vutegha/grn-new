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
                <a href="{{ route('admin.job-offers.index') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm font-medium">
                    Offres d'Emploi
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/40 text-xs"></i>
                <span class="text-white font-medium text-sm">Statistiques</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Statistiques des Offres d\'Emploi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Statistiques des Offres d'Emploi</h1>
                <p class="text-gray-600">Vue d'ensemble des performances et métriques</p>
            </div>
            <a href="{{ route('admin.job-offers.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux offres
            </a>
        </div>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-briefcase text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Offres</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_offers'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Offres Actives</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_offers'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Candidatures</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_applications'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Vues Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_views'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Répartition par statut -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-chart-pie mr-3"></i>
                    Répartition par Statut
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($stats['by_status'] ?? [] as $status => $count)
                        @php
                            $statusConfig = [
                                'active' => ['color' => 'green', 'icon' => 'fa-check-circle', 'text' => 'Actives'],
                                'draft' => ['color' => 'gray', 'icon' => 'fa-edit', 'text' => 'Brouillons'],
                                'paused' => ['color' => 'yellow', 'icon' => 'fa-pause-circle', 'text' => 'En pause'],
                                'closed' => ['color' => 'red', 'icon' => 'fa-times-circle', 'text' => 'Fermées']
                            ];
                            $config = $statusConfig[$status] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => $status];
                            $percentage = ($stats['total_offers'] ?? 0) > 0 ? round(($count / $stats['total_offers']) * 100) : 0;
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }}-600 mr-2"></i>
                                <span class="text-gray-700">{{ $config['text'] }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-{{ $config['color'] }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900 w-12 text-right">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Répartition par source -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-iri-secondary to-iri-primary px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-chart-donut mr-3"></i>
                    Répartition par Source
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($stats['by_source'] ?? [] as $source => $count)
                        @php
                            $sourceConfig = [
                                'internal' => ['color' => 'blue', 'icon' => 'fa-building', 'text' => 'Internes'],
                                'partner' => ['color' => 'green', 'icon' => 'fa-handshake', 'text' => 'Partenaires']
                            ];
                            $config = $sourceConfig[$source] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => $source];
                            $percentage = ($stats['total_offers'] ?? 0) > 0 ? round(($count / $stats['total_offers']) * 100) : 0;
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }}-600 mr-2"></i>
                                <span class="text-gray-700">{{ $config['text'] }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-{{ $config['color'] }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900 w-12 text-right">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Offres les plus performantes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
            <h3 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-trophy mr-3"></i>
                Top 10 des Offres les Plus Performantes
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidatures</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vues</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux Conv.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($stats['top_offers'] ?? [] as $offer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-briefcase text-purple-600 text-sm"></i>
                                    </div>
                                    <div class="ml-3">
                                        <a href="{{ route('admin.job-offers.show', $offer) }}" 
                                           class="text-sm font-medium text-gray-900 hover:text-iri-primary">
                                            {{ Str::limit($offer->title, 40) }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $offer->location }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $offer->applications_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ $offer->views_count ?? 0 }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $views = $offer->views_count ?? 0;
                                    $applications = $offer->applications_count ?? 0;
                                    $conversionRate = $views > 0 ? round(($applications / $views) * 100, 1) : 0;
                                @endphp
                                <span class="text-sm font-medium {{ $conversionRate > 5 ? 'text-green-600' : ($conversionRate > 2 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $conversionRate }}%
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'active' => 'green',
                                        'draft' => 'gray',
                                        'paused' => 'yellow',
                                        'closed' => 'red'
                                    ];
                                    $color = $statusColors[$offer->status] ?? 'gray';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ ucfirst($offer->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-chart-line text-4xl mb-4 opacity-50"></i>
                                <p>Aucune donnée disponible</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Métriques temporelles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-calendar mr-2"></i>
                    Cette Période
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Cette semaine</span>
                    <span class="font-semibold text-gray-900">{{ $stats['this_week'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Ce mois</span>
                    <span class="font-semibold text-gray-900">{{ $stats['this_month'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Cette année</span>
                    <span class="font-semibold text-gray-900">{{ $stats['this_year'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Moyennes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-calculator mr-2"></i>
                    Moyennes
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Candidatures/Offre</span>
                    <span class="font-semibold text-gray-900">{{ $stats['avg_applications'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Vues/Offre</span>
                    <span class="font-semibold text-gray-900">{{ $stats['avg_views'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Taux de conversion</span>
                    <span class="font-semibold text-gray-900">{{ $stats['avg_conversion'] ?? 0 }}%</span>
                </div>
            </div>
        </div>

        <!-- Offres expirées -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alertes
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Offres expirées</span>
                    <span class="font-semibold text-red-600">{{ $stats['expired_offers'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Expirent sous 7j</span>
                    <span class="font-semibold text-yellow-600">{{ $stats['expiring_soon'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Sans candidature</span>
                    <span class="font-semibold text-gray-600">{{ $stats['no_applications'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des barres de progression
    const progressBars = document.querySelectorAll('.bg-green-500, .bg-blue-500, .bg-yellow-500, .bg-red-500, .bg-gray-500');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 100);
    });

    // Actualisation automatique des données
    setInterval(function() {
        if (!document.hidden) {
            // Optionnel: recharger les données via AJAX
            console.log('Refreshing stats...');
        }
    }, 60000); // Toutes les minutes
});
</script>
@endpush
