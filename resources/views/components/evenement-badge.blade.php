@props(['evenement'])

@php
    $now = now();
    if ($evenement->date_evenement && $evenement->date_evenement > $now) {
        $badgeClass = 'bg-green-100 text-green-800 border-green-200';
        $badgeText = 'À venir';
        $badgeIcon = 'fas fa-clock';
    } elseif ($evenement->date_evenement && $evenement->date_evenement <= $now) {
        $badgeClass = 'bg-gray-100 text-gray-800 border-gray-200';
        $badgeText = 'Passé';
        $badgeIcon = 'fas fa-check';
    } else {
        $badgeClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
        $badgeText = 'Non défini';
        $badgeIcon = 'fas fa-question';
    }
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $badgeClass }}">
    <i class="{{ $badgeIcon }} mr-1"></i>
    {{ $badgeText }}
</span>
