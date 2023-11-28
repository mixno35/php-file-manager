<?php
global $language_tag, $content, $login, $main_path, $server_encoding;

include_once "lang/lang.php"; // Загружаем языковой пакет
include_once "php/data.php"; // Загружаем системные настройки
include_once "secure/session.php"; // Проверка на авторизацию

include_once "class/FileManager.php";

$file_manager = new FileManager();

$resource_v = time(); // Устанавливаем версию для ресурсов
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <script src="assets/js/m35/parse-url.js"></script>
    <script src="assets/js/m35/alert.js"></script>
    <script src="assets/js/m35/popup-window.js?v=<?= $resource_v ?>"></script>
    <script src="assets/js/system.js?v=<?= $resource_v ?>"></script>

    <script>
        const stringOBJ = <?= $content ?>;
    </script>
</head>
<body>
    <div class="progress" id="progress" style="display: none">
        <div class="progress-item">
            <div></div><div></div><div></div>
        </div>
    </div>

    <header>
        <i class="fa fa-bars directory-menu" id="action-directory-menu"></i>

        <h1 class="title">
            <?= str_get_string("document_name_2", true) ?>
        </h1>

        <div class="container-user">
            <img src="assets/icons/avatar.png" alt="Avatar image">
            <h4><?= $login ?></h4>
        </div>
    </header>

    <main id="main">
        <nav class="left-directory-manager" id="left-directory-manager">
            <section class="list-manager custom-scroll" id="list-directory-manager">

            </section>
<!--            <section class="details-manager">-->
<!--                <h2>--><?php //= str_get_string("text_details_manager") ?><!--</h2>-->
<!--                <ul>-->
<!--                    <li>--><?php //= str_replace("%1s", (PHP_VERSION ?? "NaN"), str_get_string("text_php_version")) ?><!--</li>-->
<!--                    <li>--><?php //= str_replace("%1s", ($_SERVER["SERVER_SOFTWARE"] ?? "NaN"), str_get_string("text_php_server")) ?><!--</li>-->
<!--                    <li>--><?php //= str_replace("%1s", $file_manager->format_size($file_manager->get_directory_size($main_path["server"])), str_get_string("text_php_total_size")) ?><!--</li>-->
<!--                </ul>-->
<!--            </section>-->
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
                        <i class="fa fa-check-double"></i>
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
        let isGrid = false;
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