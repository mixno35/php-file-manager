<?php
include_once "php/data.php";
include_once "lang/lang.php";

include_once "class/FileManager.php";
include_once "php/class/CheckSession.php";

$check_session = new CheckSession();

if (!$check_session->check()) {
    http_response_code(403);
    exit();
}

$file_manager = new FileManager();

$path = trim($_GET["path"]) ?? "";
?>
<?php if (empty($path)) { // Проверяем, не пустой ли путь ?>
    <?php exit(); ?>
<?php } ?>

<?php if (!is_dir($path)) { // Проверяем, есть ли такая папка ?>
    <?php exit(); ?>
<?php } ?>

<?php
$folders = $file_manager->get_dirs($path);

asort($folders);
?>

<?php if (sizeof($folders) < 1) { ?>
    <?php exit(); ?>
<?php } ?>

<ul>
    <?php foreach ($folders as $folder) { ?>
        <?php
        $name = basename($folder);
        $count = $file_manager->get_dirs($folder);

        $uniID = uniqid();
        ?>
        <li title="<?= $name ?>" data-path="<?= $folder ?>" class="<?= empty($count) ? 'empty' : '' ?>" id="item-folder-<?= $uniID ?>">
            <span onclick="itemLoadNavDirMng('item-folder-<?= $uniID ?>', 'section-item-folder-id-<?= $uniID ?>', <?= sizeof($count) ?>)">
                <?php $size = sizeof($count); ?>
                <img alt="Folder" id="status-icon-item-folder-<?= $uniID ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABg0lEQVR4nO3YzyvDcRzH8dfN3Un+gbWShJZEkiQtSRJaLCX8EVYODlKUww7IDr4H5SJyU3Pxa34Nm18HhzH7pty/4dte8ge8D+y72bs+r3rc38/rGzAzMzMz82q2haBtIWtb4F/k1pG3LTzmLITxH8vE8PIcA72QWcNMyQOeVkBPLWOupAEPUdBr91EspldRWZKA1BLotbfdBvJ+knyYKtjn1aj7vtcaEQMuF0Av2Ts/x0946iMZcsWAxBzolexWPXk3XhSQdjALFupkvoKv2wHybqxoIO0rOUTehssepDE9Qg0gB4SoAcSA1DA1gBwwSA0gBtwMUAOIAdf91AByQB81gBhw1UsNIAYke6gB5IAgNYC0/GU3NYAc0EUNIAZcdFIDiAHnHdQAckA7NYAYcNZGDSAGnLZQA8gBzdQAYkCiiRpADDgJUAPIAY3UAGLAcT01gBxQRw0gzdn3u/mjWpYzJ+6XX4u5jeqIE/e5+cMaliMn7nOzm1XTYoCZmZmZGX65b1fjrzHebGEuAAAAAElFTkSuQmCC">
                <span class="folder"><?= $name ?></span>
                <span class="count"><?= $size > 99 ? "99+" : $size ?></span>
                <?php if ($size > 0) { ?>
                    <i class="fa fa-chevron-down"></i>
                <?php } ?>
            </span>
            <section id="section-item-folder-id-<?= $uniID ?>" class="section-item-folder"></section>
        </li>
    <?php } ?>
</ul>
