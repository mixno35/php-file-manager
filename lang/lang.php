<?php
/* HINT^ - Языковые настройки */
/* HINT^ - Языковые настройки */
/* HINT^ - Языковые настройки */

/* HINT^ - Короткое языковое значение (ru, by, en...) */
/* HINT^ - Изменять при изменении языка пользователем */
$languageID = substr(($_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? "en"), 0, 2);

/* HINT^ - Стандартное языковое значение (ru-RU, be-BY, en-US...) */
/* HINT^ - Изменять при изменении языка пользователем */
$languageTAG = substr(($_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? "en-US"), 0, 5);

/* HINT^ - Место, где лежать все языки */
$path_document_root = "";
$path_main_lang = "lang";

/* HINT^ - Загрузка стандартного языкового пакета в JSON */
$content_default = file_get_contents("$path_main_lang/en.json");

/* HINT^ - Загрузка языкового пакета в JSON из настроек пользователя */
$content_setting = $content_default;
$content_user_lang = trim(str_replace("/", "", substr(strval($_COOKIE["lang"] ?? "en"), 0, 2)));
if (file_exists("$path_main_lang/$content_user_lang.json")) {
    $content_setting = file_get_contents("$path_main_lang/$content_user_lang.json");
}

/* HINT^ - Преобразование языкового пакета в список */
$string_default = json_decode($content_default, true);
$string_setting = json_decode($content_setting, true);

/* HINT^ - Заменяем повторяющиеся ключи */
$string = array_merge($string_default, $string_setting);

/* HINT^ - Изменять при изменении языка пользователем */
$language_tag = strval($string["language_tag"] ?? ($languageTAG ?? "en-US")); // Для атрибута lang=""

/* HINT^ - Возвращаем JSON */
$content = json_encode($string); // Для JS списка

/**
 * Функция выводит необходимый текст по его ключу, если текста под этим ключом нет, будет выведен этот ключ
 * @param string $key
 * @param bool $html (необязательно) обрабатывает html теги
 * @param array $replace
 * @return string
 */
function str_get_string(string $key = "", bool $html = false, array $replace = []):string {
    global $string;
    $str = array_key_exists($key, $string) ? $string[$key] : $key;
    if (sizeof($replace) > 0)
        for($i = 0; $i < sizeof($replace); $i++) $str = str_replace("%" . ($i + 1) . "s", $replace[$i], $str);

    return $html ? $str : htmlspecialchars($str);
}