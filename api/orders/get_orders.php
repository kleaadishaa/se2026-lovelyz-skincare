<?php

include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/rate_limit.inc.php';

header('Content-Type: application/json');

rateLimit($pdo, 30, 60);

$user_id = validateJWT();

try {

    $stmt = $pdo->prepare(
        "SELECT
            o.order_id,
            o.status,
            o.total_price,
            o.created_at,
            u.email,
            u.username
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.user_id = ?
         ORDER BY o.created_at DESC"

    );

    $stmt->execute([$user_id]);

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(true, 'OK', 200, [
        'data' => $orders,
        'count' => count($orders)
    ]);

} catch (Exception $e) {

    sendResponse(false, 'Gabim gjatë leximit.', 500);
}