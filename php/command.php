<?php
include_once "class/PathManager.php";

$path_manager = new PathManager();

$data = $_POST;

$command = $data["command"] ?? ""; // Извлекаем название команды, которую надо выполнить

if ($command === "remove") { // Удаление файла/папки
    include_once "command/remove.php";
    exit();
} if ($command === "rename") { // Переименование файла/папки
    include_once "command/rename.php";
    exit();
} if ($command === "create-dir") { // Создание папки
    include_once "command/create-dir.php";
    exit();
} if ($command === "create-file") { // Создание файла
    include_once "command/create-file.php";
    exit();
}

echo json_encode([
    "type" => "error",
    "message_id" => "api_command_unknown"
], 128);