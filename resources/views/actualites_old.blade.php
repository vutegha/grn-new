@extends('layouts.iri')

@section('content')
<!-- <div class="max-w-6xl mx-auto px-6 py-12 bg-light-green">
    <h2 class="text-xl font-bold text-olive border-b-4 border-olive inline-block mb-8">
        YOU MAY ALSO LIKE
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-10 gap-y-12">

        <!-- Carte 1 --
        <div class="bg-olive rounded-xl shadow hover:shadow-xl transform hover:scale-105 transition duration-300 flex flex-col justify-between h-[320px] overflow-hidden group p-4">
            <img src="https://via.placeholder.com/400x250" alt="..." class="w-full h-40 object-cover rounded-t-xl">
            <div class="flex flex-col justify-end flex-1 mt-4">
                <div class="text-xs font-bold uppercase text-light-green mb-1">Featured • News</div>
                <h3 class="text-sm font-semibold text-light-gray leading-snug">
                    Training on Innovative Land Tools in Zambia; Adaptation towards Emerging Social Issues
                </h3>
            </div>
        </div>

        <!-- Carte 2 --
        <div class="bg-olive rounded-xl shadow hover:shadow-xl transform hover:scale-105 transition duration-300 flex flex-col justify-between h-[320px] overflow-hidden group p-4">
            <img src="https://via.placeholder.com/400x250" alt="..." class="w-full h-40 object-cover rounded-t-xl">
            <div class="flex flex-col justify-end flex-1 mt-4">
                <div class="text-xs font-bold uppercase text-beige mb-1">
                    Featured • GLTN Publications • News
                </div>
                <h3 class="text-sm font-semibold text-light-gray leading-snug">
                    Join Us in Celebrating International Women’s Day 2025: Strengthening Gender Equality
                </h3>
            </div>
        </div>

        <!-- Carte 3 --
        <div class="bg-olive rounded-xl shadow hover:shadow-xl transform hover:scale-105 transition duration-300 flex flex-col justify-between h-[320px] overflow-hidden group p-4">
            <img src="https://via.placeholder.com/400x250" alt="..." class="w-full h-40 object-cover rounded-t-xl">
            <div class="flex flex-col justify-end flex-1 mt-4">
                <div class="text-xs font-bold uppercase text-coral mb-1">
                    Featured • Partner Publications
                </div>
                <h3 class="text-sm font-semibold text-light-gray leading-snug">
                    Climate Responsible Land Governance and Disaster Resilience
                </h3>
            </div>
        </div>

        <!-- Carte 4 --
        <div class="bg-olive rounded-xl shadow hover:shadow-xl transform hover:scale-105 transition duration-300 flex flex-col justify-between h-[320px] overflow-hidden group p-4">
            <img src="https://via.placeholder.com/400x250" alt="..." class="w-full h-40 object-cover rounded-t-xl">
            <div class="flex flex-col justify-end flex-1 mt-4">
                <div class="text-xs font-bold uppercase text-coral mb-1">
                    Partner Publications
                </div>
                <h3 class="text-sm font-semibold text-light-gray leading-snug">
                    Housing and Property Restitution for Refugees and Displaced Persons
                </h3>
            </div>
        </div>

    </div>
</div> -->



<div class="max-w-6xl mx-auto px-6 py-12 bg-grayish">
   








    <h2 class="text-xl font-bold text-[#505c10] border-b-4 border-[#505c10] inline-block mb-8">
        ACTUALITÉS
    </h2>

    @if($actualites->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        @foreach($actualites as $item)
        <div class="group bg-olive rounded-xl shadow hover:shadow-xl transform hover:scale-105 transition duration-300 flex flex-col" x-data="{ open: false }">
            <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/img/iri.jpg') }}" 
                 alt="{{ $item->titre }}" class="rounded-t-xl w-full h-48 object-cover">

            <div class="p-4 flex flex-col space-y-1 flex-1">
                <div class="text-xs font-bold uppercase text-orange-300">
                    Actualité • {{ $item->created_at->format('d M Y') }}
                </div>

                <h3 class="text-sm font-semibold text-[#dde3da] leading-snug">
                    {{ $item->titre }}
                </h3>

                <button @click="open = !open" 
                        class="mt-2 text-xs text-[#dde3da] hover:text-[#ee6751] underline focus:outline-none transition">
                    <span x-text="open ? 'Masquer le résumé' : 'En savoir plus'"></span>
                </button>

                <div x-show="open" x-transition class="mt-2 text-[#dde3da] text-xs">
                    {{ Str::limit($item->resume, 200) }}
                </div>

                <div class="mt-2">
                    <a href="{{ route('site.actualite', ['slug' => $item->slug]) }}"
                       class="inline-block btn-light-green text-xs font-semibold transition">
                        Lire plus →
                    </a>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="mt-10 flex justify-center">
        {{ $actualites->links('pagination::tailwind') }}
    </div>
    @else
        <div class="text-center text-[#505c10]">
            Aucune actualité disponible pour le moment.
        </div>
    @endif
</div>


@endsection
