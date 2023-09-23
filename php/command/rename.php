<?php
global $data;

$path = array(
    "old" => $data["path"] ?? "", // Старое имя (путь)
    "new" => $data["new_path"] ?? "" // Новое имя (путь)
);

if ((basename($path["old"]) ?? "NaN") === (basename($path["new"]) ?? "NaN")) { // Если новое имя такое же как и старое
    echo json_encode([
        "type" => "error",
        "message_id" => "api_rename_different_from_old"
    ], 128);

    exit();
}

if (is_dir($path["old"])) {
    if (rename($path["old"], $path["new"])) {
        echo json_encode([
            "type" => "success",
            "message_id" => "api_rename_dir_success"
        ], 128);
    } else {
        echo json_encode([
            "type" => "error",
            "message_id" => "api_rename_dir_error"
        ], 128);
    }

    exit();
}

if (is_file($path["old"]) && file_exists($path["old"])) {
    if (rename($path["old"], $path["new"])) {
        echo json_encode([
            "type" => "success",
            "message_id" => "api_rename_file_success"
        ], 128);
    } else {
        echo json_encode([
            "type" => "error",
            "message_id" => "api_rename_file_error"
        ], 128);
    }

    exit();
}

echo json_encode([
    "type" => "error",
    "message_id" => "api_command_path_skip"
], 128);