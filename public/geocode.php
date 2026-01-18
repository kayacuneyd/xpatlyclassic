<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$query = trim((string) ($_GET['q'] ?? ''));
if ($query === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing query']);
    exit;
}

$url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=ee&q=' . rawurlencode($query);

$cacheDir = __DIR__ . '/../storage/cache/geocode';
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0770, true);
}
$cacheFile = $cacheDir . '/' . md5($query) . '.json';
$cacheTtl = 86400;

if (is_file($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
    echo file_get_contents($cacheFile);
    exit;
}

$response = false;
$httpCode = 0;
$curlError = '';

if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 8,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'User-Agent: XpatlyLocal/1.0 (contact: support@xpatly.eu)',
            'Referer: ' . ($_SERVER['HTTP_REFERER'] ?? 'http://localhost')
        ],
    ]);
    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
} else {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: XpatlyLocal/1.0 (contact: support@xpatly.eu)\r\nAccept: application/json\r\n",
            'timeout' => 8,
        ],
    ]);
    $response = @file_get_contents($url, false, $context);
    $httpCode = $response !== false ? 200 : 0;
}

if ($response === false || $httpCode !== 200) {
    if (is_file($cacheFile)) {
        echo file_get_contents($cacheFile);
        exit;
    }
    http_response_code(502);
    echo json_encode([
        'error' => 'Geocode request failed',
        'status' => $httpCode,
        'detail' => $curlError ?: 'Upstream error'
    ]);
    exit;
}

@file_put_contents($cacheFile, $response);
echo $response;
