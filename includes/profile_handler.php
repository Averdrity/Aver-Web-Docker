<?php
// /includes/profile_handler.php

require_once 'config.php';
require_once 'db.php';
require_once 'session.php'; // Handles session + auth helpers

header('Content-Type: application/json');

// Validate method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Auth check
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'getProfile':
        getProfile($pdo);
        break;

    case 'saveProfile':
        saveProfile($pdo);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
        break;
}

// =============================
// HANDLERS
// =============================

function getProfile(PDO $pdo): void {
    $userId = getUserId();

    try {
        $stmt = $pdo->prepare("SELECT nickname, country, bio FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Profile not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function saveProfile(PDO $pdo): void {
    $userId  = getUserId();
    $nickname = trim($_POST['nickname'] ?? '');
    $country  = trim($_POST['country'] ?? '');
    $bio      = trim($_POST['bio'] ?? '');

    // Basic validation
    if (!$nickname || !$country || !$bio) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET nickname = :nickname, country = :country, bio = :bio 
            WHERE id = :id
        ");

        $success = $stmt->execute([
            'nickname' => htmlspecialchars($nickname),
            'country'  => htmlspecialchars($country),
            'bio'      => htmlspecialchars($bio),
            'id'       => $userId
        ]);

        // Clear cached session data
        clearUserCache();

        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Profile updated successfully' : 'Failed to update profile'
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
