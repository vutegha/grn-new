@extends('layouts.admin')
@section('content')

<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-xl font-bold mb-4">Créer un rapport</h1>
   {{-- Messages d’erreurs --}}
@if ($errors->any())
    <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <strong class="font-bold">Erreur :</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Message de succès --}}
@if (session('alert'))
    <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {!! session('alert') !!}
    </div>
@endif

    <form action="{{ route('admin.rapports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.rapports._form')
    </form>
</div>
@endsection
