<?php

class Crypt {

    private string $key;

    public function __construct(string $key) {
        $this->setKey($key);
    }

    public function encrypt(string $text):string {
        $encryptedText = "";
        $textLength = strlen($text);
        $keyLength = strlen($this->getKey());

        for ($i = 0; $i < $textLength; $i++) {
            $encryptedText .= $text[$i] ^ $this->getKey()[$i % $keyLength];
        }

        return base64_encode($encryptedText);
    }

    public function decrypt(string $text):string {
        $encryptedText = base64_decode($text);
        $decryptedText = "";
        $textLength = strlen($encryptedText);
        $keyLength = strlen($this->getKey());

        for ($i = 0; $i < $textLength; $i++) {
            $decryptedText .= $encryptedText[$i] ^ $this->getKey()[$i % $keyLength];
        }

        return $decryptedText;
    }

    private function getKey():string {
        return $this->key;
    }

    public function setKey(string $key):void {
        $this->key = $key;
    }
}