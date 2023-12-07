<?php
global $session_name;

include_once dirname(__FILE__, 3) . "/php/data.php";
include_once dirname(__FILE__, 3) . "/php/class/Crypt.php";

include_once "Crypt.php";

$crypt = new Crypt();

session_start();

const SESSION_USERS = array(
    "admin" => "admin"
);

class CheckSession {

    public function check():bool {
        global $session_name, $crypt;

        if (empty(trim($_SESSION[$session_name] ?? ""))) return false;

        $session_str = $crypt->decrypt($_SESSION[$session_name] ?? "xx:xx");
        $session_user = explode(":", $session_str);
        $session_login = $session_user[0] ?? "";
        $session_password = $session_user[1] ?? md5("empty");

        if (array_key_exists($session_login, SESSION_USERS) and md5(SESSION_USERS[$session_login]) === $session_password) {
            if (!defined("USER_LOGIN")) define("USER_LOGIN", $session_login);
            return true;
        }

        return false;
    }
}