<?php
// /api/web_search.php

header('Content-Type: application/json');
require_once(__DIR__ . '/../includes/config.php'); // Optional: auth check or config

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// ======================
// Get & validate search query
// ======================
$input = json_decode(file_get_contents('php://input'), true);
$query = trim($input['query'] ?? '');

if (!$query) {
    echo json_encode(['success' => false, 'message' => 'Missing search query.']);
    exit;
}

// ======================
// Main Dispatcher
// ======================
try {
    $results = runMultiEngineSearch($query);
    echo json_encode(['success' => true, 'results' => $results]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Search failed: ' . $e->getMessage()]);
    exit;
}


// ======================
// MULTI-ENGINE SEARCH LOGIC
// ======================
function runMultiEngineSearch($query): array {
    $results = [];

    // DuckDuckGo
    $results = array_merge($results, searchDuckDuckGo($query));

    // Optional: Google (if you have an API key)
    // $results = array_merge($results, searchGoogle($query));

    // Optional: Bing, Reddit, Wikipedia, etc.
    // $results = array_merge($results, searchBing($query));
    // $results = array_merge($results, searchWikipedia($query));

    return $results;
}


// ======================
// ENGINE: DuckDuckGo (HTML scraping)
// ======================
function searchDuckDuckGo(string $query): array {
    $encodedQuery = urlencode($query);
    $url = "https://html.duckduckgo.com/html/?q={$encodedQuery}";

    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "User-Agent: Mozilla/5.0\r\n"
        ]
    ]);

    $html = @file_get_contents($url, false, $context);
    if (!$html) return [];

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $nodes = $xpath->query('//a[@class="result__a"]');
    $summaries = $xpath->query('//a[@class="result__snippet"]');

    $results = [];
    for ($i = 0; $i < $nodes->length; $i++) {
        $title = trim($nodes[$i]->nodeValue ?? '');
        $href = $nodes[$i]->getAttribute('href') ?? '';
        $snippet = trim($summaries[$i]->nodeValue ?? '');

        if ($title && $href) {
            $results[] = [
                'source' => 'DuckDuckGo',
                'title' => $title,
                'url' => $href,
                'snippet' => $snippet
            ];
        }
    }

    return $results;
}


// ======================
// ENGINE: Google (Placeholder for Custom Search API)
// ======================
function searchGoogle(string $query): array {
    // Note: This requires a Google Programmable Search Engine + API key
    // Replace below if integrated
    return [[
        'source' => 'Google',
        'title' => '[Google Search Placeholder]',
        'url' => 'https://www.google.com/search?q=' . urlencode($query),
        'snippet' => 'Google Search API integration goes here...'
    ]];
}


// ======================
// ENGINE: Wikipedia (example of future expansion)
// ======================
function searchWikipedia(string $query): array {
    $url = "https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . urlencode($query) . "&format=json";

    $json = @file_get_contents($url);
    if (!$json) return [];

    $data = json_decode($json, true);
    $results = [];

    foreach ($data['query']['search'] ?? [] as $item) {
        $results[] = [
            'source' => 'Wikipedia',
            'title' => $item['title'],
            'url' => "https://en.wikipedia.org/wiki/" . urlencode(str_replace(' ', '_', $item['title'])),
            'snippet' => strip_tags($item['snippet'])
        ];
    }

    return $results;
}
