@extends('layouts.admin')

@section('title', 'Modifier Information de Contact')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-edit text-iri-primary mr-3"></i>
            Modifier Information de Contact
        </h1>
        <nav class="text-sm breadcrumbs">
            <ol class="flex space-x-2 text-gray-600">
                @foreach($breadcrumbs as $breadcrumb)
                    @if($breadcrumb['url'])
                        <li><a href="{{ $breadcrumb['url'] }}" class="hover:text-iri-primary">{{ $breadcrumb['name'] }}</a></li>
                        <li>/</li>
                    @else
                        <li class="text-gray-900 font-medium">{{ $breadcrumb['name'] }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.contact-info.update', $contactInfo) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.contact-info._form', ['contactInfo' => $contactInfo])
    </form>
</div>
@endsection
