<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g. Italian" value="{{ old('name', $feature->name ?? '') }}" required />
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" placeholder="e.g. italian" value="{{ old('slug', $feature->slug ?? '') }}" required />
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label" for="sort_order">Sort Order</label>
    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" placeholder="e.g. 0" value="{{ old('sort_order', $feature->sort_order ?? 0) }}" />
    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>
<input type="hidden" name="status" value="{{ old('status', $feature->status ?? 1) }}">
<button type="submit" class="btn btn-warning">{{ isset($feature) ? 'Update' : 'Save' }}</button>
<a href="{{ route('features.index') }}" class="btn btn-secondary">Cancel</a>

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
  // Simple slug generator helper
  document.getElementById('name').addEventListener('input', function() {
    let nameVal = this.value;
    let slugInput = document.getElementById('slug');
    if (slugInput) {
      slugInput.value = nameVal.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    }
  });

  $(document).ready(function() {
      // jQuery Validation
      $('form').validate({
          rules: {
              name: { required: true },
              slug: { required: true }
          },
          messages: {
              name: { required: "Please enter Feature name" },
              slug: { required: "Please enter Feature slug" }
          },
          errorElement: 'div',
          errorClass: 'invalid-feedback d-block',
          highlight: function(element) {
              $(element).addClass('is-invalid');
          },
          unhighlight: function(element) {
              $(element).removeClass('is-invalid');
          },
          errorPlacement: function(error, element) {
              error.insertAfter(element);
          }
      });
  });
</script>
@endsection
