<?php
global $language_tag, $session_name, $main_path;

include_once "lang/lang.php"; // Загружаем языковой пакет
include_once "php/data.php"; // Загружаем системные настройки

$resource_v = time(); // Устанавливаем версию для ресурсов
?>

<link rel="stylesheet" type="text/css" href="assets/css/system/root.css?v=<?= time() ?>">
<link rel="stylesheet" type="text/css" href="assets/css/auth.css?v=<?= time() ?>">

<form id="form-auth" method="post" autocomplete="off" autocapitalize="off">
    <h1>
        <?= str_get_string("document_name_short") ?>
        <span><?= str_get_string("document_file_manager") ?></span>
    </h1>

    <label>
        <input name="login" id="form-input-login" type="text" placeholder="<?= str_get_string('hint_auth_enter_login') ?>" autocomplete="off" autocapitalize="off" minlength="3" maxlength="16">
    </label>
    <label>
        <input name="password" id="form-input-password" type="password" placeholder="<?= str_get_string('hint_auth_enter_password') ?>" autocomplete="off" autocapitalize="off" minlength="3" maxlength="64">
    </label>

    <button type="submit"><?= str_get_string('action_auth_submit') ?></button>

    <h4><?= str_get_string("document_copyright", true) ?></h4>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/md5.js"></script>

<script>
    document.title = "<?= str_get_string('document_name') ?>";

    document.getElementById("form-auth").addEventListener("submit", (ev) => {
        ev.preventDefault();
        ev.stopPropagation();

        const serializeParams = new URLSearchParams(Array.from(
            new FormData(document.getElementById("form-auth"))
        ));

        let login = serializeParams.get("login");
        let password = serializeParams.get("password");
        let passwordMD5 = CryptoJS.MD5(password).toString();

        if (login.length < 3 || login.length > 16) {
            alert("<?= str_get_string('message_auth_login_incorrect') ?>")
            return;
        } if (password.length < 3 || password.length > 64) {
            alert("<?= str_get_string('message_auth_password_incorrect') ?>")
            return;
        }

        setCookie("<?= $session_name ?>", `${login}:${passwordMD5}`, 0)

        setTimeout(() => {
            window.location.reload();
        }, 200);
    });
</script>
<script>
    function setCookie(name = "", value = "", days = 0) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }
</script>