@extends('layouts.app')

@section('content')
    <h1>Créer un Rapport</h1>
    <form method="POST" action="{{ route('rapport.store') }}">
        @csrf
        <!-- Champs à définir ici -->
        <button type="submit">Enregistrer</button>
    </form>
@endsection