<?php
global $main_path, $privileges;

include_once "lang/lang.php";
include_once "php/data.php";
include_once "secure/user-privileges.php";

include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$path = trim($_GET["path"]) ?? "";
$id = trim($_GET["id"]) ?? "";

$array_units_size = array(
    str_get_string("text_size_b"),
    str_get_string("text_size_kb"),
    str_get_string("text_size_mb"),
    str_get_string("text_size_gb"),
    str_get_string("text_size_tb")
);
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

<?php if (!$privileges["preview"]["file"] && is_file($path)) { ?>
    <h4 class="message-cont"><?= str_get_string("text_privileges_forbidden") ?></h4>
<?php exit(); } ?>
<?php if (!$privileges["preview"]["dir"] && is_dir($path)) { ?>
    <h4 class="message-cont"><?= str_get_string("text_privileges_forbidden") ?></h4>
<?php exit(); } ?>
<?php //if (!is_readable($path)) { ?>
<!--    <h4 class="message-cont">--><?php //= str_get_string("text_access_denied") ?><!--</h4>-->
<?php //exit(); } ?>

<div class="container custom-scroll">
    <div class="content">
        <div class="preview">
            <img src="<?= $file_parse->get_icon($path, $privileges['view_file'], 256) ?>" alt="Image">

            <?php if (is_file($path) && strlen(trim($file_manager->get_file_format($path))) > 0) { ?>
                <span class="format"><?= $file_manager->get_file_format($path) ?></span>
            <?php } ?>
        </div>

        <div class="info">
            <span>
                <label><?= is_dir($path) ? str_get_string("text_about_dir_name") : str_get_string("text_about_file_name") ?></label>
                <var><?= basename($path) ?></var>
            </span>
            <span>
                <label><?= str_get_string("text_about_path") ?></label>
                <var><?= $file_manager->parse_separator($path) ?></var>
            </span>
            <?php if ($privileges["view_file"]) { ?>
                <span>
                    <label><?= str_get_string("text_about_url") ?></label>
                    <var><?= $file_manager->get_current_url($file_manager->parse_separator($path), true) ?></var>
                </span>
            <?php } ?>
            <span>
                <label><?= str_get_string("text_about_size") ?></label>
                <var><?= is_dir($path) ? $file_manager->format_size($file_manager->get_directory_size($path), $array_units_size) : $file_manager->format_size($file_manager->get_file_size($path), $array_units_size) ?></var>
            </span>
            <span>
                <label><?= str_get_string("text_about_date_modified") ?></label>
                <var><?= date("Y-m-d H:i:s", $file_manager->get_date_modified($path)) ?></var>
            </span>
            <span>
                <label><?= str_get_string("text_about_permissions") ?></label>
                <var><?= $file_manager->get_permissions_string($path) . " (" . $file_manager->get_permissions_int($path) . ")" ?></var>
            </span>
            <?php if (is_file($path) and file_exists($path)) { ?>
                <span>
                    <label><?= str_get_string("text_about_mime_type") ?></label>
                    <var><?= $file_manager->get_mime_type($path) ?></var>
                </span>
            <?php } ?>
        </div>
    </div>
</div>

