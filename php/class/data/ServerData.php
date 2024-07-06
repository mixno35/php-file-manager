<?php
/**
 * Автор класса: mixno35
 * Последнее обновление класса: 27 июня 2024
 *
 * PHP 8.0+
 */

namespace class\data;

class ServerData {

    const
        REQUEST_METHOD_GET = "GET",
        REQUEST_METHOD_POST = "POST",
        REQUEST_METHOD_PUT = "PUT",
        REQUEST_METHOD_DELETE = "DELETE",
        REQUEST_METHOD_HEAD = "HEAD",
        REQUEST_METHOD_OPTIONS = "OPTIONS",
        REQUEST_METHOD_PATCH = "PATCH",
        REQUEST_METHOD_TRACE = "TRACE",
        REQUEST_METHOD_CONNECT = "CONNECT";

    const
        CACHE_CONTROL_PRIVATE = "private",
        CACHE_CONTROL_PUBLIC = "public",
        CACHE_CONTROL_NO_CACHE = "no-cache",
        CACHE_CONTROL_NO_STORE = "no-store";

    const
        CONTENT_TYPE_ALL = "*/*",

        CONTENT_TYPE_TEXT_PLAIN = "text/plain",
        CONTENT_TYPE_TEXT_HTML = "text/html",
        CONTENT_TYPE_TEXT_CSS = "text/css",
        CONTENT_TYPE_TEXT_JAVASCRIPT = "text/javascript",
        CONTENT_TYPE_TEXT_XML = "text/xml",
        CONTENT_TYPE_TEXT_CSV = "text/csv",
        CONTENT_TYPE_TEXT_MARKDOWN = "text/markdown",

        CONTENT_TYPE_IMAGE_JPEG = "image/jpeg",
        CONTENT_TYPE_IMAGE_PNG = "image/png",
        CONTENT_TYPE_IMAGE_GIF = "image/gif",
        CONTENT_TYPE_IMAGE_BMP = "image/bmp",
        CONTENT_TYPE_IMAGE_WEBP = "image/webp",
        CONTENT_TYPE_IMAGE_SVG_XML = "image/svg+xml",

        CONTENT_TYPE_AUDIO_MPEG = "audio/mpeg",
        CONTENT_TYPE_AUDIO_WAV = "audio/wav",
        CONTENT_TYPE_AUDIO_OGG = "audio/ogg",
        CONTENT_TYPE_AUDIO_WEBM = "audio/webm",

        CONTENT_TYPE_VIDEO_MP4 = "video/mp4",
        CONTENT_TYPE_VIDEO_WEBM = "video/webm",
        CONTENT_TYPE_VIDEO_OGG = "video/ogg",

        CONTENT_TYPE_APPLICATION_JSON = "application/json",
        CONTENT_TYPE_APPLICATION_XML = "application/xml",
        CONTENT_TYPE_APPLICATION_XHTML_XML = "application/xhtml+xml",
        CONTENT_TYPE_APPLICATION_PDF = "application/pdf",
        CONTENT_TYPE_APPLICATION_ZIP = "application/zip",
        CONTENT_TYPE_APPLICATION_GZIP = "application/gzip",
        CONTENT_TYPE_APPLICATION_JAVASCRIPT = "application/javascript",
        CONTENT_TYPE_APPLICATION_LD_JSON = "application/ld+json",
        CONTENT_TYPE_APPLICATION_OCTET_STREAM = "application/octet-stream",
        CONTENT_TYPE_APPLICATION_X_WWW_FORM_URLENCODED = "application/x-www-form-urlencoded",

        CONTENT_TYPE_MULTIPART_FORM_DATA = "multipart/form-data",
        CONTENT_TYPE_MULTIPART_BYTERANGES = "multipart/byteranges",

        CONTENT_TYPE_FONT_WOFF = "font/woff",
        CONTENT_TYPE_FONT_WOFF2 = "font/woff2",
        CONTENT_TYPE_FONT_TTF = "font/ttf",
        CONTENT_TYPE_FONT_OTF = "font/otf";


