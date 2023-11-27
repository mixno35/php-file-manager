<?php
global $data, $path_manager;

$paths = explode(", ", $data["path"]) ?? [];

$removed_count = 0;

function delete_directory($dirPath):void {
    global $removed_count;
    if (is_dir($dirPath)) {
        $files = scandir($dirPath);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) delete_directory($filePath);
                else unlink($filePath);
            }
        }

        if (rmdir($dirPath)) $removed_count++;
    }
}

foreach ($paths as $path) {
    if (is_readable($path))
        if ($path_manager->chmod_detect($path))
            if (is_dir($path)) {
                delete_directory($path);
            } else if (is_file($path) && file_exists($path)) {
                if (unlink($path)) $removed_count++;
            }
}

echo json_encode([
    "type" => "success",
    "message_id" => "api_remove_success",
    "return" => [$removed_count]
], 128);