<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
    http_response_code(405);
    exit();
}

include_once dirname(__FILE__, 2) . "/php/data.php";

include_once dirname(__FILE__, 2) . "/php/class/CheckSession.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$check_session = new CheckSession(new Crypt(READY_KEY));

function convertToBytes($value):int {
    $unit = strtoupper(substr($value, -1));
    $number = (int) $value;
    switch ($unit) {
        case "G":
            $number *= 1024; // 1 ГБ = 1024 МБ
        case "M":
            $number *= 1024; // 1 МБ = 1024 КБ
        case "K":
            $number *= 1024; // 1 КБ = 1024 байта
    }
    return $number;
}

if (!$check_session->check()) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_command_unknown_user"
    ], 128);

    exit();
}

$postMaxSize = ini_get("post_max_size");
$contentLength = $_SERVER["CONTENT_LENGTH"];

if ($contentLength > convertToBytes($postMaxSize)) {
    http_response_code(400);
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