{{-- 
    File: _form.blade.php
    Description: Dynamic search related view component.
    Part of the Michigan Explorer dynamic search system.
--}}
@php
    $actionTypes = \App\Enums\ActionType::cases();
    $colors = [
        'text-primary' => ['name' => 'Primary', 'hex' => '#0d6efd'],
        'text-secondary' => ['name' => 'Secondary', 'hex' => '#6c757d'],
        'text-success' => ['name' => 'Success', 'hex' => '#198754'],
        'text-danger' => ['name' => 'Danger', 'hex' => '#dc3545'],
        'text-warning' => ['name' => 'Warning', 'hex' => '#ffc107'],
        'text-info' => ['name' => 'Info', 'hex' => '#0dcaf0'],
        'text-dark' => ['name' => 'Dark', 'hex' => '#212529'],
        'text-white' => ['name' => 'White', 'hex' => '#ffffff']
    ];

    $popularIcons = [
        'fas fa-hotel', 'fas fa-utensils', 'fas fa-map-pin', 'fas fa-fire', 
        'fas fa-map-marker-alt', 'fas fa-calendar-alt', 'fas fa-star', 
        'fas fa-compass', 'fas fa-bed', 'fas fa-water', 'fas fa-cloud', 
        'fas fa-dice', 'fas fa-ticket-alt', 'fas fa-hiking', 'fas fa-campground',
        'fas fa-tree', 'fas fa-swimmer', 'fas fa-shopping-bag'
    ];
@endphp

