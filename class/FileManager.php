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

    public function get_permissions_string(string $path = ""):string {
        $perms = fileperms($path);
        if ($perms === false) {
            return "NaN";
        }

        $symbolic = is_dir($path) ? "d" : "-";
        $symbolic .= ($perms & 0x0100) ? "r" : "-";
        $symbolic .= ($perms & 0x0080) ? "w" : "-";
        $symbolic .= ($perms & 0x0040) ? (($perms & 0x0800) ? "s" : "x") : (($perms & 0x0800) ? "S" : "-");
        $symbolic .= ($perms & 0x0020) ? "r" : "-";
        $symbolic .= ($perms & 0x0010) ? "w" : "-";
        $symbolic .= ($perms & 0x0008) ? (($perms & 0x0400) ? "s" : "x") : (($perms & 0x0400) ? "S" : "-");
        $symbolic .= ($perms & 0x0004) ? "r" : "-";
        $symbolic .= ($perms & 0x0002) ? "w" : "-";
        $symbolic .= ($perms & 0x0001) ? (($perms & 0x0200) ? "t" : "x") : (($perms & 0x0200) ? "T" : "-");

        return $symbolic;
    }

    public function get_permissions_int(string $path = ""):string {
        $perms = fileperms($path);
        if ($perms === false) {
            return "0";
        }

        return sprintf("%04o", $perms & 0777);
    }
}