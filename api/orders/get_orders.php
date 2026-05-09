<?php
include '../../includes/dbh.inc.php';
include '../../includes/auth_check.inc.php';
header('Content-Type: application/json');

$user_id = authenticate($pdo);

try {
    $stmt = $pdo->prepare(
        "SELECT 
            o.id, o.status, o.total_price, o.created_at,
            u.first_name, u.last_name
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.user_id = ?
         ORDER BY o.created_at DESC"
    );
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(true, "OK", 200, ["data" => $orders, "count" => count($orders)]);

} catch (Exception $e) {
    sendResponse(false, "Gabim gjatë leximit.", 500);
}