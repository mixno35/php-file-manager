<?php
include_once "crypt.php";

$auth_info = $_GET["sdsdg"];
$session_name = "OKpgfxYTMR";

echo "Authorize protection...";
?>

<script src="../assets/js/system.js"></script>

<script>
    setCookie("<?= $session_name ?>", "<?= str_encrypt($auth_info) ?>", 0);
    setTimeout(() => {
        window.location.replace("../");
    }, 200);
</script>
