<?php
global $language_tag, $session_name, $main_path, $host;

include_once "lang/lang.php"; // Загружаем языковой пакет
include_once "php/data.php"; // Загружаем системные настройки

$resource_v = time(); // Устанавливаем версию для ресурсов
?>

<link rel="stylesheet" type="text/css" href="assets/css/system/root.css?v=<?= $resource_v ?>">
<link rel="stylesheet" type="text/css" href="assets/css/auth.css?v=<?= $resource_v ?>">

<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<form id="form-auth" method="post" action="secure/auth.php" target="_top" autocomplete="off" autocapitalize="off">
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

<script>
    document.title = "<?= str_get_string('document_name') ?>";
</script>