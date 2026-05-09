<?php
include '../../includes/dbh.inc.php';
include '../../includes/auth_check.inc.php';
header('Content-Type: application/json');

$user_id  = authenticate($pdo);
$data     = json_decode(file_get_contents("php://input"));
$statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

$order_id = isset($data->id)     ? (int)$data->id      : 0;
$status   = isset($data->status) ? trim($data->status) : '';

if ($order_id <= 0 || !in_array($status, $statuses, true)) {
    sendResponse(false, "Të dhëna të pavlefshme.", 400);
}

try {
    $check = $pdo->prepare(
        "SELECT id FROM orders WHERE id = ? AND user_id = ?"
    );
    $check->execute([$order_id, $user_id]);

    if (!$check->fetch()) {
        sendResponse(false, "Porosia nuk u gjet.", 404);
    }

    $stmt = $pdo->prepare(
        "UPDATE orders SET status = ? WHERE id = ? AND user_id = ?"
    );
    $stmt->execute([$status, $order_id, $user_id]);

    sendResponse(true, "Statusi u përditësua.");

} catch (Exception $e) {
    sendResponse(false, "Gabim gjatë përditësimit.", 500);
}