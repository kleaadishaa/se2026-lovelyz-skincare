<?php
function authenticate($pdo) {
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        sendResponse(false, "Token mungon.", 401);
    }

    $auth = $headers['Authorization'];

    if (!str_starts_with($auth, 'Bearer ')) {
        sendResponse(false, "Format i gabuar i tokenit.", 401);
    }

    $token = trim(substr($auth, 7));

    $stmt = $pdo->prepare(
        "SELECT user_id FROM api_tokens 
         WHERE token = ? AND expires_at > NOW()"
    );
    $stmt->execute([$token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        sendResponse(false, "Token i pavlefshëm ose i skaduar.", 401);
    }

    return (int)$row['user_id'];
}