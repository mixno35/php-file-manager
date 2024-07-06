<?php

namespace class\data;

use JetBrains\PhpStorm\NoReturn;

class ResponseData {

    const RESPONSE_ERROR = 0, RESPONSE_SUCCESS = 1;

    /**
     * Отправляет HTTP-ответ с указанным статусом и сообщением, затем завершает выполнение скрипта.
     * @param int $status Код статуса ответа. Может быть `self::RESPONSE_ERROR` или `self::RESPONSE_SUCCESS`.
     * @param string|null $message Сообщение ответа. Если не указано, используется сообщение по умолчанию.
     * @return void
     * @NoReturn
     */
    #[NoReturn]
    public function response(int $status, ?string $message = null): void {
        if (empty($message)) {
            if (function_exists("str_lang_string")) {
                $message = str_lang_string("php_no_response_message");
            } else {
                $message = "Без комментариев...";
            }
        }

        if ($status === self::RESPONSE_ERROR) {
            http_response_code(400);
        } else if ($status === self::RESPONSE_SUCCESS) {
            http_response_code(200);
        }
        die($message);
    }
}