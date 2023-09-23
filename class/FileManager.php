<?php
class FileManager {
    public function get_directory_size(string $path = ""):int {
        $totalSize = 0;

        $dir = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        foreach (new RecursiveIteratorIterator($dir) as $file) {
            $totalSize += $file->getSize();
        }

        return $totalSize;
    }

    public function get_file_size(string $path = ""):int {
        return file_exists($path) ? filesize($path) : 0;
    }

    public function get_date_modified(string $path = ""):int {
        if (is_file($path))
            return file_exists($path) ? filemtime($path) : 0;
        else if (is_dir($path))
            return filemtime($path) ?? 0;

        return 0;
    }

    public function get_file_format(string $path = ""):string {
        return file_exists($path) ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : "NaN";
    }

    public function format_size($size = 0, $units = array("B", "KB", "MB", "GB", "TB")):string {
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . " " . $units[$unit];
    }

    public function get_folders($_path = ""):array {
        return array_filter(scandir($_path), function($item) use ($_path) {
            return is_dir($_path . DIRECTORY_SEPARATOR . $item) && $item != "." && $item != "..";
        });
    }

    public function get_files($_path = ""):array {
        return array_filter(scandir($_path), function($item) use ($_path) {
            return is_file($_path . DIRECTORY_SEPARATOR . $item) && $item != "." && $item != "..";
        });
    }
}