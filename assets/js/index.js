const
    COMMAND_CREATE_FILE = "create-file",
    COMMAND_CREATE_DIRECTORY = "create-dir",
    COMMAND_CREATE_RENAME = "rename",
    COMMAND_CREATE_REMOVE = "remove",
    COMMAND_UPLOAD_FILE = "upload-file",
    COMMAND_MOVE = "move";

let element_popup_dom = null;
let element_popup_sticky = null;

let timer_search_main;

const THEME = getCookie("theme") ?? "auto";

document.addEventListener("DOMContentLoaded", () => {
    window.addEventListener("popstate", () => {
        const currentURL = window.location.href;
        loadMainFileManager(url_param(currentURL).get("p"));
    });

    if (THEME === "auto") {
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
            document.documentElement.setAttribute("data-theme", "dark");
        } else document.documentElement.setAttribute("data-theme", "light");
    } else document.documentElement.setAttribute("data-theme", getCookie("theme"));

    document.querySelectorAll("#menu-selected-fd ul li").forEach((element) => {
        element.setAttribute("title", element.childNodes[3].outerText);
    });
});

window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (event) => {
    if (THEME === "auto")
        document.documentElement.setAttribute("data-theme", (event.matches ? "dark" : "light"));
});

document.addEventListener("keydown", (event) => {
    if (event.ctrlKey && event.altKey && event.code === "KeyF") run_command().create(openedDirectory).file();
    if (event.ctrlKey && event.altKey && event.code === "KeyD") run_command().create(openedDirectory).dir();
    if (event.ctrlKey && event.code === "Delete" && select_path().length() > 0) run_command().delete(select_path().getAll());
});

document.querySelector("input[type='search']#main-search").addEventListener("input", (event) => {
    clearTimeout(timer_search_main);
    timer_search_main = setTimeout(() => {
        const value = event.target.value;
        if (value.length > 0) url_param().set("s", encodeURIComponent(value));
        else url_param().delete("s");
        updateMainFileManager();
    }, 1000);
});

document.body.addEventListener("click", () => { popup_close() });

document.getElementById("input-upload-content")
    .addEventListener("change", (event) => { uploadNewFiles(event.currentTarget.files) });

loadNavDirectoryManager(serverDirectory, "list-directory-manager");
loadMainFileManager((url_param().get("p") ?? serverDirectory));

document.getElementById("action-dev-report")
    .addEventListener("click", () => { window.open("//t.me/mixno35") });
document.getElementById("action-dev-paid").addEventListener("click", () => {
    window.open("//www.donationalerts.com/r/mixno35", "_blank");
});
document.getElementById("action-dev-settings").addEventListener("click", () => {
    dialog(DIALOG_STYLE_PATH, [getStringBy("tooltip_dev_settings"), "settings.php"]);
});

document.getElementById("menu-selected-open").addEventListener("click", () => {
    const container = document.querySelector("ul#file-manager-list li.item-fm.selected");
    clickToPathDuo(container.getAttribute("data-path"), Boolean(Number(container.getAttribute("data-isdir"))), container.id);
});
document.getElementById("menu-selected-rename")
    .addEventListener("click", () => { run_command().rename(select_path().get(0)) });
document.getElementById("menu-selected-info")
    .addEventListener("click", () => { openFileDetail(select_path().get(0)) });
document.getElementById("menu-selected-delete")
    .addEventListener("click", () => { run_command().delete(select_path().getAll()) });
try {
    document.getElementById("menu-selected-download-archive").addEventListener("click", () => {
        window.open("content/archive-download.php?obj=" + encodeURIComponent(JSON.stringify(select_path().getAll())), "_blank");
    });
} catch (e) { console.log(e) }
document.getElementById("menu-selected-select-all").addEventListener("click", () => {
    const items = document.querySelectorAll("ul#file-manager-list li.item-fm");
    if (items.length === select_path().length()) {
        items.forEach((element) => {
            select_path(element.getAttribute("data-path"), () => {
                element.classList.remove("selected");
            }).remove();
        });
    } else {
        items.forEach((element) => {
            select_path(element.getAttribute("data-path"), () => {
                element.classList.add("selected");
            }).add();
        });
    }

    setTimeout(updateSelectPathsContainer, 100)
});

document.querySelector(".action-search").addEventListener("click", () => {
    document.querySelector(".search-container").classList.toggle("show");
});
document.querySelector(".action-search-close").addEventListener("click", () => {
    const main_search = document.getElementById("main-search");

    if (main_search.value.trim().length > 0) {
        main_search.value = "";
        url_param().delete("s");
        updateMainFileManager();
        return;
    }

    document.querySelector(".search-container").classList.toggle("show");
});

document.getElementById("action-directory-menu").addEventListener("click", () => {
    document.getElementById("left-directory-manager").classList.toggle("show");
});