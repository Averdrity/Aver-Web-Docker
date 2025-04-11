<?php
// /includes/auth_handler.php

require_once 'config.php';
require_once 'db.php';
require_once 'session.php'; // Handles session, isLoggedIn, getUserId, etc.

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Unhandled action'];

switch ($action) {
    case 'register':
        $response = handleRegister($pdo);
        break;

    case 'login':
        $response = handleLogin($pdo);
        break;

    default:
        $response['message'] = 'Invalid or missing action.';
        break;
}

echo json_encode($response);


// ==========================
// HANDLER FUNCTIONS
// ==========================

function handleRegister(PDO $pdo): array
{
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$username || !$email || !$password || !$confirm) {
        return ['success' => false, 'message' => 'All fields are required.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email address.'];
    }

    if ($password !== $confirm) {
        return ['success' => false, 'message' => 'Passwords do not match.'];
    }

    try {
        // Check for duplicates
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);

        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Username or email already exists.'];
        }

        // Create user
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        clearUserCache();

        return ['success' => true, 'message' => 'Registration successful.'];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
    }
}

function handleLogin(PDO $pdo): array
{
    $input    = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$input || !$password) {
        return ['success' => false, 'message' => 'Username/email and password are required.'];
    }

    try {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :input OR email = :input");
        $stmt->execute(['input' => $input]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            clearUserCache();
            return ['success' => true, 'message' => 'Login successful.'];
        } else {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Login failed: ' . $e->getMessage()];
    }
}
