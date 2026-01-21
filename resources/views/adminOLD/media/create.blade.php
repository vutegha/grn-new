@extends('layouts.app')

@section('content')
    <h1>Créer un Media</h1>
    <form method="POST" action="{{ route('media.store') }}">
        @csrf
        <!-- Champs à définir ici -->
        <button type="submit">Enregistrer</button>
    </form>
@endsection