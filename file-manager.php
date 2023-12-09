<?php
global $main_path, $settings, $privileges;

session_start();

include_once "php/data.php";
include_once "secure/user-privileges.php";

include_once "lang/lang.php";

include_once "class/FileManager.php";
include_once "class/FileParseManager.php";
include_once "php/class/CheckSession.php";

$check_session = new CheckSession();

if (!$check_session->check()) {
    http_response_code(403);
    exit();
}

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$path = $file_manager->parse_separator(trim(strval($_GET["path"]) ?? ""));
$isGrid = intval($_GET["grid"] ?? 0);
$search_type = intval($_GET["search_type"] ?? 0);
$search = trim(strval($_GET["search"] ?? ""));

$file_manager->check_path($path, str_get_string("action_go_to_home"), addslashes($main_path["server"]));

if (!is_dir($path)) exit();

$pathComponents = explode(DIRECTORY_SEPARATOR, $path);
$rootDirectory = $main_path["server"];

$branch = [];
$tree_pre = [];
$tree = [];

foreach ($pathComponents as $index => $component) {
    $branch[] = $component;
    $fullPath = implode(DIRECTORY_SEPARATOR, $branch);
    $tree_pre[] = $fullPath;
}

foreach ($tree_pre as $item) if (strpos($item, $rootDirectory) === 0) $tree[] = $item;

$uniID = uniqid();
?>
<nav class="header">
    <div class="manager-content left">
        <button class="item-nav-button" onclick="window.history.back()" title="<?= str_get_string("tooltip_go_to_path_back") ?>">
            <i class="fa fa-arrow-left"></i>
        </button>
        <button class="item-nav-button" onclick="window.history.forward()" title="<?= str_get_string("tooltip_go_to_path_forward") ?>">
            <i class="fa fa-arrow-right"></i>
        </button>
        <button class="item-nav-button" onclick="updateMainFileManager()" title="<?= str_get_string("tooltip_go_to_path_refresh") ?>">
            <i class="fa fa-refresh"></i>
        </button>
    </div>

    <ul class="tree">
        <?php foreach ($tree as $item) { ?>
            <li class="<?= ($path === $item) ? 'last-child' : '' ?>" title="<?= basename($item) ?>">
                <span onclick="loadMainFileManager('<?= addslashes($item) ?>', true)">
                    <?= ($item === $rootDirectory) ? str_get_string("action_dir_home") : basename($item) ?>
                </span>
            </li>
            <?php if ($path !== $item) { ?>
                <i class="divider fa fa-chevron-right"></i>
            <?php } ?>
        <?php } ?>
    </ul>

    <div class="manager-content right">
        <button class="item-nav-button" id="file-manager-upload-content" title="<?= str_get_string('tooltip_upload_content') ?>" onclick="document.getElementById('input-upload-content').click()">
            <i class="fa-solid fa-arrow-up-from-bracket"></i>
        </button>
        <button class="item-nav-button" id="file-manager-list-toggle" title="<?= $isGrid === 1 ? str_get_string('tooltip_toggle_linear') : str_get_string('tooltip_toggle_grid') ?>" onclick="toggle_grid_linear(!isGrid)">
            <i class="fa <?= $isGrid === 1 ? 'fa-bars' : 'fa-border-all' ?>" id="file-manager-list-toggle-icon"></i>
        </button>
        <button class="item-nav-button" id="action-nav-create-fd" title="<?= str_get_string('tooltip_create_new_fd') ?>" onclick="event.stopPropagation(); event.preventDefault(); popup_window([
            {name: getStringBy('action_create_new_file'), icon: 'fa-file-lines', for_dir: true, for_file: true},
            {name: getStringBy('action_create_new_dir'), icon: 'fa-folder', for_dir: true, for_file: true}
        ], [
            () => run_command().create(openedDirectory).file(),
            () => run_command().create(openedDirectory).dir()
        ])">
            <i class="fa fa-add"></i>
        </button>
    </div>
</nav>

<?php
$directories = $file_manager->get_dirs($path);
$files = $file_manager->get_files($path);

asort($directories);
asort($files);

$result = array_merge($directories, $files);

if (strlen(trim($search)) > 0) {
    unset($result);
    $result = [];
    $file_manager->search(($search_type >= 1 ? $main_path["server"] : $path), trim($search), $result);
}
?>

