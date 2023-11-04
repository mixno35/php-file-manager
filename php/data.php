<?php
$path1 = getcwd() ?? ""; // Путь к файловому менеджеру
$path2 = dirname($path1); // Путь к сайту в котором находится файловый менеджер

$host = $_SERVER["HTTP_HOST"] ?? "NaN";

$server_encoding = mb_http_output() ?? "UTF-8";

$session_name = substr(md5($host . date("d-m-Y")), 0, 10); // Сессия для авторизации

$main_path = array(
    "file_manager" => $path1,
    "server" => $path2
);