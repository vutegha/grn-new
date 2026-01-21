@extends('layouts.app')

@section('content')
    <h1>Créer un Auteur</h1>
    <form method="POST" action="{{ route('auteur.store') }}">
        @csrf
        <!-- Champs à définir ici -->
        <button type="submit">Enregistrer</button>
    </form>
@endsection