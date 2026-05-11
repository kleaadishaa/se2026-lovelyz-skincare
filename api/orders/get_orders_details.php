<?php
include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/rate_limit.inc.php';
header('Content-Type: application/json');
rateLimit($pdo, 30, 60);
$user_id = validateJWT();

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ID e pavlefshme."]);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "SELECT 
            o.order_id, o.status, o.total_price, o.created_at,
            o.shipping_street, o.shipping_city, o.shipping_country,
            u.username, u.email
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.order_id = ? AND o.user_id = ?"
    );
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Porosia nuk u gjet."]);
        exit;
    }

    // Merr produktet e kësaj porosie
    $stmt_d = $pdo->prepare(
        "SELECT product_name, price, quantity, discount_percent
         FROM order_details
         WHERE order_id = ?"
    );
    $stmt_d->execute([$order_id]);
    $order['products'] = $stmt_d->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "message" => "OK", "data" => $order]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Gabim gjatë leximit."]);
}