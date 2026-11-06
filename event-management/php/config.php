<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'event_management');

define('SQLITE_DB', __DIR__ . '/../database/eventhub.sqlite');

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$pdo = null;

if ($conn && !$conn->connect_error) {
    $conn->set_charset('utf8mb4');
} else {
    $conn = null;
    try {
        $pdo = new PDO('sqlite:' . SQLITE_DB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            user_id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            contact_number TEXT,
            is_admin INTEGER DEFAULT 0,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    } catch (Throwable $error) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'field' => 'form',
            'message' => 'Database error: Unable to create the local database.'
        ]);
        exit;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
