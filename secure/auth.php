<?php
if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
    http_response_code(502);
    exit();
}

global $session_name, $host;

session_start();

include_once dirname(__FILE__, 2) . "/php/data.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$crypt = new Crypt();

$auth_login = $_POST["login"] ?? "xx";
$auth_password = md5($_POST["password"] ?? "xx");

$auth_info = "$auth_login:$auth_password";

$auth_session = $crypt->encrypt($auth_info);

$_SESSION[$session_name] = $auth_session;

header("Location: ../");