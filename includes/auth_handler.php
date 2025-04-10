<?php
// /includes/auth_handler.php

require_once 'config.php';
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'register') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm_password'] ?? '';

  if (!$username || !$email || !$password || !$confirm) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
  }

  if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
  }

  $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
  $stmt->execute(['username' => $username, 'email' => $email]);
  if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'User already exists.']);
    exit;
  }

  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->execute([$username, $email, $hash]);

  $_SESSION['user_id'] = $pdo->lastInsertId();
  echo json_encode(['success' => true]);
  exit;
}

if ($action === 'login') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if (!$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Missing credentials.']);
    exit;
  }

  $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
  $stmt->execute(['username' => $username]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid login.']);
  }
  exit;
}

// Fallback error
echo json_encode(['success' => false, 'message' => 'Unknown action']);