<article class="" id="article-file-manager-container" data-path="<?= $path ?>">
    <ul class="file-manager <?= ($isGrid === 1 && sizeof($result) > 0) ? 'grid' : '' ?>" id="file-manager-list">
        <?php if (sizeof($result) > 0) { ?>
            <?php foreach ($result as $item) { ?>
                <?php
                $liUniID = uniqid();
                $name = basename($item);
                ?>
                <li title="<?= $name ?>" draggable="true" oncontextmenu="if ('vibrate' in navigator) navigator.vibrate(200); popup_window([
                    {name: getStringBy('tooltip_open_view_w'), icon: 'fa-arrow-up-right-from-square', for_dir: true, for_file: true},
                    {name: getStringBy('tooltip_rename_w'), icon: 'fa-pen', for_dir: true, for_file: true},
                    {name: getStringBy('tooltip_download_w'), icon: 'fa-download', for_dir: false, for_file: true},
                    {name: getStringBy('tooltip_details_w'), icon: 'fa-info-circle', for_dir: true, for_file: true},
                    {name: getStringBy('tooltip_delete_w'), icon: 'fa-trash-can', for_dir: true, for_file: true}
                ], [
                    () => clickToPathDuo(this.getAttribute('data-path'), this.getAttribute('data-isdir'), this.id),
                    () => run_command().rename(this.getAttribute('data-path')),
                    () => download('view-content/blob.php?p=' + encodeURIComponent(this.getAttribute('data-path')), '<?= $name ?>'),
                    () => openFileDetail(this.getAttribute('data-path')),
                    () => run_command().delete([this.getAttribute('data-path')])
                ], this.getAttribute('data-isdir'))" ondragstart="drag().start()" class="item-fm" ondragend="drag().end()" ondrag="drag().live()" ondragenter="drag().enter()" ondragleave="drag().leave()" ondragover="drag().over()" ondrop="drag().drop()" onclick="clickToPath(this.getAttribute('data-path'), this.getAttribute('data-isdir'), this.id)" id="item-file-manager-<?= $liUniID ?>" data-path="<?= addslashes($item) ?>" data-isdir="<?= is_dir($item) ?>" data-href="<?= $file_manager->get_current_url($item, true) ?>">
                    <span class="first">
                        <span class="image-preview">
                            <img loading="lazy" src="<?= $file_parse->get_icon($item, ($settings['list_image_preview'] and $privileges['view_file']), 48) ?>" alt="Image">
                        </span>
                        <span class="name">
                            <?= $name ?>
                        </span>
                    </span>
                    <span class="other">
                        <?php if (is_dir($item)) { ?>
                            <?php
                            $count_dirs = sizeof($file_manager->get_dirs($item));
                            $count_files = sizeof($file_manager->get_files($item));
                            ?>
                            <?php if (($count_dirs + $count_files) > 0) { ?>
                                <span class="count">
                                    <span class="itm">
                                        <?= $count_dirs ?>
                                        <i class="fa-solid fa-folder"></i>
                                    </span>
                                    <span class="itm">
                                        <?= $count_files ?>
                                        <i class="fa-solid fa-file"></i>
                                    </span>
                                </span>
                            <?php } else { ?>
                                <span class="count"><?= str_get_string("message_dir_empty_short") ?></span>
                            <?php } ?>
                        <?php } ?>
                        <?php if (is_file($item)) { ?>
                            <?php
                            $array_units_size = array(
                                str_get_string("text_size_b"),
                                str_get_string("text_size_kb"),
                                str_get_string("text_size_mb"),
                                str_get_string("text_size_gb"),
                                str_get_string("text_size_tb")
                            );
                            ?>
                            <span class="count">
                                <?= $file_manager->format_size($file_manager->get_file_size($item), $array_units_size) ?>
                            </span>
                        <?php } ?>
                    </span>
                </li>
            <?php } ?>
        <?php } else { ?>
            <h5 class="message" oncontextmenu="document.getElementById('action-nav-create-fd').click(); return false;"><?= strlen(trim($search)) > 0 ? str_get_string("message_search_no_result") : str_get_string("message_dir_empty") ?></h5>
        <?php } ?>
    </ul>
</article>