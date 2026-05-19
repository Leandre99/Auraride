<?php
$dir = new RecursiveDirectoryIterator(__DIR__);
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile()) {
        $ext = $file->getExtension();
        $filename = $file->getFilename();
        if (in_array($ext, ['php', 'env']) || strpos($filename, '.blade.php') !== false) {
            if (strpos($file->getPathname(), 'vendor') !== false || strpos($file->getPathname(), 'storage') !== false || strpos($file->getPathname(), 'node_modules') !== false) {
                continue;
            }
            $content = file_get_contents($file->getPathname());
            $newContent = str_replace(['ATLAS TAXI / VTC', 'Atlas Taxi / VTC'], ['ATLAS TAXI / VTC', 'Atlas Taxi / VTC'], $content);
            if ($newContent !== $content) {
                file_put_contents($file->getPathname(), $newContent);
                echo "Updated: " . $file->getPathname() . "\n";
            }
        }
    }
}
echo "Done.\n";
