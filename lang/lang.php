<?php
$lang_default_code = "en";
$lang_default_tag = "en-US";

$languageID = substr(($_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? $lang_default_code), 0, 2);
$languageTAG = substr(($_SERVER["HTTP_ACCEPT_LANGUAGE"] ?? $lang_default_tag), 0, 5);

$path_main_lang = dirname(__FILE__);

$content_default = file_get_contents("$path_main_lang/$lang_default_code.json");

$content_setting = $content_default;
$content_user_lang = trim(str_replace("/", "", substr(strval($languageID ?? ($_COOKIE["lang"] ?? $lang_default_code)), 0, 2)));
if (file_exists("$path_main_lang/$content_user_lang.json")) {
    $content_setting = file_get_contents("$path_main_lang/$content_user_lang.json");
}

$string_default = parse_json_decode($content_default, true);
$string_setting = parse_json_decode($content_setting, true);

$string = array_merge($string_default, $string_setting);

$language_tag = strval($string["language_tag"] ?? ($languageTAG ?? $lang_default_tag)); // Для атрибута lang=""

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

/**
 * Функция помогает избежать ошибок в json файле возникших из-за лишней запятой
 * @param string $json Декодируемая строка json. Эта функция работает только со строками, закодированными в UTF-8. PHP реализует надмножество JSON - оно также будет кодировать и декодировать скалярные типы и NULL. Стандарт JSON поддерживает эти значения только в том случае, если они вложены внутри массива или объекта.
 * @param bool|null $associative При значении TRUE возвращаемые объекты будут преобразованы в ассоциативные массивы.
 * @param int $depth [необязательно] Указанная пользователем глубина рекурсии.
 * @param int $flags [необязательно] Битовая маска параметров декодирования JSON: JSON_BIGINT_AS_STRING декодирует большие целые числа как их исходное строковое значение. JSON_INVALID_UTF8_IGNORE игнорирует недопустимые символы UTF-8, JSON_INVALID_UTF8_SUBSTITUTE преобразует недопустимые символы UTF-8 в \0xfffd, JSON_OBJECT_AS_ARRAY декодирует объекты JSON как массив PHP, начиная с версии 7.2.0, используемой по умолчанию, если параметр $assoc равен null, JSON_THROW_ON_ERROR при передаче этого флага поведение ошибок этих функций изменяется. Состояние глобальной ошибки остается нетронутым, и если возникает ошибка, которая в противном случае установила бы его, эти функции вместо этого генерируют исключение JSONException
 * @return mixed
 */
function parse_json_decode(string $json, ?bool $associative = null, int $depth = 512, int $flags = 0) {
    $json = preg_replace("/,\s*([\]}])/", "$1", $json);

    $decodedData = json_decode($json, $associative, $depth, $flags);

    if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
        return json_decode($json, $associative, $depth, $flags);
    }

    return $decodedData;
}