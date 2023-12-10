<?php
global $host, $main_path;

include_once dirname(__FILE__, 2) . "/php/data.php";
include_once dirname(__FILE__, 2) . "/class/URLParse.php";

$url_parse = new URLParse();

const SEARCH_SORT_DIR_FILE = 2, SEARCH_SORT_FILE_DIR = 1, SEARCH_SORT_NONE = 0;

class FileManager {
    public function get_directory_size(string $path):int {
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

    public function get_file_size(string $path):int {
        if (!is_readable($path)) return 0;
        return file_exists($path) ? filesize($path) : 0;
    }

    public function get_date_modified(string $path):int {
        if (!is_readable($path)) return 0;
        if (is_file($path))
            return file_exists($path) ? filemtime($path) : 0;
        else if (is_dir($path))
            return filemtime($path) ?? 0;

        return 0;
    }

    public function get_file_format(string $path):string {
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

    public function get_dirs(string $path): array {
        $path = realpath($path);

        if (!is_readable($path) || !is_dir($path)) return [];

        $dirs = array_filter(scandir($path), function ($item) use ($path) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $item;

            if ($item === "." || $item === "..") return false;

            return is_dir($fullPath);
        });

        return array_map(function ($dir) use ($path) {
            return $path . DIRECTORY_SEPARATOR . $dir;
        }, $dirs);
    }

    public function get_files(string $path, string $mimetype = null): array {
        $path = realpath($path);

        if (!is_readable($path) || !is_dir($path)) return [];

        $files = array_filter(scandir($path), function ($item) use ($path, $mimetype) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $item;

            if ($item === "." || $item === "..") return false;

            if (isset($mimetype)) return is_file($fullPath) && (explode("/", $this->get_mime_type($fullPath))[0] === $mimetype);
            else return is_file($fullPath);
        });

        return array_map(function ($file) use ($path) {
            return $path . DIRECTORY_SEPARATOR . $file;
        }, $files);
    }

    public function get_permissions_string(string $path):string {
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

    public function get_permissions_int(string $path):string {
        if (!is_readable($path)) return "Access denied";

        $perms = fileperms($path);
        if ($perms === false) {
            return "0";
        }

        return sprintf("%04o", $perms & 0777);
    }

    public function get_mime_type(string $path):string {
        if (is_file($path) and file_exists($path)) {
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($file_info, $path);
            finfo_close($file_info);

            return $mime;
        }

        return "mime/type";
    }

    public function check_path(string $path = "", string $string = "", string $main_path = ""):void {
        if (!is_dir($path)) {
            if (!is_file($path)) {
                echo "<div class='unknown-path'><button onclick='loadMainFileManager(`$main_path`)'>$string</button></div>";
                exit();
            }
        }
    }

    public function get_current_url(string $path, bool $show_scheme = false):string {
        global $host, $main_path, $url_parse;

        $currentPageUrl = ($show_scheme ? ((isset($_SERVER["HTTPS"]) ? "https" : "http") . "://") : "") . $host;
        $relativePath = str_replace($main_path["server"], $currentPageUrl, $path);

        return $url_parse->current_url($this->parse_separator($relativePath, "/"), true);
    }

    public function parse_separator(string $path, string $separator = DIRECTORY_SEPARATOR, string $pattern = "/\\\\+/"):string {
        return preg_replace($pattern, $separator, $path);
    }

    public function search(string $dir, string $search, &$result, int $sort = SEARCH_SORT_NONE):void {
        $files = scandir($dir);

        $compareFunction = function ($a, $b) use ($dir, $sort) {
            $pathA = $dir . DIRECTORY_SEPARATOR . $a;
            $pathB = $dir . DIRECTORY_SEPARATOR . $b;

            $typeComparison = is_dir($pathB) - is_dir($pathA);

            if ($typeComparison != 0) return $sort === SEARCH_SORT_DIR_FILE ? -$typeComparison : $typeComparison;

            $nameComparison = strcasecmp($a, $b);

            return $sort === SEARCH_SORT_FILE_DIR ? -$nameComparison : $nameComparison;
        };

        usort($files, $compareFunction);

        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $current = $dir . DIRECTORY_SEPARATOR . $file;
                if (empty($search) || stripos($file, $search) !== false) $result[] = $current;
                if (is_dir($current)) $this->search($current, $search, $result);
            }
        }
    }
}