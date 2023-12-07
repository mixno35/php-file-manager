<?php

class URLParse {

    public function is_blob_url(string $url):bool {
        return strpos($url, "blob:") === 0;
    }
}