<?php
// pattern_detector.php
// 🔍 Smart AI Memory Pattern Detector

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
$input = $_POST['text'] ?? '';
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Missing input']);
    exit;
}

// Load patterns from JSON config
$patternFile = __DIR__ . '/../data/patterns.json';
if (!file_exists($patternFile)) {
    echo json_encode(['success' => false, 'message' => 'Pattern file missing']);
    exit;
}
$patterns = json_decode(file_get_contents($patternFile), true);
$detected = [];

// Match input against patterns
foreach ($patterns as $p) {
    $pattern = $p['pattern'];
    $topic = $p['topic'] ?? 'Unknown';
    $tags = $p['tags'] ?? [];
    $priority = $p['priority'] ?? 0;
    $title = $p['title'] ?? 'Detected Fact';
    $contentTemplate = $p['content'] ?? '';

    if (preg_match("/$pattern/", $input, $matches)) {
        $content = $contentTemplate;
        for ($i = 1; $i < count($matches); $i++) {
            $content = str_replace("{" . $i . "}", $matches[$i], $content);
        }

        $detected[] = [
            'title' => $title,
            'content' => $content,
            'category' => $topic,
            'tags' => $tags,
            'priority' => $priority
        ];
    }
}

// Optionally auto-save detected memories
$saved = [];
foreach ($detected as $mem) {
    try {
        $stmt = $pdo->prepare("INSERT INTO memories (user_id, title, content, category, tags, is_favorite, created_at, updated_at)
                               VALUES (?, ?, ?, ?, ?, 0, NOW(), NOW())");
        $stmt->execute([
            $userId,
            htmlspecialchars($mem['title']),
            htmlspecialchars($mem['content']),
            $mem['category'],
            json_encode($mem['tags'])
        ]);
        $saved[] = $mem;
    } catch (PDOException $e) {
        // Fail silently for this pattern
    }
}

// Final result
echo json_encode([
    'success' => true,
    'matched' => count($detected),
    'saved' => count($saved),
    'memories' => $saved
]);
