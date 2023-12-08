<?php
global $main_path, $server_encoding, $default_avatar, $session_name, $settings, $uni_id;

include_once dirname(__FILE__, 2) . "/lang/lang.php";

include_once dirname(__FILE__, 2) . "/secure/session.php";

include_once dirname(__FILE__, 2) . "/php/data.php";

$content = json_decode(file_get_contents(dirname(__FILE__, 2) . "/assets/settings.json"), true);
?>
<div class="user-container">
    <section>
        <img src="<?= $default_avatar ?>" alt="Avatar">
        <span><?= str_get_string("message_welcome_back_user", true, [(USER_LOGIN ?? "NaN")]) ?></span>
        <i class="fa-solid fa-arrow-right-from-bracket" title="<?= str_get_string('tooltip_logout') ?>" onclick="window.location.href = 'secure/log-out.php'"></i>
    </section>
</div>

<div class="content-settings">
    <?php foreach ($content as $item) { ?>
        <?php
        $id = str_replace("%s", $uni_id, $item["id"] ?? "none");
        $param = $item["param"] ?? "none";
        ?>
        <label>
            <span>
                <?= str_get_string($item["title"] ?? "NaN") ?>
                <?php if (!empty(trim(strval($item["message"] ?? "")))) { ?>
                    <var><?= str_get_string($item["message"]) ?></var>
                <?php } ?>
            </span>
            <?php if ($item["type"] === "switch") { ?>
                <input type="checkbox" class="switch" onchange="setSetting('<?= $id ?>', this.checked); eval(`<?= $item['callback'] ?? '' ?>`)" <?= $settings[$param] ? "checked" : "" ?>>
            <?php } ?>
            <?php if ($item["type"] === "dropdown") { ?>
                <?php
                $list = $item["list"] ?? [];
                ?>
                <select onchange="setSetting('<?= $id ?>', this.value); eval(`<?= $item['callback'] ?? '' ?>`)">
                    <?php foreach ($list as $dd_item) { ?>
                        <option value="<?= $dd_item['key'] ?>" <?= (($settings[$param] ?? "none") === $dd_item["key"]) ? "selected" : "" ?>><?= str_get_string($dd_item["title"]) ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
        </label>
    <?php } ?>
</div>
