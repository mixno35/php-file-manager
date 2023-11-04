<?php
global $main_path;

include_once "php/data.php";
include_once "lang/lang.php";
include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$path = $file_manager->parse_separator(trim($_GET["path"]) ?? "");

$file_manager->check_path($path, str_get_string("action_go_to_home"), addslashes($main_path["server"]));

if (!is_dir($path))
    exit();

$pathComponents = explode(DIRECTORY_SEPARATOR, $path);
$rootDirectory = $main_path["server"];

$branch = [];
$tree_pre = [];
$tree = [];

// Построение ветки и полных путей
foreach ($pathComponents as $index => $component) {
    $branch[] = $component;
    $fullPath = implode(DIRECTORY_SEPARATOR, $branch);
    $tree_pre[] = $fullPath;
}

// Фильтруем список ветки
foreach ($tree_pre as $item) {
    if (strpos($item, $rootDirectory) === 0)
        $tree[] = $item;
}

$uniID = uniqid();
?>
<nav class="header">
    <div class="manager-content">
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

    <div class="manager-content">
        <button style="display: none" class="item-nav-button" id="file-manager-list-toggle" title="<?= str_get_string('tooltip_toggle_linear') ?>" onclick="toggle_grid_linear(!isGrid)">
            <i class="fa fa-border-all" id="file-manager-list-toggle-icon"></i>
        </button>
        <button class="item-nav-button" title="<?= str_get_string('tooltip_create_new_fd') ?>" onclick="popup_window([
            {'name': 'Create file'},
            {'name': 'Create directory'}
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
?>

<article class="custom-scroll" data-path="<?= $path ?>">
    <ul class="file-manager <?php // if (sizeof($result) > 0 and ($_GET['grid'] ?? false)) { echo 'grid'; } ?>" id="file-manager-list">
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
                $f_path = $path . DIRECTORY_SEPARATOR . $item;
                $li_uniID = uniqid();
                ?>
                <li draggable="true" oncontextmenu="popup_window([
                    {'name': 'Context 1'},
                    {'name': 'Context 2'}
                ])" ondragstart="drag().start()" class="item-fm" ondragend="drag().end()" ondrag="drag().live()" ondragenter="drag().enter()" ondragleave="drag().leave()" ondragover="drag().over()" ondrop="drag().drop()" onclick="clickToPath('<?= addslashes($f_path) ?>', <?= is_dir($f_path) ? 1 : 0 ?>, this.id)" id="item-file-manager-<?= $li_uniID ?>" data-path="<?= addslashes($f_path) ?>" data-isdir="<?= is_dir($f_path) ?>" data-href="<?= $file_manager->get_current_url($f_path, true) ?>">
                    <span class="first">
                        <span class="image-preview">
                            <img src="<?= $file_parse->get_icon($f_path) ?>" alt="Image">
                        </span>
                        <span class="name">
                            <?= $item ?>
                        </span>
                    </span>
                    <span class="other">
                        <?php if (is_dir($f_path)) { ?>
                            <span class="count">
                                <?php $count = (sizeof($file_manager->get_files($f_path)) + sizeof($file_manager->get_folders($f_path))); ?>
                                <?= $count === 0 ? str_get_string("message_dir_empty_short") : $count ?>
                            </span>
                        <?php } ?>
                    </span>
                </li>
            <?php } ?>
            <script>
                const array_paths = document.querySelectorAll("ul#file-manager-list li");
                console.log(array_paths);

                for(let i = 0; i < array_paths.length; i++) {
                    console.log(array_paths[0].getAttribute("data-path"));
                    const index = selectPaths.indexOf(array_paths[0].getAttribute("data-path"));
                    if (index !== -1)
                        array_paths[i].classList.add("selected");
                }
            </script>
        <?php } else { ?>
            <h5 class="message"><?= str_get_string("message_dir_empty") ?></h5>
        <?php } ?>
    </ul>
</article>