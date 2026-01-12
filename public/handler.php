<?php
require_once 'stimulsoft/helper.php';

error_reporting(0);

// Please configure the security level as you required.
// By default is to allow any requests from any domains.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Engaged-Auth-Token");


$handler = new StiHandler();
$handler->registerErrorHandlers();


$handler->onBeginProcessData = function ($event) {
	// Current database type: 'XML', 'JSON', 'MySQL', 'MS SQL', 'PostgreSQL', 'Firebird', 'Oracle'
	$database = $event->database;
	// Current connection name
	$connection = $event->connection;
	// Current data source name
	$dataSource = $event->dataSource;
	// Connection string for the current data source
	$connectionString = $event->connectionString;
	// SQL query string for the current data source
	$queryString = $event->queryString;
	
	return StiResult::success();
	//return StiResult::error("Message for some connection error.");
};

$handler->onPrintReport = function ($event) {
	return StiResult::success();
};

$handler->onBeginExportReport = function ($event) {
	$settings = $event->settings;
	$format = $event->format;
	return StiResult::success();
};

$handler->onEndExportReport = function ($event) {
	$format = $event->format; // Export format
	$data = $event->data; // Base64 export data
	$fileName = $event->fileName; // Report file name
	
	file_put_contents('reports/'.$fileName.'.'.strtolower($format), base64_decode($data));
	
	//return StiResult::success();
	return StiResult::success("Export OK. Message from server side.");
};

$handler->onEmailReport = function ($event) {
	$event->settings->from = "accounts@numaktech.com";
	$event->settings->host = "mail.numaktech.com";
	$event->settings->login = "accounts@numaktech.com";
	$event->settings->password = "accountsnumaktech@123";
};

$handler->onDesignReport = function ($event) {
	return StiResult::success();
};

$handler->onCreateReport = function ($event) {
	$fileName = $event->fileName;
	return StiResult::success();
};

$handler->onSaveReport = function ($event) {
	$report = $event->report; // Report object
	$reportJson = $event->reportJson; // Report JSON
	$fileName = $event->fileName; // Report file name
	
	file_put_contents('reports/'.$fileName.".mrt", $reportJson);
	
	//return StiResult::success();
	return StiResult::success("Save Report OK: ".$fileName);
	//return StiResult::error("Save Report ERROR. Message from server side.");
};

$handler->onSaveAsReport = function ($event) {
	return StiResult::success();
};

$handler->process();
