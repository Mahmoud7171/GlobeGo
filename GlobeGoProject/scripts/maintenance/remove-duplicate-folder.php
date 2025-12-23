<?php
/**
 * Script to remove the duplicate GlobeGoProject folder
 * WARNING: This will delete the duplicate folder. Make sure you have backups!
 * Run via browser: http://localhost/GlobeGoProject/remove-duplicate-folder.php
 */
require_once __DIR__ . '/config/config.php';

// Check if admin (security check)
if (!isLoggedIn() || !isAdmin()) {
    die("Access denied. Admin access required.");
}

$duplicateFolder = __DIR__ . '/GlobeGoProject';

echo "<h2>Remove Duplicate Folder</h2>";
echo "<pre>";

if (!is_dir($duplicateFolder)) {
    echo "✓ No duplicate folder found. The project is already clean!\n";
    exit;
}

// Calculate size before deletion
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

$sizeBefore = getDirSize($duplicateFolder);
$sizeMB = round($sizeBefore / (1024 * 1024), 2);

echo "Found duplicate folder: GlobeGoProject/GlobeGoProject/\n";
echo "Size: {$sizeMB} MB\n\n";

if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    echo "Deleting duplicate folder...\n";
    
    function deleteDirectory($dir) {
        if (!is_dir($dir)) return false;
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        return rmdir($dir);
    }
    
    if (deleteDirectory($duplicateFolder)) {
        echo "✓ Successfully deleted duplicate folder!\n";
        echo "✓ Freed up {$sizeMB} MB of space\n";
    } else {
        echo "✗ Error: Could not delete folder. You may need to delete it manually.\n";
    }
} else {
    echo "⚠️  WARNING: This will permanently delete the duplicate folder!\n";
    echo "   Make sure you have backups if needed.\n\n";
    echo "<form method='POST'>";
    echo "<input type='hidden' name='confirm' value='yes'>";
    echo "<button type='submit' style='padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;'>Delete Duplicate Folder</button>";
    echo "</form>";
}

echo "</pre>";
?>

