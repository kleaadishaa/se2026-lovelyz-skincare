<?php
include '../../includes/response.inc.php';

function rateLimit($pdo, $maxRequests = 30, $seconds = 60) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $currentTime = time();
    $windowStart = $currentTime - $seconds;

    $pdo->prepare(
        "DELETE FROM rate_limits WHERE request_time < ?"
    )->execute([$windowStart]);

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM rate_limits
         WHERE ip_address = ?
         AND request_time >= ?"
    );

    $stmt->execute([$ip, $windowStart]);

    $requestCount = $stmt->fetchColumn();

    if ($requestCount >= $maxRequests) {
        sendResponse(false, 'Too many requests.', 429);
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO rate_limits (ip_address, request_time)
         VALUES (?, ?)"
    );

    $stmt->execute([$ip, $currentTime]);
}