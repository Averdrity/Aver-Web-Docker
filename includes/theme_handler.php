<?php
require_once('session.php');
require_once('db.php');

if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$theme = $_POST['theme'] ?? 'dark';
$theme = in_array($theme, ['light', 'dark']) ? $theme : 'dark';

$stmt = $pdo->prepare("UPDATE users SET theme = ? WHERE id = ?");
$stmt->execute([$theme, $_SESSION['user_id']]);

$_SESSION['theme'] = $theme;

echo json_encode(['success' => true]);
