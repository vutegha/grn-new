@extends('layouts.iri')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12 bg-grayish">
    <h1 class="text-3xl font-bold text-olive mb-4">
    Résultats pour : "{{ $query }}"
</h1>

@if($totalResults > 0)
    <p class="text-sm text-olive mb-8">
        {{ $totalResults }} résultat{{ $totalResults > 1 ? 's' : '' }} trouvé{{ $totalResults > 1 ? 's' : '' }}
        en {{ $elapsed }} seconde{{ $elapsed > 1 ? 's' : '' }}.
    </p>
@endif


    @php
    function extractAndHighlight($text, $keyword, $length = 80) {
        $keyword = trim($keyword);
        if (!$keyword) return Str::limit($text, $length);

        $pos = stripos($text, $keyword);
        if ($pos === false) return Str::limit($text, $length);

        $start = max($pos - intval($length / 2), 0);
        $snippet = substr($text, $start, $length);
        return preg_replace("/(" . preg_quote($keyword, '/') . ")/i", '<mark>$1</mark>', $snippet);
    }
    @endphp

    @forelse($results as $item)
        <div class="flex items-start mb-6 p-4 bg-light-green rounded-lg shadow-sm hover:shadow-lg hover:-translate-y-1 transition duration-300 cursor-pointer group"
            @if($item->type_global === 'Publication')
                onclick="window.location='{{ route('publication.show', ['slug' => $item->slug]) }}'"
            @elseif($item->type_global === 'Actualité')
                onclick="window.location='{{ route('site.actualite', ['slug' => $item->slug]) }}'"
            @elseif($item->type_global === 'Rapport')
                onclick="window.open('{{ asset('storage/'.$item->fichier) }}', '_blank')"
            @endif
        >
            <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded bg-gray-200 mr-4">
                @if($item->type_global === 'Actualité' && $item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" 
                         alt="{{ $item->titre ?? 'Actualité' }}" 
                         class="w-full h-full object-cover" />
                @elseif($item->type_global === 'Publication' && $item->fichier_pdf)
                    <img src="{{ asset('assets/img/pdf-placeholder.jpg') }}" 
                         alt="PDF" 
                         class="w-full h-full object-cover" />
                @elseif($item->type_global === 'Rapport' && $item->fichier)
                    <img src="{{ asset('assets/img/pdf-placeholder.jpg') }}" 
                         alt="Rapport" 
                         class="w-full h-full object-cover" />
                @else
                    <img src="{{ asset('assets/img/iri.jpg') }}" 
                         alt="Default" 
                         class="w-full h-full object-cover" />
                @endif
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-bold text-olive group-hover:text-coral transition">
                        {{ $item->titre }}
                    </h2>

                    @if(($item->type_global === 'Publication' && $item->fichier_pdf) || ($item->type_global === 'Rapport' && $item->fichier))
                        @php
                            $fileUrl = $item->type_global === 'Publication' 
                                ? Storage::url($item->fichier_pdf)
                                : Storage::url($item->fichier);
                            $ext = strtoupper(pathinfo($fileUrl, PATHINFO_EXTENSION));
                        @endphp
                        <span class="bg-coral bg-opacity-80 text-light-gray text-xs font-bold px-2 py-0.5 shadow">
                            {{ $ext }}
                        </span>
                    @endif
                </div>

                <p class="text-sm text-olive italic mt-1">
                    <span class="uppercase font-semibold">{{ $item->type_global }}</span>
                    | {{ $item->date_global->format('d M Y') }}
                    @if(property_exists($item, 'categorie') && $item->categorie)
                        | Catégorie: <span class="font-semibold">{{ $item->categorie->nom }}</span>
                    @endif
                </p>

                <p class="text-olive mt-2 text-sm">
                    @if($item->type_global === 'Publication' && $item->resume)
                        {!! extractAndHighlight($item->resume, $query) !!}
                    @elseif($item->type_global === 'Rapport' && $item->resume)
                        {!! extractAndHighlight($item->resume, $query) !!}
                    @elseif($item->type_global === 'Actualité' && $item->contenu)
                        {!! extractAndHighlight($item->contenu, $query) !!}
                    @else
                        Aucune description disponible.
                    @endif
                </p>
            </div>
        </div>
    @empty
        <p class="text-olive italic">Aucun résultat trouvé pour "{{ $query }}".</p>
    @endforelse

    <div class="mt-8 flex justify-center">
        {{ $results->links('pagination::tailwind') }}
    </div>
</div>
@endsection
