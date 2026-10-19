<?php

// Update Database
$pdo = new PDO('mysql:host=localhost;dbname=michigan_explorer', 'root', '');

$tables = [
    'attractions' => 'featured_image',
    'blogs' => 'featured_image',
    'events' => 'featured_image',
    'hotels' => 'featured_image',
    'pages' => 'featured_image',
    'restaurants' => 'featured_image'
];

foreach ($tables as $table => $column) {
    try {
        $stmt = $pdo->prepare("UPDATE `$table` SET `$column` = REPLACE(`$column`, '.png', '.jpg') WHERE `$column` LIKE '%.png'");
        $stmt->execute();
        echo "Updated table $table\n";
    } catch (Exception $e) {
        // Table might not exist or column might be different, ignore
    }
}

// Also update Unsplash links in DB to use w=500&q=60
foreach ($tables as $table => $column) {
    try {
        $stmt = $pdo->prepare("UPDATE `$table` SET `$column` = CONCAT(`$column`, '&w=500&q=60') WHERE `$column` LIKE '%images.unsplash.com%' AND `$column` NOT LIKE '%w=500%'");
        $stmt->execute();
    } catch (Exception $e) {
    }
}

// Update Blade Files
$viewsDir = __DIR__ . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        $newContent = preg_replace('/(assets\/img\/.*\.png|images\/.*\.png|storage\/demo\/.*\.png)/i', '$1_TEMP_REPLACE', $content);
        $newContent = str_replace('.png_TEMP_REPLACE', '.jpg', $newContent);
        
        if ($newContent !== $content) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Updated blade: " . $file->getFilename() . "\n";
        }
    }
}
