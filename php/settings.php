<?php
$hua = $_SERVER["HTTP_USER_AGENT"] ?? "NaN";
$uni_id = substr(md5($hua), 0, 8) . "_" . date("Y");

$settings = array(
    "list_image_preview" => boolval($_COOKIE["lip_$uni_id"] ?? false),
    "server_details" => boolval($_COOKIE["sd_$uni_id"] ?? false)
);