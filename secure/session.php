<?php
include_once dirname(__FILE__, 2) . "/php/data.php";

include_once dirname(__FILE__, 2) . "/php/class/CheckSession.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$check_session = new CheckSession(new Crypt(READY_KEY));

if (!$check_session->check()) {
    include_once "auth.php";
    exit();
}