<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="section-title mb-0 font-heading text-heading fw-bold">{{ $title }}</h2>
        @if(isset($subtitle))
            <p class="section-subtitle mb-0 mt-2 text-muted fs-11rem mw-600px">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actionUrl) && isset($actionText))
        <a href="{{ $actionUrl }}" class="btn btn-link text-primary fw-bold text-decoration-none d-none d-md-block fs-105rem">
            {{ $actionText }} <i class="fas fa-arrow-right ms-1"></i>
        </a>
    @endif
</div>
@if(isset($actionUrl) && isset($actionText))
    <div class="d-md-none mb-4">
        <a href="{{ $actionUrl }}" class="btn btn-link text-primary fw-bold text-decoration-none p-0 fs-105rem">
            {{ $actionText }} <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
@endif
