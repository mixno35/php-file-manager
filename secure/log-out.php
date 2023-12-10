<?php
include_once dirname(__FILE__, 2) . "/php/data.php";

if (session_status() === PHP_SESSION_NONE) session_start();

unset($_SESSION[SESSION_NAME]);

session_destroy();

header("Location: ../");