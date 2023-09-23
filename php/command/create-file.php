<?php
global $data;

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

$f_path = $path . DIRECTORY_SEPARATOR . $name;

if (file_exists($f_path)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_file_file_exist"
    ], 128);

    exit();
}

if (file_put_contents($f_path, "") !== false) {
    echo json_encode([
        "type" => "success",
        "message_id" => "api_create_file_success"
    ], 128);

    exit();
} else {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_file_error"
    ], 128);

    exit();
}