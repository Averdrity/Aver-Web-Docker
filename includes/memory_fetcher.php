<?php
// /src/includes/memory_fetcher.php
// 🔍 AI Memory Fetcher – Selects relevant memories per prompt context

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session.php';

header('Content-Type: application/json');

// ✅ Auth check
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = getUserId();
$input = $_POST['input'] ?? '';

if (!$input || strlen($input) < 3) {
    echo json_encode(['success' => false, 'message' => 'Missing or too short input']);
    exit;
}

// ================================
// Fetch all memories for the user
// ================================
try {
    $stmt = $pdo->prepare("SELECT id, title, content, tags, category, priority, created_at FROM memories WHERE user_id = ?");
    $stmt->execute([$userId]);
    $memories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB error']);
    exit;
}

// ================================
// Scoring logic
// ================================
$ranked = [];
$inputLower = strtolower($input);

foreach ($memories as $mem) {
    $score = 0;

    $content = strtolower($mem['content'] ?? '');
    $title = strtolower($mem['title'] ?? '');
    $tags = json_decode($mem['tags'] ?? '[]', true);
    $priority = (int)($mem['priority'] ?? 5);

    // Match input keywords to title/content
    if (strpos($title, $inputLower) !== false) $score += 10;
    if (strpos($content, $inputLower) !== false) $score += 10;

    // Tag scoring
    foreach ($tags as $tag) {
        if (stripos($input, $tag) !== false) $score += 5;
    }

    // Add base weight by priority
    $score += $priority;

    // Save if relevant
    if ($score > 5) {
        $mem['score'] = $score;
        $ranked[] = $mem;
    }
}

// Sort by score descending
usort($ranked, fn($a, $b) => $b['score'] <=> $a['score']);

// Limit to top N
$top = array_slice($ranked, 0, 5);

// ================================
// Response
// ================================
echo json_encode([
    'success' => true,
    'matched' => count($top),
    'memories' => $top
]);
