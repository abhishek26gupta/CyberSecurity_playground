<?php
session_start();

// 1. Missing Parameter Check
if (!isset($_GET['debug'])) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Missing required system parameter"
    ]);
    exit();
}

// 2. Incorrect Value Check
if ($_GET['debug'] !== 'true') {
    header('Content-Type: application/json');
    http_response_code(422);
    echo json_encode([
        "status" => "error",
        "message" => "Incorrect value for parameter 'debug'."
    ]);
    exit();
}

header('Content-Type: application/json');

$debug_data = [
    "system_info" => [
        "hostname" => "SECURE-CORP-SRV-01",
        "os" => "Windows NT 10.0; Win64; x64",
        "php_version" => phpversion(),
        "server_root" => "C:\\Users\\Abhishek\\SecureCorp_Intranet\\",
        "internal_ip" => "10.0.5.12"
    ],
    "environment_vars" => [
        "DB_CONNECTION" => "sqlite",
        "DB_DATABASE" => "internal_storage.db",
        "APP_ENV" => "local",
        "APP_DEBUG" => "true",
        "SESSION_DRIVER" => "file",
        "TMP_DIR" => "C:\\Windows\\Temp\\php_uploads\\"
    ],
    "internal_logs" => [
        "11:02:01 - CRITICAL: session_start() called before headers sent in legacy_lib.php",
        "11:02:05 - INFO: SQLite connection successful.",
        "11:02:10 - WARN: Brute force detection triggered for IP: 127.0.0.1",
        "11:02:12 - NOTICE: Garbage collection (GC) cleanup: 42 session files removed.",
        "11:02:15 - DEBUG: Developer mode bypass active. Skipping WAF checks.",
        "11:02:18 - TRACE: Rendering view dashboard.php for abhishek",
        "11:02:22 - ERROR: Socket timeout while fetching avatar from http://localhost:8080/api/v1/user",
        "11:02:25 - INFO: Maintenance task 'db_vacuum' completed in 14ms.",
        "11:02:30 - SEC_ALERT: Unencrypted flag found in memory buffer: FLAG{DEBUG_LOG_SENSITIVE_LEAK}",
        "11:02:20 - AUTH: Found hardcoded test credentials in dev_config.json: [username=abhishek password=hunter@123]"
    ],
    "loaded_modules" => [
        "core", "bcmath", "calendar", "ctype", "date", "dom", "filter", "hash", "iconv", "json", "libxml", "pcre", "pdo_sqlite"
    ],
    "request_metadata" => $_SERVER
];

echo json_encode($debug_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit();
?>