<?php
global $language_tag, $content, $main_path, $server_encoding, $default_avatar, $settings;

include_once "lang/lang.php"; // Загружаем языковой пакет
include_once "php/data.php"; // Загружаем системные настройки
include_once "php/settings.php"; // Загружаем системные настройки
include_once "secure/session.php"; // Проверка на авторизацию

include_once "class/FileManager.php";

$file_manager = new FileManager();

$resource_v = time(); // Устанавливаем версию для ресурсов

$perms = octdec(substr(sprintf("%o", fileperms($main_path["file_manager"])), -4));

//if ($perms == 0777) {
//    echo "Разрешение 777 выдано.";
//} else {
//    echo "Разрешение 777 не выдано.";
//}
?>

<html lang="<?= $language_tag ?? "en-US" ?>">
<head>
    <title><?= str_get_string("document_name") ?></title>

    <meta charset="<?= $server_encoding ?? 'UTF-8' ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="assets/css/system/root.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/default.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/progress.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/alert.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico?v=2">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="assets/js/m35/parse-url.js?v=<?= $resource_v ?>"></script>
    <script src="assets/js/m35/alert.js?v=<?= $resource_v ?>"></script>
    <script src="assets/js/m35/popup-window.js?v=<?= $resource_v ?>"></script>
    <script src="assets/js/system.js?v=<?= $resource_v ?>"></script>

    <script src="assets/js/index-drag-upload.js?v=<?= $resource_v ?>"></script>

    <script>
        const stringOBJ = <?= $content ?>;
    </script>
</head>
<body ondragover="drag_upload().over()" ondrop="drag_upload().drop()">
    <div class="progress" id="progress" style="display: none">
        <div class="progress-item">
            <div></div><div></div><div></div>
        </div>
    </div>

    <input type="file" id="input-upload-content" multiple style="display: none">

    <header>
        <i class="fa fa-bars directory-menu" id="action-directory-menu"></i>

        <h1 class="title">
            <?= str_get_string("document_name_2", true) ?>
        </h1>

        <label class="search-container">
            <input type="search" id="main-search" value="<?= ($_GET['s'] ?? '') ?>" placeholder="<?= str_get_string('hint_search') ?>">
            <i class="fa-solid fa-magnifying-glass action-search"></i>
        </label>

        <div class="container-user">
            <img src="<?= $default_avatar ?>" alt="Avatar image">
            <h4><?= (USER_LOGIN ?? "NaN") ?></h4>
        </div>
    </header>

    <main id="main">
        <nav class="left-directory-manager" id="left-directory-manager">
            <section class="list-manager custom-scroll" id="list-directory-manager">

            </section>

            <ul class="container-upload-content custom-scroll" id="container-upload-content"></ul>

            <?php if ($settings["server_details"]) { ?>
                <section class="details-manager">
                    <h2><?= str_get_string("text_details_manager") ?></h2>
                    <ul>
                        <li><?= str_get_string("text_php_version", false, [(PHP_VERSION ?? "NaN")]) ?></li>
                        <li><?= str_get_string("text_php_server", false, [($_SERVER["SERVER_SOFTWARE"] ?? "NaN")]) ?></li>
                        <li><?= str_get_string("text_php_total_size", false, [$file_manager->format_size($file_manager->get_directory_size($main_path["server"]))]) ?></li>
                    </ul>
                </section>
            <?php } ?>

            <section class="dev-menu">
                <i class="fa fa-flag" title="<?= str_get_string('tooltip_dev_report') ?>" id="action-dev-report"></i>
                <i class="fa fa-circle-dollar-to-slot" title="<?= str_get_string('tooltip_dev_paid') ?>" id="action-dev-paid"></i>
                <i class="fa fa-gear" title="<?= str_get_string('tooltip_dev_settings') ?>" id="action-dev-settings"></i>
            </section>
        </nav>
        <div id="resize-divider" class="resize-divider"></div>
        <article class="main-file-manager">
            <div id="main-file-manager"></div>

            <div class="menu-selected-fd" id="menu-selected-fd" style="display: none">
                <ul class="list-menu-selected">
                    <li data-type="single" id="menu-selected-open">
                        <i class="fa fa-arrow-up-right-from-square"></i>
                        <span><?= str_get_string("tooltip_open_view_w") ?></span>
                    </li>
                    <li data-type="single" id="menu-selected-rename">
                        <i class="fa fa-pen"></i>
                        <span><?= str_get_string("tooltip_rename_w") ?></span>
                    </li>
                    <li data-type="single" id="menu-selected-info">
                        <i class="fa fa-info-circle"></i>
                        <span><?= str_get_string("tooltip_details_w") ?></span>
                    </li>
                    <li data-type="multiple" id="menu-selected-delete">
                        <i class="fa fa-trash-can"></i>
                        <span><?= str_get_string("tooltip_delete_w") ?></span>
                    </li>
                    <li data-type="multiple" id="menu-selected-select-all">
                        <i class="fa-regular fa-square-check"></i>
                        <span><?= str_get_string("tooltip_select_all") ?></span>
                    </li>
                </ul>
                <span class="count" id="text-selected-count"><?= str_get_string("text_selected_count", false, [0]) ?></span>
            </div>
        </article>
    </main>

    <section class="container-for-toast" id="container-for-toast">
        <!-- Здесь будут все уведомления -->
    </section>

    <script>
        let serverDirectory = "<?= addslashes($main_path["server"]) ?>";
        let openedDirectory = serverDirectory;
        let isGrid = Boolean(<?= ($settings["default_list_type"] === "grid") ?>);
        let isGlobalSearch = true;
        let count_file_manager_items = 0;
        let clickCount = 0;
        let selectPaths = [];
        let pathFileDetail = "";
    </script>

    <script src="assets/js/funcs.js?v=<?= $resource_v ?>"></script>
    <script src="assets/js/index.js?v=<?= $resource_v ?>"></script>
    <script src="assets/js/index-rezisable.js?v=<?= $resource_v ?>"></script>
    <script src="assets/js/index-drag.js?v=<?= $resource_v ?>"></script>
    <script src="assets/js/index-command.js?v=<?= $resource_v ?>"></script>
</body>
</html>