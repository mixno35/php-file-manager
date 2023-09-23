<?php
/* HINT^ - Используем эту функцию, если пользователь не авторизован */
function without_auth():void {
    include_once "auth.php";
    exit();
}

/* HINT^ - Список всех пользователей. "login" => "password" */
$users = array(
    "admin" => "admin"
);

/* HINT^ - Название сессии в которой будет храниться сессия */
$session_name = "OKpgfxYTMR";

/* HINT^ - Небольшая проверка на авторизацию */
if (empty(trim($_COOKIE[$session_name] ?? ""))) {
    without_auth();
}

$user = explode(":", $_COOKIE[$session_name] ?? "xx:xx"); // Сессия должна быть в формате user:password, чтобы проверка прошла успешно
$login = $user[0] ?? ""; // Здесь должен быть логин
$password = $user[1] ?? md5("empty"); // Здесь должен быть пароль

if (array_key_exists($login, $users)) {
    if (md5($users[$login]) !== $password) {
        without_auth();
    }
} else {
    without_auth();
}