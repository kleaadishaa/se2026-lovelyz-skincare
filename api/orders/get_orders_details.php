<?php
include '../../includes/dbh.inc.php';
include '../../includes/auth_check.inc.php';
header('Content-Type: application/json');

$user_id  = authenticate($pdo);
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    sendResponse(false, "ID e pavlefshme.", 400);
}

try {
    // Kontrollo që porosia i përket këtij useri
    $stmt = $pdo->prepare(
        "SELECT 
            o.id, o.status, o.total_price, o.created_at,
            u.first_name, u.last_name
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.id = ? AND o.user_id = ?"
    );
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        sendResponse(false, "Porosia nuk u gjet.", 404);
    }

    // Merr produktet e kësaj porosie
    $stmt_d = $pdo->prepare(
        "SELECT product_name, price, quantity, discount_percent
         FROM order_details
         WHERE order_id = ?"
    );
    $stmt_d->execute([$order_id]);
    $order['products'] = $stmt_d->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(true, "OK", 200, ["data" => $order]);

} catch (Exception $e) {
    sendResponse(false, "Gabim gjatë leximit.", 500);
}