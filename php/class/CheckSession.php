<?php
const SESSION_USERS = array(
    "admin" => "admin"
); // "login" => "password"

class CheckSession {

    private Crypt $crypt;

    public function __construct(Crypt $crypt) {
        $this->setCrypt($crypt);
    }

    public function check():bool {
        session_start();

        if (empty(trim($_SESSION[SESSION_NAME] ?? ""))) return false;

        $session_str = $this->getCrypt()->decrypt($_SESSION[SESSION_NAME] ?? "xx::::xx");
        $session_user = explode("::::", $session_str);
        $session_login = $session_user[0] ?? "";
        $session_password = $session_user[1] ?? password_hash("empty", PASSWORD_DEFAULT);

        if (array_key_exists($session_login, SESSION_USERS) and password_verify(SESSION_USERS[$session_login], $session_password)) {
            if (!defined("USER_LOGIN")) define("USER_LOGIN", $session_login);
            return true;
        }

        return false;
    }

    private function getCrypt():Crypt {
        return $this->crypt;
    }

    public function setCrypt(Crypt $crypt):void {
        $this->crypt = $crypt;
    }
}