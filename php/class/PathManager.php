<?php
class PathManager {
    public function chmod_change(string $path = ""):bool {
        return chmod($path, 0777);
    }
}
