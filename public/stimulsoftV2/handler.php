<?php
// handler.php - Stimulsoft PHP handler

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Load .env file for environment variables
$envPath = __DIR__ . '/../../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($val));
        }
    }
}

// Check if helper is already loaded from Laravel's autoloader
if (!function_exists('stiErrorHandler')) {
    $helperPath = __DIR__ . '/stimulsoft/helper.php';
    if (!file_exists($helperPath)) {
        http_response_code(500);
        header('Content-Type: application/json');
        die(json_encode(['error' => 'Helper file not found: ' . $helperPath]));
    }
    require_once $helperPath;
}

// Create the Stimulsoft handler
$handler = new StiHandler();
$handler->registerErrorHandlers();

// Called when the designer requests to prepare variables
$handler->onPrepareVariables = function ($args) {
    return StiResult::success();
};

// Called when engine begins processing a data source (use to set connection string / SQL)
$handler->onBeginProcessData = function ($args) {
    if (isset($args->connection) && $args->connection === 'MySQL') {
        $args->connectionString =
            'Server=' . (getenv('DB_HOST') ?: 'localhost') .
            ';Database=' . (getenv('DB_DATABASE') ?: 'numaktec_trading10') .
            ';uid=' . (getenv('DB_USERNAME') ?: 'root') .
            ';password=' . (getenv('DB_PASSWORD') ?: '') . ';';
    }
    return StiResult::success();
};

// Called when the report is printed
$handler->onPrintReport = function ($args) {
    $fileName = $args->fileName ?? '';
    return StiResult::success();
};

// Called at the beginning of export
$handler->onBeginExportReport = function ($args) {
    return StiResult::success();
};

// Called at the end of export (base64 data received)
$handler->onEndExportReport = function ($args) {
    $format = $args->format;
    $data = $args->data; // base64
    $fileName = $args->fileName ?? 'export';
    $fileExtension = $args->fileExtension ?? 'pdf';
    
    $exportDir = __DIR__ . '/reports/exports';
    if (!is_dir($exportDir)) mkdir($exportDir, 0755, true);
    file_put_contents($exportDir . '/' . $fileName . '.' . $fileExtension, base64_decode($data));
    
    return StiResult::success('Successful export of the report.');
};

// Email sending (if used)
$handler->onEmailReport = function ($args) {
    $args->settings->from = '******@gmail.com';
    $args->settings->host = 'smtp.gmail.com';
    $args->settings->login = '******';
    $args->settings->password = '******';
    return StiResult::success('Email sent successfully.');
};

$handler->onDesignReport = function ($args) {
    return StiResult::success();
};

// When a new report is requested to be created in designer
$handler->onCreateReport = function ($args) {
    return StiResult::success();
};

// Handle builtin Stimulsoft save flow
$handler->onSaveReport = function ($args) {
    try {
        $fileName = preg_replace('/[^a-zA-Z0-9_-]/', '', $args->fileName);
        if (empty($fileName)) throw new Exception("Invalid filename.");

        $filePath = __DIR__ . '/reports/' . $fileName . '.mrt';
        if (!file_put_contents($filePath, $args->reportJson)) {
            throw new Exception("Failed to save $fileName.mrt.");
        }

        return StiResult::success("Saved: $fileName.mrt");
    } catch (Exception $e) {
        return StiResult::error($e->getMessage());
    }
};

$handler->onSaveAsReport = function ($args) {
    return StiResult::success();
};

// Process the request (Stimulsoft will handle requests it knows about)
$handler->process();

