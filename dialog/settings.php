<?php
global $language_tag, $content, $login, $main_path, $server_encoding, $default_avatar, $session_name;

include_once "../lang/lang.php"; // Загружаем языковой пакет
include_once "../php/data.php"; // Загружаем системные настройки
include_once "../secure/session.php"; // Проверка на авторизацию
?>
<div class="user-container">
    <section>
        <img src="<?= $default_avatar ?>" alt="Avatar">
        <span><?= str_get_string("message_welcome_back_user", true, [$login]) ?></span>
        <i class="fa-solid fa-arrow-right-from-bracket" title="<?= str_get_string('tooltip_logout') ?>" onclick="setCookie('<?= $session_name ?>', '', 0); window.location.reload()"></i>
    </section>
</div>
