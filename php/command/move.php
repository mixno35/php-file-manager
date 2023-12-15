<?php
include_once dirname(__FILE__) . "/secure/check-token.php";

global $data, $path_manager, $privileges;

if (!$privileges["move"]) {
    echo json_encode([
        "type" => "error",
        "message_id" => "text_privileges_forbidden"
    ], 128);

    exit();
}