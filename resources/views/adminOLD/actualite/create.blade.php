@extends('layouts.app')

@section('content')
    <h1>Créer un Actualite</h1>
    <form method="POST" action="{{ route('actualite.store') }}">
        @csrf
        <!-- Champs à définir ici -->
        <button type="submit">Enregistrer</button>
    </form>
@endsection