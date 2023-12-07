<?php
global $session_name;

include_once dirname(__FILE__, 2) . "/php/data.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$crypt = new Crypt();

$str_dec = $crypt->decrypt($_COOKIE[$session_name] ?? "xx:xx");
$user = explode(":", $str_dec);
$login = $user[0] ?? "";

$array_privileges = array(
    "admin" => array(
        "view_file" => true,
        "preview_detail" => array(
            "file" => true,
            "dir" => true
        )
    )
);

$privileges = array(
    "view_file" => $array_privileges[$login]["view_file"] ?? false, // Просмотр файлов (view.php)
    "preview_detail" => array(
        "file" => $array_privileges[$login]["preview_detail"]["file"] ?? false, // Просмотр подробной информации о файле (file-detail.php)
        "dir" => $array_privileges[$login]["preview_detail"]["dir"] ?? false // Просмотр подробной информации о папке (file-detail.php)
    )
);