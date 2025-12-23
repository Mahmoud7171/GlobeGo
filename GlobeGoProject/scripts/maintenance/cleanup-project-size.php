<?php
/**
 * Script to identify and help clean up large files and duplicates
 * Run via browser: http://localhost/GlobeGoProject/cleanup-project-size.php
 */
echo "<h2>Project Size Analysis</h2>";
echo "<pre>";

$baseDir = __DIR__;
$totalSize = 0;
$largeFiles = [];
$duplicateFolder = $baseDir . '/GlobeGoProject';

// Check if duplicate folder exists
if (is_dir($duplicateFolder)) {
    $duplicateSize = getDirSize($duplicateFolder);
    echo "⚠️  DUPLICATE FOLDER FOUND:\n";
    echo "   Path: GlobeGoProject/GlobeGoProject/\n";
    echo "   Size: " . formatBytes($duplicateSize) . "\n";
    echo "   This folder appears to be a duplicate of the main project!\n\n";
}

// Find large files
echo "📁 LARGE FILES (>1MB):\n";
echo str_repeat("-", 80) . "\n";
findLargeFiles($baseDir, $largeFiles, 1 * 1024 * 1024); // 1MB

usort($largeFiles, function($a, $b) {
    return $b['size'] - $a['size'];
});

foreach (array_slice($largeFiles, 0, 20) as $file) {
    echo sprintf("%-60s %10s\n", 
        substr($file['path'], strlen($baseDir) + 1), 
        formatBytes($file['size'])
    );
}

echo "\n";
echo "📊 SUMMARY:\n";
echo str_repeat("-", 80) . "\n";
echo "Total large files (>1MB): " . count($largeFiles) . "\n";
echo "Total size of large files: " . formatBytes(array_sum(array_column($largeFiles, 'size'))) . "\n";

echo "\n";
echo "💡 RECOMMENDATIONS:\n";
echo str_repeat("-", 80) . "\n";
echo "1. Delete the duplicate GlobeGoProject/GlobeGoProject/ folder (saves ~69MB)\n";
echo "2. Optimize large images (compress JPG/PNG files)\n";
echo "3. Consider using WebP format for images (smaller file size)\n";
echo "4. Remove unused test/debug files\n";

function getDirSize($dir) {
    $size = 0;
    if (is_dir($dir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($files as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
    }
    return $size;
}

function findLargeFiles($dir, &$results, $minSize) {
    if (!is_dir($dir)) return;
    
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..' || $file == 'GlobeGoProject') continue;
        
        $path = $dir . '/' . $file;
        if (is_file($path)) {
            $size = filesize($path);
            if ($size >= $minSize) {
                $results[] = ['path' => $path, 'size' => $size];
            }
        } elseif (is_dir($path)) {
            findLargeFiles($path, $results, $minSize);
        }
    }
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

echo "</pre>";
?>

