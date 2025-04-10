<?php
// Include database and configuration files
require_once 'db.php'; // db connection
require_once 'config.php'; // config for database

// Ensure the user is logged in before accessing the profile
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'getProfile') {
    // Get user profile information
    $userId = $_SESSION['user_id'];

    // Query to fetch user profile
    $stmt = $pdo->prepare("SELECT nickname, country, bio FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Profile not found']);
    }

} elseif ($action === 'saveProfile') {
    // Save updated profile information
    $nickname = $_POST['nickname'];
    $country = $_POST['country'];
    $bio = $_POST['bio'];

    // Validate input fields
    if (empty($nickname) || empty($country) || empty($bio)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Update the user's profile
    $stmt = $pdo->prepare("UPDATE users SET nickname = :nickname, country = :country, bio = :bio WHERE id = :id");
    $result = $stmt->execute([
        'nickname' => $nickname,
        'country' => $country,
        'bio' => $bio,
        'id' => $userId
    ]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
    }
}
?>
