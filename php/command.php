<?php
session_start();

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
    http_response_code(502);
    exit();
}

include_once dirname(__FILE__, 2) . "/php/data.php";

include_once dirname(__FILE__, 2) . "/php/class/CheckSession.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$check_session = new CheckSession(new Crypt(READY_KEY));

if (!$check_session->check()) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_command_unknown_user"
    ], 128);

    exit();
}

global $privileges;

include_once dirname(__FILE__, 2) . "/secure/user-privileges.php"; // Загружаем привилегии пользователя

include_once dirname(__FILE__, 2) . "/class/PathManager.php";

$path_manager = new PathManager();

$data = $_POST;

$token = md5(uniqid());

$_SESSION["uni_token"] = $token;
$data["uni_token"] = $token;

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
} if ($command === "upload-file") { // Загрузка файла
    include_once "command/upload-file.php";
    exit();
}

echo json_encode([
    "type" => "error",
    "message_id" => "api_command_unknown"
], 128);