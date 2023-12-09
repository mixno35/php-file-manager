<?php

class URLParse {

    public function is_blob_url(string $url):bool {
        return strpos($url, "blob:") === 0;
    }

    public function current_url(string $text, bool $encode = false):string {
        $parsedUrl = parse_url($text);

        if (isset($parsedUrl["path"])) {
            $path = $parsedUrl["path"];


            if ($encode) {
                $path = rawurlencode($path);
                $path = str_replace("%2F", "/", $path);
            }

            return $parsedUrl["scheme"] . "://" . $parsedUrl["host"] . $path;
        }

        return $text;
    }
}