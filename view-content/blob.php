<?php
$blob_path = $_GET["p"] ?? "";

if (!file_exists($blob_path)) {
    http_response_code(404);
    exit();
}

$last_modified_time = filemtime($blob_path);
$etag = md5_file($blob_path);

if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) || isset($_SERVER["HTTP_IF_NONE_MATCH"])) {
    if (strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) >= $last_modified_time || trim($_SERVER["HTTP_IF_NONE_MATCH"], "\"") === $etag) {
        http_response_code(304);
        exit();
    }
}

$blob_mime_type = mime_content_type($blob_path);

if ($blob_mime_type === false) {
    http_response_code(500);
    exit();
}

// Получаем имя файла без пути
$filename = basename($blob_path);

// Отправляем правильные заголовки
header("Content-Type: $blob_mime_type");
header("Content-Length: " . filesize($blob_path));
header("Content-Disposition: inline; filename=\"$filename\"");
header("Cache-Control: public, max-age=0");
header("Pragma: no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s", $last_modified_time) . " GMT");
header("Etag: $etag");

if (!readfile($blob_path)) {
    http_response_code(500);
    exit();
}

readfile($blob_path);