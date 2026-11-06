<?php
require_once 'config.php';

header('Content-Type: application/json');

function registration_error($field, $message) {
    echo json_encode(['success' => false, 'field' => $field, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    registration_error('form', 'Invalid request method. Please submit the registration form again.');
}

$name = trim($_POST['name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$contact = trim($_POST['contact'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '') registration_error('name', 'Full Name: Please enter your full name.');
if (strlen($name) < 3) registration_error('name', 'Full Name: Must be at least 3 characters.');
if ($email === '') registration_error('email', 'Email: Please enter your email address.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) registration_error('email', 'Email: Please enter a valid email address.');
if (!preg_match('/^\d{10}$/', $contact)) registration_error('contact', 'Contact Number: Enter exactly 10 digits without spaces or symbols.');
if ($password === '') registration_error('password', 'Password: Please enter a password.');
if (strlen($password) < 6) registration_error('password', 'Password: Must be at least 6 characters.');

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    if ($pdo) {
        $check = $pdo->prepare('SELECT user_id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) registration_error('email', 'Email: This email address is already registered.');

        $insert = $pdo->prepare('INSERT INTO users (name, email, password, contact_number) VALUES (?, ?, ?, ?)');
        if (!$insert->execute([$name, $email, $hashedPassword, $contact])) {
            registration_error('form', 'Database error: Registration could not be completed. Please try again.');
        }
    } else {
        $stmt = $conn->prepare('SELECT user_id FROM users WHERE email = ?');
        if (!$stmt) registration_error('form', 'Database error: Could not prepare the email check.');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) registration_error('email', 'Email: This email address is already registered.');
        $stmt->close();

        $stmt = $conn->prepare('INSERT INTO users (name, email, password, contact_number) VALUES (?, ?, ?, ?)');
        if (!$stmt) registration_error('form', 'Database error: Could not prepare the registration query.');
        $stmt->bind_param('ssss', $name, $email, $hashedPassword, $contact);
        if (!$stmt->execute()) registration_error('email', 'Email: This email address is already registered or the database rejected the registration.');
        $stmt->close();
    }
} catch (PDOException $error) {
    if ((int)$error->getCode() === 23000) {
        registration_error('email', 'Email: This email address is already registered.');
    }
    registration_error('form', 'Database error: Registration could not be completed. Please try again.');
}

echo json_encode(['success' => true, 'field' => null, 'message' => 'Registration successful! Redirecting to login...']);
