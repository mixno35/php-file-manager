<?php
session_start();

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
    http_response_code(405);
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

unset($data["command"]);

switch ($command) {
    case "remove":
        include_once dirname(__FILE__) . "/command/remove.php"; break;
    case "rename":
        include_once dirname(__FILE__) . "/command/rename.php"; break;
    case "create-dir":
        include_once dirname(__FILE__) . "/command/create-dir.php"; break;
    case "create-file":
        include_once dirname(__FILE__) . "/command/create-file.php"; break;
    case "upload-file":
        include_once dirname(__FILE__) . "/command/upload-file.php"; break;
    default:
        echo json_encode(["type" => "error", "message_id" => "api_command_unknown"], 128);
}