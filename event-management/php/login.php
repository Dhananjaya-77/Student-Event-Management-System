<?php
require_once 'config.php';
header('Content-Type: application/json');

function login_error($field, $message) {
    echo json_encode(['success' => false, 'field' => $field, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    login_error('form', 'Invalid request method.');
}

$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) login_error('email', 'Email: Please enter a valid email address.');
if ($password === '') login_error('password', 'Password: Please enter your password.');

$user = null;
try {
    if ($pdo) {
        $stmt = $pdo->prepare('SELECT user_id, name, email, password, is_admin FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) $user = $row;
    } else {
        $stmt = $conn->prepare('SELECT user_id, name, email, password, is_admin FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    }
} catch (Throwable $error) {
    login_error('form', 'Database error: Unable to check your login.');
}

if (!$user || !password_verify($password, $user['password'])) {
    login_error('password', 'Invalid email or password.');
}

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['name'] = $user['name'];
$_SESSION['email'] = $user['email'];
$_SESSION['is_admin'] = (bool)$user['is_admin'];

echo json_encode([
    'success' => true,
    'message' => 'Login successful!',
    'is_admin' => (bool)$user['is_admin']
]);
