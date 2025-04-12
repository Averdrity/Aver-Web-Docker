<?php
// /includes/memory_handler.php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session.php';

header('Content-Type: application/json');

// ✅ Ensure user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = getUserId();
$action = $_POST['action'] ?? null;

switch ($action) {
    case 'save':
        saveMemory($pdo, $userId);
        break;

    case 'load':
        loadMemories($pdo, $userId);
        break;

    case 'edit':
        editMemory($pdo, $userId);
        break;

    case 'delete':
        deleteMemory($pdo, $userId);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid or missing action']);
        break;
}

// ========================
// ACTIONS
// ========================

function saveMemory($pdo, $userId) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tags = $_POST['tags'] ?? '[]'; // JSON string
    $isFavorite = isset($_POST['is_favorite']) ? (int)$_POST['is_favorite'] : 0;
    $category = $_POST['category'] ?? null;

    if (!$title || !$content) {
        echo json_encode(['success' => false, 'message' => 'Title and content are required']);
        return;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO memories (user_id, title, content, tags, is_favorite, category, created_at, updated_at)
                               VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $userId,
            htmlspecialchars($title),
            htmlspecialchars($content),
            $tags,
            $isFavorite,
            $category
        ]);

        echo json_encode(['success' => true, 'message' => 'Memory saved successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function loadMemories($pdo, $userId) {
    try {
        $stmt = $pdo->prepare("SELECT id, title, content, tags, is_favorite, category, created_at, updated_at
                               FROM memories WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $memories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'memories' => $memories]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to load memories']);
    }
}

function editMemory($pdo, $userId) {
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tags = $_POST['tags'] ?? '[]';
    $isFavorite = isset($_POST['is_favorite']) ? (int)$_POST['is_favorite'] : 0;
    $category = $_POST['category'] ?? null;

    if (!$id || !$title || !$content) {
        echo json_encode(['success' => false, 'message' => 'Missing fields']);
        return;
    }

    try {
        $stmt = $pdo->prepare("UPDATE memories SET title = ?, content = ?, tags = ?, is_favorite = ?, category = ?, updated_at = NOW()
                               WHERE id = ? AND user_id = ?");
        $stmt->execute([
            htmlspecialchars($title),
            htmlspecialchars($content),
            $tags,
            $isFavorite,
            $category,
            $id,
            $userId
        ]);

        echo json_encode(['success' => true, 'message' => 'Memory updated']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error updating memory']);
    }
}

function deleteMemory($pdo, $userId) {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Missing memory ID']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM memories WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);

        echo json_encode(['success' => true, 'message' => 'Memory deleted']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting memory']);
    }
}
