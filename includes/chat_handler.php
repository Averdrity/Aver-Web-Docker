<?php
// /src/includes/chat_handler.php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/auth.php');

header('Content-Type: application/json');

// Require login
if (!isLoggedIn()) {
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit;
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? null;

try {
  switch ($action) {
    case 'save':
      saveChat($userId);
      break;
    case 'load':
      loadChats($userId);
      break;
    case 'delete':
      deleteChat($userId);
      break;
    case 'rename':
      renameChat($userId);
      break;
    default:
      echo json_encode(['success' => false, 'message' => 'Invalid action']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

exit;

// -----------------------------
// Chat Actions
// -----------------------------

function saveChat($userId) {
  global $pdo;

  $title = $_POST['title'] ?? 'Untitled';
  $messages = $_POST['messages'] ?? [];

  $stmt = $pdo->prepare("INSERT INTO chats (user_id, title, content) VALUES (?, ?, ?)");
  $stmt->execute([$userId, $title, json_encode($messages)]);

  echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
}

function loadChats($userId) {
  global $pdo;

  $stmt = $pdo->prepare("SELECT id, title, content, created_at FROM chats WHERE user_id = ? ORDER BY created_at DESC");
  $stmt->execute([$userId]);
  $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(['success' => true, 'chats' => $chats]);
}

function deleteChat($userId) {
  global $pdo;

  $chatId = $_POST['chat_id'] ?? null;
  if (!$chatId) throw new Exception("Missing chat ID");

  $stmt = $pdo->prepare("DELETE FROM chats WHERE id = ? AND user_id = ?");
  $stmt->execute([$chatId, $userId]);

  echo json_encode(['success' => true]);
}

function renameChat($userId) {
  global $pdo;

  $chatId = $_POST['chat_id'] ?? null;
  $newTitle = $_POST['title'] ?? null;
  if (!$chatId || !$newTitle) throw new Exception("Missing data");

  $stmt = $pdo->prepare("UPDATE chats SET title = ? WHERE id = ? AND user_id = ?");
  $stmt->execute([$newTitle, $chatId, $userId]);

  echo json_encode(['success' => true]);
}
