<?php
class PathManager {
    public function chmod_detect(string $path = ""):bool {
        return chmod($path, 0777);
    }
}
