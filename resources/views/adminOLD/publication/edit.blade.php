@extends('layouts.admin')

@section('content')
    <h1>Modifier Publication</h1>
    <form method="PUT" action="{{ route('publication.update', $item) }}">
        @csrf
        @method('PUT')
        <!-- Champs à définir ici -->
        <button type="submit">Mettre à jour</button>
    </form>
@endsection