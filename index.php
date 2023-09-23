<?php
global $language_tag, $content, $login, $main_path;

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

    <link rel="stylesheet" href="assets/css/system/root.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/default.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/progress.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= $resource_v ?>">

    <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico?v=2">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <script src="assets/js/system.js?v=<?= $resource_v ?>"></script>

    <script>
        const stringOBJ = JSON.parse(JSON.stringify(<?= $content ?>));
    </script>
</head>
<body>
    <div class="progress" id="progress" style="display: none">
        <div class="lds-facebook"><div></div><div></div><div></div></div>
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
            <section class="details-manager">
                <h2><?= str_get_string("text_details_manager") ?></h2>
                <ul>
                    <!-- Версия PHP -->
                    <li><?= str_replace("%1s", (PHP_VERSION ?? "NaN"), str_get_string("text_php_version")) ?></li>
                    <!-- Тип сервера -->
                    <li><?= str_replace("%1s", ($_SERVER["SERVER_SOFTWARE"] ?? "NaN"), str_get_string("text_php_server")) ?></li>
                    <!-- Общий размер сервера -->
                    <li><?= str_replace("%1s", $file_manager->format_size($file_manager->get_directory_size($main_path["server"])), str_get_string("text_php_total_size")) ?></li>
                </ul>
            </section>
        </nav>
        <div id="resize-divider" class="resize-divider"></div>
        <article class="main-file-manager" id="main-file-manager">

        </article>
    </main>

    <section class="container-for-toast" id="container-for-toast">
        <!-- Здесь будут все уведомления -->
    </section>

    <script>
        let openedDirectory = "<?= addslashes($main_path["server"]) ?>";
    </script>

    <script>
        const resizableElement = document.getElementById("left-directory-manager");
        const resizeHandle = document.getElementById("resize-divider");
        let isResizing = false;
        let startX;
        let startWidth;

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

                    if (_container_id !== null)
                        document.getElementById(_container_id).innerHTML = result;
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
                                if (_container_id !== null)
                                    document.getElementById(_container_id).innerHTML = "";
                            } else {
                                loadNavDirectoryManager(document.getElementById(_element_id).getAttribute("data-path"), _container_id);
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
                        loadMainFileManager(document.getElementById(_element_id).getAttribute("data-path"));
                    }
                    clickCount = 0;
                }, 170); // Задержка для определения двойного клика
            }
        }

        function clickToFile(_path = "", _is_dir = false) {
            event.stopPropagation();
            event.preventDefault();

            clickCount++;
            if (clickCount === 1) {
                setTimeout(function () {
                    if (clickCount === 1) {
                        // Одиночный клик
                        openFileDetail(_path);
                    } else {
                        // Двойной клик
                        if (_is_dir)
                            loadMainFileManager(_path);
                        else
                            alert("Двойной клик");
                    }
                    clickCount = 0;
                }, 170); // Задержка для определения двойного клика
            }
        }
    </script>

    <script>
        function loadMainFileManager(_path = "") {
            progress();

            $.ajax({
                url: "file-manager.php",
                data: {
                    path: _path
                },
                success: function (result) {
                    progress();

                    openedDirectory = _path;
                    document.getElementById("main-file-manager").innerHTML = result;
                }
            });
        }

        function updateMainFileManager() {
            if (document.getElementById("file-detail"))
                document.getElementById("file-detail").remove()

            loadMainFileManager(openedDirectory);
        }

        loadMainFileManager("<?= addslashes($main_path["server"]) ?>");
    </script>

    <script>
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
        let draggingPath = "";
        let draggingPathStart = "";
        let draggedIsDir = 0;

        const drag = () => {
            return {
                start: () => {
                    isDragging = true;
                    event.dataTransfer.setData("text/plain", "");
                    draggingPathStart = event.target.getAttribute("data-path");
                },
                end: () => {
                    isDragging = false;
                },
                enter: () => {
                    if (isDragging) {
                        draggedIsDir = Number(event.target.getAttribute("data-isdir"));
                        draggingPath = event.target.getAttribute("data-path");
                    }

                    if (Boolean(draggedIsDir))
                        if (draggingPathStart !== draggingPath)
                            document.getElementById(event.target.id).classList.add("drag-in");
                },
                leave: () => {
                    try {
                        document.getElementById(event.target.id).classList.remove("drag-in");
                    } catch (e) {}
                },
                drop: () => {
                    if (Boolean(draggedIsDir)) {
                        if (draggingPathStart !== draggingPath) {
                            toast().show(draggingPathStart + " перемещено в " + draggingPath);
                        }
                    }

                    draggingPath = "";
                    draggingPathStart = "";
                    draggedIsDir = 0;

                    try {
                        document.getElementById(event.target.id).classList.remove("drag-in");
                    } catch (e) {}
                },
                live: () => {

                },
                over: () => {
                    if (Boolean(draggedIsDir))
                        if (draggingPathStart !== draggingPath)
                            event.preventDefault();
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
                remove: (path = "") => {
                    if (confirm(stringOBJ["message_are_remove"]))
                        command("remove", {path: path}, updateMainFileManager);
                }
            }
        }

        const command = (command = "", data = {}, callback = null) => {
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
                    // console.log(result);

                    try {
                        let json = JSON.parse(result);

                        if (json["type"] === "success") {
                            if (callback !== null)
                                callback();
                        }

                        toast().show(stringOBJ[json["message_id"]] ?? json["message_id"]);
                    } catch (e) {
                        toast().show(e);
                        console.error(e);
                    }
                }
            });
        };
    </script>
</body>
</html>