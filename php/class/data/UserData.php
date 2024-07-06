<?php

namespace class\data;

class UserData {

    private array $users = [];
    private string $SESSION_NAME = "user";

    /**
     * Конструктор класса UserData.
     * Инициализирует сессию и загружает пользователей из JSON файла.
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $filePath = dirname(__FILE__, 3) . "/json/users.json";

        if (file_exists($filePath) && is_file($filePath)) {
            $fileContents = file_get_contents($filePath, true);
            $users = json_decode($fileContents, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $this->set_users($users);
            }
        }
    }

    /**
     * Проверяет, авторизован ли пользователь.
     * @return bool Возвращает <b>true</b>, если пользователь авторизован, иначе <b>false</b>.
     */
    public function authorized(): bool {
        $login = strval($this->user()[0] ?? "");
        $password = strval($this->user()[1] ?? "");

        if (!empty($login) && !empty($password)) {
            $users = $this->get_users();
            if (array_key_exists($login, $users)) {
                $hashedPassword = $users[$login]["password"] ?? "";
                if (!empty($hashedPassword)) {
                    return password_verify($password, $hashedPassword);
                }
            }
        }

        return false;
    }

    /**
     * Авторизует пользователя и сохраняет его данные в сессии.
     * @param string $login Логин пользователя.
     * @param string $password Пароль пользователя.
     * @return void
     */
    public function auth(string $login, string $password): void {
        session_regenerate_id();

        $_SESSION[$this->SESSION_NAME] = implode(";", array(
            $login, $password
        ));
    }

    /**
     * Возвращает массив пользователей.
     * @return array Ассоциативный массив пользователей.
     */
    private function get_users(): array {
        return $this->users;
    }

    /**
     * Устанавливает массив пользователей.
     * @param array $users Ассоциативный массив пользователей.
     * @return void
     */
    private function set_users(array $users): void {
        $this->users = $users;
    }

    /**
     * Возвращает данные текущего пользователя из сессии.
     * @return array Массив с логином и паролем пользователя.
     */
    private function user(): array {
        return explode(";", strval($_SESSION[$this->SESSION_NAME] ?? ""));
    }
}