    /**
     * Получает данные о сервере из глобального массива <code>$this->getServer</code>.
     *
     * Если передан ключ <code>$key</code>, функция возвращает соответствующее значение из <code>$this->getServer</code>,
     * либо <b>null</b>, если значение с указанным ключом не найдено.
     *
     * Если <code>$key</code> не передан, функция возвращает все данные о сервере в виде ассоциативного массива.
     *
     * @param string|null $key Ключ, для которого требуется получить значение из <code>$this->getServer</code>.
     * @return array|string|null Если передан <code>$key</code>, то возвращает соответствующее значение из <code>$this->getServer</code> или <b>null</b>,
     *                           в противном случае возвращает все данные о сервере в виде ассоциативного массива.
     */
    public function getServer(?string $key = null): array|string|null {
        if (!empty($key)) {
            if (!empty($_SERVER[$key])) {
                return $_SERVER[$key];
            } else {
                return null;
            }
        } else {
            return $_SERVER;
        }
    }

    /**
     * Получает тип контента запроса из заголовка <u>HTTP_ACCEPT</u>.
     *
     * Если заголовок <u>HTTP_ACCEPT</u> доступен, функция возвращает его значение.
     * В противном случае, возвращает значение по умолчанию "text/html".
     *
     * @return string Тип контента запроса.
     */
    public function getContentType(): string {
        if (getallheaders()["Content-Type"]) {
            return getallheaders()["Content-Type"];
        } else if (!empty($this->getServer("HTTP_ACCEPT"))) {
            return $this->getServer("HTTP_ACCEPT");
        } else if (!empty($this->getServer("HTTP_CONTENT_TYPE"))) {
            return $this->getServer("HTTP_CONTENT_TYPE");
        } else {
            return "text/html";
        }
    }

    /**
     * Получает имя хоста из глобального массива <code>$this->getServer</code> и возвращает его в виде строки.
     * Если имя хоста не доступно, возвращает значение "localhost".
     *
     * @return string Имя хоста.
     */
    public function getHost(): string {
        if (!empty($this->getServer("HTTP_HOST"))) {
            return trim($this->getServer("HTTP_HOST"));
        } else {
            return "localhost";
        }
    }

    /**
     * Получает источник перехода (HTTP_REFERER) из глобального массива <code>$this->getServer</code> и возвращает его в виде строки.
     * Если источник перехода не доступен, возвращает значение "localhost".
     *
     * @return string Источник перехода (HTTP_REFERER).
     */
    public function getReferer(): string {
        if (!empty($this->getServer("HTTP_REFERER"))) {
            return $this->getServer("HTTP_REFERER");
        } else {
            return "localhost";
        }
    }

    /**
     * Получает IP-адрес клиента из различных источников и возвращает его в виде строки.
     *
     * @return string IP-адрес клиента.
     */
    public function getClientIP(): string {
        if (!empty($this->getServer("HTTP_CLIENT_IP"))) {
            return $this->getServer("HTTP_CLIENT_IP");
        } else if (!empty($this->getServer("HTTP_CF_CONNECTING_IP"))) {
            return $this->getServer("HTTP_CF_CONNECTING_IP");
        } else if (!empty($this->getServer("HTTP_X_FORWARDED_FOR"))) {
            return $this->getServer("HTTP_X_FORWARDED_FOR");
        } else if (!empty($this->getServer("HTTP_X_FORWARDED"))) {
            return $this->getServer("HTTP_X_FORWARDED");
        } else if (!empty($this->getServer("HTTP_FORWARDED_FOR"))) {
            return $this->getServer("HTTP_FORWARDED_FOR");
        } else if (!empty($this->getServer("HTTP_FORWARDED"))) {
            return $this->getServer("HTTP_FORWARDED");
        } else if (!empty($this->getServer("REMOTE_ADDR"))) {
            return $this->getServer("REMOTE_ADDR");
        } else {
            return "111.11.11.1";
        }
    }

    /**
     * Возвращает строку, содержащую значение заголовка User-Agent клиента.
     *
     * @return string Строка заголовка User-Agent клиента. Пустая строка, если заголовок отсутствует.
     */
    public function getClientUserAgent(): string {
        if (!empty($_SERVER["HTTP_USER_AGENT"])) {
            return $_SERVER["HTTP_USER_AGENT"];
        } else {
            return "";
        }
    }

    /**
     * Получает порт клиента из данных о сервере.
     *
     * @return int Порт клиента.
     */
    public function getClientPort(): int {
        if (!empty($this->getServer("REMOTE_PORT"))) {
            return intval($this->getServer("REMOTE_PORT"));
        } else {
            return 0;
        }
    }

