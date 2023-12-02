const DIALOG_STYLE_MESSAGE = 0, DIALOG_STYLE_PATH = 1

const dialog = (style = DIALOG_STYLE_MESSAGE, content = [], close = true) => {
    let content_title = content[0] ?? getStringBy("title_information");
    let content_value = content[1] ?? "";
    let content_data = content[2] ?? {};

    if (content_title.length < 1 || content_title.toUpperCase() === "NULL")
        content_title = getStringBy("title_information");

    if ((style === DIALOG_STYLE_MESSAGE || style === DIALOG_STYLE_PATH) && content_value.length < 1) return;

    if (isMobileDevice()) {
        if (document.querySelector("div.dialog-container"))
            document.querySelector("div.dialog-container").remove();
    }

    const id_element = "dialog-" + generate_text(10);

    const container = document.createElement("div");
    container.classList.add("dialog-container");
    container.setAttribute("id", id_element);

    const container_title = document.createElement("p");
    container_title.classList.add("dialog-title");
    container_title.setAttribute("id", id_element + "header");

    const container_title_span = document.createElement("span");
    container_title_span.innerText = content_title;

    container_title.appendChild(container_title_span);

    if (close) {
        const container_close = document.createElement("i");
        container_close.classList.add("fa");
        container_close.classList.add("fa-times");
        container_close.classList.add("dialog-action-close");
        container_close.setAttribute("title", `${getStringBy("action_close_dialog")} (Esc)`);
        container_close.addEventListener("click", (event) => {
            event.stopPropagation();
            event.preventDefault();

            event.currentTarget.offsetParent.parentElement.remove();
        })

        container_title.appendChild(container_close);
    }

    const container_content = document.createElement("div");
    container_content.classList.add("dialog-content");

    if (style === DIALOG_STYLE_MESSAGE) {
        const message = document.createElement("p");
        message.classList.add("dialog-message");
        message.innerText = content_value;

        container_content.appendChild(message);
    } else if (style === DIALOG_STYLE_PATH) {
        container_content.classList.add("loading-content");

        $.ajax(`./dialog/${content_value}`, {
            type: METHOD_POST,
            cache: false,
            data: content_data,
            dataType: "html",
            success: (response) => {
                container_content.classList.remove("loading-content");
                container_content.appendChild(getChildNode(String(response)));
            },
            error: (xhr, json) => {
                console.error(xhr)
                console.info(json)
            }
        });
    }

    container.appendChild(container_title);
    container.appendChild(container_content);

    document.body.appendChild(container);

    if (!isMobileDevice()) dragElement(container);
}