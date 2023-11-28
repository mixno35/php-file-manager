const popup_window = (actions = [], callback = [], is_dir = false, insert_element = document.body) => {
    popup_close();

    if (actions.length < 1) return;
    event.preventDefault();

    const popup_container = document.createElement("ol");
        popup_container.classList.add("popup");

    for (let i = 0; i < actions.length; i++) {
        const for_dir = actions[i]["for_dir"] ?? false;
        const for_file = actions[i]["for_file"] ?? false;

        if ((for_dir && is_dir) || (for_file && !is_dir)) {
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
    }

    insert_element.appendChild(popup_container);

    if (!isMobileDevice()) {
        element_popup_dom = event.currentTarget;
        element_popup_sticky = popup_container;

        const mouseX = event.clientX;
        const mouseY = event.clientY;

        const popupWidth = popup_container.offsetWidth;
        const popupHeight = popup_container.offsetHeight;

        const left = mouseX + popupWidth <= window.innerWidth ? mouseX : window.innerWidth - popupWidth;
        const top = mouseY + popupHeight <= window.innerHeight ? mouseY : window.innerHeight - popupHeight;

        popup_container.style.left = left + "px";
        popup_container.style.top = top + "px";
    }

    // console.log(actions);
}

const popup_close = (stopped = true) => {
    if (document.querySelector(".popup")) {
        document.querySelector(".popup").remove();

        element_popup_dom = null;
        element_popup_sticky = null;

        if (stopped) {
            event.stopPropagation();
            event.preventDefault();
        }
    }
}