    /**
     * Возвращает протокол (например, "http" или "https") с или без разделителя протокола "://".
     *
     * @param bool|null $protocol_separator Указывает, следует ли включать разделитель протокола.
     *                                     Если установлено в <b>true</b>, возвращает протокол с разделителем "://".
     *                                     Если установлено в <b>false</b> или <b>null</b>, возвращает протокол без разделителя.
     * @return string Возвращает строку, содержащую протокол с или без разделителя "://".
     */
    public function getProtocol(?bool $protocol_separator = false): string {
        if (empty($protocol_separator)) {
            $protocol_separator = false;
        }

        if ($this->isProtocolHTTPS()) {
            $protocol = "https";
        } else {
            $protocol = "http";
        }

        if ($protocol_separator) {
            return sprintf("%s://", $protocol);
        } else {
            return $protocol;
        }
    }

    /**
     * Получает имя сервера из данных о сервере.
     *
     * @param bool|null $clear_subdomain Опциональный параметр для указания необходимости очистки поддомена из имени сервера.
     *                                   Если установлен в <b>true</b> и имя сервера не является IP-адресом, функция удаляет поддомен из имени сервера.
     *                                   Если установлен в <b>false</b> или <b>null</b>, функция возвращает полное имя сервера.
     * @return string Имя сервера.
     */
    public function getName(?bool $clear_subdomain = false): string {
        if (empty($clear_subdomain)) {
            $clear_subdomain = false;
        }

        if (!empty($this->getServer("SERVER_NAME"))) {
            $string = trim($this->getServer("SERVER_NAME"));

            if ($clear_subdomain && !filter_var($string, FILTER_VALIDATE_IP)) {
                $string = preg_replace("/^(.+?\.)+?([^.]+\.[a-z]{2,})$/", "$2", $string);
            }
            return $string;
        } else {
            return "localhost";
        }
    }

    /**
     * Получает порт сервера из глобального массива <code>$this->getServer</code> и возвращает его в виде целого числа.
     * Если порт сервера не доступен, возвращает значение 80.
     *
     * @return int Порт сервера.
     */
    public function getPort(): int {
        if (!empty($this->getServer("SERVER_PORT"))) {
            return intval($this->getServer("SERVER_PORT"));
        } else {
            return 80;
        }
    }

    /**
     * Получает адрес электронной почты администратора сервера из глобального массива <code>$this->getServer</code> и возвращает его в виде строки.
     * Если адрес электронной почты администратора не доступен, возвращает пустую строку.
     *
     * @return string Адрес электронной почты администратора сервера.
     */
    public function getAdminEmail(): string {
        if (!empty($this->getServer("SERVER_ADMIN")) && $this->getServer("SERVER_ADMIN") !== "[no address given]") {
            return $this->getServer("SERVER_ADMIN");
        } else {
            return "";
        }
    }

    /**
     * Получает IP-адрес сервера из глобального массива <code>$this->getServer</code> и возвращает его в виде строки.
     * Если IP-адрес сервера не доступен, возвращает значение "127.0.0.1".
     *
     * @return string IP-адрес сервера.
     */
    public function getIP(): string {
        if (!empty($this->getServer("SERVER_ADDR"))) {
            return $this->getServer("SERVER_ADDR");
        } else {
            return "127.0.0.1";
        }
    }

    /**
     * Получает детали протокола, используемого сервером, из глобального массива <code>$this->getServer</code> и возвращает их в виде строки.
     * Если детали протокола не доступны, возвращает значение "HTTP/1.1".
     *
     * @return string Детали протокола, используемого сервером.
     */
    public function getProtocolDetail(): string {
        if (!empty($this->getServer("SERVER_PROTOCOL"))) {
            return $this->getServer("SERVER_PROTOCOL");
        } else {
            return "HTTP/1.1";
        }
    }

    /**
     * Получает информацию о программном обеспечении сервера из глобального массива <code>$this->getServer</code> и возвращает её в виде строки.
     * Если информация о программном обеспечении сервера не доступна, возвращает значение "NaN" (не число).
     *
     * @return string Информация о программном обеспечении сервера.
     */
    public function getSoftware(): string {
        if (!empty($this->getServer("SERVER_SOFTWARE"))) {
            return $this->getServer("SERVER_SOFTWARE");
        } else {
            return "NaN";
        }
    }

