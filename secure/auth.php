<?php
function containsOnlyTextAndNumbers(string $text):bool {
    return preg_match("/^[a-zA-Z0-9]+$/", $text);
}

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
    http_response_code(405);
    exit();
}

global $host;

if (session_status() === PHP_SESSION_NONE) session_start();

include_once dirname(__FILE__, 2) . "/php/data.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$crypt = new Crypt(READY_KEY);

$message = "";

$auth_login = trim($_POST["login"] ?? "login");
$auth_password = password_hash(trim($_POST["password"] ?? "password"), PASSWORD_DEFAULT);

if (!containsOnlyTextAndNumbers($auth_login)) {
    $message = rawurlencode("text_login_incorrect_authorization");
} else {
    $auth_info = "$auth_login::::$auth_password";
    $auth_session = $crypt->encrypt($auth_info);

    $_SESSION[SESSION_NAME] = $auth_session;

    include_once dirname(__FILE__, 2) . "/php/class/CheckSession.php";

    $check_session = new CheckSession($crypt);

    if (!$check_session->check()) $message = rawurlencode("text_incorrect_authorization");
}

$result = (strlen($message) ? "?m=$message" : "");

header("Location: ../$result");