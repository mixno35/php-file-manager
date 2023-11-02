<?php
include_once "crypt.php";

$auth_info = $_GET["sdsdg"];
$session_name = "OKpgfxYTMR";

echo "Authorized";

setcookie($session_name, str_encrypt($auth_info), 0, "/");
?>

<script>
    window.close();
</script>
