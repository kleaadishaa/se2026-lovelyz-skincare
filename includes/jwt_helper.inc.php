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

    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        sendResponse(false, 'Missing token.', 401);
        exit;
    }

    $authHeader = $headers['Authorization'];

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