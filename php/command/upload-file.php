<?php
global $data, $path_manager;

include_once dirname(__FILE__, 3) . "/class/URLParse.php";

$url_parse = new URLParse();

$blob = strval($_POST["blob"] ?? "");
$path = strval($_POST["path"] ?? "");

echo $path;

if (!$url_parse->is_blob_url($blob)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_upload_file_blob_incorrect"
    ], 128);

    exit();
}