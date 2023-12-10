<?php
function containsOnlyTextAndNumbers(string $text):bool {
    return preg_match("/^[a-zA-Z0-9]+$/", $text);
}

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
    http_response_code(405);
    exit();
}

global $host;

session_start();

include_once dirname(__FILE__, 2) . "/php/data.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$crypt = new Crypt(READY_KEY);

$auth_login = $_POST["login"] ?? "login";
$auth_password = password_hash($_POST["password"] ?? "password", PASSWORD_DEFAULT);

if (!containsOnlyTextAndNumbers($auth_login)) die("Login incorrect.");

$auth_info = "$auth_login::::$auth_password";
$auth_session = $crypt->encrypt($auth_info);

$_SESSION[SESSION_NAME] = $auth_session;

header("Location: ../");