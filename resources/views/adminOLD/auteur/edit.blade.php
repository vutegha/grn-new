@extends('layouts.app')

@section('content')
    <h1>Modifier Auteur</h1>
    <form method="POST" action="{{ route('auteur.update', $item) }}">
        @csrf
        @method('PUT')
        <!-- Champs à définir ici -->
        <button type="submit">Mettre à jour</button>
    </form>
@endsection