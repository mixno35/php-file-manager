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

if (is_dir($f_path)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_dir_dir_exist"
    ], 128);

    exit();
}

if (mkdir($f_path)) {
    echo json_encode([
        "type" => "success",
        "message_id" => "api_create_dir_success"
    ], 128);

    exit();
} else {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_dir_error"
    ], 128);

    exit();
}