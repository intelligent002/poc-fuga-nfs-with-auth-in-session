<?php

// connect to session
session_start();

// Require login
if (!isset($_SESSION["status"]) || $_SESSION["status"] !== "logged in") {
    header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
    echo "Access denied, user is not logged in.";
    exit;
}

// Require ?file parameter
if (!isset($_GET["file"])) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    echo "Missing parameter: file";
    exit;
}

// Validate and resolve real path of the passed file with its path
$requested_file_with_relative_path = $_GET["file"];

// Define base directory for files
$storage = "//fuga-store/storage/";

// Prepare comparable paths
$storage = rtrim(str_replace("\\", "/", $storage), "/");

// On Windows it may get capitalized like "//FUGA-STORE/STORAGE/www/users/1/bla.txt"
// to address that we will do our checkup with strIpos in the later stage
$path = str_replace("\\", "/", realpath($storage . "/" . $requested_file_with_relative_path));

// Security check: path must exist
if ($path === false) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    echo "File not found - path empty";
    exit;
}

// Security check: path must stay inside storage
if (stripos($path, $storage) !== 0) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    echo "File not found - security violation, [$path] not in [$storage]";
    exit;
}

// Security check: path must be a real file
if (!is_file($path)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    echo "File not found - not a file";
    exit;
}

// Detect MIME type
$file_info = finfo_open(FILEINFO_MIME_TYPE);

// Fallback to generic octet-stream
$mime = finfo_file($file_info, $path)
        ?: "application/octet-stream";

// Release the finfo
finfo_close($file_info);

// Decide on Content-Disposition (inline is the default for images, attachment for downloads)
if (isset($_GET["disposition"]) && strtolower($_GET["disposition"]) === "attachment") {
    $disposition = "attachment";
} else {
    $disposition = "inline";
}

// Send headers
header("Content-Type: " . $mime);
header("Content-Length: " . filesize($path));
header("Content-Disposition: $disposition; filename=\"" . basename($path) . "\"; filename*=UTF-8''" . rawurlencode(basename($path)));
header("Cache-Control: private, max-age=0, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Clean output buffering and stream file
if (ob_get_level()) {
    ob_end_clean();
}

// do not limit the file download process by time
set_time_limit(0);

// pipe the file to client by chunks
readfile($path);

// make sure nothing stays in buffer
flush();

// we are done.
exit;
