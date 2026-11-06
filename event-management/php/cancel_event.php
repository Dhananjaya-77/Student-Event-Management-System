<?php
ob_start();
require_once 'config.php';
ob_end_clean();
header('Content-Type: application/json');

function cancel_error($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

if (!isset($_SESSION['user_id'])) cancel_error('Please login first.');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') cancel_error('Invalid request method.');

$userId = (int)$_SESSION['user_id'];
$eventId = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
if ($eventId <= 0) cancel_error('Invalid event selected.');

try {
    if ($pdo) {
        $stmt = $pdo->prepare('DELETE FROM registrations WHERE user_id = ? AND event_id = ?');
        $stmt->execute([$userId, $eventId]);
        if ($stmt->rowCount() === 0) cancel_error('You are not registered for this event.');
    } else {
        $stmt = $conn->prepare('DELETE FROM registrations WHERE user_id = ? AND event_id = ?');
        $stmt->bind_param('ii', $userId, $eventId);
        $stmt->execute();
        if ($stmt->affected_rows === 0) cancel_error('You are not registered for this event.');
    }
    echo json_encode(['success' => true, 'message' => 'Registration cancelled successfully.']);
} catch (Throwable $error) {
    cancel_error('Could not cancel registration. Please try again.');
}
