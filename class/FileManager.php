<?php
class FileManager {
    public function get_directory_size(string $path = ""):int {
        if (!is_readable($path)) return 0;

        $totalSize = 0;
        $dir = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        try {
            $iterator = new RecursiveIteratorIterator($dir);
            foreach ($iterator as $file) {
                $totalSize += $file->getSize();
            }
        } catch (Exception $e) {
            $totalSize = 0;
        }

        return $totalSize;
    }

    public function get_file_size(string $path = ""):int {
        if (!is_readable($path)) return 0;
        return file_exists($path) ? filesize($path) : 0;
    }

    public function get_date_modified(string $path = ""):int {
        if (!is_readable($path)) return 0;
        if (is_file($path))
            return file_exists($path) ? filemtime($path) : 0;
        else if (is_dir($path))
            return filemtime($path) ?? 0;

        return 0;
    }

    public function get_file_format(string $path = ""):string {
        if (!is_readable($path)) return "Access denied";
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
        if (!is_readable($_path)) return [];
        return array_filter(scandir($_path), function($item) use ($_path) {
            return is_dir($_path . DIRECTORY_SEPARATOR . $item) && $item != "." && $item != "..";
        });
    }

    public function get_files($_path = ""):array {
        if (!is_readable($_path)) return [];
        return array_filter(scandir($_path), function($item) use ($_path) {
            return is_file($_path . DIRECTORY_SEPARATOR . $item) && $item != "." && $item != "..";
        });
    }

    public function get_permissions_string(string $path = ""):string {
        if (!is_readable($path)) return "Access denied";

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
        if (!is_readable($path)) return "Access denied";

        $perms = fileperms($path);
        if ($perms === false) {
            return "0";
        }

        return sprintf("%04o", $perms & 0777);
    }

    public function get_mime_type(string $path = ""):string {
        if (is_file($path) and file_exists($path))
            return mime_content_type($path) ?? "NaN";

        return "NaN";
    }

    public function check_path(string $path = "", string $string = "", string $main_path = ""):void {
        if (!is_dir($path)) {
            if (!is_file($path)) {
                echo "<div class='unknown-path'><button onclick='loadMainFileManager(`$main_path`)'>$string</button></div>";
                exit();
            }
        }
    }

    public function get_current_url(string $absolute_path, bool $show_scheme = false):string {
        $currentPageUrl = ($show_scheme ? ((isset($_SERVER["HTTPS"]) ? "https" : "http") . "://") : "") . ($_SERVER["HTTP_HOST"] ?? "NaN");
        $currentDirectory = dirname($currentPageUrl);
        $relativePath = str_replace(getcwd() ?? "", "", $absolute_path);

        return $this->parse_separator($currentDirectory . DIRECTORY_SEPARATOR . $relativePath, "/");
    }

    public function parse_separator(string $path, string $separator = DIRECTORY_SEPARATOR, string $pattern = "/\\\\+/"):string {
        return preg_replace($pattern, $separator, $path);
    }
}