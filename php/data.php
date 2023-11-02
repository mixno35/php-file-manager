<?php
$path1 = getcwd() ?? ""; // Путь к файловому менеджеру
$path2 = dirname($path1); // Путь к сайту в котором находится файловый менеджер

$session_name = "OKpgfxYTMR";

$main_path = array(
    "file_manager" => $path1,
    "server" => $path2
);