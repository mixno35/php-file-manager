<?php
$data = $_POST;

$command = $data["command"] ?? ""; // Извлекаем название команды, которую надо выполнить

if (str_starts_with("remove", $command)) { // Удаление файла/папки
    include_once "command/remove.php";
    exit();
} if (str_starts_with("rename", $command)) { // Переименование файла/папки
    include_once "command/rename.php";
    exit();
} if (str_starts_with("create-dir", $command)) { // Создание папки
    include_once "command/create-dir.php";
    exit();
} if (str_starts_with("create-file", $command)) { // Создание файла
    include_once "command/create-file.php";
    exit();
}

echo json_encode([
    "type" => "error",
    "message_id" => "api_command_unknown"
], 128);