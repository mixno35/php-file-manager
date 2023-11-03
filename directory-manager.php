<?php
include_once "php/data.php";
include_once "lang/lang.php";
include_once "class/FileManager.php";

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
$folders = $file_manager->get_folders($path);

asort($folders);
?>

<?php if (sizeof($folders) < 1) { ?>
    <?php exit(); ?>
<?php } ?>

<ul style="max-height: 999999px;">
    <?php foreach ($folders as $folder) { ?>
        <?php
        $f_path = $path. DIRECTORY_SEPARATOR . $folder;
        $f_folder = $file_manager->get_folders($f_path);

        $uniID = uniqid();
        ?>
        <li title="<?= $f_path ?>" data-path="<?= $f_path ?>" class="<?= empty($f_folder) ? 'empty' : '' ?>" id="item-folder-<?= $uniID ?>">
            <span onclick="itemLoadNavDirMng('item-folder-<?= $uniID ?>', 'section-item-folder-id-<?= $uniID ?>', <?= sizeof($f_folder) ?>)">
                <img alt="Folder" id="status-icon-item-folder-<?= $uniID ?>" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABg0lEQVR4nO3YzyvDcRzH8dfN3Un+gbWShJZEkiQtSRJaLCX8EVYODlKUww7IDr4H5SJyU3Pxa34Nm18HhzH7pty/4dte8ge8D+y72bs+r3rc38/rGzAzMzMz82q2haBtIWtb4F/k1pG3LTzmLITxH8vE8PIcA72QWcNMyQOeVkBPLWOupAEPUdBr91EspldRWZKA1BLotbfdBvJ+knyYKtjn1aj7vtcaEQMuF0Av2Ts/x0946iMZcsWAxBzolexWPXk3XhSQdjALFupkvoKv2wHybqxoIO0rOUTehssepDE9Qg0gB4SoAcSA1DA1gBwwSA0gBtwMUAOIAdf91AByQB81gBhw1UsNIAYke6gB5IAgNYC0/GU3NYAc0EUNIAZcdFIDiAHnHdQAckA7NYAYcNZGDSAGnLZQA8gBzdQAYkCiiRpADDgJUAPIAY3UAGLAcT01gBxQRw0gzdn3u/mjWpYzJ+6XX4u5jeqIE/e5+cMaliMn7nOzm1XTYoCZmZmZGX65b1fjrzHebGEuAAAAAElFTkSuQmCC">
                <span class="folder"><?= $folder ?></span>
                <span class="count"><?php $size = sizeof($f_folder); echo $size > 99 ? "99+" : $size; ?></span>
            </span>
            <section id="section-item-folder-id-<?= $uniID ?>"></section>
        </li>
    <?php } ?>
</ul>
