@extends('layouts.app')

@section('content')
    <h1>Liste des Services</h1>
    <a href="{{ route('service.create') }}">Créer</a>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->id }} - <a href="{{ route('service.edit', $item) }}">Modifier</a></li>
        @endforeach
    </ul>
@endsection