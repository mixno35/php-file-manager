<?php
// Глобальные переменные
global $language_tag;

// Подключение классов
use class\manager\PathManager,
    class\data\UserData,
    class\data\ServerData;

// Защита доступа
require_once dirname(__FILE__) . "/php/protect.php";
// Загрузка языковых настроек
require_once dirname(__FILE__) . "/php/lang.php";

// Подключение классов
require_once dirname(__FILE__) . "/php/class/manager/PathManager.php";
require_once dirname(__FILE__) . "/php/class/data/UserData.php";
require_once dirname(__FILE__) . "/php/class/data/ServerData.php";

// Инициализация объектов классов
$pathManager = new PathManager();
$userData = new UserData();
$serverData = new ServerData();

// Проверка, авторизован ли пользователь
$isUserAuthorized = $userData->authorized();

// Получение значений логина и пароля из POST-запроса
$login = $serverData->valuePost("login");
$password = $serverData->valuePost("password");

// Если логин и пароль не пусты, и пользователь не авторизован, то авторизуем пользователя
if (!empty($login) && !empty($password) && !$isUserAuthorized) {
    $userData->auth($login, $password);
    // Перезагрузка страницы для обновления состояния авторизации
    header("Refresh: 0");
}

// Очистка переменных логина и пароля
unset($login, $password);

// Переменная с текущим временем для уникального значения
$file_v = time();
?>

<html lang="<?= $language_tag ?>">
<head>
    <meta charset="UTF-8">
    <title><?= str_lang_string("document_title") ?></title>

    <link rel="stylesheet" href="assets/style/root.css?<?= $file_v ?>">
    <link rel="stylesheet" href="assets/style/kit.css?<?= $file_v ?>">
    <link rel="stylesheet" href="assets/style/style.css?<?= $file_v ?>">
</head>
<body>
<?php if ($isUserAuthorized): ?>
<?php else: ?>
    <form method="<?= $serverData::REQUEST_METHOD_POST ?>" action="." class="authorization" autocomplete="off">
        <label>
            <?= str_lang_string("text_enter_login") ?>
            <input type="text" name="login" required minlength="3">
        </label>
        <label>
            <?= str_lang_string("text_enter_password") ?>
            <input type="password" name="password" required minlength="4">
        </label>
        <button type="submit"><?= str_lang_string("action_login") ?></button>
    </form>
<?php endif; ?>
</body>
</html>