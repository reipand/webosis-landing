<?php

// Use mysqli exceptions for easier debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Use 127.0.0.1 to force TCP (avoids permission problems with Unix socket when using "localhost")
$host = '127.0.0.1';
$user = 'reip';
$pass = 'bcst2526';
$db   = 'db_osissmkbi';
$port = 3306;

try {
	$koneksi = mysqli_connect($host, $user, $pass, $db, $port);
} catch (mysqli_sql_exception $e) {
	// Fail fast with a clear message for debugging in development
	// In production, avoid echoing credentials or detailed errors.
	http_response_code(500);
	echo 'Database connection failed: ' . $e->getMessage();
	exit;
}

?>