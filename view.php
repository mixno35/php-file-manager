<?php
global $language_tag, $content, $login, $main_path, $privileges, $server_encoding;

include_once "lang/lang.php"; // Загружаем языковой пакет
include_once "php/data.php"; // Загружаем системные настройки
include_once "secure/session.php"; // Проверка на авторизацию
include_once "php/user-privileges.php"; // Загружаем привилегии пользователя

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
$file_type_2 = explode("/", $file_manager->get_mime_type($path))[1];

function get_mode_codemirror(string $path = ""):string {
    $plain = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    switch ($plain) {
        case "js":
            return "javascript";
        case "pwn":
        case "inc":
        case "php":
            return "text/x-php";
        case "xml":
            return "xml";
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
    <title><?= str_get_string("document_name_view") ?> | <?= basename($path) ?></title>

    <meta charset="<?= $server_encoding ?? 'UTF-8' ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css">
    <link rel="stylesheet" href="https://codemirror.net/5/theme/neo.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="assets/css/system/root.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/default.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/system/progress.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/css/view.css?v=<?= $resource_v ?>">
    <link rel="stylesheet" href="assets/custom/fontawesome-free/css/all.css">

    <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico?v=2">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <script src="assets/js/m35/parse-url.js"></script>
    <script src="assets/js/system.js?v=<?= $resource_v ?>"></script>

    <script>
        const stringOBJ = JSON.parse(JSON.stringify(<?= $content ?>));
    </script>
</head>
<body>
    <?php if (!$privileges["view_file"]) { ?>
        <h4 class="file-viewer-unknown-file"><?= str_get_string("text_privileges_forbidden") ?></h4>
    <?php exit(); } ?>

    <?php if ($file_type === "font") { ?>
        <header>
            <h1 class="title">
                <?= str_get_string("document_name_view_2", true) ?>
            </h1>
        </header>

        <?php
        $font_name = "font_" . uniqid();

        function get_font_format(string $path):string {
            global $file_manager;

            $format = $file_manager->get_file_format($path);

            switch ($format) {
                case "ttf":
                    return "truetype";
                case "otf":
                    return "opentype";
                case "woff":
                    return "woff";
                case "woff2":
                    return "woff2";
                case "eot":
                    return "embedded-opentype";
                default:
                    return $format;
            }
        }
        ?>

        <style>
            @font-face {
                font-family: <?= $font_name ?>;
                src: url("<?= $file_manager->get_current_url($path, true); ?>") format("<?= get_font_format($path) ?>");
            }

            .font {
                font-family: <?= $font_name ?>, "<?= $font_name ?>", serif;
            }
        </style>
    <?php exit(); } ?>

    <?php if ($file_type === "text" or $file_type_2 === "json" or $file_type === "log") { ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/clike/clike.min.js" integrity="sha512-l8ZIWnQ3XHPRG3MQ8+hT1OffRSTrFwrph1j1oc1Fzc9UKVGef5XN9fdO0vm3nW0PRgQ9LJgck6ciG59m69rvfg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/javascript/javascript.min.js" integrity="sha512-I6CdJdruzGtvDyvdO4YsiAq+pkWf2efgd1ZUSK2FnM/u2VuRASPC7GowWQrWyjxCZn6CT89s3ddGI+be0Ak9Fg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/sql/sql.min.js" integrity="sha512-JOURLWZEM9blfKvYn1pKWvUZJeFwrkn77cQLJOS6M/7MVIRdPacZGNm2ij5xtDV/fpuhorOswIiJF3x/woe5fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/php/php.min.js" integrity="sha512-jZGz5n9AVTuQGhKTL0QzOm6bxxIQjaSbins+vD3OIdI7mtnmYE6h/L+UBGIp/SssLggbkxRzp9XkQNA4AyjFBw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/css/css.min.js" integrity="sha512-rQImvJlBa8MV1Tl1SXR5zD2bWfmgCEIzTieFegGg89AAt7j/NBEe50M5CqYQJnRwtkjKMmuYgHBqtD1Ubbk5ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/sass/sass.min.js" integrity="sha512-KX6urL7liHg1q4mBDqbaX4WGbiTlW0a4L6gwr6iBl2AUmf3n+/L0ho5mf7zJzX8wHCv6IpDbcwVQ7pKysReD8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/nginx/nginx.min.js" integrity="sha512-kgLrmRot2x/yBR/HMHKt1S1Q0gIFOt6JGwAqrowCFxtal0MLUrqwzOu1YUA59Uds85K/1dnw9xZrXCs/5FAFJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/python/python.min.js" integrity="sha512-2M0GdbU5OxkGYMhakED69bw0c1pW3Nb0PeF3+9d+SnwN1ryPx3wiDdNqK3gSM7KAU/pEV+2tFJFbMKjKAahOkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/htmlmixed/htmlmixed.min.js" integrity="sha512-HN6cn6mIWeFJFwRN9yetDAMSh+AK9myHF1X9GlSlKmThaat65342Yw8wL7ITuaJnPioG0SYG09gy0qd5+s777w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js" integrity="sha512-LarNmzVokUmcA7aUDtqZ6oTS+YXmUKzpGdm8DxC46A6AHu+PQiYCUlwEGWidjVYMo/QXZMFMIadZtrkfApYp/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/yaml/yaml.min.js" integrity="sha512-+aXDZ93WyextRiAZpsRuJyiAZ38ztttUyO/H3FZx4gOAOv4/k9C6Um1CvHVtaowHZ2h7kH0d+orWvdBLPVwb4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/html-hint.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/css-hint.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/javascript-hint.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/hint/sql-hint.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/addon/comment/continuecomment.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/keymap/emacs.min.js" integrity="sha512-vkAJFl6fSbUY4MDhe50ATyWN/8jLYZPtxqELsXbbxA+bSxk8n/0iVBeGQqCJJYv2mn1bhBKs7du3A0HbtgrLEA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/comment/comment.min.js" integrity="sha512-UaJ8Lcadz5cc5mkWmdU8cJr0wMn7d8AZX5A24IqVGUd1MZzPJTq9dLLW6I102iljTcdB39YvVcCgBhM0raGAZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/comment/continuecomment.min.js" integrity="sha512-bPfnPUeDAbKU71b0+CKJBuYLXujAOrzS3bjB1GLr5lgmPEjvWYnmjOG8cioWf7YdSj/SaXMCnYr44C/E0XGzTw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/display/panel.min.js" integrity="sha512-kS6L87i8KUuHFk6QTxAyZSp1xza7zuaDg1Mt9rjQXjRQyraT0oqEeVbC6No/pxNvE1dMAZYBQ5r/8GZQUowhnw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <header>
            <h1 class="title">
                <?= str_get_string("document_name_view_2", true) ?>
            </h1>
        </header>

        <?php
        $encoding = $_GET["from_encode"] ?? ($server_encoding ?? "UTF-8");

        $content = file_get_contents($path);
        $content = mb_convert_encoding($content, $server_encoding ?? "UTF-8", $encoding);

        $array_encoding = array(
            "UCS-4", "UCS-2", "UTF-32", "UTF-16", "UTF-7", "UTF-8", "ASCII", "EUC-JP", "CP932", "CP51932", "JIS",
            "JIS-ms", "CP50220", "CP50221", "CP50222", "ISO-8859-1", "ISO-8859-2", "ISO-8859-3", "ISO-8859-4",
            "ISO-8859-5", "ISO-8859-6", "ISO-8859-7", "ISO-8859-8", "ISO-8859-9", "ISO-8859-10", "ISO-8859-13",
            "ISO-8859-14", "ISO-8859-15", "ISO-8859-16", "CP936", "GB18030", "CP950", "HZ", "ISO-2022-KR", "CP949",
            "Windows-1251", "Windows-1252", "IBM866", "ArmSCII-8"
        );
        ?>

        <label><textarea class="editor custom-scroll" id="editor"><?= ltrim($content); ?></textarea>
        </label>

        <footer class="view-footer">
            <label for="width_tmp_select" style="display: none"></label>
            <select style="visibility: hidden" id="width_tmp_select">
                <option id="width_tmp_option"></option>
            </select>
            <p class="clickable" id="spaces-read-container">
                <span id="spaces-read">0</span> spaces
            </p>
            <p><?= $file_manager->get_file_format($path) ?></p>
            <label for="character-unicode" style="display: none"></label>
            <select id="character-unicode">
                <?php foreach ($array_encoding as $item) { ?>
                    <option value="<?= strtolower($item) ?>" <?= strtolower($item) === strtolower($encoding) ? "selected" : "" ?>><?= $item ?></option>
                <?php } ?>
            </select>
            <i class="fa fa-lock-open" id="read-only"></i>
        </footer>

        <script>
            const isReadOnly = <?= intval($_GET["read-only"] ?? 0) ?>;

            let sublimeKeyMap = {
                'Ctrl-L': 'goLineRight',
                'Ctrl-Alt-L': 'goLineLeft',
                'Ctrl-D': 'selectNextOccurrence',
                'Ctrl-U': 'undoSelection',
                'Ctrl-Shift-U': 'redoSelection',
                'Ctrl-Enter': 'insertLineAfter',
                'Ctrl-Shift-Enter': 'insertLineBefore',
                'Ctrl-Shift-D': 'duplicateLine',
                'Ctrl-J': 'autoIndent',
                'Ctrl-/': 'toggleComment',
                'Ctrl-Shift-K': 'deleteLine',
                'F9': 'sortLines',
                'F5': 'refresh',
                'Ctrl-F': 'find',
                'F3': 'findNext',
                'Shift-F3': 'findPrev',
                'Ctrl-H': 'replace',
                'Ctrl-Shift-H': 'replaceAll',
                'Ctrl-S': 'save',
            };

            let tab_size = <?= intval($_GET["spaces"] ?? 4) ?>;
            let editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
                mode: "<?= get_mode_codemirror($path) ?>",
                main: "codemirror",
                theme: "neo",
                lineNumbers: true,
                indentWithTabs: true,
                tabSize: tab_size,
                moveOnDrag: true,
                readOnly: false,
                continueComments: true,
                toggleComment: true,
                extraKeys: {"Ctrl-Space": "autocomplete"}
            });

            document.getElementById("character-unicode").addEventListener("change", (event) => {
                url_param().set("from_encode", event.target.value, true);
                document.querySelector("#width_tmp_option").innerHTML = event.target.value;
                document.querySelector("#character-unicode").style.width = Number(document.querySelector("#width_tmp_select").offsetWidth + 6) + "px";
            });

            document.getElementById("read-only").addEventListener("click", (event) => {
                if (event.currentTarget.classList.contains("fa-lock-open")) {
                    event.currentTarget.classList.add("fa-lock");
                    event.currentTarget.classList.remove("fa-lock-open");
                } else {
                    event.currentTarget.classList.remove("fa-lock");
                    event.currentTarget.classList.add("fa-lock-open");
                }

                editor.setOption("readOnly", !editor.getOption("readOnly"));

                setTimeout(() => {
                    url_param().set("read-only", String(editor.getOption("readOnly") ? 1 : 0));
                }, 100)
            });

            if(isReadOnly) document.getElementById("read-only").click();

            document.addEventListener("DOMContentLoaded", () => {
                document.querySelector("#width_tmp_option").innerHTML = document.querySelector("#character-unicode option[selected]").outerText;
                document.querySelector("#character-unicode").style.width = Number(document.querySelector("#width_tmp_select").offsetWidth + 6) + "px";

                document.getElementById("spaces-read").innerText = tab_size;
            });

            document.getElementById("spaces-read-container").addEventListener("click", () => {
                let spaces = prompt(stringOBJ["message_enter_tab_size_view"], tab_size);
                if (spaces === null || spaces === "null") return;
                if (spaces < 1 || spaces > 12) {
                    alert(stringOBJ["message_enter_tab_size_view"]);
                    return;
                }

                tab_size = spaces;
                editor.setOption("tabSize", tab_size);
                document.getElementById("spaces-read").innerText = tab_size;
                url_param().set("spaces", String(tab_size));
            });
        </script>
    <?php exit(); } ?>

    <?php if ($file_type === "image") { ?>
        <div class="image-preview" id="image-preview">
            <header id="header">
                <h1 class="title">
                    <?= str_get_string("document_name_view_2", true) ?>
                </h1>
            </header>

            <img class="preview" draggable="false" id="preview" src="<?= $file_parse->get_icon($path, true) ?>" alt="Image">
        </div>

        <script>
            const image = document.getElementById("preview");
            const image_preview = document.getElementById("image-preview");

            let isDragging = false;
            let startX, startY, translateX, translateY;

            let scale = 1;
            let offsetX = 0;
            let offsetY = 0;

            // document.addEventListener("click", () => {
            //     setTimeout(() => {
            //         if (isDragging) return;
            //
            //         if (document.getElementById("header").style.display === "none")
            //             document.getElementById("header").style.display = "flex";
            //         else
            //             document.getElementById("header").style.display = "none";
            //     }, 100);
            // });

            document.addEventListener("dblclick", () => {
                scale = scale > 1 ? 1 : 3;
                offsetX = 0;
                offsetY = 0;

                image.style.transform = `scale(${scale}) translate(${offsetX}px, ${offsetY}px)`;
            });

            image_preview.addEventListener("wheel", (e) => {
                e.preventDefault();
                const wheelDelta = e.deltaY;

                scale += wheelDelta * -0.004;
                scale = Math.min(Math.max(1, scale), 6);

                image.style.transform = `scale(${scale}) translate(${offsetX}px, ${offsetY}px)`;
            });

            image_preview.addEventListener("mousedown", (e) => {
                e.preventDefault();
                e.stopPropagation();

                isDragging = true;
                startX = e.clientX - offsetX;
                startY = e.clientY - offsetY;
            });

            document.addEventListener("mouseup", () => {
                event.preventDefault();
                event.stopPropagation();

                isDragging = false;
            });

            document.addEventListener("mousemove", (e) => {
                event.preventDefault();
                event.stopPropagation();

                if (!isDragging) return;

                offsetX = e.clientX - startX;
                offsetY = e.clientY - startY;

                image.style.transform = `scale(${scale}) translate(${offsetX}px, ${offsetY}px)`;
            });
        </script>
    <?php exit(); } ?>

    <h4 class="file-viewer-unknown-file"><?= str_get_string("text_unknown_type_file") ?></h4>
</body>
</html>