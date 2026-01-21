@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Creer une Publication')
    <h1>Créer un Publication</h1>
    

<div class="w-full px-6 py-6 mx-auto">
        <!-- liste 1 -->
       @php
            $formAction = route('admin.publication.store');
        @endphp
        @include('admin.publication._form')
     
       
</div>
    
@endsection