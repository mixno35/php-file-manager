<?php
global $data;

$path = $data["path"] ?? "";

if (is_dir($path)) {
    if (rmdir($path)) {
        echo json_encode([
            "type" => "success",
            "message_id" => "api_remove_dir_success"
        ], 128);
    } else {
        echo json_encode([
            "type" => "error",
            "message_id" => "api_remove_dir_error"
        ], 128);
    }

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

echo json_encode([
    "type" => "success",
    "message_id" => "api_remove_skip"
], 128);