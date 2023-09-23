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

            document.getElementById("container-for-toast").appendChild(toast_container);

            setTimeout(() => {
                document.getElementById(id).remove();
            }, 5000);
        }
    }
}

function progress() {
    if (document.getElementById("progress").style.display === "flex")
        document.getElementById("progress").style.display = "none";
    else
        document.getElementById("progress").style.display = "flex";
}