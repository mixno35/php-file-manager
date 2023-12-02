<?php
global $session_name;

include_once dirname(__FILE__, 2) . "/php/data.php";
include_once "crypt.php";

/* HINT^ - Используем эту функцию, если пользователь не авторизован */
function without_auth():void {
    include_once "auth.php";
    exit();
}

/* HINT^ - Список всех пользователей. "login" => "password" */
$users = array(
    "admin" => "admin"
);

/* HINT^ - Небольшая проверка на авторизацию */
if (empty(trim($_COOKIE[$session_name] ?? ""))) {
    without_auth();
}

$str_dec = str_decrypt($_COOKIE[$session_name] ?? "xx:xx");
$user = explode(":", $str_dec); // Сессия должна быть в формате user:password, чтобы проверка прошла успешно
$login = $user[0] ?? ""; // Здесь должен быть логин
$password = $user[1] ?? md5("empty"); // Здесь должен быть пароль

if (array_key_exists($login, $users)) {
    if (md5($users[$login]) !== $password) {
        without_auth();
    }
} else {
    without_auth();
}