<?php
include_once "secure/session.php"; // Проверка на авторизацию

global $language_tag, $content, $login, $main_path, $privileges, $server_encoding;

include_once "lang/lang.php"; // Загружаем языковой пакет
include_once "php/data.php"; // Загружаем системные настройки
include_once "secure/user-privileges.php"; // Загружаем привилегии пользователя

include_once "class/FileManager.php";
include_once "class/FileParseManager.php";

$file_manager = new FileManager();
$file_parse = new FileParseManager();

$resource_v = time(); // Устанавливаем версию для ресурсов

$path = $_GET["p"] ?? "";

if (!file_exists($path)) {
    die(str_get_string("text_file_not_exists"));
}

$mime_type = $file_manager->get_mime_type($path);
$file_type = explode("/", $mime_type)[0];
$file_type_2 = explode("/", $mime_type)[1];

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
    <title><?= str_get_string("document_name") ?> <?= str_get_string("document_name_view") ?> | <?= basename($path) ?></title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico?v=2">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <script>const stringOBJ = <?= $content ?>;</script>

    <script src="assets/js/m35/parse-url.js"></script>
    <script src="assets/js/system.js?v=<?= $resource_v ?>"></script>
</head>
<body>
    <?php if (!$privileges["view_file"]) { ?>
        <h4 class="file-viewer-unknown-file"><?= str_get_string("text_privileges_forbidden") ?></h4>
    <?php exit(); } ?>

    <?php if ($file_type === "video" or $file_type === "audio") { ?>
        <?php
        $title = basename($path) ?? str_get_string("text_media_unknown");
        $artist = "";
        $album = "";
        $artwork = "";
        ?>
        <div class="media-preview" oncontextmenu="return false;">
            <<?= $file_type ?> id="media-player">
                <source src="content/blob.php?p=<?= rawurlencode($path) ?>">
            </<?= $file_type ?>>
            <?php if ($file_type === "audio") { ?>
                <?php
                include_once "class/getid3/getid3.php";
                $getID3 = new getID3();
                $fileInfo = $getID3->analyze($path);

//                print_r($fileInfo);

                $title = $fileInfo["tags"]["id3v2"]["title"][0] ?? ($fileInfo["filename"] ?? str_get_string("text_media_unknown"));
                $artist = $fileInfo["tags"]["id3v2"]["artist"][0] ?? "";
                $album = $fileInfo["tags"]["id3v2"]["album"][0] ?? "";
                ?>
                <div class="audio-container">
                    <h1><?= $title ?></h1>
                    <h2 <?php if (strlen($artist) < 1) { ?>style="display: none"<?php } ?>><?= $artist ?></h2>
                    <h3 <?php if (strlen($album) < 1) { ?>style="display: none"<?php } ?>><?= $album ?></h3>
                </div>
            <?php } ?>
            <div class="controls" id="media-controls">
                <section class="progress">
                    <div class="seek" style="width: 0"></div>
                </section>
                <div class="left">
                    <p>
                        <span class="current">00:00</span> / <span class="duration">00:00</span>
                    </p>
                </div>
                <i class="fa fa-backward small" id="action-backward" title="<?= str_get_string('tooltip_media_backward') ?>"></i>
                <i class="fa fa-play" id="action-play-pause" title="<?= str_get_string('tooltip_media_play') ?>"></i>
                <i class="fa fa-forward small" id="action-forward" title="<?= str_get_string('tooltip_media_forward') ?>"></i>
                <div class="right">
                    <i class="fa small" id="action-pb-rate" title="<?= str_get_string('tooltip_media_playback_rate') ?>">1x</i>
                    <i class="fa fa-repeat small no-active" id="action-loop" title="<?= str_get_string('tooltip_media_loop') ?>"></i>
                    <i class="fa fa-expand small" id="action-fullscreen" title="<?= str_get_string('tooltip_media_fullscreen_enter') ?>"></i>
                </div>
            </div>
        </div>

        <script>
            const mediaPlayer = document.querySelector("#media-player");
            const mediaControls = document.querySelector("#media-controls");
            const seek = document.querySelector(".seek");

            const timeDuration = document.querySelector(".duration");
            const timeCurrent = document.querySelector(".current");

            const array_playbackRate = [1, 1.5, 2, 3, 4, 5];
            let currentPlaybackRateIndex = 0;

            if ("mediaSession" in navigator) {
                navigator.mediaSession.metadata = new MediaMetadata({
                    title: "<?= $title ?>",
                    artist: "<?= $artist ?>",
                    album: "<?= $album ?>",
                    artwork: [{ src: "<?= $artwork ?>" }]
                });

                navigator.mediaSession.setActionHandler("seekbackward", () => {
                    document.getElementById("action-backward").click();
                });
                navigator.mediaSession.setActionHandler("seekforward", () => {
                    document.getElementById("action-forward").click();
                });
                navigator.mediaSession.setActionHandler("seekto", () => {
                    /* Code excerpted. */
                });
            }

            let timer,
                isHoveredMediaControls = false,
                isAudio = Boolean(<?= $file_type === "audio" ? 1 : 0 ?>),
                isDraggingSeek = false;

            if (isAudio) document.getElementById("action-fullscreen").style.display = "none";

            document.getElementById("action-play-pause").addEventListener("click", () => {
                if (mediaPlayer.paused) mediaPlayer.play();
                else mediaPlayer.pause();
            });
            document.getElementById("action-fullscreen").addEventListener("click", (event) => {
                const element = document.querySelector(".media-preview");
                if (!isFullscreen()) {
                    if (element.requestFullscreen) element.requestFullscreen();
                    else if (element.mozRequestFullScreen) element.mozRequestFullScreen();
                    else if (element.webkitRequestFullscreen) element.webkitRequestFullscreen();
                    else if (element.msRequestFullscreen) element.msRequestFullscreen();
                } else {
                    if (document.exitFullscreen) document.exitFullscreen();
                    else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
                    else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
                    else if (document.msExitFullscreen) document.msExitFullscreen();
                }
            });
            document.getElementById("action-loop").addEventListener("click", (event) => {
                mediaPlayer.loop = !mediaPlayer.loop;
                event.currentTarget.classList.toggle("no-active");
            });
            document.getElementById("action-pb-rate").addEventListener("click", (event) => {
                currentPlaybackRateIndex++;
                if (currentPlaybackRateIndex >= array_playbackRate.length) currentPlaybackRateIndex = 0;
                const current = array_playbackRate[currentPlaybackRateIndex];

                mediaPlayer.playbackRate = current;
                event.currentTarget.innerText = `${current}x`;
            });
            document.getElementById("action-forward").addEventListener("click", () => { mediaPlayer.currentTime += 10 });
            document.getElementById("action-backward").addEventListener("click", () => { mediaPlayer.currentTime -= 10 });

            document.querySelector(".progress").addEventListener("click", (event) => {
                const progress = document.querySelector(".progress");

                const clickPosition = event.clientX - progress.getBoundingClientRect().left;
                const progressWidth = progress.offsetWidth;
                const percentage = Number(clickPosition / progressWidth);

                mediaPlayer.currentTime = Number(percentage * mediaPlayer.duration);
            });

            window.matchMedia("(display-mode: fullscreen)").addEventListener("change", ({ matches }) => {
                const action_fullscreen = document.getElementById("action-fullscreen");

                if (matches) {
                    action_fullscreen.classList.remove("fa-expand");
                    action_fullscreen.classList.add("fa-compress");
                    action_fullscreen.setAttribute("title", getStringBy("tooltip_media_fullscreen_exit"));
                } else {
                    action_fullscreen.classList.add("fa-expand");
                    action_fullscreen.classList.remove("fa-compress");
                    action_fullscreen.setAttribute("title", getStringBy("tooltip_media_fullscreen_enter"));
                }
            });

            document.addEventListener("keydown", (event) => {
                if (event.code === "Space") document.getElementById("action-play-pause").click();
                if (event.code === "ArrowLeft") document.getElementById("action-backward").click();
                if (event.code === "ArrowRight") document.getElementById("action-forward").click();
                if (event.code === "KeyF" && !isAudio) document.getElementById("action-fullscreen").click();
                if (event.code === "KeyR" || event.code === "KeyL") document.getElementById("action-loop").click();
                if (event.code === "KeyS") document.getElementById("action-pb-rate").click();
            });

            mediaPlayer.addEventListener("pause", () => {
                document.getElementById("action-play-pause").classList.remove("fa-pause");
                document.getElementById("action-play-pause").classList.add("fa-play");
                document.getElementById("action-play-pause").setAttribute("title", getStringBy("tooltip_media_play"));
                showMediaControls();
            });
            mediaPlayer.addEventListener("play", () => {
                document.getElementById("action-play-pause").classList.add("fa-pause");
                document.getElementById("action-play-pause").classList.remove("fa-play");
                document.getElementById("action-play-pause").setAttribute("title", getStringBy("tooltip_media_pause"));
                hideMediaControls();
            });
            mediaPlayer.addEventListener("ended", () => { showMediaControls() });
            mediaPlayer.addEventListener("loadedmetadata", (event) => {
                timeDuration.innerText = formatTime(event.currentTarget.duration);
            });
            mediaPlayer.ontimeupdate = (event) => {
                timeCurrent.innerText = formatTime(event.currentTarget.currentTime);
                if (!isDraggingSeek) updatingProgressSeek();
            };

            seek.addEventListener("mousedown", (event) => {
                isDraggingSeek = true;
                updateProgressSeek(event);
                document.querySelector(".progress").classList.add("dragging");
            });
            document.addEventListener("mouseup", () => {
                isDraggingSeek = false;
                document.querySelector(".progress").classList.remove("dragging");
            });
            document.addEventListener("mousemove", (event) => {
                showMediaControls();
                if (isDraggingSeek) updateProgressSeek(event);
            });

            mediaControls.addEventListener("mouseenter", () => { isHoveredMediaControls = true });
            mediaControls.addEventListener("mouseleave", () => { isHoveredMediaControls = false });

            function showMediaControls() {
                clearTimeout(timer);
                mediaControls.style.transform = "translateY(0)";
                document.body.style.cursor = "unset";
                timer = setTimeout(() => { hideMediaControls() }, 2000);
            }

            function hideMediaControls() {
                if (!isHoveredMediaControls && !mediaPlayer.paused && !isAudio) {
                    mediaControls.style.transform = "translateY(100%)";
                    document.body.style.cursor = "none";
                }
            }

            function updatingProgressSeek() {
                document.querySelector(".seek").style.width =
                    ((event.currentTarget.currentTime / event.currentTarget.duration) * 100) + "%";
            }

            showMediaControls();

            function isFullscreen() {
                return document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
            }
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);

                const formattedMinutes = padZero(minutes);
                const formattedSeconds = padZero(remainingSeconds);

                return formattedMinutes + ":" + formattedSeconds;
            }
            function padZero(number) {
                return (number < 10 ? "0" : "") + number;
            }

            function updateProgressSeek(event) {
                const progressSection = document.querySelector(".progress");
                const progressWidth = progressSection.clientWidth;
                let seekWidth = event.clientX - progressSection.getBoundingClientRect().left;

                if (seekWidth < 0) seekWidth = 0;
                else if (seekWidth > progressWidth) seekWidth = progressWidth;

                const percentage = (seekWidth / progressWidth) * 100;
                seek.style.width = percentage + "%";
            }
        </script>
    <?php exit(); } ?>

    <?php if ($file_type === "font" or $file_type_2 === "x-font-ttf") { ?>
        <header>
            <h1 class="title">
                <?= str_get_string("document_name") ?>
                <span>
                    <?= str_get_string("document_name_view") ?>
                </span>
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
                src: url("content/blob.php?p=<?= rawurlencode($path) ?>") format("<?= get_font_format($path) ?>");
            }

            .font {
                font-family: <?= $font_name ?>, "<?= $font_name ?>", serif;
                line-height: 100%;
            }
        </style>

        <label>
            <input value="" class="input-text-preview" placeholder="<?= str_get_string('message_test_font') ?>">
        </label>

        <div class="fonts-container"></div>

        <script>
            const set_text_test = (_text = "") => {
                const font_elements = document.getElementsByClassName("font");

                for(let i = 0; i < font_elements.length; i++) {
                    font_elements[i].innerText = _text;
                }
            }

            document.body.style.overflow = "auto";

            const create_font_preview = (_tag, _font_size = 12) => {
                let elm = document.createElement(_tag);
                    elm.classList.add("font");
                    elm.classList.add("font-preview");
                    elm.style.fontSize = _font_size + "px";
                    elm.setAttribute("data-name", "<?= basename($path) ?> - " + _font_size + "px");

                document.querySelector("div.fonts-container").appendChild(elm);
            }

            create_font_preview("h1", 48);
            create_font_preview("h2", 36);
            create_font_preview("h3", 32);
            create_font_preview("h4", 21);
            create_font_preview("h5", 16);
            create_font_preview("h6", 14);

            document.querySelector("input.input-text-preview").addEventListener("input", (event) => {
                set_text_test(((event.currentTarget.value !== "") ? event.currentTarget.value : stringOBJ["message_test_font"]));
            });

            setTimeout(() => { set_text_test(stringOBJ["message_test_font"]) }, 200);
        </script>
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
                <?= str_get_string("document_name") ?>
                <span>
                    <?= str_get_string("document_name_view") ?>
                </span>
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
            <img class="preview" draggable="false" id="preview" src="" loading="eager" alt="Image">

            <?php
            $array_explore = $file_manager->get_files(dirname($path), "image");
            ?>

            <?php if (sizeof($array_explore) > 1) { ?>
                <div class="explore-images">
                    <?php foreach ($array_explore as $item) { ?>
                        <div class="item-explore" title="<?= basename($item) ?>" onclick="event.stopPropagation(); event.preventDefault(); openImage(this.getAttribute('data-path'))" data-path="<?= addslashes($file_manager->parse_separator($item)) ?>">
                            <img src="<?= $file_parse->get_icon($item, $privileges["view_file"], 86) ?>" alt="<?= basename($item) ?>">
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <script>
            const image = document.getElementById("preview");
            const image_preview = document.getElementById("image-preview");

            let isDragging = false;
            let isPinching = false;
            let startX, startY, translateX, translateY;
            let initialDistance, initialScale;

            let scale = 1;
            let offsetX = 0;
            let offsetY = 0;

            let time;

            if (isMobileDevice()) document.querySelector(".explore-images").remove();

            openImage("<?= addslashes(addslashes($file_manager->parse_separator($path))) ?>");

            document.addEventListener("mousemove", () => { try { showExploreImages() } catch (exx) {} });

            function openImage(path) {
                document.querySelectorAll(".item-explore").forEach((element) => {
                    if (validPath(element.getAttribute("data-path"), path)) element.classList.add("active");
                    else element.classList.remove("active");
                });

                image.style.opacity = "0";
                image.src = "content/blob.php?p=" + encodeURIComponent(path);

                image.addEventListener("load", () => {
                    scale = 1;
                    offsetX = 0;
                    offsetY = 0;

                    setTimeout(() => { image.style.opacity = "1" }, 100);

                    updateTransform();
                });
            }

            function showExploreImages() {
                clearTimeout(time);

                if (scale <= 1) document.querySelector(".explore-images").classList.remove("hide");

                time = setTimeout(() => { hideExploreImages() }, 2000);
            }
            function hideExploreImages() {
                document.querySelector(".explore-images").classList.add("hide");
            }

            image.addEventListener("dblclick", (e) => {
                e.preventDefault();
                const clickX = e.clientX;
                const clickY = e.clientY;

                scale = scale > 1 ? 1 : 3;
                const newScale = scale;

                offsetX = clickX - (clickX - offsetX) * (newScale / scale);
                offsetY = clickY - (clickY - offsetY) * (newScale / scale);

                updateTransform();
            });

            image_preview.addEventListener("wheel", (e) => {
                e.preventDefault();
                const wheelDelta = e.deltaY;

                scale += wheelDelta * -0.004;
                scale = Math.min(Math.max(1, scale), 6);

                if (scale <= 1) {
                    offsetX = 0;
                    offsetY = 0;
                }

                if (scale > 1) try { hideExploreImages() } catch (exx) {}

                updateTransform();
            });

            image_preview.addEventListener("mousedown", (e) => {
                e.preventDefault();
                e.stopPropagation();

                isDragging = true;

                image.classList.add("dragging");

                startX = e.clientX - offsetX;
                startY = e.clientY - offsetY;
            });

            document.addEventListener("mouseup", () => {
                event.preventDefault();
                event.stopPropagation();

                isDragging = false;

                image.classList.remove("dragging");
            });

            document.addEventListener("mousemove", (e) => {
                event.preventDefault();
                event.stopPropagation();

                if (!isDragging) return;

                offsetX = e.clientX - startX;
                offsetY = e.clientY - startY;

                updateTransform();
            });

            image_preview.addEventListener("touchstart", (e) => {
                if (e.touches.length === 2) {
                    isPinching = true;
                    const x1 = e.touches[0].clientX;
                    const y1 = e.touches[0].clientY;
                    const x2 = e.touches[1].clientX;
                    const y2 = e.touches[1].clientY;

                    initialDistance = Math.hypot(x2 - x1, y2 - y1);
                    initialScale = scale;
                } else if (e.touches.length === 1) {
                    isDragging = true;
                    startX = e.touches[0].clientX - offsetX;
                    startY = e.touches[0].clientY - offsetY;
                }
            });

            document.addEventListener("touchmove", (e) => {
                if (isPinching && e.touches.length === 2) {
                    const x1 = e.touches[0].clientX;
                    const y1 = e.touches[0].clientY;
                    const x2 = e.touches[1].clientX;
                    const y2 = e.touches[1].clientY;

                    const distance = Math.hypot(x2 - x1, y2 - y1);

                    scale = initialScale * (distance / initialDistance);
                    scale = Math.min(Math.max(1, scale), 6);

                    updateTransform();
                } else if (isDragging && e.touches.length === 1) {
                    offsetX = e.touches[0].clientX - startX;
                    offsetY = e.touches[0].clientY - startY;

                    updateTransform();
                }
            });

            document.addEventListener("touchend", (e) => {
                isDragging = false;
                isPinching = false;
            });

            function updateTransform() {
                const scaledOffsetX = offsetX / scale;
                const scaledOffsetY = offsetY / scale;

                image.style.transform = `scale(${scale}) translate(${scaledOffsetX}px, ${scaledOffsetY}px)`;
            }

        </script>
    <?php exit(); } ?>

    <h4 class="file-viewer-unknown-file">
        <?= str_get_string("text_unknown_type_file") ?>
        <span class="message"><?= $mime_type ?></span>
    </h4>
</body>
</html>