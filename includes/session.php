<?php
// /includes/session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

// Get the current logged-in user's ID
function getUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

// Cached fetch of user data from DB
function getUserData(PDO $pdo): ?array {
    if (!isLoggedIn()) return null;

    // Cache user data in session to reduce DB calls
    if (!isset($_SESSION['user_data'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([getUserId()]);
        $_SESSION['user_data'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return $_SESSION['user_data'];
}

// Optional: Clear cached user data manually (e.g. after profile update)
function clearUserCache(): void {
    unset($_SESSION['user_data']);
}
