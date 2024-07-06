<?php
namespace class\manager;

class PathManager {

    const ROOT_DIR_SITE = 0, ROOT_DIR_FILE_MANAGER = 1, ROOT_DIR_FILE = 2;

    /**
     * Возвращает путь к корневому каталогу в зависимости от уровня.
     * @param int $level Уровень корневого каталога. Может быть `self::ROOT_DIR_SITE`, `self::ROOT_DIR_FILE_MANAGER` или уровень исполняемого файла.
     * @return string Путь к корневому каталогу.
     */
    public function root_dir(int $level = self::ROOT_DIR_SITE): string {
        if ($level === self::ROOT_DIR_SITE) {
            return dirname(__FILE__, 5);
        } else if ($level === self::ROOT_DIR_FILE_MANAGER) {
            return dirname(__FILE__, 4);
        } else {
            return dirname(__FILE__);
        }
    }

    /**
     * Защищает корневой каталог файлового менеджера от изменений.
     * @return void
     */
    public function protect_file_manager(): void {
        if (str_contains($this->root_dir(self::ROOT_DIR_FILE), $this->root_dir(self::ROOT_DIR_FILE_MANAGER))) {
            if (function_exists("str_lang_string")) {
                die(str_lang_string("php_file_manager_root"));
            } else {
                die("Вы не можете изменить корень файлового менеджера");
            }
        }
    }

}