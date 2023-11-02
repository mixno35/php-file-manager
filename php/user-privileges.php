<?php
global $session_name;

$user = explode(":", $_COOKIE[$session_name] ?? "xx:xx"); // Сессия должна быть в формате user:password, чтобы проверка прошла успешно
$login = $user[0] ?? ""; // Здесь должен быть логин

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