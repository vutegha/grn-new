@extends('layouts.app')

@section('content')
    <h1>Liste des Actualites</h1>
    <a href="{{ route('actualite.create') }}">Créer</a>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->id }} - <a href="{{ route('actualite.edit', $item) }}">Modifier</a></li>
        @endforeach
    </ul>
@endsection