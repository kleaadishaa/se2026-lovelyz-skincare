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
    $check = $pdo->prepare(
        "SELECT id FROM orders WHERE id = ? AND user_id = ?"
    );
    $check->execute([$order_id, $user_id]);

    if (!$check->fetch()) {
        sendResponse(false, "Porosia nuk u gjet.", 404);
    }

    // CASCADE kujdeset për order_details automatikisht
    $stmt = $pdo->prepare(
        "DELETE FROM orders WHERE id = ? AND user_id = ?"
    );
    $stmt->execute([$order_id, $user_id]);

    sendResponse(true, "Porosia u fshi.");

} catch (Exception $e) {
    sendResponse(false, "Gabim gjatë fshirjes.", 500);
}