<?php
global $data, $path_manager;

$path = $data["path"] ?? "";
$name = $data["name"] ?? "";

if (strlen($name) < 1) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_short_char"
    ], 128);

    exit();
}

if (!is_dir($path)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_is_not_dir"
    ], 128);

    exit();
}

if (!is_readable($path)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_is_readable"
    ], 128);

    exit();
}

$f_path = $path . DIRECTORY_SEPARATOR . $name;

if (file_exists($f_path)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_file_file_exist"
    ], 128);

    exit();
}

if ($path_manager->chmod_detect($path)) {
    $file = fopen($f_path, "w");

    if ($file) {
        if (fwrite($file, "") !== false) {
            echo json_encode([
                "type" => "success",
                "message_id" => "api_create_file_success",
                "return" => [$name]
            ], 128);

            fclose($file);
        } else {
            echo json_encode([
                "type" => "error",
                "message_id" => "api_create_file_error"
            ], 128);
        }

        exit();
    } else {
        echo json_encode([
            "type" => "error",
            "message_id" => "api_create_file_not_exist"
        ], 128);
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