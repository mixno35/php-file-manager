const PROGRESS_TYPE_FM = 2, PROGRESS_TYPE_NAV = 1, PROGRESS_TYPE_ALL = 0;
const SEARCH_TYPE_LOCAL_PLUS = 2, SEARCH_TYPE_LOCAL = 1, SEARCH_TYPE_GLOBAL = 0;
const METHOD_POST = "POST", METHOD_GET = "GET";

let z_index_alert = 79;

const generate_text = (length = 6) => {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let randomText = '';

    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        randomText += characters.charAt(randomIndex);
    }

    return randomText;
}

// Работа с уведомлениями
const toast = () => {
    return {
        show: (message = "", html = false) => {
            let id = "toast-" + generate_text();

            let toast_container = document.createElement("div");
                toast_container.setAttribute("id", id);
                toast_container.classList.add("toast-container");

            let toast_text = document.createElement("p");
                toast_text.classList.add("text");
                if (html)
                    toast_text.innerHTML = message;
                else
                    toast_text.innerText = message;

            toast_container.appendChild(toast_text);

            if (isMobileDevice()) document.getElementById("container-for-toast").innerHTML = "";

            document.getElementById("container-for-toast").appendChild(toast_container);

            setTimeout(() => {
                document.getElementById(id).remove();
            }, 5000);
        }
    }
}

function progress(type = PROGRESS_TYPE_ALL) {
    if (type === PROGRESS_TYPE_ALL) {
        if (document.getElementById("progress").style.display === "flex")
            document.getElementById("progress").style.display = "none";
        else
            document.getElementById("progress").style.display = "flex";
    } else if (type === PROGRESS_TYPE_NAV) {

    } else if (type === PROGRESS_TYPE_FM) {

    } else {
        toast().show(stringOBJ["message_unknown_progress_type"]);
    }
}

function isValidFName(name = "") {
    // Запрещенные символы в именах файлов и папок
    const forbidden_chars = /[\/:*?"<>|\\]/;
    return !forbidden_chars.test(name);
}

function setCookie(name = "", value = "", days = 0) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function isMobileDevice() {
    return navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i);
}

function dragElement(_element) {
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById(_element.id + "header")) {
        document.getElementById(_element.id + "header").onmousedown = dragMouseDown;
        // document.getElementById(_element.id + "header").onmouseup = dragMouseUp;
    } else {
        _element.onmousedown = dragMouseDown;
        // _element.onmouseup = dragMouseUp;
    }

    _element.style.zIndex = String(Number(z_index_alert++));

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        document.onmousemove = elementDrag;

        let id_container = e.currentTarget.offsetParent.id;

        document.getElementById(id_container).style.zIndex = String(Number(z_index_alert++));
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;

        if ((_element.offsetTop - pos2) < 1 || (_element.offsetTop - pos2) > (window.outerHeight - 145)) return;
        if ((_element.offsetLeft - pos1) < 1 || (_element.offsetLeft - pos1) > (window.outerWidth - _element.innerWidth)) return;

        _element.style.top = (_element.offsetTop - pos2) + "px";
        _element.style.left = (_element.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

function getChildNode(string) {
    return new DOMParser().parseFromString(string, "text/html").body
}

function getStringBy(key, replace = []) {
    let result = stringOBJ[key] ?? key
    if (replace.length > 0)
        for(let i = 0; i < replace.length; i++) result = result.replaceAll(`%${Number(i + 1)}s`, replace[i])

    return result
}