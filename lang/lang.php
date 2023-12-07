<?php
$languageID = substr(($_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? "en"), 0, 2);
$languageTAG = substr(($_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? "en-US"), 0, 5);

$path_main_lang = dirname(__FILE__);

$content_default = file_get_contents("$path_main_lang/en.json");

$content_setting = $content_default;
$content_user_lang = trim(str_replace("/", "", substr(strval($_COOKIE["lang"] ?? "en"), 0, 2)));
if (file_exists("$path_main_lang/$content_user_lang.json")) {
    $content_setting = file_get_contents("$path_main_lang/$content_user_lang.json");
}

$string_default = parse_json_decode($content_default, true);
$string_setting = parse_json_decode($content_setting, true);

$string = array_merge($string_default, $string_setting);

$language_tag = strval($string["language_tag"] ?? ($languageTAG ?? "en-US")); // Для атрибута lang=""

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

function parse_json_decode($json, bool $associative = null) {
    $json = preg_replace('/,\s*([\]}])/', '$1', $json);

    $decodedData = json_decode($json, $associative);

    if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
        return json_decode($json, $associative);
    }

    return $decodedData;
}