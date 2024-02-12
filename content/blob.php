<?php
include_once dirname(__FILE__, 2) . "/php/data.php";

include_once dirname(__FILE__, 2) . "/php/class/CheckSession.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$check_session = new CheckSession(new Crypt(READY_KEY));

if (!$check_session->check()) {
    http_response_code(403);
    exit();
}

global $privileges;

include_once dirname(__FILE__, 2) . "/secure/user-privileges.php"; // Загружаем привилегии пользователя

if (!$privileges["view_file"]) {
    http_response_code(403);
    exit();
}

include_once dirname(__FILE__, 2) . "/class/URLParse.php";

$url_parse = new URLParse();

$blob_path = $_GET["p"] ?? "";

if ($url_parse->is_blob_url($blob_path)) header("Refresh: 0; URL=$blob_path"); else {
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

    $filename = basename($blob_path);

    header("Content-Type: $blob_mime_type");
    header("Content-Length: " . filesize($blob_path));
    header("Content-Disposition: inline; filename=\"$filename\"");
    header("Cache-Control: public, max-age=0");
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $last_modified_time) . " GMT");
    header("Etag: $etag");

    $type = $_GET["type"] ?? "";

    if ($type === "stream") {
        $handle = fopen($blob_path, "rb");

        if ($handle === false) {
            http_response_code(500);
            exit();
        }

        while (!feof($handle)) {
            $chunk = fread($handle, 8192);

            if ($chunk === false) {
                fclose($handle);
                http_response_code(500);
                exit();
            }

            echo $chunk;
            ob_flush();
            flush();
        }

        fclose($handle);
    } else {
        if (!readfile($blob_path)) {
            http_response_code(500);
            exit();
        }
    }
}