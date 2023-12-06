<?php
global $main_path, $settings;

include_once "php/data.php";
include_once "php/settings.php";

include_once "lang/lang.php";

include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$path = $file_manager->parse_separator(trim(strval($_GET["path"]) ?? ""));
$isGrid = intval($_GET["grid"]);
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
            <li class="<?= ($path === $item) ? 'last-child' : '' ?>">
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
$directories = $file_manager->get_folders($path);
$files = $file_manager->get_files($path);

asort($directories);
asort($files);

$result = array_merge($directories, $files);

if (strlen(trim($search)) > 0) {
    unset($result);
    $result = [];
    $file_manager->search($main_path["server"], trim($search), $result);
}
?>

<article class="custom-scroll" id="article-file-manager-container" data-path="<?= $path ?>">
    <ul class="file-manager <?= ($isGrid === 1 && sizeof($result) > 0) ? 'grid' : '' ?>" id="file-manager-list">
        <?php if ($path !== $rootDirectory) { ?>
            <li ondblclick="loadMainFileManager('<?= addslashes(dirname($path)) ?>')" class="dir-back" style="display: none">
                <span class="first">
                    <span class="image-preview">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADXklEQVR4nO2ZSUwTYRiGv8QoLriBuOCGbC4IIrjhbowaNGqMMWqMMRoXvHn0YgTcUBRRAS2lbSgXY6JRowh6QEE2FXFnEWRpbWkpbVGicYHXTJvRaFt/kWk7JH2TJ+lhJpknzz9zKZFnnnnmmWdCTptDa7RKUmuVhO6iyaYurZJqNNm0g9y1JjmpmuWEntIko3i3CNRJCELxVkJJLheoSicITMorKfm4TOBFKkFIWm5FA1X7gOq4nlEVp0J1XCxToCKZIBSaG9zD7xWQPc1MgdITBCFQXYsC3uwWHGKtMJHQE0qSvKC+MQd4s9MpEGvfKrcAr3eIFmINr7ZDzBBbYBvEDDEFXm6FmCG2wGaIGWIKvNgEMUNMgecbIWaILbABYoaYAs/WQ8wQU6ByLcQMsQXWQMwQa11PYyFmiC2wCmKGmAIVKyBmiCnwZDnEDLEFlkHMEFPg8RK4g+/lS9BRtMgC99vRdWyBRwvhar6VLYDm3gIckGfgoCIF5oIYh9f+g8B8uJKvpTFouLMY6yS5GJGigd8ZFfR3Zzu8ni1QPg+u4kvxXNTeXoqVmfcx6lwL/M5qLRK6/GiH97AFyubAFXwunI2Km6sRk1kJ/zQdRp9vwchULfzOaqDLm+nwvn8QmAVn86kwGkXXNyBKWoNxGa0Ym67HmAu6nxW0uZHQ59uHLVAaBWfSURCJ61d3ISxLjYmSNoy/aLBI+KfpMfq87mcF3zPvMTxZjWGnVBiS1Azv400YdKwRA440wCvhHfrF16PPodoiOwKRcBafH8zA5av7MVnWisAsEwIy2zDhksGmAifBvQs+p60SQ0+qMPhEE7yPN2Lg0Qb0T7QK9D1cZ1ukszhC1VUSAWdgyAtDjLIOwXKzRWCS1Gi3gvUoWSv4nP57BRsBFIXHdj4MU3UVT4fQGPKmYn5OPUIU7QiSWSUCMo2WCuMv/qrAv9C2FawSfAWvBDsCzl5o9of1IQrzF65CkOxXBf4o/V5Ba1OBO0p8BU6C3LEpSvOKYEX7B/4ocRX4o2SvAv9CcxX4ozTwKCfxrpDctcnyj+HBCvN7exWotyxUaZoULDPV/FmBetOmZLf7BspMJVwF/rNKvW3T0nTegVJjvvUoGTrd/Tz/tWlX0C9QakyeKGnr1t+zPwBsd5Gb7hztLAAAAABJRU5ErkJggg==" alt="Image">
                    </span>
                    <?= str_get_string("action_dir_back") ?>
                </span>
            </li>
        <?php } ?>
        <?php if (sizeof($result) > 0) { ?>
            <?php foreach ($result as $item) { ?>
                <?php
                $liUniID = uniqid();
                $name = basename($item);
                ?>
                <li title="<?= $name ?>" draggable="true" oncontextmenu="popup_window([
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
                            <img src="<?= $file_parse->get_icon($item, $settings["list_image_preview"], 48) ?>" alt="Image">
                        </span>
                        <span class="name">
                            <?= $name ?>
                        </span>
                    </span>
                    <span class="other">
                        <?php if (is_dir($item)) { ?>
                            <?php
                            $count_dirs = sizeof($file_manager->get_folders($item));
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
                            <span class="count">
                                <?= $file_manager->format_size($file_manager->get_file_size($item)) ?>
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