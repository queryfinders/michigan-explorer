{{-- 
    File: index.blade.php
    Description: Dynamic search related view component.
    Part of the Michigan Explorer dynamic search system.
--}}
@extends('layouts/layoutMaster')
@section('title', 'Search Shortcuts')
@section('content')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-1">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
        <li class="breadcrumb-item active" aria-current="page">Search Shortcuts</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Search Shortcuts</h3>
        <p class="text-muted mb-0">Manage shortcuts for the search system.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('search-shortcuts.create') }}" class="btn btn-warning text-white">Add Shortcut</a>
    </div>
</div>

<div class="card" id="ajax-table-container">
    @include('new_content.admin.search_shortcuts._table')
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('sortableList');
    if (el && el.querySelectorAll('tr[data-id]').length > 0) {
        new Sortable(el, {
            animation: 150,
            onEnd: function () {
                const orderedIds = Array.from(el.querySelectorAll('tr')).map(row => row.dataset.id);
                fetch("{{ route('search-shortcuts.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ordered_ids: orderedIds })
                }).then(response => response.json())
                  .then(data => {
                      if(data.success) {
                          // Toast notification could go here
                      }
                  });
            }
        });
    }

    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.checked ? 1 : 0;
            fetch(`/admin/search-shortcuts/status/${id}/${status}`)
                .then(response => response.json())
                .then(data => {
                    // Handled
                });
        });
    });
});
</script>

@endsection
