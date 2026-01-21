@extends('layouts.app')

@section('content')
    <h1>Modifier Categorie</h1>
    <form method="POST" action="{{ route('categorie.update', $item) }}">
        @csrf
        @method('PUT')
        <!-- Champs à définir ici -->
        <button type="submit">Mettre à jour</button>
    </form>
@endsection