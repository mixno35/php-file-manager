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
            <div id="main-file-manager">

            </div>

            <div class="menu-selected-fd" id="menu-selected-fd" style="display: none">
                <ul class="list-menu-selected">
                    <li data-type="single" id="menu-selected-open">
                        <i class="fa fa-arrow-up-right-from-square"></i>
                        <span><?= str_get_string("tooltip_open_view_w") ?></span>
                    </li>
                    <li data-type="single" id="menu-selected-rename">
                        <i class="fa fa-pencil"></i>
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
        document.getElementById("action-dev-report").addEventListener("click", () => {
            window.open("//linkbox.su/r/mixno35");
        });
        document.getElementById("action-dev-paid").addEventListener("click", () => {
            window.open("//www.donationalerts.com/r/mixno35");
        });
        document.getElementById("action-dev-settings").addEventListener("click", () => {
            // ----------------------------------------
            // ----------------------------------------
            // ----------------------------------------
            // ----------------------------------------
        });
    </script>

    <script>
        let openedDirectory = "<?= addslashes($main_path["server"]) ?>";
        let isGrid = false;
        let count_file_manager_items = 0;

        const
            COMMAND_CREATE_FILE = "create-file",
            COMMAND_CREATE_DIRECTORY = "create-dir",
            COMMAND_CREATE_RENAME = "rename",
            COMMAND_CREATE_REMOVE = "remove";
    </script>

    <script>
        const resizableElement = document.getElementById("left-directory-manager");
        const resizeHandle = document.getElementById("resize-divider");
        let isResizing = false;
        let startX, startWidth;

        resizeHandle.addEventListener("mousedown", (e) => {
            isResizing = true;
            startX = e.clientX;
            startWidth = parseFloat(getComputedStyle(resizableElement, null).getPropertyValue("width"));

            document.body.style.cursor = "e-resize";
        });

        document.addEventListener("mousemove", (e) => {
            if (!isResizing) return;
            const newWidth = startWidth + (e.clientX - startX);
            resizableElement.style.width = newWidth + "px";
        });

        document.addEventListener("mouseup", () => {
            if (isResizing) {
                document.body.style.cursor = "default";
                startWidth = parseFloat(getComputedStyle(resizableElement, null).getPropertyValue("width"));
            }

            isResizing = false;
        });
    </script>

    <script>
        let clickCount = 0;

        function loadNavDirectoryManager(_path = "", _container_id = null) {
            progress();

            $.ajax({
                url: "directory-manager.php",
                data: {
                    path: _path
                },
                success: function (result) {
                    progress();
                    if (_container_id !== null) document.getElementById(_container_id).innerHTML = result;
                }
            });
        }

        loadNavDirectoryManager("<?= addslashes($main_path["server"]) ?>", "list-directory-manager");

        function itemLoadNavDirMng(_element_id = null, _container_id = null, _count = 0) {
            event.stopPropagation();
            event.preventDefault();

            clickCount++;
            if (clickCount === 1) {
                setTimeout(function() {
                    if (clickCount === 1) {
                        // Одиночный клик
                        if (_count > 0) {
                            if (document.getElementById(_element_id).classList.contains("open")) {
                                if (_container_id !== null) document.getElementById(_container_id).innerHTML = "";
                            } else {
                                loadNavDirectoryManager(
                                    document.getElementById(_element_id).getAttribute("data-path"),
                                    _container_id
                                );
                            }

                            document.getElementById(_element_id).classList.toggle("open");

                            setTimeout(() => {
                                document.getElementById("status-icon-" + _element_id).src = document.getElementById(_element_id).classList.contains("open") ?
                                    "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACCUlEQVR4nO3Wz0vTcRzH8dc9ukTXkC6xvt8RWghaeBHWsKjEwA5BHhLWXyGDICSiCLEo1w9Su4RJdfFW0vc7N+baZi5tEcY2kQlBdSv4fF7e/BbffSnsu803fF/wuL+ffOH7/QLBggULFsyvbT7F6do0qpvT4E7UpqBrU/hYm8QQWrGNJ6hsTII+iTc9YP0x6KfqI4w2NaD8EGyAm+UJ7GtKwJcEuJutJVBZe4A+z4DP98Fd7x7KngGlu6AE8NrqOCgBvFYcAyWA197fBiWA1/K3QAngtXc3QAngtcx1sJHWXxwjV2Lk6pX/sxIrsBQzXAHpUbBRqrNHyQ/Dfpp1BSSvgY1Qed5OFod8dslyBVhXQb+Vnx0hly82wsz24VwaLHHpAmUZvOMEFAaWWThPUfIDI05A7swc82cpSu5c7LeAvgRzpyhLX/92gMpG4jp7kpIwG+12nkCm97Je7KUkTEUOOgGLPVGd6aEkzEf2OAHpE6ZOd1MKle768ccHjNbxvTrVSSlUqvOT6yusku3f9UIHJVALHe7fCGWHi9oOUwJlh2fcAZYxp22TEijLGK/zBIxEqw/T/4iWMVInwIyLCUgazm+E8yYyh8UE2If73QG2GRUT8DbU5Q6YNw9oq/XH6b9QlkG+PrQf9fbtVdvEr/mQavWR2sPPNyH19WXbWN3jgwULFiwYdrAt2QA1nS4z4bcAAAAASUVORK5CYII=" :
                                    "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABg0lEQVR4nO3YzyvDcRzH8dfN3Un+gbWShJZEkiQtSRJaLCX8EVYODlKUww7IDr4H5SJyU3Pxa34Nm18HhzH7pty/4dte8ge8D+y72bs+r3rc38/rGzAzMzMz82q2haBtIWtb4F/k1pG3LTzmLITxH8vE8PIcA72QWcNMyQOeVkBPLWOupAEPUdBr91EspldRWZKA1BLotbfdBvJ+knyYKtjn1aj7vtcaEQMuF0Av2Ts/x0946iMZcsWAxBzolexWPXk3XhSQdjALFupkvoKv2wHybqxoIO0rOUTehssepDE9Qg0gB4SoAcSA1DA1gBwwSA0gBtwMUAOIAdf91AByQB81gBhw1UsNIAYke6gB5IAgNYC0/GU3NYAc0EUNIAZcdFIDiAHnHdQAckA7NYAYcNZGDSAGnLZQA8gBzdQAYkCiiRpADDgJUAPIAY3UAGLAcT01gBxQRw0gzdn3u/mjWpYzJ+6XX4u5jeqIE/e5+cMaliMn7nOzm1XTYoCZmZmZGX65b1fjrzHebGEuAAAAAElFTkSuQmCC";
                            }, 100);
                        }
                    } else {
                        // Двойной клик
                        loadMainFileManager(document.getElementById(_element_id).getAttribute("data-path"), true);
                    }
                    clickCount = 0;
                }, 170); // Задержка для определения двойного клика
            }
        }

        let selectPaths = [];

        function clickToPath(_path = "", _is_dir = false, _element_id = null) {
            event.stopPropagation();
            event.preventDefault();

            clickCount++;
            if (clickCount === 1) {
                setTimeout(function () {
                    if (clickCount === 1) clickToPathSingle(_path, _is_dir, _element_id); // Одиночный клик
                    else clickToPathDuo(_path, _is_dir, _element_id); // Двойной клик
                    clickCount = 0;
                }, 200);
            }
        }

        function clickToPathSingle(_path = "", _is_dir = false, _element_id = null) {
            const index = selectPaths.indexOf(_path);
            if (index === -1) {
                selectPaths.push(_path);
                document.getElementById(_element_id).classList.add("selected");
            } else {
                selectPaths.splice(index, 1);
                document.getElementById(_element_id).classList.remove("selected");
            }
            setTimeout(() => { updateSelectPathsContainer() }, 100);
        }

        function clickToPathDuo(_path = "", _is_dir = false, _element_id = null) {
            if (_is_dir) loadMainFileManager(_path, true);
            else window.open("view.php?p=" + encodeURIComponent(_path), "_blank");
        }

        function updateSelectPathsContainer() {
            // console.log(selectPaths);
            const isSelected = selectPaths.length > 0;

            try {
                document.getElementById("text-selected-count").innerText = getStringBy("text_selected_count", [selectPaths.length]);
            } catch (exx) {}

            document.getElementById("menu-selected-fd").style.display = isSelected ? "flex" : "none";

            const elements_single = document.querySelectorAll("div#menu-selected-fd ul.list-menu-selected li[data-type='single']");

            for(let i = 0; i < elements_single.length; i++) {
                elements_single[i].style.display = (selectPaths.length > 1) ? "none" : "flex";
            }

            if (isSelected) document.getElementById("main-file-manager").classList.add("selected");
            else document.getElementById("main-file-manager").classList.remove("selected");
        }

        document.getElementById("menu-selected-open").addEventListener("click", () => {
            const container = document.querySelector("ul#file-manager-list li.item-fm.selected");
            clickToPathDuo(container.getAttribute("data-path"), Boolean(Number(container.getAttribute("data-isdir"))), container.id);
        });
        document.getElementById("menu-selected-rename").addEventListener("click", () => {
            run_command().rename(selectPaths[0]);
        });
        document.getElementById("menu-selected-info").addEventListener("click", () => {
            openFileDetail(selectPaths[0]);
        });
        document.getElementById("menu-selected-delete").addEventListener("click", () => {
            run_command().delete(selectPaths);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            window.addEventListener("popstate", () => {
                const currentURL = window.location.href;
                loadMainFileManager(url_param(currentURL).get("p"));
            });
        });
    </script>

    <script>
        function loadMainFileManager(_path = "", _update = false) {
            progress();

            $.ajax({
                url: "file-manager.php",
                data: {
                    path: _path,
                    grid: (isGrid ? 1 : 0)
                },
                success: function (result) {
                    progress();

                    // openFileDetail(_path); // Открываем информацию о директории при ее открытии

                    openedDirectory = _path;
                    document.getElementById("main-file-manager").innerHTML = result;

                    if (_update) url_param().set("p", _path);

                    selectPaths = [];
                    setTimeout(() => { updateSelectPathsContainer() }, 100);
                    setTimeout(() => {
                        count_file_manager_items = document.querySelectorAll("ul#file-manager-list li.item-fm").length;
                    }, 400);
                }
            });
        }

        function updateMainFileManager() {
            loadMainFileManager(openedDirectory);
        }

        loadMainFileManager("<?= addslashes($_GET['p'] ?? $main_path["server"]) ?>", <?= empty($_GET["p"] ?? "") ?>);
    </script>

    <script>
        let pathFileDetail = "";

        function openFileDetail(_path = "") {
            // if (document.querySelectorAll(".file-detail").length >= 3) {
            //     toast().show(stringOBJ["message_file_detail_limit_window"]);
            //     return;
            // }

            // let make_id = "file-detail-" + generate_text();
            let make_id = "file-detail";

            if (document.getElementById(make_id))
                document.getElementById(make_id).remove()

            progress();

            $.ajax({
                url: "file-detail.php",
                data: {
                    path: _path,
                    id: make_id
                },
                success: function (result) {
                    progress();

                    pathFileDetail = _path;

                    let container = document.createElement("nav");
                        container.setAttribute("id", make_id);
                        container.classList.add("file-detail");
                        container.innerHTML = result;

                    document.getElementById("main").appendChild(container);
                }
            });
        }
    </script>

    <script>
        let isDragging = false;
        let draggedIsDir = 0;

        let movePath = "";
        let movePathStart = "";

        const drag = () => {
            return {
                start: () => {
                    isDragging = true;
                    event.dataTransfer.setData("text/plain", "");
                    movePathStart = event.target.getAttribute("data-path");
                },
                end: () => {
                    isDragging = false;
                },
                enter: () => {
                    if (isDragging) {
                        draggedIsDir = Number(event.target.getAttribute("data-isdir"));
                        movePath = event.target.getAttribute("data-path");
                    }

                    if (Boolean(draggedIsDir))
                        if (movePathStart !== movePath)
                            document.getElementById(event.target.id).classList.add("drag-in");
                },
                leave: () => {
                    try {
                        document.getElementById(event.target.id).classList.remove("drag-in");
                    } catch (e) {}
                },
                drop: () => {
                    if (Boolean(draggedIsDir)) {
                        if (movePathStart !== movePath) run_command().move(movePathStart, movePath);
                    }

                    movePath = "";
                    movePathStart = "";

                    draggedIsDir = 0;

                    try {
                        document.getElementById(event.target.id).classList.remove("drag-in");
                    } catch (e) {}
                },
                live: () => {

                },
                over: () => {
                    if (Boolean(draggedIsDir))
                        if (movePathStart !== movePath) event.preventDefault();
                }
            }
        }
    </script>

    <script>
        const run_command = () => {
            /**
             * Это обработчик всех команд, через него проходят все команды перед их выполнением. Можно конечно напрямую, но зачем изобретать велосипед...
             */
            return {
                delete: (object) => {
                    if (!Array.isArray(object)) return toast().show(getStringBy("message_object_invalid"));
                    if (object.length < 1) return toast().show(getStringBy("message_object_remove_empty"));

                    let paths = object.join(", ");
                    if (confirm(stringOBJ["message_are_remove"].replace("%1s", paths)))
                        command(COMMAND_CREATE_REMOVE, {path: paths}, (() => {
                            updateMainFileManager();
                        }));
                },
                rename: (path) => {
                    const name_path = path.replace(/^.*[\\\/]/, "").trim();
                    const renamed = prompt(stringOBJ["hint_rename_enter_new_name"].replace("%1s", path), name_path);

                    if (renamed !== null) {
                        if (renamed.length >= 1) {
                            if (isValidFName(renamed)) {
                                if (name_path.trim() !== renamed.trim()) {
                                    const new_path = path.replace(/[^\\\/]*$/, renamed.trim());

                                    command(COMMAND_CREATE_RENAME, {path: path, new_path: new_path}, updateMainFileManager);
                                } else {
                                    toast().show(stringOBJ["api_rename_different_from_old"]);
                                }
                            } else {
                                toast().show(stringOBJ["api_rename_forbidden_chars"]);
                            }
                        } else {
                            toast().show(stringOBJ["api_rename_short_char"]);
                        }
                    }
                },
                move: (object, path) => {
                    /**
                     * Array object - что перемещаем
                     * String path - куда перемещаем
                     */
                    if (!Array.isArray(object)) return toast().show(getStringBy("message_object_invalid"));

                    toast().show(object + " перемещено в " + path);
                },
                create: (path) => {
                    return {
                        dir: () => {
                            const name = prompt(stringOBJ["text_enter_a_name_dir"]);

                            if (name === null)
                                return;

                            if (name.trim().length < 1) {
                                toast().show(stringOBJ["api_create_short_char"]);
                                return;
                            } if (!isValidFName(name)) {
                                toast().show(stringOBJ["api_create_forbidden_chars"]);
                                return;
                            }

                            command(COMMAND_CREATE_DIRECTORY, {path: path, name: name}, updateMainFileManager);
                        },
                        file: () => {
                            const name = prompt(stringOBJ["text_enter_a_name_file"]);

                            if (name === null)
                                return;

                            if (name.trim().length < 1) {
                                toast().show(stringOBJ["api_create_short_char"]);
                                return;
                            } if (!isValidFName(name)) {
                                toast().show(stringOBJ["api_create_forbidden_chars"]);
                                return;
                            }

                            command(COMMAND_CREATE_FILE, {path: path, name: name}, updateMainFileManager);
                        }
                    }
                }
            }
        }

        const command = (command, data = {}, callback = null) => {
            /**
             * String command - Название команды, которую нужно выполнить
             * Array data - Все параметры, которые потребуются для выполнения команды
             * Function callback - Функция, которая будет вызвана после успешного выполнения команды
             */
            progress();

            let query = data;
            query["command"] = command; // Добавляем команду в запрос

            $.ajax({
                url: "php/command.php",
                method: "POST",
                data: query,
                success: function (result) {
                    progress();
                    console.log(result);

                    try {
                        let json = JSON.parse(result);

                        if (json["type"] === "success") {
                            if (callback !== null) callback(); // Вызываем функцию, если выполнение функции прошло успешно
                        }

                        toast().show(getStringBy(json["message_id"], json["return"]));
                    } catch (e) {
                        toast().show(e);
                        console.error(e);
                    }
                }
            });
        };
    </script>

    <script>
        const popup_window = (actions = []) => {
            if (actions.length < 1) return;

            event.preventDefault();
        }
    </script>

    <script>
        document.addEventListener("keydown", (event) => {
            if (event.ctrlKey && event.altKey && event.keyCode === 70) // Комбинация клавиш для создания файла
                run_command().create(openedDirectory).file();
            if (event.ctrlKey && event.altKey && event.keyCode === 68) // Комбинация клавиш для создания директории
                run_command().create(openedDirectory).dir();
            if (event.ctrlKey && event.altKey && event.keyCode === 82) // Комбинация клавиш для переименования файла/директории
                run_command().rename(pathFileDetail);
            if (event.ctrlKey && event.keyCode === 46) // Комбинация клавиш для удаления файла/директории
                run_command().remove(pathFileDetail);
        });
    </script>

    <script>
        const toggle_grid_linear = () => {
            const container = document.getElementById("file-manager-list");
            const container_toggle = document.getElementById("file-manager-list-toggle");
            const container_toggle_icon = document.getElementById("file-manager-list-toggle-icon");

            container_toggle_icon.classList.remove("fa-border-all");
            container_toggle_icon.classList.remove("fa-bars");

            if (!isGrid) {
                container_toggle.setAttribute("title", stringOBJ["tooltip_toggle_grid"]);
                container_toggle_icon.classList.add("fa-border-all");
                container.classList.remove("grid");
            } else {
                container_toggle.setAttribute("title", stringOBJ["tooltip_toggle_linear"]);
                container_toggle_icon.classList.add("fa-bars");
                container.classList.add("grid");
            }

            isGrid = !isGrid;
        }
    </script>
</body>
</html>