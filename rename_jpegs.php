<?php
$dirs = [
    'p:/xampp83/htdocs/michigan-explorer/public/images',
    'p:/xampp83/htdocs/michigan-explorer/storage/app/public/demo',
    'p:/xampp83/htdocs/michigan-explorer/storage/app/public/pages'
];

foreach ($dirs as $dir) {
    foreach (glob($dir . '/*.png') as $file) {
        $img = @imagecreatefromjpeg($file);
        if ($img) {
            $newName = preg_replace('/\.png$/i', '.jpg', $file);
            imagejpeg($img, $newName, 60);
            imagedestroy($img);
            unlink($file);
            echo "Renamed and compressed: " . basename($file) . " to " . basename($newName) . "\n";
        }
    }
}
