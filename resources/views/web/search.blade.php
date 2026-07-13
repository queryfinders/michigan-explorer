@extends('web.layout.app_layout')

@section('title', 'Search Results - Michigan Explorer')

@section('webLayoutContent')
<div class="search-hero bg-dark text-white py-5 text-center section-padding">
    <div class="container mt-5 pt-4">
        <h1 class="display-5 fw-bold section-title text-white">Search Results</h1>
        <p class="lead">Results for "{{ $q }}"</p>
        <form action="{{ route('web.search') }}" method="GET" class="mt-4 mx-auto search-form-wrapper">
            <div class="input-group input-group-lg shadow">
                <input type="text" class="form-control border-0 px-4" name="q" value="{{ $q }}" placeholder="Search for hotels, events, attractions..." required>
                <button class="btn btn-primary px-4 fw-bold" type="submit">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="container section-padding pb-5 mb-5">
    @if($results->count() > 0)
        <div class="row g-4">
            @foreach($results as $item)
            <div class="col-md-4">
                <div class="card premium-card h-100">
                    <div class="img-wrapper">
                        <img src="{{ $item->image ? asset($item->image) : 'https://placehold.co/600x400/e9ecef/495057?text=Result' }}" class="card-img-top result-img" alt="{{ $item->title }}">
                    </div>
                    <div class="card-body">
                        <span class="badge bg-primary mb-2 px-3 py-2 rounded-pill fw-bold">{{ $item->type }}</span>
                        <h5 class="card-title fw-bold mt-2">{{ $item->title }}</h5>
                        <p class="card-text text-muted small mt-2">{{ Str::limit($item->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-4 pt-0 text-center">
                        <a href="{{ $item->url }}" class="btn btn-outline-primary w-100 rounded-pill">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <h3 class="text-muted fw-bold">No results found.</h3>
            <p class="lead">Try adjusting your search terms.</p>
        </div>
    @endif
</div>
@endsection
