<?php
include_once dirname(__FILE__) . "/secure/check-token.php";

global $data, $path_manager, $privileges;

if (!$privileges["create"]["dir"]) {
    echo json_encode([
        "type" => "error",
        "message_id" => "text_privileges_forbidden"
    ], 128);

    exit();
}

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

if (is_dir($f_path)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_dir_dir_exist"
    ], 128);

    exit();
}

if ($path_manager->chmod_detect($path)) {
    if (mkdir($f_path)) {
        echo json_encode([
            "type" => "success",
            "message_id" => "api_create_dir_success",
            "return" => [$name]
        ], 128);
    } else {
        echo json_encode([
            "type" => "error",
            "message_id" => "api_create_dir_error"
        ], 128);
    }

    exit();
} else {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_not_permission_777"
    ], 128);

    exit();
}