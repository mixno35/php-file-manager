<?php
const lang_default_code = "ru"; // Язык по умолчанию
const lang_default_tag = "ru-RU"; // Тег по умолчанию

$lang_get = trim($_GET["lang"] ?? "");
// Язык устройства пользователя
$lang_id = substr(($_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? lang_default_code), 0, 2);
// Тег устройства пользователя
$lang_tag = substr(($_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? lang_default_tag), 0, 5);
// Язык исходя из настроек cookie "lang"
$lang_setting = trim(str_replace("/", "", substr(strval($_COOKIE["lang"] ?? $lang_id), 0, 2)));
// Если есть атрибут "lang" в GET запросе, игнорируем параметр в cookies
$lang_setting = strlen($lang_get) >= 2 ? substr($lang_get, 0, 2) : $lang_setting;

$path_main_lang = dirname(__DIR__) . "/assets/lang";

// Загружаем стандартный языковой пакет и языковой пакет исходя из настроек
$content_default = load_lang(lang_default_code);
$content_setting = load_lang($lang_setting);

if (lang_default_code !== $lang_setting) {
    $string = array_merge($content_default, $content_setting);
} else {
    $string = $content_default;
}

$language_tag = strval(array_key_exists("language_tag", $string) ? $string["language_tag"] : lang_default_tag); // Получаем тег, чтобы использовать в атрибуте lang=""

$content_lang = json_encode($string);

/**
 * Загружает и декодирует файл языка в формате JSON на основе заданного языкового тега.
 * @param string $lang_tag Языковой тег (например, "en", "fr").
 * @return array Ассоциативный массив данных языка. Пустой массив, если файл не существует или не является файлом JSON.
 */
function load_lang(string $lang_tag): array {
    global $path_main_lang;

    $path = "$path_main_lang/$lang_tag.json";
    if (file_exists($path) && is_file($path)) {
        $fileInfo = pathinfo($path);
        if (isset($fileInfo["extension"]) && strtolower($fileInfo["extension"]) === "json") {
            return parse_json_decode(file_get_contents("$path_main_lang/$lang_tag.json"), true);
        } else return array();
    } else return array();
}

/**
 * Функция выводит необходимый текст по его ключу, если текста под этим ключом нет, будет выведен этот ключ
 * @param string $key если строка с таким ключом существует, она будет использована; в противном случае будет использован сам ключ.
 * @param bool $html (необязательно) обрабатывает html теги
 * @param array $replace принимает несколько значений, нужно для замены %1s, %2s и т.д...
 * @return string
 */
function str_lang_string(string $key, bool $html = false, ...$replace): string {
    global $string;

    $array = $string;

    $str = array_key_exists($key, $array) ? $array[$key] : $key;
    $str = sprintf($str, ...$replace);

    return $html ? $str : htmlspecialchars(trim($str));
}

/**
 * Функция помогает избежать ошибок в json файле возникших из-за лишней запятой
 */
function parse_json_decode(string $json, ?bool $associative = null, int $depth = 512, int $flags = 0): mixed {
    $json = preg_replace("/,\s*([]}])/", "$1", $json);

    $decodedData = json_decode($json, $associative, $depth, $flags);

    if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
        return json_decode($json, $associative, $depth, $flags);
    } else return $decodedData;
}