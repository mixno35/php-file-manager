const
    COMMAND_CREATE_FILE = "create-file",
    COMMAND_CREATE_DIRECTORY = "create-dir",
    COMMAND_CREATE_RENAME = "rename",
    COMMAND_CREATE_REMOVE = "remove";

let element_popup_dom = null;
let element_popup_sticky = null;

document.addEventListener("DOMContentLoaded", () => {
    window.addEventListener("popstate", () => {
        const currentURL = window.location.href;
        loadMainFileManager(url_param(currentURL).get("p"));
    });
});

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

document.body.addEventListener("click", () => {
    popup_close();
});

loadNavDirectoryManager(serverDirectory, "list-directory-manager");
loadMainFileManager((url_param().get("p") ?? serverDirectory));

document.getElementById("action-dev-report").addEventListener("click", () => {
    window.open("//linkbox.su/r/mixno35");
});
document.getElementById("action-dev-paid").addEventListener("click", () => {
    window.open("//www.donationalerts.com/r/mixno35");
});
document.getElementById("action-dev-settings").addEventListener("click", () => {
    dialog(DIALOG_STYLE_PATH, [getStringBy("tooltip_dev_settings"), "settings.php"]);
});

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
document.getElementById("menu-selected-select-all").addEventListener("click", (event) => {
    const items = document.querySelectorAll("ul#file-manager-list li.item-fm");
    if (items.length === selectPaths.length) {
        items.forEach((element) => {
            element.classList.remove("selected");
            remove_selectPaths(element.getAttribute("data-path"));
        });
    } else {
        items.forEach((element) => {
            element.classList.add("selected");
            add_selectPaths(element.getAttribute("data-path"));
        });
    }

    setTimeout(updateSelectPathsContainer, 100)
});

document.getElementById("action-directory-menu").addEventListener("click", () => {
    document.getElementById("left-directory-manager").classList.toggle("show");
});