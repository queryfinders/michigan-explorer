<div class="mb-3">
  <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $policy->name ?? '') }}" placeholder="e.g. Check-in Time" required />
  @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="form-label" for="input_type">Input Type <span class="text-danger">*</span></label>
  <select class="form-select @error('input_type') is-invalid @enderror" id="input_type" name="input_type" required>
      <option value="text" {{ old('input_type', $policy->input_type ?? '') == 'text' ? 'selected' : '' }}>Single Line Text</option>
      <option value="textarea" {{ old('input_type', $policy->input_type ?? '') == 'textarea' ? 'selected' : '' }}>Multi-line Text Area</option>
  </select>
  @error('input_type')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="form-label" for="sort_order">Sort Order <span class="text-danger">*</span></label>
  <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $policy->sort_order ?? 0) }}" required />
  @error('sort_order')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $policy->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
  </div>
</div>

<div class="pt-3">
  <button type="submit" class="btn btn-warning me-sm-3 me-1">{{ isset($policy) ? 'Update' : 'Save' }}</button>
  <a href="{{ route('hotel-policies.index') }}" class="btn btn-label-secondary">Cancel</a>
</div>

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
  $(document).ready(function() {
      // jQuery Validation
      $('form').validate({
          rules: {
              name: { required: true },
              input_type: { required: true },
              sort_order: { required: true, number: true }
          },
          messages: {
              name: { required: "Please enter policy name" },
              input_type: { required: "Please select an input type" },
              sort_order: { required: "Please enter a sort order", number: "Must be a number" }
          },
          errorElement: 'div',
          errorClass: 'text-danger mt-1 small',
          errorPlacement: function(error, element) {
              error.insertAfter(element);
          }
      });
  });
</script>
@endsection
