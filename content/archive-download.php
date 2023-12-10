<?php
if (version_compare(PHP_VERSION, "8.1.0") === -1) {
    http_response_code(404);
    exit();
}

global $host;

include_once dirname(__FILE__, 2) . "/php/data.php";

include_once dirname(__FILE__, 2) . "/php/class/CheckSession.php";
include_once dirname(__FILE__, 2) . "/php/class/Crypt.php";

$check_session = new CheckSession(new Crypt(READY_KEY));

if (!$check_session->check()) {
    http_response_code(403);
    exit();
}

global $privileges;

include_once dirname(__FILE__, 2) . "/secure/user-privileges.php"; // Загружаем привилегии пользователя

if (!$privileges["view_file"]) {
    http_response_code(403);
    exit();
}

$paths = json_decode(rawurldecode($_GET["obj"] ?? "[]"), true);

$archiveName = $host . "_" . date("Y-m-d_H-i-s") . "_" . sizeof($paths) . ".zip";

use ZipStream\ZipStream, ZipStream\OperationMode, ZipStream\CompressionMethod, ZipStream\Exception\OverflowException,
    ZipStream\Exception\FileNotFoundException, ZipStream\Exception\FileNotReadableException;

include_once dirname(__FILE__, 2) . "/class/ZipStream/ZipStream.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Version.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Time.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/PackField.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/OperationMode.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/LocalFileHeader.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/GeneralPurposeBitFlag.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/File.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/EndOfCentralDirectory.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/DataDescriptor.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/CompressionMethod.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/CentralDirectoryFileHeader.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Zs/ExtendedInformationExtraField.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Zip64/ExtendedInformationExtraField.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Zip64/EndOfCentralDirectoryLocator.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Zip64/EndOfCentralDirectory.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Zip64/DataDescriptor.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/StreamNotSeekableException.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/StreamNotReadableException.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/SimulationFileUnknownException.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/ResourceActionException.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/OverflowException.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/FileSizeIncorrectException.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/FileNotReadableException.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/FileNotFoundException.php";
include_once dirname(__FILE__, 2) . "/class/ZipStream/Exception/DosTimeOverflowException.php";

include_once dirname(__FILE__, 2) . "/class/FileManager.php";

$file_manager = new FileManager();

$zip = new ZipStream(
    OperationMode::NORMAL,
    "StormGuardian Files | $archiveName | by. ZipStream",
    null,
    CompressionMethod::DEFLATE,
    6,
    true,
    true,
    true,
    null,
    $archiveName
);

foreach ($paths as $path) {
    add_to_archive($path, $zip, $file_manager);
}

try {
    $zip->finish();
} catch (OverflowException $e) { print_r($e); }

function add_to_archive(string $path, ZipStream $zip, FileManager $file_manager):void {
    if (is_file($path)) {
        $relativePath = mb_convert_encoding(sanitizeFileName(basename($path)), "CP866", "UTF-8");
        try {
            $zip->addFileFromPath($relativePath, $path);
        } catch (FileNotFoundException | FileNotReadableException $e) { print_r($e); }
    } else if (is_dir($path)) {
        $zip->addDirectory(basename($path));
        $new_paths = array_merge($file_manager->get_files($path), $file_manager->get_dirs($path));
        foreach ($new_paths as $new_path) {
            add_to_archive($new_path, $zip, $file_manager);
        }
    }
}

function sanitizeFileName(string $filename):string {
    return preg_replace("/[\/\?<>\\\*:\|\":]/", "_", $filename);
}