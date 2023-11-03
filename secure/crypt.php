<?php
if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
    $ip = $_SERVER["HTTP_CLIENT_IP"];
} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else {
    $ip = $_SERVER["REMOTE_ADDR"];
}

$userAgent = ($_SERVER["HTTP_USER_AGENT"] ?? "sdfsdfdfgdfgdfhsdhdfhf");
$key = md5($userAgent . ($ip ?? "0.0.0.0"));


function str_encrypt($text = ""):string {
    global $key;
    $encryptedText = "";
    $textLength = strlen($text);
    $keyLength = strlen($key);

    for ($i = 0; $i < $textLength; $i++) {
        $encryptedText .= $text[$i] ^ $key[$i % $keyLength];
    }

    return base64_encode($encryptedText);
}

function str_decrypt($text = ""):string {
    global $key;
    $encryptedText = base64_decode($text);
    $decryptedText = "";
    $textLength = strlen($encryptedText);
    $keyLength = strlen($key);

    for ($i = 0; $i < $textLength; $i++) {
        $decryptedText .= $encryptedText[$i] ^ $key[$i % $keyLength];
    }

    return $decryptedText;
}
