<?php
global $data, $path_manager;

$paths = explode(", ", $data["path"]) ?? [];

//die(print_r($path, true));

function delete_directory($dirPath):void {
    if (is_dir($dirPath)) {
        $files = scandir($dirPath);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) {
                    // Рекурсивно удалить подпапку
                    delete_directory($filePath);
                } else {
                    // Удалить файл
                    unlink($filePath);
                }
            }
        }
        // Удалить пустую папку
        rmdir($dirPath);
    }
}

$removed_count = 0;

foreach ($paths as $path) {
    if (is_readable($path))
        if ($path_manager->chmod_detect($path))
            if (is_dir($path)) {
                delete_directory($path);
                $removed_count++;
            } else if (is_file($path) && file_exists($path)) {
                if (unlink($path)) $removed_count++;
            }
}

echo json_encode([
    "type" => "success",
    "message_id" => "api_remove_success",
    "return" => [$removed_count]
], 128);