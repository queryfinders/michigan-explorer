<?php

$dirs = [
    __DIR__ . '/public/images',
    __DIR__ . '/storage/app/public/demo',
    __DIR__ . '/storage/app/public/pages'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    
    // Follow symlinks if needed, or just iterate directly
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $path = $file->getPathname();
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            
            if ($ext === 'png') {
                $image = @imagecreatefrompng($path);
                if ($image) {
                    $newPath = preg_replace('/\.png$/i', '.jpg', $path);
                    
                    $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
                    imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                    imagealphablending($bg, TRUE);
                    imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    
                    imagejpeg($bg, $newPath, 60);
                    imagedestroy($image);
                    imagedestroy($bg);
                    
                    unlink($path); // Delete old png
                    echo "Converted: " . basename($path) . " to JPG\n";
                } else {
                    echo "Failed to load PNG: " . basename($path) . "\n";
                }
            }
        }
    }
}
