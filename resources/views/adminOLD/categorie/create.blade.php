@extends('layouts.app')

@section('content')
    <h1>Créer un Categorie</h1>
    <form method="POST" action="{{ route('categorie.store') }}">
        @csrf
        <!-- Champs à définir ici -->
        <button type="submit">Enregistrer</button>
    </form>
@endsection