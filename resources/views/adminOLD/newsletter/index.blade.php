@extends('layouts.app')

@section('content')
    <h1>Liste des Newsletters</h1>
    <a href="{{ route('newsletter.create') }}">Créer</a>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->id }} - <a href="{{ route('newsletter.edit', $item) }}">Modifier</a></li>
        @endforeach
    </ul>
@endsection