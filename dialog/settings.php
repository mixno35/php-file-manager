<?php
global $language_tag, $content, $login, $main_path, $server_encoding,
       $default_avatar, $session_name, $settings, $uni_id;

include_once "../lang/lang.php";

include_once "../secure/session.php";

include_once "../php/data.php";
include_once "../php/settings.php";

$content = json_decode(file_get_contents(dirname(__FILE__, 2) . "/assets/settings.json"), true);
?>
<div class="user-container">
    <section>
        <img src="<?= $default_avatar ?>" alt="Avatar">
        <span><?= str_get_string("message_welcome_back_user", true, [$login]) ?></span>
        <i class="fa-solid fa-arrow-right-from-bracket" title="<?= str_get_string('tooltip_logout') ?>" onclick="setCookie('<?= $session_name ?>', '', 0); window.location.reload()"></i>
    </section>
</div>

<div class="content-settings">
    <?php foreach ($content as $item) { ?>
        <label>
            <span>
                <?= str_get_string($item["title"]) ?>
                <var><?= str_get_string($item["message"]) ?></var>
            </span>
            <?php if ($item["type"] === "switch") { ?>
                <input type="checkbox" class="switch" onchange="setSetting('<?= str_replace('%s', $uni_id, $item['id']) ?>', this.checked)" <?= $settings[$item["param"]] ? "checked" : "" ?>>
            <?php } ?>
        </label>
    <?php } ?>
</div>
