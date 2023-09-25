<?php
global $language_tag, $content, $login, $main_path;

include_once "lang/lang.php"; // Загружаем языковой пакет
include_once "php/data.php"; // Загружаем системные настройки
include_once "secure/session.php"; // Проверка на авторизацию

include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$resource_v = time(); // Устанавливаем версию для ресурсов

$path = $_GET["p"] ?? "";

if (!file_exists($path)) {
    die(str_get_string("text_file_not_exists"));
}

$file_type = explode("/", $file_manager->get_mime_type($path))[0];

function get_module_codemirror(string $path = ""):string {
    $plain = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    switch ($plain) {
        case "js":
            return "javascript";
        case "php":
            return "text/x-php";
        case "xml":
        case "html":
            return "htmlmixed";
        case "css":
            return "text/css";
        case "scss":
            return "text/x-scss";
        case "less":
            return "text/x-less";
        default:
            return $plain;
    }
}
?>

<html lang="<?= $language_tag ?? "en-US" ?>">
<head>
    <title><?= str_get_string("document_name") ?> | <?= $path ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="assets/css/system/root.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/default.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/progress.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/view.css?v=<?= $resource_v ?>">

    <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico?v=2">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <script src="assets/js/system.js?v=<?= $resource_v ?>"></script>

    <script>
        const stringOBJ = JSON.parse(JSON.stringify(<?= $content ?>));
    </script>
</head>
<body>
    <?php if ($file_type === "text") { ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/clike/clike.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/javascript/javascript.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/php/php.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/css/css.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/htmlmixed/htmlmixed.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/html-hint.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/css-hint.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/javascript-hint.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/sql-hint.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/comment/continuecomment.js"></script>

        <header>
            <h1 class="title">
                <?= str_get_string("document_name_2", true) ?>
            </h1>
        </header>

        <label><textarea class="editor custom-scroll" id="editor"><?= ltrim(file_get_contents($path)); ?></textarea>
        </label>

        <script>
            let editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
                mode: "<?= get_module_codemirror($path) ?>",
                lineNumbers: true,
                extraKeys: {"Ctrl-Space": "autocomplete"}
            });
        </script>
    <?php } ?>

    <?php if ($file_type === "image") { ?>
        <div class="image-preview" id="image-preview">
            <header id="header">
                <h1 class="title">
                    <?= str_get_string("document_name_2", true) ?>
                </h1>
            </header>

            <img class="preview" draggable="false" id="preview" src="<?= $file_parse->get_icon($path, true) ?>" alt="Image">
        </div>

        <script>
            const image = document.getElementById("preview");
            const image_preview = document.getElementById("image-preview");

            document.body.addEventListener("click", () => {
                if (isDragging) return;

                if (document.getElementById("header").style.display === "none")
                    document.getElementById("header").style.display = "flex";
                else
                    document.getElementById("header").style.display = "none";
            });

            let isDragging = false;
            let startX, startY, translateX, translateY;

            let scale = 1;
            let offsetX = 0;
            let offsetY = 0;

            image_preview.addEventListener("wheel", (e) => {
                e.preventDefault();
                const wheelDelta = e.deltaY;

                scale += wheelDelta * -0.004;
                scale = Math.min(Math.max(1, scale), 6);

                image.style.transform = `scale(${scale}) translate(${offsetX}px, ${offsetY}px)`;
            });

            image_preview.addEventListener("mousedown", (e) => {
                isDragging = true;
                startX = e.clientX - offsetX;
                startY = e.clientY - offsetY;
            });

            document.addEventListener("mouseup", () => {
                isDragging = false;
            });

            document.addEventListener("mousemove", (e) => {
                if (!isDragging) return;

                offsetX = e.clientX - startX;
                offsetY = e.clientY - startY;

                image.style.transform = `scale(${scale}) translate(${offsetX}px, ${offsetY}px)`;
            });
        </script>
    <?php } ?>
</body>
</html>