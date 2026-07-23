{{-- 
    File: create.blade.php
    Description: Dynamic search related view component.
    Part of the Michigan Explorer dynamic search system.
--}}
@extends('layouts/layoutMaster')
@section('title', 'Add Search Shortcut')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Dashboard / Settings / Search Shortcuts /</span> Create Shortcut
    </h4>

    <form action="{{ route('search-shortcuts.store') }}" method="POST">
        @csrf
        @include('new_content.admin.search_shortcuts._form')
    </form>
</div>

@endsection
