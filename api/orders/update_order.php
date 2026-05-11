<?php
include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/rate_limit.inc.php';
header('Content-Type: application/json');
rateLimit($pdo, 30, 60);
$user_id = validateJWT();

$data     = json_decode(file_get_contents("php://input"));
$statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

$order_id = isset($data->order_id) ? (int)$data->order_id : 0;
$status   = isset($data->status)   ? trim($data->status)  : '';

if ($order_id <= 0 || !in_array($status, $statuses, true)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Të dhëna të pavlefshme."]);
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

    $stmt = $pdo->prepare(
        "UPDATE orders SET status = ? WHERE order_id = ? AND user_id = ?"
    );
    $stmt->execute([$status, $order_id, $user_id]);

    echo json_encode(["success" => true, "message" => "Statusi u përditësua."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Gabim gjatë përditësimit."]);
}