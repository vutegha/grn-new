@extends('layouts.app')

@section('content')
    <h1>Modifier Newsletter</h1>
    <form method="POST" action="{{ route('newsletter.update', $item) }}">
        @csrf
        @method('PUT')
        <!-- Champs à définir ici -->
        <button type="submit">Mettre à jour</button>
    </form>
@endsection