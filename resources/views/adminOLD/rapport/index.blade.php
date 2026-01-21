@extends('layouts.app')

@section('content')
    <h1>Liste des Rapports</h1>
    <a href="{{ route('rapport.create') }}">Créer</a>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->id }} - <a href="{{ route('rapport.edit', $item) }}">Modifier</a></li>
        @endforeach
    </ul>
@endsection