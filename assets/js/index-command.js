const MOVE_TYPE_NONE = 0,
    MOVE_TYPE_COPY_PASTE = 1,
    MOVE_TYPE_CUT_PASTE = 2;

const run_command = () => {
    /**
     * Это обработчик всех команд, через него проходят все команды перед их выполнением. Можно конечно напрямую, но зачем изобретать велосипед...
     */
    return {
        delete: (object) => {
            if (!Array.isArray(object)) return toast().show(getStringBy("message_object_invalid"));
            if (object.length < 1) return toast().show(getStringBy("message_object_remove_empty"))

            let path_object = [];

            object.forEach((item) => { path_object.push(extractFileName(removeExtraSlashes(item))) });

            const formData = new FormData();
            formData.append("path", object.join(", "));

            if (confirm(getStringBy("message_are_remove").replace("%1s", path_object.join(", "))))
                command(COMMAND_CREATE_REMOVE, formData, (() => {
                    updateMainFileManager();
                }));
        },
        rename: (path) => {
            const name_path = path.replace(/^.*[\\\/]/, "").trim();
            const renamed = prompt(getStringBy("hint_rename_enter_new_name").replace("%1s", removeExtraSlashes(path)), name_path);

            if (renamed !== null) {
                if (renamed.length >= 1) {
                    if (isValidFName(renamed)) {
                        if (name_path.trim() !== renamed.trim()) {
                            const new_path = path.replace(/[^\\\/]*$/, renamed.trim());

                            const formData = new FormData();
                            formData.append("path", path);
                            formData.append("new_path", new_path);

                            command(COMMAND_CREATE_RENAME, formData, updateMainFileManager);
                        } else toast().show(getStringBy("api_rename_different_from_old"));
                    } else toast().show(getStringBy("api_rename_forbidden_chars"));
                } else toast().show(getStringBy("api_rename_short_char"));
            }
        },
        move: (object, path, type = MOVE_TYPE_NONE) => {
            /**
             * Object object - что перемещаем
             * String path - куда перемещаем
             */
            if (!Array.isArray(object)) return toast().show(getStringBy("message_object_invalid"));

            if (type === MOVE_TYPE_NONE) {
                toast().show(getStringBy("tooltip_choose_action"));

                return popup_window([
                    {name: getStringBy("tooltip_copy_type_1"), for_dir: true, for_file: true},
                    {name: getStringBy("tooltip_copy_type_2"), for_dir: true, for_file: true}
                ], [
                    () => run_command().move(object, path, MOVE_TYPE_COPY_PASTE),
                    () => run_command().move(object, path, MOVE_TYPE_CUT_PASTE)
                ]);
            }

            let path_object = [];

            object.forEach((item) => { path_object.push(extractFileName(removeExtraSlashes(item))) });

            if (confirm(getStringBy("message_are_move", [getStringBy("tooltip_copy_type_" + type).toLowerCase(), path_object.join(", "), extractFileName(removeExtraSlashes(path))]))) {
                const form_data = new FormData();
                form_data.append("object", path_object.join(", "));
                form_data.append("type", type);
                form_data.append("path", path);

                command(COMMAND_MOVE, form_data);
            }
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

                    const formData = new FormData();
                    formData.append("path", path);
                    formData.append("name", name);

                    command(COMMAND_CREATE_DIRECTORY, formData, updateMainFileManager);
                },
                file: () => {
                    // dialog(DIALOG_STYLE_PATH, [
                    //     getStringBy("action_create_new_file"),
                    //     "create-file.php",
                    //     {path: path}
                    // ]);

                    const name = prompt(getStringBy("text_enter_a_name_file"));

                    if (name === null) return;

                    if (name.trim().length < 1) {
                        toast().show(getStringBy("api_create_short_char"));
                        return;
                    } if (!isValidFName(name)) {
                        toast().show(getStringBy("api_create_forbidden_chars"));
                        return;
                    }

                    const formData = new FormData();
                    formData.append("path", path);
                    formData.append("name", name);

                    command(COMMAND_CREATE_FILE, formData, updateMainFileManager);
                }
            }
        }
    }
}

/**
 * Функция для выполнения команд
 * @param command
 * @param data
 * @param callback
 * @param xhr_callback
 * @param show_progress
 * @param callback_in_error Выводить callback даже если выполнение команды прошло с ошибкой
 */
const command = (command, data = new FormData(), callback = () => {}, xhr_callback = null, show_progress = true, callback_in_error = false) => {
    if (show_progress) progress();

    const formData = data;
    formData.append("command", command);

    const xhr = typeof xhr_callback === "function" ? xhr_callback() : new window.XMLHttpRequest();

    $.ajax({
        xhr: () => { return xhr },
        url: "php/command.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: (result) => {
            if (show_progress) progress();
            console.log(result);

            try {
                let json = JSON.parse(result);

                if (json["type"] === "success") {
                    if (callback !== null && typeof callback === "function") callback(json);
                } if (json["type"] === "error") {
                    if (callback_in_error && callback !== null && typeof callback === "function") callback(json);
                }

                toast().show(getStringBy(json["message_id"], json["return"]));
            } catch (e) {
                toast().show(e);
                console.error(e);
            }
        },
        error: (response) => {
            if (show_progress) progress();

            console.log(response);

            const json = {
                type: "error",
                message_id: response.statusText
            };

            if (callback_in_error && callback !== null && typeof callback === "function") callback(json);
        }
    });
};