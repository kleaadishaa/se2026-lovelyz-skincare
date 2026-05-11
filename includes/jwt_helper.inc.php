<?php

require_once __DIR__ . '/jwt_config.inc.php';


function generateJWT($user) {
    global $jwt_secret, $jwt_issuer, $jwt_expire;

    $payload = [
        'iss' => $jwt_issuer,
        'iat' => time(),
        'exp' => time() + $jwt_expire,
        'data' => [
            'id' => $user['id'],
            'email' => $user['email']
        ]
    ];

    return Firebase\JWT\JWT::encode($payload, $jwt_secret, 'HS256');
}

function validateJWT() {
    global $jwt_secret;

    // Try multiple ways to get the Authorization header
    $authHeader = null;

    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } else {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
        }
    }

    if (!$authHeader) {
        sendResponse(false, 'Missing token.', 401);
        exit;
    }

    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        sendResponse(false, 'Invalid token format.', 401);
        exit;
    }

    $jwt = $matches[1];

    try {
        $decoded = Firebase\JWT\JWT::decode(
            $jwt,
            new Firebase\JWT\Key($jwt_secret, 'HS256')
        );

        return $decoded->data->id;

    } catch (Exception $e) {
        sendResponse(false, 'Invalid or expired token.', 401);
        exit;
    }
}
