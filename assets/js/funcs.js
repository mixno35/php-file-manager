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

function itemLoadNavDirMng(_element_id = null, _container_id = null, _count = 0) {
    event.stopPropagation();
    event.preventDefault();

    popup_close();

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
                loadMainFileManager(document.getElementById(_element_id).getAttribute("data-path"), true);
            }
            clickCount = 0;
        }, 170); // Задержка для определения двойного клика
    }
}


function clickToPath(_path = "", _is_dir = false, _element_id = null) {
    event.stopPropagation();
    event.preventDefault();

    popup_close();

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
    selectPathSolo(_path, _element_id);
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

function selectPathSolo(_path, _element_id) {
    const index = selectPaths.indexOf(_path);
    if (index === -1) {
        add_selectPaths(_path);
        document.getElementById(_element_id).classList.add("selected");
    } else {
        remove_selectPaths(_path);
        document.getElementById(_element_id).classList.remove("selected");
    }
    setTimeout(() => { updateSelectPathsContainer() }, 100);
}

function add_selectPaths(path) {
    const index = selectPaths.indexOf(path);
    if (index === -1) selectPaths.push(path);
}
function remove_selectPaths(path) {
    const index = selectPaths.indexOf(path);
    if (index !== -1) selectPaths.splice(index, 1);
}

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

            // openFileDetail(_path);

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

function openFileDetail(_path = "") {
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

const toggle_grid_linear = () => {
    const container = document.getElementById("file-manager-list");
    const container_toggle = document.getElementById("file-manager-list-toggle");
    const container_toggle_icon = document.getElementById("file-manager-list-toggle-icon");

    container_toggle_icon.classList.remove("fa-border-all");
    container_toggle_icon.classList.remove("fa-bars");

    if (!isGrid) {
        container_toggle.setAttribute("title", getStringBy("tooltip_toggle_grid"));
        container_toggle_icon.classList.add("fa-border-all");
        container.classList.remove("grid");
    } else {
        container_toggle.setAttribute("title", getStringBy("tooltip_toggle_linear"));
        container_toggle_icon.classList.add("fa-bars");
        container.classList.add("grid");
    }

    isGrid = !isGrid;
}

function download(url, filename) {
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
        })
        .catch(console.error);
}