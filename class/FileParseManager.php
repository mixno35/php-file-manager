<?php
class FileParseManager {

    public function get_icon(string $path = "", bool $preview = false):string {
        $imageExtensions = ["jpg", "jpeg", "png", "gif", "bmp", "ico", "webp"]; // Изображение
        $textCodeExtensions = ["html", "php", "js", "py", "xml", "svg"]; // Код
        $textExtensions = ["txt", "csv", "log", "json", "css", "scss", "md", "htaccess"]; // Текст
        $videoExtensions = ["mp4", "3gp", "wav"]; // Видео
        $databaseExtensions = ["sql", "sqlite", "sqlite3", "sqlitedb"]; // Базы данных
        $archiveExtensions = ["zip", "rar", "7z"]; // Архивы

        if (is_file($path)) {
            if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $imageExtensions))
                return $preview ? $this->generate_image($path) : "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB9klEQVR4nO3Z30uTURwG8PM/uOmrbYF4p1kXoTfbaGNua870jMIuuvJigy66itC24Y+5SRQ10CvBqJsguunK6wqECRJoOoYs+mFyCqcjUJBw8MjLdKAR893Oe3wX54Hn/nz4ft9zLl5CZGRk/v8EfyJAGTYpA2pt19zXBeGAAYYfPA6vtj32STyCcjq82o7oagnx/Fu6bgHCEZQj4FJ0TTyCcgR0RtbEIyhngHAE5Qi4HMmIR1COgCuRjHgE5Qi4msxVRHS//L5gWIAvvSseQTkCtJRIwFHkBJhcodoiV4idXKHJHWC7WGp8pw5vIfXgx8kXJUD8BNS1UaeQLwITRlmhG1/+oD05D2VwGm33X8O3XKifl7jv8z5a775AgydRrnIrBe9S3viAQHYPF4dm0dAT/6vKzafwLm1hIHcA37MceoZX0Pv2l3EA/tXfsN6Zgck9/s82B5/AMbII972P5fpfbZ4/wL9cgOV2CibXaMUqniRs4feaEURPwAX6CGZn7Mxt8SRgD3/QhCB6AszXoprb7I7DFnp3ZgTRE9DoeFhVW1zjsJ9C9L5h4gHW/ik02YerqsU1Bkfo5DdBRQOc81lY+xNosj2oqhbnaBnhfbx+vg+ZXiUScBQ5ASZXyDi/mDR0g/AKZfALRmwEGa5zA8jIyBDD5hDLfr1cDkhKoQAAAABJRU5ErkJggg==";
            if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $textCodeExtensions))
                return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACWUlEQVR4nO3Zz0sUURwA8CHtEgZKQakIBnXZ0qiliMqEOlQsNV//G9vdqVORh/BQB0sFPeShTnqpQ3YyjCQPOrtRZrvuTmP0S8jMDrbf+H4nJ8cd2bTn2xl5X3iX7/fN8v3w3rzHsJqmQoWKrR9tsxgDGy2wEf93HOvJjEgH6DbmRTRPI5KckI8AQc3TOJiYdBC92dHQAqQjQCDgUMKUjwCBgKa4KR8BggHSESAQ0BxPyUeAQMDheEo+AgQCotenSiKO982MBBZwfnRePgIEAtYzNAX4E2oF7BBvoRPPvuH2a6+wpnM6nID9D2ZRazexrjsXTsCu2+8YEBn6FD6AbhWw0kgz4NSL78EF6FYBW14uFOXPTixy89oVE2PZJTdPc+kZCAKAGqnvzXOT0adzntqRJ18ZsLNjys1Fh+d4Lj2jl0BsOsBtvt3EyqtpbB3/4ak33n/PtYZ+y83RHJpL+foSiE0FXM79wtruGW6kwkhjy1jxFqq+9ZbrzY+/ePJnxhdcxJ6uLP+WdMCOG6+d5pMpbmh1/VK+gNsSKZ6zemWWERVJp06/VT6A4Q+gHNUJQRhfgFFGAC07XU7LW+j0mPeYbHr0mWvVPjfwyi20926ZtlCpl7ihz+L8vgHb80xgXmLwO0aH/x6jVTffcJNHV+QCd4zCGhdZLLPETRLg3OTPovmBusjAZ5x8Pu9sKyP9T41C0ACRwY8M2H0ns+HmoZyA2nvOBXfg4YdwAmo6p/kjhj5mQgkAQUNTgK2yArrAv5jWMXLCAGDjRcmIXJuNF4QBVKhQoQU2fgPLARVKM1KO1gAAAABJRU5ErkJggg==";
            if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $textExtensions))
                return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABBklEQVR4nO3Zvw4BQRAG8GtU+yT+HYmHQBQ+b6LkTqGRiEahlHgdtUNCdMh6AA8wcola7nKTsct8yfT7y87sFBsEGo3m9zN4UA+W7rBERStcnDbigL6lG8fh06pMEirHyVwUAKbDp1WN9/IIMAPEEWAE1OKDPAKMgHp0kEeAGSCOACMgjI7yCDACGtFRHgFGQGt2yYDYTZ0FtLfPTIjm8rxxEpCnAgW8ozdgtYWK5WdbyIzWVBquCpUZr78IGHsOgO8thH+eAfOhdeDDDDgBgO8tBN8BRvfASvdApugMDB19Ro3uAdI98N9DDAWQzA1wfjHlqCsbAJa6wojrwFKHDaDRaAJn8wKaFL5xkwdSHQAAAABJRU5ErkJggg==";
            if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $videoExtensions))
                return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACSUlEQVR4nO3ZyU8TURwH8DkoFVyAuhRlse5GFk0avZi4JBrc+/h3ilHZ3JClKBYQYo96US9cPDfhojFhCQe6zdIppaVA3IhGvqYzlWBihMrL6xvzvskvc/1+3pJ3GEkSERH5/9MQx1WiQyM6sN45ORgJMAe4dag0ymfm2M1R9ghCqXxmqhvHTMRQdMSyAOYIQhFQ0zjOHkEoAmo94+wRhDKAOYJQBNR5JtgjCEXAcc8EewShCHC1Ta2KOOWXA9wC6kc+skcQioBcRhKAbMQO6OIIrS9rXcETrz/DNfwFV4I/rHmJD/sXcOj5gvE9HViEO2YxwIGheewfnMO+Z2k4B9I4+mIe9ZPfrQNwDqSxt38WVX2zqPSlUPE0iQpfEq63n+DWlvgHVPWlUOlLGsXLe2ew50kCux8nUNYzDac/hQtj3/gGlBulZ5ZLO7zT2NUdx84uHTs6MxND9Zs5XFeW+ASU9SRWlI4bpbd3xGB/FENpu4aShyqKH6hw9Oo4+/4rf4C/ld52X8HWewq23JWxuS2K4naFP4BZXFtRXMkWN0sXtUZR2BKBvVvBmXcc7kDJb6stL692UWvEKF7YEsbBl0lck9f20EmsAX8qvak5DFtTCHavinMfVl91kk/AryNilg6j4E4ItuYQjrxK4YZqgXfAli298XYQG24FUepVcH50MefiJF8As/gUCpqCqBlOw639W3GSt0vcJcPRr+HiZG4vLuEFQHskAchG7IAujhA/v5hyGEWiFaLjMmOE0qDjEjWAiIiIxG1+Au9UGa/Ci951AAAAAElFTkSuQmCC";
            if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $databaseExtensions))
                return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADuElEQVR4nO2Zb0wbZQDG+eLH5e5gDFqQZX/cbGErGXEKyt291+yDZkskhA3K5hJ1xhg1UyesQGKyxSm0dxi6TCQDR++YMZtGszIFx6KSqBGctDCVLGSRzKXS3lH6aR+2PObKxp8O/dIeNdk9yS+5T2+eX94n75fLyjJjxsyDFWfvXA7xz1UQJeYS5FiT4I91EXl2UPDHRgV5dpz4tSni18KCX9OIrN0hinpHkFX9O0xkdUpQtHFBVkeJog4KcrRLkKNNRIm4nH1quX62IaX53jhH/HOdRI5NCvIcFoklMXs/irYCahLRBYgSnSRy9EOizLBpKS/4432CHF9S2lgBYYEIiDyjpCzw5MkwMiVQfmoaKQuUun/DY8enUNFxE1y3argA2xPGE74/4TjxB2wtofQI6DjuUtryO8qOX8PO96/jcXEaFb6/UNkZBtsVAXs6Aq47Av5jFeSMCiLPgigaSG8UfG8UXM8M2O6/wZ4O46nOmyj3TWNn+3XsaL2G0ncnUfLOVdhbQgn08oYIONxX4WiaZ3sS2xJMLKOkOZlxFCdhv0eLjimwPOYNNJsTSi3mhJozPCFH40TmntHmNAhsemEI2xvGV11gq3sMRYe+T13g4boLKHIFsPnQt7C9/jO2NYQME7C5g3jk8AjWvzQMy/4hWOovpUdgnkCCwroA1h8YwMbnL2Pzy8PY8uqPsB0ege3NX2A/cgX2t8dQ3BhEydEQStwTKG6aQLE7BPvRIGwNY3j0yBVsfWsUW94YwabXfsKGV35A0YvfofC5IVjrv4FlgUvGCRTW9S/i0rm4QIHrqyS+XobVNbCEQVjrl2IK3B/zBlzmhFKLOSFXhidUUHMuY89o3t5A6gLZggjLs32rLrCu6jwY0pG6AMO3QSfH+QHydvfAUn0OBUsl0iRgqR9Afs0XyN0tg3GeBM21J0ibwDyeBNnEi7W7fMh9ugvr9pxBftVZWKrPw7r3c1j3XYC1NoCC2v5FgdqLsNb2w7ovgPyaL5FX/Rnyqj5F7p4+rH2mB9m7ToHh9cLSXdqNFVgZ74rQnPgvSP+BKbAY8wY4c0IP+oS4trGMCbDSrykLrHGeyKH51k8YrvX2qgmw0m2Klc6ucfrS98uJcr63keE9jQzfNsxwbbfSLsCKtyhWGqZYqYGq7NiQZWjKPnqIIt4ymvccZHjPMYb3yAzvucxw3iDNeW4wvFejeW/8ngDFiXGaEzWK9d6gODFIc+IQxUoyxYrH6ErpIFUp7dDPNLa0GTNmsv6P+QdWCmw63bKziAAAAABJRU5ErkJggg==";
            if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $archiveExtensions))
                return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADzElEQVR4nO2Z2W9MYRjG+yeIhjkz7UyHmVGSiaWVjC2q0UEtsYUoQ9CIxFRLY21rvVCklMZWqQZBCVGaao3W0FhatVQYy5i2qj1drrjk7pH3cMSF1PteHHExb/JLznznfc7z/e6aNCYmOtGJTnT+y+lNy5vV6y1Q+7wF+BtvpuTiW5NZBGX6GN/uS8vv6vHmpYsFKMgq+CnwtdEsgi3gJYmCTrkA9+M/Bb7UmkWIBLwFEAu0p2wGl+cT/Oi9rIigTLugQyzQMt4PLsGxq9FeqoigTIugQyzQkLQaXKpGLsXbg4oIyjQIOsQCVe4McLkwYgGe55t+sMOED0cS0HXOhb4rwzXoOVycoL3T9yhTJegQC1QMmw8upY6ZeOQ3oSnXjI4yF3orhv+RT2VObYd2KVMh6BALlNnTweWINQ3B5Sa0HXdCPZ/YL7RDu5QpE3SIBUrip4LLPstkNG6wobM8kUVTjk3LlAg6xAJF5hRw2W2agLeFDnScHsbiTaFDyxQJOgwXiJQ40XbCxYJ2DRdIvfgZXCadjCBS4hJBmVRBh+EC4UNOEf+dwLuDDhGGC0ws7QKH7BsqQm0qnu4aitA+Bwvafd2qIqtSZXUQYoGxB0LgQBfp6elBw7YEvNo7lAXtUuZVq8rqIMQC7u2PwYEuQtzbbMPLXUNY0K6eczN7xAKudbXgoF/k7kYrXuTbWdCunnMxe8QCtuXXwEG/SN16K55ttf+ieYsdwRybBj3//o529ZyN2SMWUOadAwf9IgG/Fc2bEn5Rn2XFx2cBtD+t1Z5/f0e7ek5h9ogFYqedBAf9IrVr47W/h3ToN+ddLLNHLDAg5Sg4hCMd6O7uRt3GJDzw2/Ao6we3MuO1c4Ke9XPaqctN1s7DrZ9YHYRhAmcrm6GqKsL3ryKQPQbVK+M0bq6I084JetbP7+QkIdxwTTsvv/7EOIHkh3vAYXZTMVrehxCJRES0hEOY1XiY1UEYJkBMvrUT9WcKEQzUIBgM9k+gBvXl+zG1hv/9ZKMFRlVvRfUyC25mmFG5pH9oh3Ypk2ykwOjb28DFfSUb9b44EZQZLegQC7grssBlRGkm6jIsIijjFnSIBRJPrQIXZ1EGAostIiiTKOgQCzj2LwYX+465uL3QLIIyDkGHWMCeNwdcrBum4cYcRQRl7IIOsYA1Ow1c4tak4NL0wSIoYxV0iAUsmZPARVnmwZnUQSIoYxF0iAVMPk+X4vOAw+BFSTg2MVYEZRTm900+j/wfHMpSTzpXgi5TPG6gCK6AyefpVHzjZogFohOd6EQn5l/Md/K44Gfvs1xrAAAAAElFTkSuQmCC";

            return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAA3ElEQVR4nO2ZOwrCUBBF33r8FqJbULHIZDuJrbtQcDGB9JpUFtEo4wJcwEjQXsIMY5R74PZzeKd7IQAA/p/4LitiuRGLaDfdVpm7QMRytTi+WW998Jcgo+Ob9dPjS2J3zn9WwF2CDAUGaeEvQYYCw6TwlyBjAXcJMhQYJaW/BBkKjJPSX4IMBSab00eJ2f6SdVZgnj/8JchQoM0CBN7gBRgJ6UBCjIR0ICFGQjqQECMhHUiIkZAOJMRISAcSYiSkAwnxlxOKDL+YWqw2EyCWpbNEHbMszAQAAKGzPAFFG2HgXOT3rgAAAABJRU5ErkJggg==";
        } if (is_dir($path)) {
            return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABg0lEQVR4nO3YzyvDcRzH8dfN3Un+gbWShJZEkiQtSRJaLCX8EVYODlKUww7IDr4H5SJyU3Pxa34Nm18HhzH7pty/4dte8ge8D+y72bs+r3rc38/rGzAzMzMz82q2haBtIWtb4F/k1pG3LTzmLITxH8vE8PIcA72QWcNMyQOeVkBPLWOupAEPUdBr91EspldRWZKA1BLotbfdBvJ+knyYKtjn1aj7vtcaEQMuF0Av2Ts/x0946iMZcsWAxBzolexWPXk3XhSQdjALFupkvoKv2wHybqxoIO0rOUTehssepDE9Qg0gB4SoAcSA1DA1gBwwSA0gBtwMUAOIAdf91AByQB81gBhw1UsNIAYke6gB5IAgNYC0/GU3NYAc0EUNIAZcdFIDiAHnHdQAckA7NYAYcNZGDSAGnLZQA8gBzdQAYkCiiRpADDgJUAPIAY3UAGLAcT01gBxQRw0gzdn3u/mjWpYzJ+6XX4u5jeqIE/e5+cMaliMn7nOzm1XTYoCZmZmZGX65b1fjrzHebGEuAAAAAElFTkSuQmCC";
        }

        return "";
    }

    private function generate_image(string $path = ""):string {
        if (file_exists($path)) {
            // Открываем файл для чтения
            $file = fopen($path, 'rb');

            if ($file) {
                // Читаем содержимое файла
                $fileContents = fread($file, filesize($path));

                // Закрываем файл
                fclose($file);

                // Определение MIME-типа файла
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_buffer($finfo, $fileContents);
                finfo_close($finfo);

                // Генерация Blob URL
                // Вывод Blob URL
                return "data:" . $mimeType . ";base64," . base64_encode($fileContents);
            }
        }

        return $path;
    }
}