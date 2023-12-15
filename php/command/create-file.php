<?php
include_once dirname(__FILE__) . "/secure/check-token.php";

global $data, $path_manager, $privileges;

if (!$privileges["create"]["file"]) {
    echo json_encode([
        "type" => "error",
        "message_id" => "text_privileges_forbidden"
    ], 128);

    exit();
}

$path = $data["path"] ?? "";
$name = $data["name"] ?? "";

if (strlen($name) < 1) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_short_char"
    ], 128);

    exit();
}

if (!is_dir($path)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_is_not_dir"
    ], 128);

    exit();
}

if (!is_readable($path)) {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_is_readable"
    ], 128);

    exit();
}

if ($path_manager->chmod_detect($path)) {
    $array = strstr($name, ",") ? explode(",", $name) : array($name);
    $result = 0;

    foreach ($array as $item) {
        $f_path = $path . DIRECTORY_SEPARATOR . trim($item);

        if (!file_exists($f_path) or !is_dir($f_path))
            if (file_put_contents($f_path, "Simple text.", LOCK_EX) !== false) $result++;
    }

    if ($result > 0) {
        echo json_encode([
            "type" => "success",
            "message_id" => "api_create_file_success",
            "return" => [$name, $result]
        ], 128);
    } else {
        echo json_encode([
            "type" => "error",
            "message_id" => "api_create_file_error"
        ], 128);
    }
} else {
    echo json_encode([
        "type" => "error",
        "message_id" => "api_create_not_permission_777"
    ], 128);
}

exit();