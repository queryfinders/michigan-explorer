@include('layouts.messages')

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label" for="name">Link Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $affiliateLink->name ?? '') }}" placeholder="e.g. Booking.com - Grand Hotel" required />
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="provider">Provider</label>
            <input type="text" class="form-control @error('provider') is-invalid @enderror" id="provider" name="provider"
                value="{{ old('provider', $affiliateLink->provider ?? '') }}" placeholder="e.g. Booking.com, Expedia, OpenTable" />
            @error('provider') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="link">Affiliate URL <span class="text-danger">*</span></label>
            <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link"
                value="{{ old('link', $affiliateLink->link ?? '') }}" placeholder="https://www.booking.com/hotel/..." required />
            @error('link') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                rows="3" placeholder="Optional notes about this affiliate link">{{ old('description', $affiliateLink->description ?? '') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $affiliateLink->is_active ?? 1) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light border-0 p-3">
            <h6 class="fw-bold mb-3"><i class="fa fa-info-circle me-2 text-primary"></i>How It Works</h6>
            <ul class="list-unstyled small text-muted mb-0">
                <li class="mb-2">✅ Create an Affiliate Link here</li>
                <li class="mb-2">✅ Assign it to Hotels &amp; Restaurants</li>
                <li class="mb-2">✅ Visitor clicks "Book Now"</li>
                <li class="mb-2">✅ System logs the click</li>
                <li class="mb-2">✅ Visitor is redirected instantly</li>
            </ul>
        </div>

        @isset($affiliateLink)
        <div class="card border-0 p-3 mt-3" style="background: #f0fff4;">
            <h6 class="fw-bold mb-3"><i class="fa fa-chart-bar me-2 text-success"></i>Click Stats</h6>
            <div class="d-flex justify-content-between">
                <span class="text-muted small">Total Clicks</span>
                <strong>{{ number_format($affiliateLink->total_clicks) }}</strong>
            </div>
            <div class="mt-2">
                <a href="{{ route('affiliate-links.show', $affiliateLink->id) }}" class="btn btn-sm btn-outline-success w-100">
                    <i class="fa fa-chart-line me-1"></i> View Full Analytics
                </a>
            </div>
        </div>
        @endisset
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        {{ isset($affiliateLink) ? 'Update Affiliate Link' : 'Create Affiliate Link' }}
    </button>
    <a href="{{ route('affiliate-links.index') }}" class="btn btn-secondary">Cancel</a>
</div>
