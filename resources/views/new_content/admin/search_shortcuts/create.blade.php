{{-- 
    File: create.blade.php
    Description: Dynamic search related view component.
    Part of the Michigan Explorer dynamic search system.
--}}
@extends('layouts/layoutMaster')
@section('title', 'Add Search Shortcut')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('search-shortcuts.index') }}">Search Shortcuts</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Search Shortcut</li>
      </ol>
    </nav>

    <form action="{{ route('search-shortcuts.store') }}" method="POST">
        @csrf
        @include('new_content.admin.search_shortcuts._form')
    </form>
</div>

@endsection
