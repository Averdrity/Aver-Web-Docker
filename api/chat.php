<?php
// /src/api/chat.php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo "event: error\ndata: Method not allowed\n\n";
  exit;
}

// Get prompt from request
$input = json_decode(file_get_contents('php://input'), true);
$prompt = trim($input['prompt'] ?? '');

if (!$prompt) {
  echo "event: error\ndata: Missing prompt\n\n";
  exit;
}

// Ollama settings
$ollamaUrl = 'http://host.docker.internal:11434/api/chat';
$model = 'gemma3:27b';

// Set up curl stream
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
      if (empty($line)) continue;

      $decoded = json_decode($line, true);
      if (json_last_error() !== JSON_ERROR_NONE) continue;

      // Stream done
      if (!empty($decoded['done'])) {
        return strlen($chunk);
      }

      // Stream message content
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

// Catch curl errors
if (curl_errno($ch)) {
  echo "event: error\ndata: Curl error: " . curl_error($ch) . "\n\n";
}

curl_close($ch);
