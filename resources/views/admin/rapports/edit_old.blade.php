@extends('layouts.admin')

@section('content')
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
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-xl font-bold mb-4">Modifier le rapport</h1>
    <form action="{{ route('admin.rapports.update', $rapport) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.rapports._form', ['rapport' => $rapport])
    </form>
</div>
@endsection
