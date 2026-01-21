@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Créer un nouveau projet')

<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-6">Créer un nouveau projet</h1>
@if ($errors->any())
    <div class="mb-6 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
        <strong class="font-semibold">Une erreur est survenue :</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('alert'))
    <div class="mb-6">
        {!! session('alert') !!}
    </div>
@endif
@php
$formAction = route('admin.projets.store');
@endphp
        @include('admin.projets._form')
</div>
@endsection
