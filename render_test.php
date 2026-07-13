<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $blog = App\Models\Blog::first();
    echo view('web.blogs.show', [
        'blog' => $blog, 
        'relatedBlogs' => App\Models\Blog::take(3)->get(),
        'recentBlogs' => [],
        'mostViewedBlogs' => [],
        'categories' => [],
        'tags' => []
    ])->render();
} catch (\Throwable $e) {
    echo "\n\n=== ERROR ===\n";
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "LINE: " . $e->getLine() . "\n";
}
