@props(['column', 'label', 'align' => 'left'])
@php
    $currentSort = request('sort');
    $currentDirection = request('direction', 'desc');
    $isSorted = $currentSort === $column;
    
    // Determine the next direction when clicking this header
    $nextDirection = 'asc';
    if ($isSorted) {
        $nextDirection = $currentDirection === 'asc' ? 'desc' : 'asc';
    }
    
    $url = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection]);
@endphp

<th class="{{ $align === 'center' ? 'text-center' : ($align === 'right' ? 'text-end' : '') }}">
    <a href="{{ $url }}" class="ajax-sortable text-dark text-decoration-none d-inline-flex align-items-center gap-1">
        {{ $label }}
        @if($isSorted)
            <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
        @else
            <i class="fas fa-sort text-muted" style="opacity: 0.3;"></i>
        @endif
    </a>
</th>
