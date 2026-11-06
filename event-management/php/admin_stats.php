<?php
ob_start();
require_once 'config.php';
ob_end_clean();

header('Content-Type: application/json');

// Check if admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

function scalarCount($pdo, $sql) {
    return (int)$pdo->query($sql)->fetchColumn();
}

try {
    if ($pdo) {
        $today = date('Y-m-d');
        $stats = [
            'events' => scalarCount($pdo, 'SELECT COUNT(*) FROM events'),
            'users' => scalarCount($pdo, 'SELECT COUNT(*) FROM users'),
            'registrations' => scalarCount($pdo, 'SELECT COUNT(*) FROM registrations'),
            'upcoming' => scalarCount($pdo, "SELECT COUNT(*) FROM events WHERE date >= '$today'")
        ];
    } else {
        $count = function($sql) use ($conn) {
            return (int)$conn->query($sql)->fetch_assoc()['count'];
        };
        $stats = [
            'events' => $count('SELECT COUNT(*) AS count FROM events'),
            'users' => $count('SELECT COUNT(*) AS count FROM users'),
            'registrations' => $count('SELECT COUNT(*) AS count FROM registrations'),
            'upcoming' => $count("SELECT COUNT(*) AS count FROM events WHERE date >= CURDATE()")
        ];
    }
    echo json_encode($stats);
} catch (Throwable $error) {
    echo json_encode(['events' => 0, 'users' => 0, 'registrations' => 0, 'upcoming' => 0]);
}
$conn->close();
?>