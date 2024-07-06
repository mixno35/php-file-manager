<?php
if (version_compare(phpversion(), "8.0.0", "<")) {
    if (function_exists("str_lang_string")) {
        die(str_lang_string("php_version_not_support", false, phpversion()));
    } else die(sprintf("Текущая версия PHP %s не поддерживается", phpversion()));
}
if (session_status() === PHP_SESSION_DISABLED) {
    if (function_exists("str_lang_string")) {
        die(str_lang_string("php_session_disabled", false, phpversion()));
    } else die("Чтобы продолжить, включите сессии");
}