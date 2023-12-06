const run_command = () => {
    /**
     * Это обработчик всех команд, через него проходят все команды перед их выполнением. Можно конечно напрямую, но зачем изобретать велосипед...
     */
    return {
        delete: (object) => {
            if (!Array.isArray(object)) return toast().show(getStringBy("message_object_invalid"));
            if (object.length < 1) return toast().show(getStringBy("message_object_remove_empty"));

            let paths = object.join(", ");
            if (confirm(getStringBy("message_are_remove").replace("%1s", paths)))
                command(COMMAND_CREATE_REMOVE, {path: paths}, (() => {
                    updateMainFileManager();
                }));
        },
        rename: (path) => {
            const name_path = path.replace(/^.*[\\\/]/, "").trim();
            const renamed = prompt(getStringBy("hint_rename_enter_new_name").replace("%1s", path), name_path);

            if (renamed !== null) {
                if (renamed.length >= 1) {
                    if (isValidFName(renamed)) {
                        if (name_path.trim() !== renamed.trim()) {
                            const new_path = path.replace(/[^\\\/]*$/, renamed.trim());

                            command(COMMAND_CREATE_RENAME, {path: path, new_path: new_path}, updateMainFileManager);
                        } else {
                            toast().show(getStringBy("api_rename_different_from_old"));
                        }
                    } else {
                        toast().show(getStringBy("api_rename_forbidden_chars"));
                    }
                } else {
                    toast().show(getStringBy("api_rename_short_char"));
                }
            }
        },
        move: (object, path) => {
            /**
             * Array object - что перемещаем
             * String path - куда перемещаем
             */
            if (!Array.isArray(object)) return toast().show(getStringBy("message_object_invalid"));

            toast().show(object + " перемещено в " + path);
        },
        create: (path) => {
            return {
                dir: () => {
                    const name = prompt(getStringBy("text_enter_a_name_dir"));

                    if (name === null)
                        return;

                    if (name.trim().length < 1) {
                        toast().show(getStringBy("api_create_short_char"));
                        return;
                    } if (!isValidFName(name)) {
                        toast().show(getStringBy("api_create_forbidden_chars"));
                        return;
                    }

                    command(COMMAND_CREATE_DIRECTORY, {path: path, name: name}, updateMainFileManager);
                },
                file: () => {
                    const name = prompt(getStringBy("text_enter_a_name_file"));

                    if (name === null)
                        return;

                    if (name.trim().length < 1) {
                        toast().show(getStringBy("api_create_short_char"));
                        return;
                    } if (!isValidFName(name)) {
                        toast().show(getStringBy("api_create_forbidden_chars"));
                        return;
                    }

                    command(COMMAND_CREATE_FILE, {path: path, name: name}, updateMainFileManager);
                }
            }
        }
    }
}

const command = (command, data = {}, callback = () => {}, xhr_callback = null) => {
    /**
     * String command - Название команды, которую нужно выполнить
     * Array data - Все параметры, которые потребуются для выполнения команды
     * Function callback - Функция, которая будет вызвана после успешного выполнения команды
     */
    progress();

    const query = data;
    query["command"] = command; // Добавляем команду в запрос

    const xhr = typeof xhr_callback === "function" ? xhr_callback() : new window.XMLHttpRequest();

    $.ajax({
        xhr: () => { return xhr },
        url: "php/command.php",
        method: "POST",
        data: query,
        success: function (result) {
            progress();
            console.log(result);

            try {
                let json = JSON.parse(result);

                if (json["type"] === "success") {
                    if (callback !== null && typeof callback === "function") callback();
                }

                toast().show(getStringBy(json["message_id"], json["return"]));
            } catch (e) {
                toast().show(e);
                console.error(e);
            }
        }
    });
};