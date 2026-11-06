<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please log in first.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$events = [];

try {
    if ($pdo) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS events (
            event_id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            date TEXT NOT NULL,
            venue TEXT NOT NULL,
            description TEXT,
            organizer TEXT NOT NULL
        )");
        $pdo->exec("CREATE TABLE IF NOT EXISTS registrations (
            reg_id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            event_id INTEGER NOT NULL,
            timestamp TEXT DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(user_id, event_id)
        )");

        $sampleEvents = $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
        if ((int)$sampleEvents === 0) {
            $insert = $pdo->prepare('INSERT INTO events (title, date, venue, description, organizer) VALUES (?, ?, ?, ?, ?)');
            $insert->execute(['Tech Workshop 2026', '2026-11-20', 'Computer Lab A', 'Learn modern web development basics.', 'CS Department']);
            $insert->execute(['Hackathon 2026', '2026-12-05', 'Innovation Center', 'A 24-hour coding competition.', 'Tech Club']);
            $insert->execute(['Career Fair 2026', '2026-11-25', 'Main Auditorium', 'Meet potential employers.', 'Career Services']);
        }

        $stmt = $pdo->prepare("SELECT e.*, EXISTS(
            SELECT 1 FROM registrations r WHERE r.event_id = e.event_id AND r.user_id = ?
        ) AS is_registered FROM events e ORDER BY e.date ASC");
        $stmt->execute([$userId]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $conn->prepare("SELECT e.*, (SELECT COUNT(*) FROM registrations WHERE event_id = e.event_id AND user_id = ?) AS is_registered FROM events e ORDER BY e.date ASC");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) $events[] = $row;
    }

    foreach ($events as &$event) {
        $event['is_registered'] = (bool)(int)$event['is_registered'];
    }
    unset($event);

    echo json_encode($events);
} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to load events. Please refresh the page.']);
}