    /**
     * Получает подпись сервера из глобального массива <code>$this->getServer</code> и возвращает её в виде строки.
     * Если подпись сервера не доступна, возвращает пустую строку.
     *
     * @return string Подпись сервера.
     */
    public function getSignature(): string {
        if (!empty($this->getServer("SERVER_SIGNATURE"))) {
            return $this->getServer("SERVER_SIGNATURE");
        } else {
            return "";
        }
    }

    /**
     * Проверяет, используется ли защищенный протокол <u>HTTPS</u>.
     *
     * @return bool Возвращает <b>true</b>, если текущий протокол <u>HTTPS</u>, иначе возвращает <b>false</b>.
     */
    public function isProtocolHTTPS(): bool {
        return !empty($this->getServer("HTTPS")) && strtoupper($this->getServer("HTTPS")) === "ON";
    }

    /**
     * Проверяет, используется ли стандартный протокол <u>HTTP</u>.
     *
     * @return bool Возвращает <b>true</b>, если текущий протокол <u>HTTP</u>, иначе возвращает <b>false</b>.
     */
    public function isProtocolHTTP(): bool {
        return !empty($this->getServer("HTTPS")) && strtoupper($this->getServer("HTTPS")) === "OFF";
    }

    /**
     * Получает имя скрипта (или полный путь к файлу скрипта) из данных о сервере.
     *
     * @param bool|null $basename Опциональный параметр для указания формата возвращаемого значения.
     *                            Если установлен в <b>true</b>, возвращает только базовое имя файла без пути.
     *                            Если установлен в <b>false</b> или <b>null</b>, возвращает полный путь к файлу скрипта.
     * @return string Имя скрипта (или полный путь к файлу скрипта) в зависимости от значения параметра $basename.
     */
    public function getScriptFile(?bool $basename = false): string {
        if (empty($basename)) {
            $basename = false;
        }

        if (!empty($this->getServer("SCRIPT_FILENAME"))) {
            $file_name = $this->getServer("SCRIPT_FILENAME");
            if ($basename) {
                return basename($file_name);
            } else {
                return $file_name;
            }
        } else {
            return "";
        }
    }

    /**
     * Получает строку запроса (query <b>string</b>) или массив параметров из строки запроса.
     *
     * Если параметр <code>$array</code> установлен в true, функция возвращает массив параметров,
     * в противном случае возвращает строку запроса.
     *
     * Если указан ключ <code>$key</code> и параметр <code>$array</code> установлен в <b>true</b>, функция возвращает значение параметра с указанным ключом из массива.
     *
     * @param bool|null $array Опциональный параметр для указания возвращаемого типа.
     *                         Если установлен в <b>true</b>, возвращает массив параметров.
     *                         Если установлен в <b>false</b> или <b>null</b>, возвращает строку запроса.
     * @param string|null $key Опциональный ключ параметра для получения значения из массива параметров.
     * @return string|array|null Если <code>$array</code> установлен в <b>false</b> или <b>null</b>, возвращает строку запроса.
     *                           Если <code>$array</code> установлен в <b>true</b> и <code>$key</code> не указан, возвращает массив параметров.
     *                           Если <code>$array</code> установлен в <b>true</b> и <code>$key</code> указан, возвращает значение параметра с указанным ключом из массива,
     *                           или <b>null</b>, если такого ключа нет в массиве.
     */
    public function getQuery(?bool $array = false, ?string $key = null): string|array|null {
        if (empty($array)) {
            $array = false;
        }

        if (!empty($this->getServer("QUERY_STRING"))) {
            $string = $this->getServer("QUERY_STRING");
        } else {
            $string = "";
        }

        if ($array) {
            parse_str($string, $queryArray);

            if (!empty($key)) {
                if (!empty($queryArray[$key])) {
                    return $queryArray[$key];
                } else {
                    return null;
                }
            } else {
                return $queryArray;
            }
        } else {
            return $string;
        }
    }

