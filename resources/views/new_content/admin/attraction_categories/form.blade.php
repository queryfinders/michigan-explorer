<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $attractionCategory->name ?? '') }}" placeholder="e.g. Historic Sites" required />
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-6 mb-3">
    <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $attractionCategory->slug ?? '') }}" placeholder="e.g. historic-sites" required />
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>
<div class="mb-3">
  <label class="form-label" for="description">Description</label>
  <textarea class="form-control tinymce-editor" id="description" name="description">{{ old('description', $attractionCategory->description ?? '') }}</textarea>
</div>
<input type="hidden" name="status" value="{{ old('status', $attractionCategory->status ?? 1) }}">
<button type="submit" class="btn btn-warning">{{ isset($attractionCategory) ? 'Update' : 'Save' }}</button>
<a href="{{ route('attraction-categories.index') }}" class="btn btn-secondary">Cancel</a>

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
  tinymce.init({
    selector: '.tinymce-editor',
    height: 300,
    menubar: false,
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
      'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
      'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
    'bold italic forecolor | alignleft aligncenter ' +
    'alignright alignjustify | bullist numlist outdent indent | ' +
    'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
  });

  $(document).ready(function() {
      // Auto generate slug
      $('#name').on('input', function() {
          let nameVal = $(this).val();
          $('#slug').val(nameVal.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''));
      });

      // jQuery Validation
      $('form').validate({
          rules: {
              name: { required: true },
              slug: { required: true }
          },
          messages: {
              name: { required: "Please enter category name" },
              slug: { required: "Please enter category slug" }
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
