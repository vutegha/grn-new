@extends('layouts.app')

@section('content')
    <h1>Créer un Service</h1>
    <form method="POST" action="{{ route('service.store') }}">
        @csrf
        <!-- Champs à définir ici -->
        <button type="submit">Enregistrer</button>
    </form>
@endsection