    /**
     * Получает время начала запроса (время UNIX-времени) из данных о сервере.
     *
     * @param bool|null $float Опциональный параметр для указания формата возвращаемого времени.
     *                         Если установлен в <b>true</b>, возвращает время начала запроса в формате с плавающей точкой.
     *                         Если установлен в <b>false</b> или <b>null</b>, возвращает время начала запроса в целочисленном формате.
     * @return int|float Время начала запроса в указанном формате.
     */
    public function getRequestTime(?bool $float = false): int|float {
        if (empty($float)) {
            $float = false;
        }

        if ($float) {
            if (!empty($this->getServer("REQUEST_TIME_FLOAT"))) {
                return floatval($this->getServer("REQUEST_TIME_FLOAT"));
            } else {
                return floatval(time());
            }
        } else {
            if (!empty($this->getServer("REQUEST_TIME"))) {
                return intval($this->getServer("REQUEST_TIME"));
            } else {
                return time();
            }
        }
    }

    /**
     * Получает схему запроса (например, "http" или "https") из данных о сервере.
     *
     * @return string Схема запроса (например, "http" или "https").
     */
    public function getRequestScheme(): string {
        if (!empty($this->getServer("REQUEST_SCHEME"))) {
            return strtolower($this->getServer("REQUEST_SCHEME"));
        } else {
            return "http";
        }
    }

    /**
     * Получает <u>URI</u> (Uniform Resource Identifier) запроса из данных о сервере / значение пути.
     * @param bool $trim_slash Опциональный параметр для указания нужно ли обрезать начальный слэш в <u>URI</u>. По умолчанию <b>false</b>.
     *
     * @return string <u>URI</u> (Uniform Resource Identifier) запроса.
     */
    public function getRequestURI(?bool $trim_slash = false): string {
        if (empty($trim_slash)) {
            $trim_slash = false;
        }

        if (!empty($this->getServer("REQUEST_URI"))) {
            if ($trim_slash) {
                return substr($this->getServer("REQUEST_URI"), 1);
            } else {
                return $this->getServer("REQUEST_URI");
            }
        } else {
            return "";
        }
    }

    /**
     * Получает <u>HTTP</u> метод запроса из данных о сервере.
     *
     * Если метод запроса доступен в данных о сервере (<code>$this->getServer("REQUEST_METHOD")</code>),
     * функция возвращает его в верхнем регистре.
     *
     * Если метод запроса не доступен, функция возвращает "GET" в качестве значения по умолчанию.
     *
     * @return string <u>HTTP</u> метод запроса (например, "GET", "POST", "PUT" и т. д.).
     */
    public function getRequestMethod(): string {
        if (!empty($this->getServer("REQUEST_METHOD"))) {
            return strtoupper($this->getServer("REQUEST_METHOD"));
        } else {
            return "GET";
        }
    }

    /**
     * Возвращает массив поддерживаемых <u>HTTP</u> методов запроса.
     *
     * @return array Массив поддерживаемых <u>HTTP</u> методов запроса.
     */
    public function getRequestMethods(): array {
        return array(
            self::REQUEST_METHOD_GET,
            self::REQUEST_METHOD_DELETE,
            self::REQUEST_METHOD_POST,
            self::REQUEST_METHOD_PUT,
            self::REQUEST_METHOD_CONNECT,
            self::REQUEST_METHOD_HEAD,
            self::REQUEST_METHOD_OPTIONS,
            self::REQUEST_METHOD_PATCH,
            self::REQUEST_METHOD_TRACE
        );
    }

    /**
     * Возвращает текущую кодировку HTTP-вывода в верхнем регистре.
     *
     * @return string Кодировка HTTP-вывода в верхнем регистре или "utf-8" по умолчанию, если кодировка не установлена.
     */
    public function getCharset(): string {
        return mb_strtoupper(mb_http_output() ?? "utf-8") ;
    }

    /**
     * Получает данные из массива $_POST или конкретное значение по ключу.
     *
     * @param string|null $key Ключ для получения конкретного значения из $_POST.
     * @return mixed|array|null Значение из $_POST, соответствующее заданному ключу, или весь массив $_POST, если ключ не указан.
     */
    public function valuePost(?string $key = null): mixed {
        if (empty($key)) {
            return $_POST;
        } else if (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        } else {
            return null;
        }
    }

    /**
     * Получает данные из массива $_GET или конкретное значение по ключу.
     *
     * @param string|null $key Ключ для получения конкретного значения из $_GET.
     * @return mixed|array|null Значение из $_GET, соответствующее заданному ключу, или весь массив $_GET, если ключ не указан.
     */
    public function valueGet(?string $key = null): mixed {
        if (empty($key)) {
            return $_GET;
        } else if (array_key_exists($key, $_GET)) {
            return $_GET[$key];
        } else {
            return null;
        }
    }
}