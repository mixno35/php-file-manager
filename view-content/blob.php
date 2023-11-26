<?php
$blob_path = $_GET["p"] ?? "";

$video_mime_type = mime_content_type($blob_path);

// Отправка заголовков для указания MIME-типа и кэширования
header("Content-Type: " . $video_mime_type);
header("Content-Length: " . filesize($blob_path));
header("Content-Disposition: inline; filename=\"" . basename($blob_path) . "\"");
header("Cache-Control: public, max-age=0");
header("Pragma: no-cache");

readfile($blob_path);