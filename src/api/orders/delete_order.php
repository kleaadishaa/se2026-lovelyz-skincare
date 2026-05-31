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
    $check = $pdo->prepare(
        "SELECT order_id FROM orders WHERE order_id = ? AND user_id = ?"
    );
    $check->execute([$order_id, $user_id]);

    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Porosia nuk u gjet."]);
        exit;
    }

    // CASCADE kujdeset për order_details automatikisht
    $stmt = $pdo->prepare(
        "DELETE FROM orders WHERE order_id = ? AND user_id = ?"
    );
    $stmt->execute([$order_id, $user_id]);

    echo json_encode(["success" => true, "message" => "Porosia u fshi."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Gabim gjatë fshirjes."]);
}