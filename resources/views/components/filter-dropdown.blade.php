@props([
    'name',
    'options' => [],
    'selected' => '',
    'placeholder' => 'Select...',
    'searchable' => false
])

@php
    // Ensure we have a unique ID for the dropdown elements
    $idPrefix = preg_replace('/[^a-zA-Z0-9_]/', '_', $name) . '_' . uniqid();
    $selectedLabel = $options[$selected] ?? $placeholder;
@endphp

<input type="hidden" name="{{ $name }}" id="{{ $idPrefix }}_value" value="{{ $selected }}">
<div class="custom-dropdown-wrapper" id="{{ $idPrefix }}Wrapper">
  <div class="custom-dropdown-trigger" id="{{ $idPrefix }}Trigger" onclick="toggleCustomDropdown('{{ $idPrefix }}')">
    <div class="custom-tags-area">
      <span class="category-selected-text" id="{{ $idPrefix }}Placeholder">{{ $selectedLabel }}</span>
    </div>
    <i class="fas fa-chevron-down custom-dropdown-arrow" id="{{ $idPrefix }}Arrow"></i>
  </div>
  <div class="custom-dropdown-panel" id="{{ $idPrefix }}DropdownPanel" style="display:none;">
    @if($searchable)
    <div class="custom-search-wrap">
      <i class="fas fa-search custom-search-icon"></i>
      <input type="text" class="custom-search-input" id="{{ $idPrefix }}SearchInput"
             placeholder="Search..." oninput="filterCustomDropdown(this.value, '{{ $idPrefix }}')" autocomplete="off" />
    </div>
    <div class="custom-divider"></div>
    @endif
    <div class="custom-items-list" id="{{ $idPrefix }}ItemsList" @if(!$searchable) style="padding-top: 6px;" @endif>
      @foreach($options as $val => $label)
      <label class="custom-item {{ (string)$selected === (string)$val ? 'selected' : '' }}">
        <input type="radio" name="_{{ $name }}_radio" value="{{ $val }}"
               class="d-none"
               data-name="{{ $label }}"
               data-id="{{ $val }}"
               {{ (string)$selected === (string)$val ? 'checked' : '' }}
               onchange="onCustomDropdownChange(this, '{{ $idPrefix }}')" />
        <span class="custom-item-name">{{ $label }}</span>
        <span class="custom-item-check"><i class="fas fa-check"></i></span>
      </label>
      @endforeach
      @if($searchable)
      <div class="custom-no-results d-none" id="{{ $idPrefix }}NoResults">
        <i class="fas fa-search-minus me-2"></i>No results found
      </div>
      @endif
    </div>
  </div>
</div>
