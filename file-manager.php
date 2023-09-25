<?php
global $main_path;

include_once "php/data.php";
include_once "lang/lang.php";
include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$path = trim($_GET["path"]) ?? "";


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
    <ul class="tree">
        <?php foreach ($tree as $item) { ?>
            <li class="<?= ($path === $item) ? 'last-child' : '' ?>">
                <span onclick="loadMainFileManager('<?= addslashes($item) ?>')">
                    <?= ($item === $rootDirectory) ? str_get_string("action_dir_home") : basename($item) ?>
                </span>
            </li>
            <?php if ($path !== $item) { ?>
                <span class="divider">></span>
            <?php } ?>
        <?php } ?>
    </ul>

    <div class="manager-content">
        <span class="item" title="<?= str_get_string('tooltip_create_file') ?>" onclick="run_command().create('<?= addslashes($path) ?>').file()">
            <span class="material-symbols-outlined">note_add</span>
        </span>
        <span class="item" title="<?= str_get_string('tooltip_create_dir') ?>" onclick="run_command().create('<?= addslashes($path) ?>').dir()">
            <span class="material-symbols-outlined">create_new_folder</span>
        </span>
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
    <ul class="file-manager <?= (sizeof($result) > 0) ? 'grid' : '' ?>">
        <?php if ($path !== $rootDirectory) { ?>
            <li ondblclick="loadMainFileManager('<?= addslashes(dirname($path)) ?>')" class="dir-back">
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
                $f_path = $path. DIRECTORY_SEPARATOR . $item;

                $li_uniID = uniqid();
                ?>
                <li draggable="true" oncontextmenu="contextmenu([
                    {'name': 'Context 1', 'callback': alert('Hello')},
                    {'name': 'Context 2', 'callback': alert('Hello 2')}
                ])" ondragstart="drag().start()" ondragend="drag().end()" ondrag="drag().live()" ondragenter="drag().enter()" ondragleave="drag().leave()" ondragover="drag().over()" ondrop="drag().drop()" onclick="clickToFile('<?= addslashes($f_path) ?>', <?= is_dir($f_path) ?>)" id="item-file-manager-<?= $li_uniID ?>" data-path="<?= addslashes($f_path) ?>" data-isdir="<?= is_dir($f_path) ?>">
                    <span class="first">
                        <span class="image-preview">
                            <img src="<?= $file_parse->get_icon($f_path) ?>" alt="Image">
                        </span>
                        <?= $item ?>
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
        <?php } else { ?>
            <h5 class="message"><?= str_get_string("message_dir_empty") ?></h5>
        <?php } ?>
    </ul>
</article>