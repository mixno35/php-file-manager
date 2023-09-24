<?php
global $main_path;

include_once "php/data.php";
include_once "lang/lang.php";
include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$path = trim($_GET["path"]) ?? "";
$id = trim($_GET["id"]) ?? "";
?>

<header class="header">
    <h4>
        <?= is_dir($path) ? str_get_string("text_about_dir") : str_get_string("text_about_file") ?>
    </h4>
    <button onclick="document.getElementById('<?= $id ?>').remove()" title="<?= str_get_string('tooltip_close') ?>">
        <span>&#215;</span>
    </button>
</header>

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
        </div>
    </div>

    <nav class="bottom-actions">
        <?php $ex_path = addslashes($path); ?>
        <span class="item" onclick="run_command().remove('<?= $ex_path ?>')">
            <span class="material-symbols-outlined">delete</span>
            <label><?= str_get_string("action_remove") ?></label>
        </span>
        <span class="item" onclick="run_command().rename('<?= $ex_path ?>')">
            <span class="material-symbols-outlined">edit</span>
            <label><?= str_get_string("action_rename") ?></label>
        </span>
    </nav>
</div>

