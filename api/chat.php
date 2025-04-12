<?php
// /api/chat.php

require_once __DIR__ . '/../includes/config.php';

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "event: error\ndata: Method not allowed\n\n";
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$prompt = trim($input['prompt'] ?? '');
$useWebSearch = isset($input['web_search']) && $input['web_search'] === true;

if (!$prompt) {
    echo "event: error\ndata: Missing prompt\n\n";
    exit;
}

// ============================
// ✅ Fetch web data if enabled
// ============================
if ($useWebSearch) {
    $webResultsFormatted = fetchWebSearchResults($prompt);

    if ($webResultsFormatted) {
        $prompt = <<<PROMPT
The user also found the following web results:

{$webResultsFormatted}

Now, based on the web context and your own knowledge, answer the following question:

{$prompt}
PROMPT;
    }
}

// ============================
// 🤖 Send prompt to Ollama
// ============================
$ollamaUrl = 'http://host.docker.internal:11434/api/chat';
$model = 'gemma3:27b';

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $ollamaUrl,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
        'model' => $model,
        'stream' => true,
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ]
    ]),
    CURLOPT_WRITEFUNCTION => function ($ch, $chunk) {
        $lines = explode("\n", trim($chunk));
        foreach ($lines as $line) {
            if (!$line) continue;
            $decoded = json_decode($line, true);
            if (json_last_error() !== JSON_ERROR_NONE) continue;

            if (!empty($decoded['done'])) return strlen($chunk);
            if (!empty($decoded['message']['content'])) {
                echo "data: " . $decoded['message']['content'] . "\n\n";
                @ob_flush();
                @flush();
            }
        }
        return strlen($chunk);
    }
]);

curl_exec($ch);

if (curl_errno($ch)) {
    echo "event: error\ndata: Curl error: " . curl_error($ch) . "\n\n";
}

curl_close($ch);


// ==============================================
// 🔍 FUNCTION: Pulls + formats web_search.php
// ==============================================
function fetchWebSearchResults(string $query): string {
    $payload = json_encode(['query' => $query]);
    $context = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\nContent-Length: " . strlen($payload),
            'content' => $payload,
            'timeout' => 5,
        ]
    ]);

    $response = @file_get_contents(__DIR__ . '/web_search.php', false, $context);

    if (!$response) return '';
    $json = json_decode($response, true);

    if (!isset($json['success']) || !$json['success'] || empty($json['results'])) return '';

    $formatted = '';
    foreach ($json['results'] as $res) {
        $title   = $res['title'] ?? 'Untitled';
        $snippet = $res['snippet'] ?? '';
        $source  = $res['source'] ?? 'unknown';
        $url     = $res['url'] ?? '#';

        $formatted .= "- [{$title}]({$url}) from {$source}: {$snippet}\n";
    }

    return $formatted;
}
