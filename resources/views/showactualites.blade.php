@extends('layouts.iri')

@section('content')
<!-- Section en-tête avec fond dégradé -->
<div class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 text-white">
    <div class="max-w-6xl mx-auto px-4 py-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Actualités</h1>
        <p class="text-blue-100 text-lg md:text-xl max-w-3xl">
            Découvrez les dernières nouvelles et événements du GRN-UCBC
        </p>
    </div>
</div>

<!-- Section principale avec arrière-plan subtil -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-12">

        @if($actualites->count())
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($actualites as $item)
                    <article class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        @if($item->image)
                            <div class="relative overflow-hidden">
                                <img src="{{ asset('storage/' . $item->image) }}" 
                                     alt="{{ $item->titre }}" 
                                     class="h-56 w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-grow">
                            <h2 class="text-xl font-bold text-gray-900 mb-3 leading-tight">
                                {{ Str::limit($item->titre, 60) }}
                            </h2>

                            <p class="text-gray-700 mb-6 flex-grow leading-relaxed">
                                {{ Str::limit($item->resume, 120) }}
                            </p>

                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                </div>

                                <a href="{{ route('site.actualite.id', ['id' => $item->id]) }}"
                                   class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition-all duration-200 hover:shadow-md group">
                                    <span>Lire plus</span>
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination si nécessaire -->
            @if(method_exists($actualites, 'links'))
                <div class="mt-12 flex justify-center">
                    {{ $actualites->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune actualité disponible</h3>
                <p class="text-gray-600">
                    Les actualités seront publiées ici dès qu'elles seront disponibles.
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
