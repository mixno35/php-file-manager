const popup_window = (actions = [], callback = [], insert_element = document.body) => {
    popup_close();

    if (actions.length < 1) return;
    event.preventDefault();

    const popup_container = document.createElement("ol");
        popup_container.classList.add("popup");

    for (let i = 0; i < actions.length; i++) {
        const item = document.createElement("li");

        const item_text = document.createElement("span");
            item_text.innerText = actions[i]["name"];

        if (typeof callback[i] === "function") item.addEventListener("click", callback[i]);

        const icon = document.createElement("i");
        icon.classList.add("fa");

        if (String(actions[i]["icon"] ?? "").length > 0)
            icon.classList.add(actions[i]["icon"] ?? "fa-font-awesome");

        item.appendChild(icon);
        item.appendChild(item_text);
        popup_container.appendChild(item);
    }

    insert_element.appendChild(popup_container);

    if (!isMobileDevice()) {
        const mouseX = event.clientX;
        const mouseY = event.clientY;

        const popupWidth = popup_container.offsetWidth;
        const popupHeight = popup_container.offsetHeight;

        const left = mouseX + popupWidth <= window.innerWidth ? mouseX : window.innerWidth - popupWidth;
        const top = mouseY + popupHeight <= window.innerHeight ? mouseY : window.innerHeight - popupHeight;

        popup_container.style.left = left + "px";
        popup_container.style.top = top + "px";
    }

    console.log(actions);
}

const popup_close = (stopped = true) => {
    if (document.querySelector(".popup")) {
        document.querySelector(".popup").remove();
        if (stopped) {
            event.stopPropagation();
            event.preventDefault();
        }
    }
}