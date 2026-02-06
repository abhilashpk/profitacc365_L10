<?php
// sti-handler.php
// Full, robust Stimulsoft PHP handler with a custom SaveReport JSON endpoint and debug logging.

// Debug / error reporting while debugging (remove or reduce in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Stimulsoft helper & adapters â€” adjust paths if required
require_once __DIR__ . '/stimulsoft/helper.php';
require_once __DIR__ . '/stimulsoft/adapters/mysql.php';

// Basic headers (CORS + content)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Engaged-Auth-Token');
header('Cache-Control: no-cache');

// --- Quick debug + custom SaveReport endpoint (safe) ---
// This accepts JSON payload: { action: "SaveReport", fileName: "<name>", reportJson: "<json string>" }
// It logs incoming raw bodies to handler-debug.log and writes reports/<fileName>.mrt
$rawInput = file_get_contents("php://input");
file_put_contents(__DIR__ . '/handler-debug.log', "[".date('Y-m-d H:i:s')."] RAW: " . $rawInput . PHP_EOL, FILE_APPEND);

// If request body is JSON and contains an action, handle SaveReport immediately and exit.
$json = json_decode($rawInput, true);
if (json_last_error() === JSON_ERROR_NONE && isset($json['action'])) {
    $action = strtolower($json['action']);
    if ($action === 'savereport' || $action === 'save_report') {
        header('Content-Type: application/json');
        try {
            // sanitize filename (allow only letters, numbers, dash, underscore)
            $fileName = preg_replace('/[^a-zA-Z0-9_-]/', '', ($json['fileName'] ?? '') );
            if (empty($fileName)) {
                echo json_encode(['success' => false, 'notice' => 'Invalid file name.']);
                exit;
            }

            $reportJson = $json['reportJson'] ?? '';
            // write to reports directory; it will overwrite if exists
            $path = __DIR__ . '/reports/' . $fileName . '.mrt';
            $ok = file_put_contents($path, $reportJson);

            if ($ok === false) {
                echo json_encode(['success' => false, 'notice' => "Failed to write $path"]);
            } else {
                echo json_encode(['success' => true, 'notice' => "Saved $fileName.mrt"]);
            }
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/handler-debug.log', "[".date('Y-m-d H:i:s')."] ERROR: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
            echo json_encode(['success' => false, 'notice' => 'Server exception: ' . $ex->getMessage()]);
        }
        exit; // Important: do not continue to StiHandler->process()
    }
}
// --- End custom endpoint ---

// Create the Stimulsoft handler and attach event callbacks as before
$handler = new StiHandler();
$handler->registerErrorHandlers(); 

// Called when the designer requests to prepare variables
$handler->onPrepareVariables = function ($args) { 
    return StiResult::success();
};

// Called when engine begins processing a data source (use to set connection string / SQL)
/*$handler->onBeginProcessData = function ($args) {
    if (isset($args->connection) && $args->connection == 'MySQL') {
        $args->connectionString = 'Server=localhost;Database=trading;uid=root;password=;';

    }
    return StiResult::success();
};*/

$handler->onBeginProcessData = function ($args) {
    if (isset($args->connection) && $args->connection === 'MySQL') {
        // 🔥 Use getenv() or hardcode values since we're outside Laravel
        $args->connectionString =
            'Server=' . (getenv('DB_HOST') ?: 'localhost') .
            ';Database=' . (getenv('DB_DATABASE') ?: 'laravel') .
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
    // Save to reports/exports by default
    $exportDir = __DIR__ . '/reports/exports';
    if (!is_dir($exportDir)) mkdir($exportDir, 0755, true);
    file_put_contents($exportDir . '/' . $fileName . '.' . $fileExtension, base64_decode($data));
    return StiResult::success('Successful export of the report.');
};

// Email sending (if used)
$handler->onEmailReport = function ($args) {
    // Set these values correctly if you use emailing from the server
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

// Handle builtin Stimulsoft save flow (optional additional handling)
$handler->onSaveReport = function ($args) {
    try {
        // Sanitize filename
        $fileName = preg_replace('/[^a-zA-Z0-9_-]/', '', $args->fileName);
        if (empty($fileName)) throw new Exception("Invalid filename.");

        // Save to the reports directory
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

// Debug: log hits
file_put_contents(__DIR__ . '/handler-debug.log', "[".date('Y-m-d H:i:s')."] Hit: handler.php called" . PHP_EOL, FILE_APPEND);

// Process the request (Stimulsoft will handle requests it knows about)
$handler->process();

