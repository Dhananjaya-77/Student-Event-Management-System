<?php
ob_start();
require_once 'config.php';
ob_end_clean();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($pdo) {
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare('SELECT * FROM events WHERE event_id = ?');
                $stmt->execute([(int)$_GET['id']]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                $rows = $pdo->query('SELECT * FROM events ORDER BY date DESC')->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($rows);
            }
        } else {
            if (isset($_GET['id'])) {
                $stmt = $conn->prepare('SELECT * FROM events WHERE event_id = ?');
                $stmt->bind_param('i', $_GET['id']); $stmt->execute();
                echo json_encode($stmt->get_result()->fetch_assoc());
            } else {
                $rows = [];
                $result = $conn->query('SELECT * FROM events ORDER BY date DESC');
                while ($row = $result->fetch_assoc()) $rows[] = $row;
                echo json_encode($rows);
            }
        }
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $title = trim($_POST['title'] ?? '');
        $date = $_POST['date'] ?? '';
        $venue = trim($_POST['venue'] ?? '');
        $organizer = trim($_POST['organizer'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $eventId = (int)($_POST['event_id'] ?? 0);

        if ($action === 'delete') {
            if ($pdo) { $stmt = $pdo->prepare('DELETE FROM events WHERE event_id = ?'); $stmt->execute([$eventId]); }
            else { $stmt = $conn->prepare('DELETE FROM events WHERE event_id = ?'); $stmt->bind_param('i', $eventId); $stmt->execute(); }
            echo json_encode(['success' => true, 'message' => 'Event deleted successfully']);
            exit;
        }

        if ($title === '' || $date === '' || $venue === '' || $organizer === '') {
            echo json_encode(['success' => false, 'message' => 'Title, date, venue, and organizer are required.']);
            exit;
        }

        if ($action === 'create') {
            if ($pdo) { $stmt = $pdo->prepare('INSERT INTO events (title, date, venue, organizer, description) VALUES (?, ?, ?, ?, ?)'); $stmt->execute([$title, $date, $venue, $organizer, $description]); }
            else { $stmt = $conn->prepare('INSERT INTO events (title, date, venue, organizer, description) VALUES (?, ?, ?, ?, ?)'); $stmt->bind_param('sssss', $title, $date, $venue, $organizer, $description); $stmt->execute(); }
            echo json_encode(['success' => true, 'message' => 'Event created successfully']);
            exit;
        }

        if ($action === 'update' && $eventId > 0) {
            if ($pdo) { $stmt = $pdo->prepare('UPDATE events SET title=?, date=?, venue=?, organizer=?, description=? WHERE event_id=?'); $stmt->execute([$title, $date, $venue, $organizer, $description, $eventId]); }
            else { $stmt = $conn->prepare('UPDATE events SET title=?, date=?, venue=?, organizer=?, description=? WHERE event_id=?'); $stmt->bind_param('sssssi', $title, $date, $venue, $organizer, $description, $eventId); $stmt->execute(); }
            echo json_encode(['success' => true, 'message' => 'Event updated successfully']);
            exit;
        }
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request']);
} catch (Throwable $error) {
    echo json_encode(['success' => false, 'message' => 'Event operation failed: ' . $error->getMessage()]);
}