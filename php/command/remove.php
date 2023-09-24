<?php
global $data, $path_manager;

$path = $data["path"] ?? "";

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

if ($path_manager->chmod_change($path)) {
    if (is_dir($path)) {
        delete_directory($path);

        echo json_encode([
            "type" => "success",
            "message_id" => "api_remove_dir_success"
        ], 128);

        exit();
    }

    if (is_file($path) && file_exists($path)) {
        if (unlink($path)) {
            echo json_encode([
                "type" => "success",
                "message_id" => "api_remove_file_success"
            ], 128);
        } else {
            echo json_encode([
                "type" => "success",
                "message_id" => "api_remove_file_error"
            ], 128);
        }
        exit();
    }
} else {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_not_permission_777"
    ], 128);

    exit();
}

echo json_encode([
    "type" => "error",
    "message_id" => "api_command_path_skip"
], 128);