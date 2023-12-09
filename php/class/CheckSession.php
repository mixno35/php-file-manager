<?php
global $your_key;

include_once dirname(__FILE__, 3) . "/php/data.php";
include_once dirname(__FILE__, 3) . "/php/class/Crypt.php";

$crypt = new Crypt(SESSION_NAME . $your_key);

session_start();

const SESSION_USERS = array(
    "admin" => "admin"
); // "login" => "password"

class CheckSession {

    public function check():bool {
        global $crypt;

        if (empty(trim($_SESSION[SESSION_NAME] ?? ""))) return false;

        $session_str = $crypt->decrypt($_SESSION[SESSION_NAME] ?? "xx::::xx");
        $session_user = explode("::::", $session_str);
        $session_login = $session_user[0] ?? "";
        $session_password = $session_user[1] ?? password_hash("empty", PASSWORD_DEFAULT);

        if (array_key_exists($session_login, SESSION_USERS) and password_verify(SESSION_USERS[$session_login], $session_password)) {
            if (!defined("USER_LOGIN")) define("USER_LOGIN", $session_login);
            return true;
        }

        return false;
    }
}