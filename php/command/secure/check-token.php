<?php
global $data;

$uni_token_data = $data["uni_token"] ?? "n1";
$uni_token_session = $_SESSION["uni_token"] ?? "n2";

if ($uni_token_data !== $uni_token_session) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_command_invalid_data_session"
    ], 128);

    unset($_SESSION["uni_token"], $data["uni_token"]);

    exit();
}

include_once dirname(__FILE__, 4) . "/php/data.php";

include_once dirname(__FILE__, 4) . "/php/class/CheckSession.php";
include_once dirname(__FILE__, 4) . "/php/class/Crypt.php";

$check_session = new CheckSession(new Crypt(READY_KEY));

if (!$check_session->check()) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_command_unknown_user"
    ], 128);

    exit();
}