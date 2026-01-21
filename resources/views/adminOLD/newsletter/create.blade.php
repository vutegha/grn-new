@extends('layouts.app')

@section('content')
    <h1>Créer un Newsletter</h1>
    <form method="POST" action="{{ route('newsletter.store') }}">
        @csrf
        <!-- Champs à définir ici -->
        <button type="submit">Enregistrer</button>
    </form>
@endsection