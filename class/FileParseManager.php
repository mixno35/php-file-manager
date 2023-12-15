<?php
class FileParseManager {
    private array $extensions_image = ["jpeg", "jpg", "png", "gif", "bmp", "tiff", "webp", "svg", "eps", "raw", "ico"]; // Изображение
    private array $extensions_image_non_preview = ["tiff", "svg", "eps", "raw", "ico"];
    private array $extensions_text_code_simple = ["html", "xml", "jsx", "tsx", "vue"];
    private array $extensions_text_code = ["js", "css", "php", "py", "java", "cpp", "cs", "rb", "go", "swift", "ts", "scss", "sass", "json", "yaml", "md", "sql", "perl", "lua", "groovy", "rust", "h", "m", "r", "kt", "gradle", "sh", "bash", "powershell", "dart", "asm", "bat", "ini", "cfg", "toml", "yml", "pl", "coffee", "clj", "cljs", "rkt"]; // Код
    private array $extensions_text = ["csv", "log", "json"]; // Текст
    private array $extensions_video = ["mp4", "avi", "mkv", "wmv", "mov", "flv", "3gp", "webm", "mpeg", "rm"]; // Видео
    private array $extensions_database = ["db", "sqlite", "db3", "sql", "mdf", "ndf", "rdb"]; // База данных
    private array $extensions_archive = ["zip", "rar", "tar", "tar.gz", "tar.bz2", "7z", "gz", "bz2"]; // Архив
    private array $extensions_images = ["iso", "bin", "cue", "img", "nrg", "dmg"]; // Образ
    private array $extensions_font = ["ttf", "otf", "woff", "woff2", "eot"]; // Шрифт
    private array $extensions_music = ["mp3", "wav", "flac", "aac", "ogg", "wma", "m4a", "opus", "amr"]; // Музыка

