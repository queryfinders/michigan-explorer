<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="section-title mb-0" style="font-family: var(--font-heading); color: var(--heading-color); font-weight: 700;">{{ $title }}</h2>
        @if(isset($subtitle))
            <p class="section-subtitle mb-0 mt-2 text-muted" style="font-size: 1.1rem; max-width: 600px;">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actionUrl) && isset($actionText))
        <a href="{{ $actionUrl }}" class="btn btn-link text-primary fw-bold text-decoration-none d-none d-md-block" style="font-size: 1.05rem;">
            {{ $actionText }} <i class="fas fa-arrow-right ms-1"></i>
        </a>
    @endif
</div>
@if(isset($actionUrl) && isset($actionText))
    <div class="d-md-none mb-4">
        <a href="{{ $actionUrl }}" class="btn btn-link text-primary fw-bold text-decoration-none p-0" style="font-size: 1.05rem;">
            {{ $actionText }} <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
@endif
