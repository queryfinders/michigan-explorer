<?php
$createContent = file_get_contents('temp_create.blade.php');
$editContent = file_get_contents('temp_edit.blade.php');

$formStart = strpos($createContent, '@if($errors->any())');
$formEnd = strpos($createContent, '<!-- Form Actions -->');

if ($formStart === false || $formEnd === false) {
    die("Could not find boundaries in create\n");
}

$formHtml = substr($createContent, $formStart, $formEnd - $formStart);

// Now, we replace `old('field')` with `old('field', $blog->field ?? '')`
$replacements = [
    "old('title')" => "old('title', \$blog->title ?? '')",
    "old('slug')" => "old('slug', \$blog->slug ?? '')",
    "old('excerpt')" => "old('excerpt', \$blog->excerpt ?? '')",
    "old('author_name')" => "old('author_name', \$blog->author_name ?? '')",
    "old('author_designation')" => "old('author_designation', \$blog->author_designation ?? '')",
    "old('author_avatar_alt')" => "old('author_avatar_alt', \$blog->author_avatar_alt ?? '')",
    "old('facebook_url')" => "old('facebook_url', \$blog->facebook_url ?? '')",
    "old('twitter_url')" => "old('twitter_url', \$blog->twitter_url ?? '')",
    "old('linkedin_url')" => "old('linkedin_url', \$blog->linkedin_url ?? '')",
    "old('instagram_url')" => "old('instagram_url', \$blog->instagram_url ?? '')",
    "old('featured_image_alt')" => "old('featured_image_alt', \$blog->featured_image_alt ?? '')",
    "old('meta_title')" => "old('meta_title', \$blog->meta_title ?? '')",
    "old('meta_description')" => "old('meta_description', \$blog->meta_description ?? '')",
    "old('canonical_url')" => "old('canonical_url', \$blog->canonical_url ?? '')",
    "old('og_title')" => "old('og_title', \$blog->og_title ?? '')",
    "old('og_description')" => "old('og_description', \$blog->og_description ?? '')",
    "old('schema_markup')" => "old('schema_markup', \$blog->schema_markup ?? '')",
    "old('published_at')" => "old('published_at', isset(\$blog->published_at) ? \Carbon\Carbon::parse(\$blog->published_at)->format('Y-m-d\TH:i') : '')",
    "old('content')" => "old('content', \$blog->content ?? '')",
    "old('status', 'published')" => "old('status', \$blog->status ?? 'published')",
    "old('blog_category_id')" => "old('blog_category_id', \$blog->blog_category_id ?? '')",
    "old('author_id')" => "old('author_id', \$blog->author_id ?? '')"
];

$formHtml = str_replace(array_keys($replacements), array_values($replacements), $formHtml);

// Special case for content which has htmlspecialchars
$formHtml = str_replace(
    "{!! htmlspecialchars(old('content', \$blog->content ?? '')) !!}",
    "{{ old('content', \$blog->content ?? '') }}",
    $formHtml
);

// We need to insert the Current Featured Image block if it exists (from edit.blade.php)
$featuredImageBlock = <<<'HTML'

          @if(isset($blog) && $blog->featured_image)
          <div class="mb-4 p-3 border rounded-3 bg-light">
            <label class="form-label text-muted small fw-semibold d-block mb-2">Current Featured Image</label>
            <img src="{{ asset($blog->featured_image) }}"
                 alt="{{ $blog->featured_image_alt ?? $blog->title }}"
                 class="img-thumbnail d-block" style="max-height:280px; object-fit:cover;" />
          </div>
          @endif
HTML;

$formHtml = str_replace(
    '<label class="form-label fw-semibold" for="featured_image_file">Upload Featured Image</label>',
    $featuredImageBlock . "\n" . '          <label class="form-label fw-semibold" for="featured_image_file">{{ isset($blog) && $blog->featured_image ? \'Replace Featured Image\' : \'Upload Featured Image\' }}</label>',
    $formHtml
);

// Do the same for author profile icon
$authorAvatarBlock = <<<'HTML'
          @if(isset($blog) && $blog->author_avatar)
          <div class="mb-3 p-2 border rounded-3 bg-light" style="max-width:max-content;">
            <label class="form-label text-muted small fw-semibold d-block mb-2">Current Icon</label>
            <img src="{{ asset($blog->author_avatar) }}" class="rounded-circle object-fit-cover shadow-sm border border-2 border-warning" style="width: 80px; height: 80px;" />
          </div>
          @endif
HTML;

$formHtml = str_replace(
    '<label class="form-label fw-semibold" for="author_avatar_file">Author Profile Icon</label>',
    $authorAvatarBlock . "\n" . '              <label class="form-label fw-semibold" for="author_avatar_file">Author Profile Icon</label>',
    $formHtml
);

// Now for FAQs - edit.blade.php had a loop for existing faqs
$faqContainer = <<<'HTML'
<div id="faqs-container">
            @if(isset($blog) && $blog->faqs)
              @foreach($blog->faqs as $index => $faq)
              <div class="card mb-3 faq-item border-info" id="faq_old_{{ $faq->id }}">
                <div class="card-header d-flex justify-content-between align-items-center py-2 bg-info-subtle">
                  <h6 class="card-title mb-0 text-info fw-bold"><i class="ti ti-help me-1"></i> FAQ #{{ $index + 1 }}</h6>
                  <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFaq('faq_old_{{ $faq->id }}')"><i class="ti ti-trash me-1"></i> Remove</button>
                </div>
                <div class="card-body p-3">
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Question</label>
                    <input type="text" class="form-control" name="faqs[{{ $index }}][question]" value="{{ $faq->question }}">
                  </div>
                  <div class="mb-0">
                    <label class="form-label fw-semibold">Answer</label>
                    <textarea class="form-control tinymce" name="faqs[{{ $index }}][answer]" rows="3">{{ $faq->answer }}</textarea>
                  </div>
                </div>
              </div>
              @endforeach
            @endif
          </div>
HTML;

$formHtml = str_replace('<div id="faqs-container"></div>', $faqContainer, $formHtml);

file_put_contents('resources/views/new_content/admin/blogs/form.blade.php', $formHtml);
echo "Successfully updated form.blade.php";
?>
