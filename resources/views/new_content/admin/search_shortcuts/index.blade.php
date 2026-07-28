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

<div class="card">
    <div class="table-responsive pt-0">
        <table class="table table-hover" id="shortcutsTable">
            <thead>
                <tr>
                    <th>SR NO</th>
                    <th>Icon</th>
                    <th>Title</th>
                    <th>Action Type</th>
                    <th>Destination</th>
                    <th>Clicks</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sortableList">
                @foreach($searchShortcuts as $shortcut)
                <tr data-id="{{ $shortcut->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($shortcut->icon)
                            <i class="{{ $shortcut->icon }} {{ $shortcut->icon_color }} fs-4"></i>
                        @endif
                    </td>
                    <td><strong>{{ $shortcut->title }}</strong></td>
                    <td><span class="badge bg-label-info">{{ $shortcut->action_type->label() }}</span></td>
                    <td><a href="{{ route('web.search_shortcuts.track', $shortcut->id) }}" target="_blank" class="text-primary small text-truncate d-inline-block" style="max-width:200px;">{{ $shortcut->target_url }}</a></td>
                    <td>
                        <div><span class="fw-bold">{{ number_format($shortcut->click_count) }}</span> Clicks</div>
                        @if($shortcut->last_clicked_at)
                            <small class="text-muted" title="{{ $shortcut->last_clicked_at }}">Last: {{ $shortcut->last_clicked_at->diffForHumans() }}</small>
                        @endif
                    </td>
                    <td>
                        <label class="switch">
                            <input class="switch-input status-toggle" type="checkbox" data-id="{{ $shortcut->id }}" {{ $shortcut->status ? 'checked' : '' }}>
                            <span class="switch-toggle-slider"></span>
                        </label>
                    </td>
                    <td>
                        <a href="{{ route('search-shortcuts.edit', $shortcut->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                        <form action="{{ route('search-shortcuts.destroy', $shortcut->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
                
                @if($searchShortcuts->isEmpty())
                <tr>
                    <td colspan="8" class="text-center">No shortcuts found. Create one!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
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
