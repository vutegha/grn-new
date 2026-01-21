@extends('layouts.iri')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-4 gap-10 bg-light-green rounded-xl">

    <!-- Colonne principale -->
    <div class="lg:col-span-3 bg-light-gray rounded-xl shadow-lg p-6">
        <h1 class="text-3xl font-bold text-olive mb-6">
            {{ $actualite->titre }}
        </h1>

        <div class="flex items-center text-xs text-olive mb-6 space-x-4">
            <span>Publié le {{ $actualite->created_at->format('d M Y') }}</span>
            {{-- Catégorie supprimée car non disponible pour les actualités --}}
        </div>

        @if($actualite->hasImage())
            <img src="{{ $actualite->image_url }}"
                alt="{{ $actualite->titre }}"
                class="w-full h-auto object-contain bg-light-gray rounded-lg mb-8 shadow">


        @endif

        @if($actualite->resume)
            <div class="mb-6">
                <p class="text-olive text-sm italic font-bold mb-2">
                    {{ $actualite->resume }}
                </p>
            </div>
        @endif

        @if($actualite->texte)
            <div>
                <div class="text-olive text-sm leading-relaxed space-y-4">
                    {!! nl2br(e($actualite->texte)) !!}
                </div>
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('site.actualites') }}"
               class="inline-block text-olive hover:text-coral text-sm font-semibold underline transition">
                ← Retour à la liste des actualités
            </a>
        </div>
    </div>

    <!-- Colonne secondaire -->
    <div class="lg:col-span-1 lg:border-l border-olive pl-6 bg-light-gray rounded-xl shadow-lg p-4">
        <h3 class="text-lg font-bold text-olive mb-4 pb-2 border-b border-olive">
            Actualités récentes
        </h3>
        <ul class="text-md list-disc">
            @foreach($recentActualites as $recente)
                <li>
                    <a href="{{ route('site.actualite', ['slug' => $recente->slug]) }}"
                    class="text-md text-olive hover:text-coral hover:underline transition font-semibold block">
                        {{ Str::limit($recente->titre, 50) }} - <span class="text-xs text-coral ">{{ $recente->created_at->format('d M Y') }}</span>
                    
                        
                    </a>
                    
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
