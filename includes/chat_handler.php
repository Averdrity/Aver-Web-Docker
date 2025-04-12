<?php
// /includes/chat_handler.php

require_once 'config.php';
require_once 'db.php';
require_once 'session.php';

header('Content-Type: application/json');

// Ensure user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = getUserId();
$action = $_POST['action'] ?? null;

// Route by action
switch ($action) {
    case 'save':
        handleSaveChat($pdo, $userId);
        break;

    case 'load':
        handleLoadChats($pdo, $userId);
        break;

    case 'rename':
        handleRenameChat($pdo, $userId);
        break;

    case 'delete':
        handleDeleteChat($pdo, $userId);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid or missing action.']);
        break;
}

// ========== HANDLERS ==========

function handleSaveChat($pdo, $userId) {
    $title = trim($_POST['title'] ?? 'Untitled');
    $messages = json_decode($_POST['messages'] ?? '[]', true);

    if (!$messages || !is_array($messages)) {
        echo json_encode(['success' => false, 'message' => 'Invalid message format.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO chats (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([
            $userId,
            htmlspecialchars(mb_substr($title, 0, 100)),
            json_encode($messages)
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
    }
}

function handleLoadChats($pdo, $userId) {
    try {
        $stmt = $pdo->prepare("SELECT id, title, content, created_at FROM chats WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'chats' => $chats]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to load chats.']);
    }
}

function handleRenameChat($pdo, $userId) {
    $chatId = $_POST['chat_id'] ?? null;
    $newTitle = trim($_POST['title'] ?? '');

    if (!$chatId || !$newTitle) {
        echo json_encode(['success' => false, 'message' => 'Missing data.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("UPDATE chats SET title = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([
            htmlspecialchars(mb_substr($newTitle, 0, 100)),
            $chatId,
            $userId
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Rename failed.']);
    }
}

function handleDeleteChat($pdo, $userId) {
    $chatId = $_POST['chat_id'] ?? null;
    if (!$chatId) {
        echo json_encode(['success' => false, 'message' => 'Missing chat ID.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM chats WHERE id = ? AND user_id = ?");
        $stmt->execute([$chatId, $userId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Delete failed.']);
    }
}
