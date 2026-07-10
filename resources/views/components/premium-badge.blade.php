@props([
    'type' => 'featured', // featured, rating, category
    'text' => 'Featured',
    'icon' => null
])

@php
    $baseClasses = 'badge rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1';
    
    $typeClasses = match($type) {
        'featured' => 'bg-secondary text-white px-3 py-2',
        'rating' => 'bg-white text-heading px-2 py-1',
        'category' => 'bg-primary text-white px-3 py-2',
        default => 'bg-light text-dark px-3 py-2'
    };
@endphp

<span class="{{ $baseClasses }} {{ $typeClasses }}" style="font-size: 0.85rem;">
    @if($icon)
        <i class="{{ $icon }}"></i>
    @elseif($type === 'rating')
        <i class="fas fa-star text-secondary"></i>
    @endif
    {{ $text }}
</span>
