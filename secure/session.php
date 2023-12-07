<?php
include_once dirname(__FILE__, 2) . "/php/class/CheckSession.php";

$check_session = new CheckSession();

if (!$check_session->check()) {
    include_once "auth.php";
    exit();
}