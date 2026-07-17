@extends('layouts/layoutMaster')
@section('title', 'Edit Search Shortcut')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Dashboard / Settings / Search Shortcuts /</span> Edit Shortcut
    </h4>

    <form action="{{ route('search-shortcuts.update', $searchShortcut->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('new_content.admin.search_shortcuts._form')
    </form>
</div>

@endsection
