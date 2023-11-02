<?php
global $main_path, $privileges;

include_once "php/data.php";
include_once "lang/lang.php";
include_once "php/user-privileges.php"; // Загружаем привилегии пользователя
include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$path = trim($_GET["path"]) ?? "";
$id = trim($_GET["id"]) ?? "";
?>

<?php
$file_manager->check_path($path, str_get_string("action_go_to_home"), addslashes($main_path["server"]));
?>

<header class="header">
    <h4>
        <?= is_dir($path) ? str_get_string("text_about_dir") : str_get_string("text_about_file") ?>
    </h4>
    <button onclick="document.getElementById('<?= $id ?>').remove()" title="<?= str_get_string('tooltip_close') ?>">
        <span>&#215;</span>
    </button>
</header>

<?php if (!$privileges["preview_detail"]["file"] && is_file($path)) { ?>
    <h4 class="message-cont"><?= str_get_string("text_privileges_forbidden") ?></h4>
<?php exit(); } ?>
<?php if (!$privileges["preview_detail"]["dir"] && is_dir($path)) { ?>
    <h4 class="message-cont"><?= str_get_string("text_privileges_forbidden") ?></h4>
<?php exit(); } ?>

<div class="container">
    <div class="content">
        <div class="preview">
            <img src="<?= $file_parse->get_icon($path, true) ?>" alt="Image">

            <?php if (is_file($path) && strlen(trim($file_manager->get_file_format($path))) > 0) { ?>
                <span class="format"><?= $file_manager->get_file_format($path) ?></span>
            <?php } ?>
        </div>

        <div class="info">
            <span>
                <label><?= is_dir($path) ? str_get_string("text_about_dir_name") : str_get_string("text_about_file_name") ?></label>
                <?= basename($path) ?>
            </span>
            <span>
                <label><?= str_get_string("text_about_path") ?></label>
                <?= $path ?>
            </span>
            <span>
                <label><?= str_get_string("text_about_size") ?></label>
                <?= is_dir($path) ? $file_manager->format_size($file_manager->get_directory_size($path)) : $file_manager->format_size($file_manager->get_file_size($path)) ?>
            </span>
            <span>
                <label><?= str_get_string("text_about_date_modified") ?></label>
                <?= date("Y-m-d H:i:s", $file_manager->get_date_modified($path)) ?>
            </span>
            <span>
                <label><?= str_get_string("text_about_permissions") ?></label>
                <?= $file_manager->get_permissions_string($path) . " (" . $file_manager->get_permissions_int($path) . ")" ?>
            </span>
            <?php if (is_file($path) and file_exists($path)) { ?>
                <span>
                    <label><?= str_get_string("text_about_mime_type") ?></label>
                    <?= $file_manager->get_mime_type($path) ?>
                </span>
            <?php } ?>
        </div>
    </div>

    <nav class="bottom-actions">
        <?php $ex_path = addslashes($path); ?>
        <span class="item" onclick="run_command().delete('<?= $ex_path ?>')" title="<?= str_get_string('tooltip_delete') ?>">
            <span class="material-symbols-outlined">delete</span>
            <label><?= str_get_string("action_remove") ?></label>
        </span>
        <span class="item" onclick="run_command().rename('<?= $ex_path ?>')" title="<?= str_get_string('tooltip_rename') ?>">
            <span class="material-symbols-outlined">edit</span>
            <label><?= str_get_string("action_rename") ?></label>
        </span>
        <?php if (is_file($path) and file_exists($path)) { ?>
            <span class="item" onclick="window.open('view.php?p=<?= addslashes($path) ?>', '_blank')" title="<?= str_get_string('tooltip_open_view') ?>">
                <span class="material-symbols-outlined">open_in_new</span>
                <label><?= str_get_string("action_open_view") ?></label>
            </span>
        <?php } ?>
    </nav>
</div>

