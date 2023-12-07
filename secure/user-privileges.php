<?php
global $session_name;

session_start();

include_once dirname(__FILE__, 2) . "/php/data.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$crypt = new Crypt();

$up_str_decrypt = $crypt->decrypt($_SESSION[$session_name] ?? "xx:xx");
$up_user = explode(":", $up_str_decrypt);
$up_login = $up_user[0] ?? "";

$array_privileges = array(
    "admin" => array(
        "view_file" => true,
        "preview" => array("file" => true, "dir" => true),
        "create" => array("file" => true, "dir" => true),
        "rename" => true,
        "remove" => true,
        "upload" => true
    )
);

$privileges = array(
    "view_file" => $array_privileges[$up_login]["view_file"] ?? false, // Просмотр файлов (view.php)
    "preview" => array(
        "file" => $array_privileges[$up_login]["preview"]["file"] ?? false, // Просмотр подробной информации о файле (file-detail.php)
        "dir" => $array_privileges[$up_login]["preview"]["dir"] ?? false // Просмотр подробной информации о папке (file-detail.php)
    ),
    "create" => array(
        "file" => $array_privileges[$up_login]["create"]["file"] ?? false, // Создание файлов
        "dir" => $array_privileges[$up_login]["create"]["dir"] ?? false // Создание папок
    ),
    "rename" => $array_privileges[$up_login]["rename"] ?? false, // Переименование файлов и папок
    "remove" => $array_privileges[$up_login]["remove"] ?? false, // Удаление файлов и папок
    "upload" => $array_privileges[$up_login]["upload"] ?? false // Загрузка файлов
);