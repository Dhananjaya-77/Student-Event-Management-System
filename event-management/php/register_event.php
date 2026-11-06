<?php
// Clean output buffer
ob_start();

require_once 'config.php';

// Clear any previous output
ob_end_clean();

// Set JSON header
header('Content-Type: application/json');

function event_registration_error($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) event_registration_error('Please login first.');

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') event_registration_error('Invalid request method.');

$userId = (int)$_SESSION['user_id'];
$eventId = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;

// Validate event ID
if ($eventId <= 0) event_registration_error('Invalid event selected.');

try {
    // SQLite registration
    if ($pdo) {
        // Ensure registrations table exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS registrations (
            reg_id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            event_id INTEGER NOT NULL,
            timestamp TEXT DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(user_id, event_id)
        )");

        // Check if event exists
        $eventCheck = $pdo->prepare('SELECT event_id FROM events WHERE event_id = ?');
        $eventCheck->execute([$eventId]);
        if (!$eventCheck->fetch()) event_registration_error('Event not found.');

        // Check if already registered
        $registrationCheck = $pdo->prepare('SELECT reg_id FROM registrations WHERE user_id = ? AND event_id = ?');
        $registrationCheck->execute([$userId, $eventId]);
        if ($registrationCheck->fetch()) event_registration_error('Already registered for this event.');

        // Register for event
        $insert = $pdo->prepare('INSERT INTO registrations (user_id, event_id) VALUES (?, ?)');
        $insert->execute([$userId, $eventId]);
    } else {
        // MySQL registration

        // Check if event exists
        $stmt = $conn->prepare('SELECT event_id FROM events WHERE event_id = ?');
        $stmt->bind_param('i', $eventId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) event_registration_error('Event not found.');
        $stmt->close();

        // Check if already registered
        $stmt = $conn->prepare('SELECT reg_id FROM registrations WHERE user_id = ? AND event_id = ?');
        $stmt->bind_param('ii', $userId, $eventId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) event_registration_error('Already registered for this event.');
        $stmt->close();

        // Register for event
        $stmt = $conn->prepare('INSERT INTO registrations (user_id, event_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $userId, $eventId);
        if (!$stmt->execute()) event_registration_error('Registration failed. Please try again.');
        $stmt->close();
    }

    echo json_encode(['success' => true, 'message' => 'Successfully registered for the event!']);
} catch (Throwable $error) {
    event_registration_error('Registration failed. Please try again.');
}
?>