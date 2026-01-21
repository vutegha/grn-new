@extends('layouts.app')

@section('content')
    <h1>Liste des Medias</h1>
    <a href="{{ route('media.create') }}">Créer</a>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->id }} - <a href="{{ route('media.edit', $item) }}">Modifier</a></li>
        @endforeach
    </ul>
@endsection