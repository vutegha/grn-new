@extends('layouts.admin')

@section('content')
@section('title', 'Liste des rapports')



<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">📄 Liste des rapports</h1>
        <a href="{{ route('admin.rapports.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Nouveau rapport
        </a>
    </div>

    {{-- Filtres --}}
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label for="annee" class="block text-sm font-medium text-gray-700">Filtrer par année</label>
            <select name="annee" id="annee"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Toutes les années</option>
                @foreach($annees as $a)
                    <option value="{{ $a }}" {{ request('annee') == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="categorie" class="block text-sm font-medium text-gray-700">Catégorie</label>
            <select name="categorie" id="categorie"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                🔍 Filtrer
            </button>
        </div>
    </form>

    @if(session('alert'))
        <div class="mb-4">{!! session('alert') !!}</div>
    @endif

    {{-- Tableau --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Catégorie</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rapports as $rapport)
                    <tr>
                        <td class="px-4 py-2">{{ $rapport->titre }}</td>
                        <td class="px-4 py-2">{{ $rapport->date_publication }}</td>
                        <td class="px-4 py-2">{{ $rapport->categorie->nom ?? 'N/A' }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('admin.rapports.edit', $rapport) }}" class="text-indigo-600 hover:underline">✏️ Modifier</a>
                            <form action="{{ route('admin.rapports.destroy', $rapport) }}" method="POST"
                                  onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">🗑️ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">Aucun rapport trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $rapports->appends(request()->query())->links() }}
    </div>
</div>

@endsection
