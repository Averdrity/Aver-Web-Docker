<?php
// /includes/upload_handler.php
require_once 'config.php';
require_once 'db.php';
require_once 'session.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = getUserId();
$action = $_POST['action'] ?? $_GET['action'] ?? null;

// 🧼 Auto-delete expired files (24h)
cleanupExpiredFiles($pdo);

// ===================
// 🚦 Route Actions
// ===================
switch ($action) {
    case 'upload':
        handleUpload($pdo, $userId);
        break;

    case 'list':
        listFiles($pdo, $userId);
        break;

    case 'delete':
        deleteFile($pdo, $userId);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid or missing action.']);
}

// ===================
// 📁 Upload File
// ===================
function handleUpload($pdo, $userId) {
    $allowedTypes = [
        'txt', 'md', 'json', 'html', 'css', 'js', 'php', 'xml', 'c', 'cpp',
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'
    ];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    if (!isset($_FILES['file'])) {
        echo json_encode(['success' => false, 'message' => 'No file provided.']);
        return;
    }

    $file = $_FILES['file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Unsupported file type.']);
        return;
    }

    if ($file['size'] > $maxFileSize) {
        echo json_encode(['success' => false, 'message' => 'File too large. Max 5MB.']);
        return;
    }

    // Max 20 files per user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM uploads WHERE user_id = ?");
    $stmt->execute([$userId]);
    if ($stmt->fetchColumn() >= 20) {
        echo json_encode(['success' => false, 'message' => 'Upload limit reached (max 20 files).']);
        return;
    }

    // Store file
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $uniqueName = uniqid('upload_', true) . '.' . $ext;
    $destination = $uploadDir . $uniqueName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        echo json_encode(['success' => false, 'message' => 'Failed to save file.']);
        return;
    }

    // Save to DB
    $stmt = $pdo->prepare("INSERT INTO uploads (user_id, filename, original_name, type, size, uploaded_at)
                           VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $userId,
        $uniqueName,
        $file['name'],
        $file['type'],
        $file['size']
    ]);

    echo json_encode(['success' => true, 'message' => 'File uploaded!']);
}

// ===================
// 📜 List Files
// ===================
function listFiles($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT id, original_name, filename, type, size, uploaded_at FROM uploads WHERE user_id = ?");
    $stmt->execute([$userId]);
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($files as &$file) {
        $uploaded = strtotime($file['uploaded_at']);
        $expires = $uploaded + 86400; // 24h
        $remaining = $expires - time();

        if ($remaining <= 0) {
            $file['expires_in'] = 'Expired';
        } else {
            $hours = floor($remaining / 3600);
            $mins = floor(($remaining % 3600) / 60);
            $file['expires_in'] = ($hours ? "{$hours}h " : '') . "{$mins}m";
        }
    }

    echo json_encode(['success' => true, 'files' => $files]);
}

// ===================
// ❌ Delete File
// ===================
function deleteFile($pdo, $userId) {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Missing file ID.']);
        return;
    }

    $stmt = $pdo->prepare("SELECT filename FROM uploads WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file) {
        @unlink(__DIR__ . '/../uploads/' . $file['filename']);
        $del = $pdo->prepare("DELETE FROM uploads WHERE id = ? AND user_id = ?");
        $del->execute([$id, $userId]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'File not found.']);
    }
}

// ===================
// 🧹 Auto Clean Old
// ===================
function cleanupExpiredFiles($pdo) {
    $stmt = $pdo->query("SELECT id, filename FROM uploads WHERE uploaded_at < (NOW() - INTERVAL 1 DAY)");
    $old = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($old as $file) {
        @unlink(__DIR__ . '/../uploads/' . $file['filename']);
        $del = $pdo->prepare("DELETE FROM uploads WHERE id = ?");
        $del->execute([$file['id']]);
    }
}