<div class="row">
    <!-- Left Column: Form -->
    <div class="col-lg-8">
        
        <!-- Basic Information -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bx bx-info-circle text-primary me-2"></i>Basic Information</h5>
            </div>
            <div class="card-body pt-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="preview_title_input" class="form-control form-control-lg" value="{{ old('title', $searchShortcut->title ?? '') }}" placeholder="e.g. Indiana Dunes" required>
                    <small class="text-muted d-block mt-1">The main text displayed on the Search Shortcut chip.</small>
                </div>
            </div>
        </div>

        <!-- Appearance -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bx bx-palette text-primary me-2"></i>Appearance</h5>
            </div>
            <div class="card-body pt-4">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label fw-semibold">Icon</label>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" type="button" id="iconDropdownButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: #fff;">
                                <span><i id="selected_icon_display" class="{{ old('icon', $searchShortcut->icon ?? 'fas fa-star') }} me-2"></i> <span id="selected_icon_text">{{ old('icon', $searchShortcut->icon ?? 'fas fa-star') }}</span></span>
                                <i class="bx bx-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu w-100 p-3" aria-labelledby="iconDropdownButton">
                                <input type="text" class="form-control mb-3" id="iconSearch" placeholder="Search icons...">
                                <div class="d-flex flex-wrap gap-2 icon-picker-grid" id="iconGrid" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($popularIcons as $ic)
                                        <div class="icon-option p-2 border rounded cursor-pointer text-center" data-icon="{{ $ic }}" style="width: 45px; height: 45px; display:flex; align-items:center; justify-content:center;" title="{{ $ic }}">
                                            <i class="{{ $ic }} fs-5"></i>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="icon" id="icon_input" value="{{ old('icon', $searchShortcut->icon ?? 'fas fa-star') }}">
                        <small class="text-muted d-block mt-1">Search and select an icon for the chip.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Icon Color</label>
                        <select name="icon_color" id="icon_color_input" class="form-select">
                            <option value="">Default</option>
                            @foreach($colors as $class => $color)
                                <option value="{{ $class }}" data-hex="{{ $color['hex'] }}" {{ old('icon_color', $searchShortcut->icon_color ?? '') == $class ? 'selected' : '' }}>
                                    {{ $color['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Choose a color that matches the Michigan Explorer design system.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Configuration -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bx bx-link text-primary me-2"></i>Action Configuration</h5>
            </div>
            <div class="card-body pt-4">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Action Type <span class="text-danger">*</span></label>
                    <select name="action_type" id="action_type" class="form-select" required>
                        <option value="">Select Action Type</option>
                        @foreach($actionTypes as $type)
                            <option value="{{ $type->value }}" data-type="{{ $type->value }}" {{ old('action_type', isset($searchShortcut) ? $searchShortcut->action_type->value : '') == $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1">Choose what this shortcut should open.</small>
                </div>

                <div class="mb-3" id="action_value_container" style="display:none;">
                    <label class="form-label fw-semibold" id="action_value_label">Action Value <span class="text-danger">*</span></label>
                    <div id="input_wrapper">
                        <!-- Dynamically populated by JS -->
                    </div>
                    <input type="hidden" name="action_value" id="action_value_hidden" value="{{ old('action_value', $searchShortcut->action_value ?? '') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Live Preview & Settings -->
    <div class="col-lg-4">
        
        <!-- Live Preview -->
        <div class="card mb-4 shadow-sm border-0 border-top border-primary border-3">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bx bx-show text-primary me-2"></i>Live Preview</h5>
            </div>
            <div class="card-body pt-4 text-center pb-5" style="background-color: #f8f9fa;">
                <p class="small text-muted mb-3 fw-bold text-uppercase">Homepage View</p>
                <!-- Mimic Homepage chip -->
                <a href="javascript:void(0)" class="btn rounded-pill shadow-sm px-4 py-2" id="live_preview_chip" style="background-color: white; border: 1px solid #e9ecef; color: #333; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 15px;">
                    <i id="live_preview_icon" class="fas fa-star text-primary"></i> 
                    <span id="live_preview_title">Indiana Dunes</span>
                </a>
            </div>
        </div>

        <!-- Generated URL Card -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bx bx-link-external text-primary me-2"></i>Generated Destination</h5>
            </div>
            <div class="card-body pt-4">
                <div class="d-flex align-items-center mb-2">
                    <span id="url_valid_indicator" class="badge bg-label-secondary me-2"><i class="bx bx-question-mark"></i> Pending</span>
                    <small class="text-muted fw-semibold">Final URL</small>
                </div>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-link"></i></span>
                    <input type="text" id="url_preview_input" class="form-control" readonly value="Waiting for configuration..." style="background-color: #f8f9fa;">
                    <button class="btn btn-outline-primary" type="button" id="copyUrlBtn" title="Copy URL">
                        <i class="bx bx-copy"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bx bx-cog text-primary me-2"></i>Settings</h5>
            </div>
            <div class="card-body pt-4">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Open In</label>
                    <div class="d-flex gap-3">
                        <div class="form-check custom-radio">
                            <input class="form-check-input" type="radio" name="open_in" id="open_same" value="same_tab" {{ old('open_in', $searchShortcut->open_in ?? 'same_tab') == 'same_tab' ? 'checked' : '' }}>
                            <label class="form-check-label" for="open_same">Same Tab</label>
                        </div>
                        <div class="form-check custom-radio">
                            <input class="form-check-input" type="radio" name="open_in" id="open_new" value="new_tab" {{ old('open_in', $searchShortcut->open_in ?? '') == 'new_tab' ? 'checked' : '' }}>
                            <label class="form-check-label" for="open_new">New Tab</label>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Choose whether the destination opens in the current tab or a new tab.</small>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $searchShortcut->sort_order ?? 0) }}">
                    <small class="text-muted d-block mt-1">Lower numbers appear first on the Homepage.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Status</label>
                    <div class="form-check form-switch form-switch-lg">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', $searchShortcut->status ?? 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('search-shortcuts.index') }}" class="btn btn-label-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save Shortcut</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling for color select2 items */
    .color-option-circle {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin-right: 8px;
        vertical-align: middle;
    }
    .icon-option:hover {
        background-color: #f8f9fa;
        border-color: #0d6efd !important;
    }
    .icon-picker-grid {
        scrollbar-width: thin;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const actionTypeSelect = document.getElementById('action_type');
    const actionValueContainer = document.getElementById('action_value_container');
    const actionValueLabel = document.getElementById('action_value_label');
    const inputWrapper = document.getElementById('input_wrapper');
    const hiddenValue = document.getElementById('action_value_hidden');
    
    // Preview Elements
    const titleInput = document.getElementById('preview_title_input');
    const iconInput = document.getElementById('icon_input');
    const iconColorInput = document.getElementById('icon_color_input');
    
    const livePreviewChip = document.getElementById('live_preview_chip');
    const livePreviewTitle = document.getElementById('live_preview_title');
    const livePreviewIcon = document.getElementById('live_preview_icon');
    
    const urlPreviewInput = document.getElementById('url_preview_input');
    const urlValidIndicator = document.getElementById('url_valid_indicator');
    const copyUrlBtn = document.getElementById('copyUrlBtn');

    // Icon Picker Elements
    const iconSearch = document.getElementById('iconSearch');
    const iconGrid = document.getElementById('iconGrid');
    const iconOptions = document.querySelectorAll('.icon-option');
    const selectedIconDisplay = document.getElementById('selected_icon_display');
    const selectedIconText = document.getElementById('selected_icon_text');

    const appUrl = "{{ url('/') }}";
    
    // Data sources passed from controller
    const hotelCategories = @json($hotelCategories ?? []);
    const restaurantCategories = @json($restaurantCategories ?? []);
    const attractionCategories = @json($attractionCategories ?? []);
    const eventCategories = @json($eventCategories ?? []);
    const blogCategories = @json($blogCategories ?? []);

    // --- Live Preview Logic ---
    function updateLivePreview() {
        const title = titleInput.value || 'Title...';
        const iconClass = iconInput.value || '';
        const colorClass = iconColorInput.value || '';
        
        livePreviewTitle.textContent = title;
        
        if (iconClass) {
            livePreviewIcon.className = `${iconClass} ${colorClass}`;
            livePreviewIcon.style.display = 'inline-block';
        } else {
            livePreviewIcon.style.display = 'none';
        }
    }

    titleInput.addEventListener('input', updateLivePreview);
    iconInput.addEventListener('change', updateLivePreview);

    // --- Icon Picker Logic ---
    iconSearch.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        iconOptions.forEach(opt => {
            const iconName = opt.getAttribute('data-icon').toLowerCase();
            opt.style.display = iconName.includes(term) ? 'flex' : 'none';
        });
    });

    iconOptions.forEach(opt => {
        opt.addEventListener('click', function() {
            const iconName = this.getAttribute('data-icon');
            iconInput.value = iconName;
            selectedIconDisplay.className = `${iconName} me-2`;
            selectedIconText.textContent = iconName;
            updateLivePreview();
        });
    });

    // Fallback for Icon Color Change
    iconColorInput.addEventListener('change', updateLivePreview);

    // --- URL and Action Logic ---
    function generateSelectHTML(options, selectedValue, placeholder) {
        let html = '<select class="form-select action-value-dynamic-select">';
        html += `<option value="">${placeholder}</option>`;
        options.forEach(opt => {
            const isSelected = (opt.id == selectedValue || opt.slug == selectedValue) ? 'selected' : '';
            html += `<option value="${opt.id}" ${isSelected}>${opt.name}</option>`;
        });
        html += '</select>';
        return html;
    }

    function generateInputHTML(selectedValue, type = 'text', placeholder = '') {
        return `<input type="${type}" class="form-control action-value-dynamic-input" value="${selectedValue || ''}" placeholder="${placeholder}">`;
    }

    function updateUrlPreview(type, val) {
        if(!type || (type !== 'hotels' && type !== 'restaurants' && type !== 'attractions' && type !== 'events' && type !== 'travel_guides' && !val)) {
            urlPreviewInput.value = '';
            urlValidIndicator.className = 'badge bg-label-secondary me-2';
            urlValidIndicator.innerHTML = '<i class="bx bx-question-mark"></i> Pending';
            return;
        }
        
        let url = '';
        switch(type) {
            case 'global_search': url = `${appUrl}/search?keyword=${val}`; break;
            case 'hotels': url = `${appUrl}/search?tab=hotels`; break;
            case 'restaurants': url = `${appUrl}/search?tab=restaurants`; break;
            case 'attractions': url = `${appUrl}/search?tab=attractions`; break;
            case 'events': url = `${appUrl}/search?tab=events`; break;
            case 'travel_guides': url = `${appUrl}/search?tab=travel_guides`; break;
            case 'city': url = `${appUrl}/search?keyword=${val}`; break;
            case 'hotel_category': url = `${appUrl}/search?tab=hotels&category=${val}`; break;
            case 'restaurant_category': url = `${appUrl}/search?tab=restaurants&category=${val}`; break;
            case 'attraction_category': url = `${appUrl}/search?tab=attractions&category=${val}`; break;
            case 'event_category': url = `${appUrl}/search?tab=events&category=${val}`; break;
            case 'blog_category': url = `${appUrl}/search?tab=travel_guides&category=${val}`; break;
            case 'destination': url = `${appUrl}/search?keyword=${val}`; break;
            case 'custom_url': url = val ? (val.startsWith('http') ? val : `${appUrl}/${val.replace(/^\//, '')}`) : ''; break;
        }
        
        urlPreviewInput.value = url;
        if(url) {
            urlValidIndicator.className = 'badge bg-label-success me-2';
            urlValidIndicator.innerHTML = '<i class="bx bx-check"></i> Valid';
        }
    }

    function handleTypeChange() {
        const type = $(actionTypeSelect).val() || actionTypeSelect.value;
        const currentVal = hiddenValue.value;
        
        inputWrapper.innerHTML = '';
        
        const noValueTypes = ['hotels', 'restaurants', 'attractions', 'events', 'travel_guides'];
        
        if (!type || noValueTypes.includes(type)) {
            actionValueContainer.style.display = 'none';
            hiddenValue.value = '';
            updateUrlPreview(type, '');
            return;
        }
        
        actionValueContainer.style.display = 'block';

        if (type === 'hotel_category') {
            actionValueLabel.innerHTML = 'Hotel Category <span class="text-danger">*</span>';
            inputWrapper.innerHTML = generateSelectHTML(hotelCategories, currentVal, 'Search Hotel Category...');
        } else if (type === 'restaurant_category') {
            actionValueLabel.innerHTML = 'Restaurant Category <span class="text-danger">*</span>';
            inputWrapper.innerHTML = generateSelectHTML(restaurantCategories, currentVal, 'Search Restaurant Category...');
        } else if (type === 'attraction_category') {
            actionValueLabel.innerHTML = 'Attraction Category <span class="text-danger">*</span>';
            inputWrapper.innerHTML = generateSelectHTML(attractionCategories, currentVal, 'Search Attraction Category...');
        } else if (type === 'event_category') {
            actionValueLabel.innerHTML = 'Event Category <span class="text-danger">*</span>';
            inputWrapper.innerHTML = generateSelectHTML(eventCategories, currentVal, 'Search Event Category...');
        } else if (type === 'blog_category') {
            actionValueLabel.innerHTML = 'Travel Guide Category <span class="text-danger">*</span>';
            inputWrapper.innerHTML = generateSelectHTML(blogCategories, currentVal, 'Search Travel Guide Category...');
        } else if (type === 'city' || type === 'destination' || type === 'global_search') {
            actionValueLabel.innerHTML = 'Keyword / City Name <span class="text-danger">*</span>';
            inputWrapper.innerHTML = generateInputHTML(currentVal, 'text', 'Enter search term or city name...');
        } else if (type === 'custom_url') {
            actionValueLabel.innerHTML = 'Custom URL Path <span class="text-danger">*</span>';
            inputWrapper.innerHTML = generateInputHTML(currentVal, 'text', 'e.g. /about or https://example.com');
        }
        
        const newSelect = inputWrapper.querySelector('select');
        const newInput = inputWrapper.querySelector('input');
        
        if (newSelect) {
            if(window.jQuery && $.fn.select2) {
                $(newSelect).select2({width:'100%'}).on('change', function() {
                    hiddenValue.value = this.value;
                    updateUrlPreview(type, this.value);
                });
            } else {
                newSelect.addEventListener('change', function() {
                    hiddenValue.value = this.value;
                    updateUrlPreview(type, this.value);
                });
            }
        }
        
        if (newInput) {
            newInput.addEventListener('input', function() {
                hiddenValue.value = this.value;
                updateUrlPreview(type, this.value);
            });
        }
        
        updateUrlPreview(type, currentVal);
    }

    if(window.jQuery && $.fn.select2) {
        $(actionTypeSelect).on('change', function() {
            hiddenValue.value = '';
            handleTypeChange();
        });
    } else {
        actionTypeSelect.addEventListener('change', function() {
            hiddenValue.value = '';
            handleTypeChange();
        });
    }
    
    // Copy URL
    copyUrlBtn.addEventListener('click', function() {
        if(urlPreviewInput.value) {
            navigator.clipboard.writeText(urlPreviewInput.value);
            this.innerHTML = '<i class="bx bx-check text-success"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="bx bx-copy"></i>';
            }, 2000);
        }
    });

    // Initial triggers
    updateLivePreview();
    handleTypeChange();
});
</script>
