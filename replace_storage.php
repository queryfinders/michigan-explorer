<?php

// 1. UPDATE FILES IN CODEBASE
$directories = [
    __DIR__ . '/app',
    __DIR__ . '/resources/views'
];

$fileCount = 0;

foreach ($directories as $directory) {
    if (!is_dir($directory)) continue;

    $directoryIterator = new RecursiveDirectoryIterator($directory);
    $iterator = new RecursiveIteratorIterator($directoryIterator);

    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $path = $file->getPathname();
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            
            if ($ext === 'php' || str_ends_with($path, '.blade.php')) {
                $content = file_get_contents($path);
                $originalContent = $content;
                
                // Replace asset('storage/app/public/' . $var) -> storage_asset($var)
                $content = preg_replace(
                    '/(?<![a-zA-Z0-9_])asset\(\s*[\'"]storage\/app\/public\/[\'"]\s*\.\s*(\$[a-zA-Z0-9_]+(?:->[a-zA-Z0-9_]+)*)\s*\)/',
                    'storage_asset($1)',
                    $content
                );
                
                // Replace asset('storage/' . $var) -> storage_asset($var)
                $content = preg_replace(
                    '/(?<![a-zA-Z0-9_])asset\(\s*[\'"]storage\/[\'"]\s*\.\s*(\$[a-zA-Z0-9_]+(?:->[a-zA-Z0-9_]+)*)\s*\)/',
                    'storage_asset($1)',
                    $content
                );
                
                // Replace asset($var) -> storage_asset($var)
                $content = preg_replace(
                    '/(?<![a-zA-Z0-9_])asset\(\s*(\$[a-zA-Z0-9_]+(?:->[a-zA-Z0-9_]+(?:\[[^\]]+\])*)*)\s*\)/',
                    'storage_asset($1)',
                    $content
                );
                
                // Replace hardcoded "storage/..." and 'storage/...' with 'storage/app/public/...' (for non-variable assets)
                $content = preg_replace('/\'storage\/(?!app\/public\/)/', '\'storage/app/public/', $content);
                $content = preg_replace('/"storage\/(?!app\/public\/)/', '"storage/app/public/', $content);
                
                // Replace asset('images/...') -> asset('public/images/...')
                $content = preg_replace('/(?<![a-zA-Z0-9_])asset\(\s*([\'"])images\//', 'asset($1public/images/', $content);
                
                if ($content !== $originalContent) {
                    file_put_contents($path, $content);
                    echo "Updated Code File: " . str_replace(__DIR__, '.', $path) . "\n";
                    $fileCount++;
                }
            }
        }
    }
}

echo "Finished codebase updates. Updated {$fileCount} files.\n";
