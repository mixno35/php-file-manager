<?php
global $session_name;

include_once dirname(__FILE__, 2) . "/php/data.php";

session_start();

unset($_SESSION[$session_name]);

session_destroy();

header("Location: ../");