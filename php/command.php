<?php
$data = $_POST;

$command = $data["command"] ?? ""; // Извлекаем название команды, которую надо выполнить

if (str_starts_with("remove", $command)) { // Удаление файла/папки
    include_once "command/remove.php";
    exit();
}

echo json_encode([
    "type" => "error",
    "message_id" => "api_unknown_command"
], 128);