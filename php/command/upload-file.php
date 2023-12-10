<?php
include_once dirname(__FILE__) . "/secure/check-token.php";

global $data, $path_manager, $privileges;

if (!$privileges["upload"]) {
    echo json_encode([
        "type" => "error",
        "message_id" => "text_privileges_forbidden"
    ], 128);

    exit();
}

include_once dirname(__FILE__, 3) . "/class/URLParse.php";

$url_parse = new URLParse();

$path = strval($_POST["path"] ?? "");

if (isset($_FILES["file"])) {
    if (!is_dir($path)) {
        echo json_encode([
            "type" => "error",
            "message_id" => "api_create_is_not_dir"
        ], 128);

        exit();
    }

    $tempFile = $_FILES["file"]["tmp_name"];
    $destination = $path . DIRECTORY_SEPARATOR . $_FILES["file"]["name"];

    if (is_file($destination) or file_exists($destination)) {
        echo json_encode([
            "type" => "error",
            "message_id" => "api_create_file_file_exist"
        ], 128);

        exit();
    }

    if (move_uploaded_file($tempFile, $destination)) {
        echo json_encode([
            "type" => "success",
            "message_id" => "api_upload_file_success"
        ], 128);

        exit();
    }
}

echo json_encode([
    "type" => "error",
    "message_id" => "api_unknown_error"
], 128);