    public function is_image($path):bool {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_image);
    }
    public function is_text_code($path):bool {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_text_code);
    }
    public function is_text_code_simple($path):bool {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_text_code_simple);
    }
    public function is_text($path):bool {
        return
            (explode("/", (mime_content_type($path) ?? "mime/type")))[0] === "text" ||
            in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_text);
    }
    public function is_video($path):bool {
        return
            (explode("/", (mime_content_type($path) ?? "mime/type")))[0] === "video" ||
            in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_video);
    }
    public function is_database($path):bool {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_database);
    }
    public function is_archive($path):bool {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_archive);
    }
    public function is_images($path):bool {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_images);
    }
    public function is_font($path):bool {
        return
            (explode("/", (mime_content_type($path) ?? "mime/type")))[0] === "font" ||
            in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_font);
    }
    public function is_music($path):bool {
        return
            (explode("/", (mime_content_type($path) ?? "mime/type")))[0] === "audio" ||
            in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $this->extensions_music);
    }

    public function get_file_type($path):string {
        return (explode("/", (mime_content_type($path) ?? "mime/type")))[0];
    }

    public function get_icon(string $path, bool $preview = false, int $size = 0):string {
        if (is_file($path)) {
            if ($this->is_image($path)) return $preview ? $this->generate_image($path, $size) : "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB9klEQVR4nO3Z30uTURwG8PM/uOmrbYF4p1kXoTfbaGNua870jMIuuvJigy66itC24Y+5SRQ10CvBqJsguunK6wqECRJoOoYs+mFyCqcjUJBw8MjLdKAR893Oe3wX54Hn/nz4ft9zLl5CZGRk/v8EfyJAGTYpA2pt19zXBeGAAYYfPA6vtj32STyCcjq82o7oagnx/Fu6bgHCEZQj4FJ0TTyCcgR0RtbEIyhngHAE5Qi4HMmIR1COgCuRjHgE5Qi4msxVRHS//L5gWIAvvSseQTkCtJRIwFHkBJhcodoiV4idXKHJHWC7WGp8pw5vIfXgx8kXJUD8BNS1UaeQLwITRlmhG1/+oD05D2VwGm33X8O3XKifl7jv8z5a775AgydRrnIrBe9S3viAQHYPF4dm0dAT/6vKzafwLm1hIHcA37MceoZX0Pv2l3EA/tXfsN6Zgck9/s82B5/AMbII972P5fpfbZ4/wL9cgOV2CibXaMUqniRs4feaEURPwAX6CGZn7Mxt8SRgD3/QhCB6AszXoprb7I7DFnp3ZgTRE9DoeFhVW1zjsJ9C9L5h4gHW/ik02YerqsU1Bkfo5DdBRQOc81lY+xNosj2oqhbnaBnhfbx+vg+ZXiUScBQ5ASZXyDi/mDR0g/AKZfALRmwEGa5zA8jIyBDD5hDLfr1cDkhKoQAAAABJRU5ErkJggg==";
            if ($this->is_text_code_simple($path) or $this->is_text_code($path)) return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACWUlEQVR4nO3Zz0sUURwA8CHtEgZKQakIBnXZ0qiliMqEOlQsNV//G9vdqVORh/BQB0sFPeShTnqpQ3YyjCQPOrtRZrvuTmP0S8jMDrbf+H4nJ8cd2bTn2xl5X3iX7/fN8v3w3rzHsJqmQoWKrR9tsxgDGy2wEf93HOvJjEgH6DbmRTRPI5KckI8AQc3TOJiYdBC92dHQAqQjQCDgUMKUjwCBgKa4KR8BggHSESAQ0BxPyUeAQMDheEo+AgQCotenSiKO982MBBZwfnRePgIEAtYzNAX4E2oF7BBvoRPPvuH2a6+wpnM6nID9D2ZRazexrjsXTsCu2+8YEBn6FD6AbhWw0kgz4NSL78EF6FYBW14uFOXPTixy89oVE2PZJTdPc+kZCAKAGqnvzXOT0adzntqRJ18ZsLNjys1Fh+d4Lj2jl0BsOsBtvt3EyqtpbB3/4ak33n/PtYZ+y83RHJpL+foSiE0FXM79wtruGW6kwkhjy1jxFqq+9ZbrzY+/ePJnxhdcxJ6uLP+WdMCOG6+d5pMpbmh1/VK+gNsSKZ6zemWWERVJp06/VT6A4Q+gHNUJQRhfgFFGAC07XU7LW+j0mPeYbHr0mWvVPjfwyi20926ZtlCpl7ihz+L8vgHb80xgXmLwO0aH/x6jVTffcJNHV+QCd4zCGhdZLLPETRLg3OTPovmBusjAZ5x8Pu9sKyP9T41C0ACRwY8M2H0ns+HmoZyA2nvOBXfg4YdwAmo6p/kjhj5mQgkAQUNTgK2yArrAv5jWMXLCAGDjRcmIXJuNF4QBVKhQoQU2fgPLARVKM1KO1gAAAABJRU5ErkJggg==";
            if ($this->is_text($path)) return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABBklEQVR4nO3Zvw4BQRAG8GtU+yT+HYmHQBQ+b6LkTqGRiEahlHgdtUNCdMh6AA8wcola7nKTsct8yfT7y87sFBsEGo3m9zN4UA+W7rBERStcnDbigL6lG8fh06pMEirHyVwUAKbDp1WN9/IIMAPEEWAE1OKDPAKMgHp0kEeAGSCOACMgjI7yCDACGtFRHgFGQGt2yYDYTZ0FtLfPTIjm8rxxEpCnAgW8ozdgtYWK5WdbyIzWVBquCpUZr78IGHsOgO8thH+eAfOhdeDDDDgBgO8tBN8BRvfASvdApugMDB19Ro3uAdI98N9DDAWQzA1wfjHlqCsbAJa6wojrwFKHDaDRaAJn8wKaFL5xkwdSHQAAAABJRU5ErkJggg==";
            if ($this->is_video($path)) return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACSUlEQVR4nO3ZyU8TURwH8DkoFVyAuhRlse5GFk0avZi4JBrc+/h3ilHZ3JClKBYQYo96US9cPDfhojFhCQe6zdIppaVA3IhGvqYzlWBihMrL6xvzvskvc/1+3pJ3GEkSERH5/9MQx1WiQyM6sN45ORgJMAe4dag0ymfm2M1R9ghCqXxmqhvHTMRQdMSyAOYIQhFQ0zjOHkEoAmo94+wRhDKAOYJQBNR5JtgjCEXAcc8EewShCHC1Ta2KOOWXA9wC6kc+skcQioBcRhKAbMQO6OIIrS9rXcETrz/DNfwFV4I/rHmJD/sXcOj5gvE9HViEO2YxwIGheewfnMO+Z2k4B9I4+mIe9ZPfrQNwDqSxt38WVX2zqPSlUPE0iQpfEq63n+DWlvgHVPWlUOlLGsXLe2ew50kCux8nUNYzDac/hQtj3/gGlBulZ5ZLO7zT2NUdx84uHTs6MxND9Zs5XFeW+ASU9SRWlI4bpbd3xGB/FENpu4aShyqKH6hw9Oo4+/4rf4C/ld52X8HWewq23JWxuS2K4naFP4BZXFtRXMkWN0sXtUZR2BKBvVvBmXcc7kDJb6stL692UWvEKF7YEsbBl0lck9f20EmsAX8qvak5DFtTCHavinMfVl91kk/AryNilg6j4E4ItuYQjrxK4YZqgXfAli298XYQG24FUepVcH50MefiJF8As/gUCpqCqBlOw639W3GSt0vcJcPRr+HiZG4vLuEFQHskAchG7IAujhA/v5hyGEWiFaLjMmOE0qDjEjWAiIiIxG1+Au9UGa/Ci951AAAAAElFTkSuQmCC";
            if ($this->is_database($path)) return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADuElEQVR4nO2Zb0wbZQDG+eLH5e5gDFqQZX/cbGErGXEKyt291+yDZkskhA3K5hJ1xhg1UyesQGKyxSm0dxi6TCQDR++YMZtGszIFx6KSqBGctDCVLGSRzKXS3lH6aR+2PObKxp8O/dIeNdk9yS+5T2+eX94n75fLyjJjxsyDFWfvXA7xz1UQJeYS5FiT4I91EXl2UPDHRgV5dpz4tSni18KCX9OIrN0hinpHkFX9O0xkdUpQtHFBVkeJog4KcrRLkKNNRIm4nH1quX62IaX53jhH/HOdRI5NCvIcFoklMXs/irYCahLRBYgSnSRy9EOizLBpKS/4432CHF9S2lgBYYEIiDyjpCzw5MkwMiVQfmoaKQuUun/DY8enUNFxE1y3argA2xPGE74/4TjxB2wtofQI6DjuUtryO8qOX8PO96/jcXEaFb6/UNkZBtsVAXs6Aq47Av5jFeSMCiLPgigaSG8UfG8UXM8M2O6/wZ4O46nOmyj3TWNn+3XsaL2G0ncnUfLOVdhbQgn08oYIONxX4WiaZ3sS2xJMLKOkOZlxFCdhv0eLjimwPOYNNJsTSi3mhJozPCFH40TmntHmNAhsemEI2xvGV11gq3sMRYe+T13g4boLKHIFsPnQt7C9/jO2NYQME7C5g3jk8AjWvzQMy/4hWOovpUdgnkCCwroA1h8YwMbnL2Pzy8PY8uqPsB0ege3NX2A/cgX2t8dQ3BhEydEQStwTKG6aQLE7BPvRIGwNY3j0yBVsfWsUW94YwabXfsKGV35A0YvfofC5IVjrv4FlgUvGCRTW9S/i0rm4QIHrqyS+XobVNbCEQVjrl2IK3B/zBlzmhFKLOSFXhidUUHMuY89o3t5A6gLZggjLs32rLrCu6jwY0pG6AMO3QSfH+QHydvfAUn0OBUsl0iRgqR9Afs0XyN0tg3GeBM21J0ibwDyeBNnEi7W7fMh9ugvr9pxBftVZWKrPw7r3c1j3XYC1NoCC2v5FgdqLsNb2w7ovgPyaL5FX/Rnyqj5F7p4+rH2mB9m7ToHh9cLSXdqNFVgZ74rQnPgvSP+BKbAY8wY4c0IP+oS4trGMCbDSrykLrHGeyKH51k8YrvX2qgmw0m2Klc6ucfrS98uJcr63keE9jQzfNsxwbbfSLsCKtyhWGqZYqYGq7NiQZWjKPnqIIt4ymvccZHjPMYb3yAzvucxw3iDNeW4wvFejeW/8ngDFiXGaEzWK9d6gODFIc+IQxUoyxYrH6ErpIFUp7dDPNLa0GTNmsv6P+QdWCmw63bKziAAAAABJRU5ErkJggg==";
            if ($this->is_archive($path)) return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADzElEQVR4nO2Z2W9MYRjG+yeIhjkz7UyHmVGSiaWVjC2q0UEtsYUoQ9CIxFRLY21rvVCklMZWqQZBCVGaao3W0FhatVQYy5i2qj1drrjk7pH3cMSF1PteHHExb/JLznznfc7z/e6aNCYmOtGJTnT+y+lNy5vV6y1Q+7wF+BtvpuTiW5NZBGX6GN/uS8vv6vHmpYsFKMgq+CnwtdEsgi3gJYmCTrkA9+M/Bb7UmkWIBLwFEAu0p2wGl+cT/Oi9rIigTLugQyzQMt4PLsGxq9FeqoigTIugQyzQkLQaXKpGLsXbg4oIyjQIOsQCVe4McLkwYgGe55t+sMOED0cS0HXOhb4rwzXoOVycoL3T9yhTJegQC1QMmw8upY6ZeOQ3oSnXjI4yF3orhv+RT2VObYd2KVMh6BALlNnTweWINQ3B5Sa0HXdCPZ/YL7RDu5QpE3SIBUrip4LLPstkNG6wobM8kUVTjk3LlAg6xAJF5hRw2W2agLeFDnScHsbiTaFDyxQJOgwXiJQ40XbCxYJ2DRdIvfgZXCadjCBS4hJBmVRBh+EC4UNOEf+dwLuDDhGGC0ws7QKH7BsqQm0qnu4aitA+Bwvafd2qIqtSZXUQYoGxB0LgQBfp6elBw7YEvNo7lAXtUuZVq8rqIMQC7u2PwYEuQtzbbMPLXUNY0K6eczN7xAKudbXgoF/k7kYrXuTbWdCunnMxe8QCtuXXwEG/SN16K55ttf+ieYsdwRybBj3//o529ZyN2SMWUOadAwf9IgG/Fc2bEn5Rn2XFx2cBtD+t1Z5/f0e7ek5h9ogFYqedBAf9IrVr47W/h3ToN+ddLLNHLDAg5Sg4hCMd6O7uRt3GJDzw2/Ao6we3MuO1c4Ke9XPaqctN1s7DrZ9YHYRhAmcrm6GqKsL3ryKQPQbVK+M0bq6I084JetbP7+QkIdxwTTsvv/7EOIHkh3vAYXZTMVrehxCJRES0hEOY1XiY1UEYJkBMvrUT9WcKEQzUIBgM9k+gBvXl+zG1hv/9ZKMFRlVvRfUyC25mmFG5pH9oh3Ypk2ykwOjb28DFfSUb9b44EZQZLegQC7grssBlRGkm6jIsIijjFnSIBRJPrQIXZ1EGAostIiiTKOgQCzj2LwYX+465uL3QLIIyDkGHWMCeNwdcrBum4cYcRQRl7IIOsYA1Ow1c4tak4NL0wSIoYxV0iAUsmZPARVnmwZnUQSIoYxF0iAVMPk+X4vOAw+BFSTg2MVYEZRTm900+j/wfHMpSTzpXgi5TPG6gCK6AyefpVHzjZogFohOd6EQn5l/Md/K44Gfvs1xrAAAAAElFTkSuQmCC";
            if ($this->is_images($path)) return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFfklEQVR4nO2Z209cVRTGh3qpRvESojyIwJy91rfOzAgkndRKy60tVCxYKnSg99ICYlqJ9qEPxgcf+uglMV6IrSYaE1+ML/6F5oN1zDgF5pyZQzWRnRACM2ef395nrW99e51C4WAcjIORx+goFovdqjoUQpgws3dVdd7MFgDMAZhW1VF+bmb9ZtZZ+C+Mcrn8fBzHwyJyBcCKqt4AcFVVL5pZTVXfM7NzqnrWzN4GcNrMJgCMhBCqItJTLpeffuzgPT09z5rZSTP7QFXfbwF+WFXfDCEcMbOBEMLr1Wr1qccCDyBW1TUAt9uFj+P4Df7m36p6LIqiV/eT/ZCqTqrqRh7wzIf+/v4hAEfN7LiIjPO7pVKpwpzKlZyPF8D5POEJCuAoc0hVxwCc4jWqOsP/1Wq1J/LiP5Q3fAihUqlUhkTkLaoT4UXkDOGpWiJygTnGe7dNn3fYEF5VS4QHMEJQEZnitQ6/wHk5P6/NI2FzheecAAYdfoLwAN7xOeYBLJnZFVVdpliUy2VpCb6vr+8ZAKt5w0dRpABOMGlVdZLwIrJV+AAsOjzvs+oyvUbZzrwAPtq84UMIQt1PFAfAtIjM+lw1Ebns91kREcJ/COAuv5u5wuZRpBrhK5VKSJKW14nILAXC57ykqtfN7JaqrgO4A+BjVb1nZusicjj1AlzacoUHUHTp/IfimNkFwgO45vC87x0z+4h/M0d8vtfS8ne06G224PnDuHaolRDCWqlUoqJQ4ycd/hHFEZGbSYUPIVzzHBlKKjW9Uyp6uso2dv4krwshrHInGceeRxshhE9E5HPuKuchPIAlv8cyk5aJHMdx1aW2Hn7YVau5i+WFrcKLyK2d4BnLhFfV78zsoSfnluIwdDhvFEUDHm67wdOm96dVn8xhs9vOM5ZV9VNV/VZVHwL4RVV/DSEwYQllniN7wvO+/KzpAuI4ns2asIz5engqGH1OV1dXJxUthDANYBPAz4QH8AM3CUAxLbz7pNE0ITSfVW1cAv/eecI3zhtCOA/ga1X9kk8DwP2M8Ez86TQhtJBVKkVkpT5suru7n2ucV0ReqIN/4OEUp4UnD5Wr6QJcm7Pq/Gp9zDNsGuft7e19OYFnHjCcQgZ439SFNAuYzlqkqPOJ2gD4LIqimcZ5PQQS+N88qYcywG+FddMF8HCRtcJy0QT3HWaCbtKgRVH0Yl9f30sOv5nAi8gf9DghG/xFCkyaBQxl2Hk6yxE3Yd8TnrGtqj+q6lf1Md8A/zuv0wzwXq3Hmy7A+zbN4CulUom24KoXo0URue1F6icuAMAXu8D/SauQFV63i+tgmgV07gYfx/ERhoaX/WXX8iXaAr/pmog88PjecefbgF8RkVcKaQaNUwP8cAhh0ZWGkDeTau2eZo43p1GL4/iMm8H7SeUVkW/c14+0AX85dbeCHTPCs03InaY00uKyWNGMuX+5RCvs8DOEd6tcbqHCnt0Lnvdl3ygVvC/gsB9o7rk0En7d/fp1h6/xMMJDiavWKZb6gYGBKG94M1vfqbbsOfy8epfO0avslsXmo0w02U9UlNDTVAhvE+YKj+2wHSu0eqh3Y8akvZEoDv2SJzMPLpN+xj1RLLIk5A6/RpZCK4MtDU7geUD4JTd757ybMJVIrrdK8obfYK0ptDMcYCfFmfKzw4h32Ep5w9t2dy6Xpu4Y232NisOkJSTbhHnDY9t5tt9a5GCj1TsVM3WKM8b/0fuz25A3/Pj4+JOFnEeHa3yiOMcJzxb5PoTNocJ+Db6ESM7BjS8p2oEHsNp2wqYdfLx8LeS6326FXWc4tiyV7Qy+oHPbUW3F26jqscHBwUeOn//KoIulFfdD/qhX6Dk/Y89TAOitWC/cVeb7GulgHIz/6fgLVGuzcyIKLc0AAAAASUVORK5CYII=";
            if ($this->is_font($path)) return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADW0lEQVR4nO2Wy08TQRyAJ+rVBKMXE95CKfTBY2fxYjAm/gMePOkf4MGYePFk4IQ8SqGUUoRCu1vihcTEaMJDHoWW0toHdGd7Ih4MB0M8eSNB3TG7S5bushSz0nWI/ZLvOOn3m06bHwBlypT5/5iFPB2G/BBL88kwnf/G0rwQpvP4z+WLysJjGYhi5xbO3EYUC/lEKULZIp5LfLiTf8HC/I9Sx7KlGCAM8z1mhLKlGIDp5O+xNBLMCGVVIkkGoqjh+LmHc5dZiHZ1P4BCqRCNHrFUvjpyN3Kl8FwPwJcYit/XO8dA9BiYBQvRff0I3i0Od9o5hua6TrnZwzcOdM3MAQIn4il+XbzhYufEAQufgfIcKLRsWrwIQ/HpEwPQ6AE4A5YSnx3SG+ApMBMGoq/am5zt5CqLnmlHTt14yAlByFeZ/A2gn9oQ7Q9WCwtRt/7tc2lgNgxEMfUtnv2XxkCU1TybI7mXgHRmO7nKEMUJx9HHhmHODkgnRKFnR7etMkTlPoOLAENxq9p4eQDOBUgn1LpTwVDcod4ALJW7A0iD6cjF9WJVN9+RO2n7zvnt93+D5o3rx3bkcFByR7ZdFpBASDf2OFLPmfZtSUACwY7ikTNtpwtIINi2HTsteLotq2+rpPH9vhSIm+l0W3a/IFAx0Jo1b/c3yrQj2xVozWCVzgwOODKHfjN3f6MEHBm3GDzlTGs1d/c3ypQjvSsFO2QnHSlZe9rc3d8IAXvCqQQr4Sk8YU8K47aUubu/ESbsye7X9k9YbRL7WxLm7/5G8NuS2QlbEov6bQkxXLY5Tv7u72vZahhv2RLGW7awZLNoHPua49hvjZO/+/usm0Ex1mfdlBxTjJK/+481bT7xWqOCtymGZaOSo5Yo9liirwCJjNREKkYaN7pGLdG3HsuGIMaOWjYkPY2i69jTGPnltWzUAVLwWqJxVagqdh2PiDZEJIcbItjdsDYJSMKjEyrHruHhW7JuyVXsrl/dG7MuXwckMVwQq4TKsXiofkXRVbvyfbBuxQlIwy2FFsTWrWBX3bLiYO1HPFCz9MVVv0Tm36arIFSKrV0SgyX7qxcO+qsXhgea3l0FpDJQsxjrr1nEon1V8wd91fN7vVXzH3qr5p8P3Xx/41/3lSlTBlx8fgNZ1QB8uxiAwAAAAABJRU5ErkJggg==";
            if ($this->is_music($path)) return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABkklEQVR4nO2WO07DQBCGVyByBxK4QFq8myvwiAMihNyAA8BM2rS8bgGChkNQIIQC1AmENEmZ2YJHv2jzAAmUgLHx2NJ+0jSWbf3f/rbXQjgcjn9He7uLWuKFlvCiFRo7l/Or5iq7Zq6zRXOT801jNLcfUzJ3Wb/XyBVX2MOTRD0OHkggZ8fv8grYlf8SPphAyTALfD426RRQ38M7gXQ14HdTLOB37xf85UQKiLSgnQAzrgFuXAPcuAa4cQ1wk+gGTL6e0R5WScKZVtDSCt+GA63BMbm3nVgBKkCZFHQmBfxp2IKbSmWWFBz/NTi7AEUQnk2AClCOIjyLgMnXM6TwKbUC2sNqVOFZBEjieaoFtITHtAu8OgE1akDic/wNKHyIsIFm7AI0+N+JRoAUnsQuoKf8mAUXqG3FLmCWdua0hHb48NCxm6LggLzaZliBvoJ1lvBjSOJRiNXfF9wYUZ8hCYeBw0s8sNeKpNBXuPGrd0JCm/2xmfpiK6yQglP7bR/s1sMdu2mP2a+NPWfiDRwOhwjLO8LtdhP97PxCAAAAAElFTkSuQmCC";

            return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAA3ElEQVR4nO2ZOwrCUBBF33r8FqJbULHIZDuJrbtQcDGB9JpUFtEo4wJcwEjQXsIMY5R74PZzeKd7IQAA/p/4LitiuRGLaDfdVpm7QMRytTi+WW998Jcgo+Ob9dPjS2J3zn9WwF2CDAUGaeEvQYYCw6TwlyBjAXcJMhQYJaW/BBkKjJPSX4IMBSab00eJ2f6SdVZgnj/8JchQoM0CBN7gBRgJ6UBCjIR0ICFGQjqQECMhHUiIkZAOJMRISAcSYiSkAwnxlxOKDL+YWqw2EyCWpbNEHbMszAQAAKGzPAFFG2HgXOT3rgAAAABJRU5ErkJggg==";
        } if (is_dir($path)) {
            return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABg0lEQVR4nO3YzyvDcRzH8dfN3Un+gbWShJZEkiQtSRJaLCX8EVYODlKUww7IDr4H5SJyU3Pxa34Nm18HhzH7pty/4dte8ge8D+y72bs+r3rc38/rGzAzMzMz82q2haBtIWtb4F/k1pG3LTzmLITxH8vE8PIcA72QWcNMyQOeVkBPLWOupAEPUdBr91EspldRWZKA1BLotbfdBvJ+knyYKtjn1aj7vtcaEQMuF0Av2Ts/x0946iMZcsWAxBzolexWPXk3XhSQdjALFupkvoKv2wHybqxoIO0rOUTehssepDE9Qg0gB4SoAcSA1DA1gBwwSA0gBtwMUAOIAdf91AByQB81gBhw1UsNIAYke6gB5IAgNYC0/GU3NYAc0EUNIAZcdFIDiAHnHdQAckA7NYAYcNZGDSAGnLZQA8gBzdQAYkCiiRpADDgJUAPIAY3UAGLAcT01gBxQRw0gzdn3u/mjWpYzJ+6XX4u5jeqIE/e5+cMaliMn7nOzm1XTYoCZmZmZGX65b1fjrzHebGEuAAAAAElFTkSuQmCC";
        }

        return "";
    }

    private function generate_image(string $path, int $size = 0):string {
        if (file_exists($path) || is_readable($path)) {
            $file = fopen($path, "rb");
            $format = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            if ($file) {
                $fileContents = fread($file, filesize($path));
                fclose($file);

                if ($size > 0 and !in_array($format, $this->extensions_image_non_preview)) {
                    $image = imagecreatefromstring($fileContents);
                    $originalWidth = imagesx($image);
                    $originalHeight = imagesy($image);

                    $aspectRatio = $originalWidth / $originalHeight;
                    $newWidth = $size;
                    $newHeight = round($newWidth / $aspectRatio);

                    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

                    ob_start();
                    if ($format === "webp") imagewebp($resizedImage);
                    else if ($format === "jpg" || $format === "jpeg") imagejpeg($resizedImage);
                    else if ($format === "gif") imagegif($resizedImage);
                    else if ($format === "bmp") imagebmp($resizedImage);
                    else imagepng($resizedImage);
                    $fileContents = ob_get_clean();
                    imagedestroy($resizedImage);
                }

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_buffer($finfo, $fileContents);
                finfo_close($finfo);

                return "data:" . $mimeType . ";base64," . base64_encode($fileContents);
            }
        }

        return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAA3ElEQVR4nO2ZOwrCUBBF33r8FqJbULHIZDuJrbtQcDGB9JpUFtEo4wJcwEjQXsIMY5R74PZzeKd7IQAA/p/4LitiuRGLaDfdVpm7QMRytTi+WW998Jcgo+Ob9dPjS2J3zn9WwF2CDAUGaeEvQYYCw6TwlyBjAXcJMhQYJaW/BBkKjJPSX4IMBSab00eJ2f6SdVZgnj/8JchQoM0CBN7gBRgJ6UBCjIR0ICFGQjqQECMhHUiIkZAOJMRISAcSYiSkAwnxlxOKDL+YWqw2EyCWpbNEHbMszAQAAKGzPAFFG2HgXOT3rgAAAABJRU5ErkJggg==";
    }
}