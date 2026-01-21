@extends('layouts.app')

@section('content')
    <h1>Liste des Categories</h1>
    <a href="{{ route('categorie.create') }}">Créer</a>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->id }} - <a href="{{ route('categorie.edit', $item) }}">Modifier</a></li>
        @endforeach
    </ul>
@endsection