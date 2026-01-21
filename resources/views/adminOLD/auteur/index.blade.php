@extends('layouts.app')

@section('content')
    <h1>Liste des Auteurs</h1>
    <a href="{{ route('auteur.create') }}">Créer</a>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->id }} - <a href="{{ route('auteur.edit', $item) }}">Modifier</a></li>
        @endforeach
    </ul>
@endsection