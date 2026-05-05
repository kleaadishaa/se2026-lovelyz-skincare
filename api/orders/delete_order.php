<?php
include '../includes/dbh.inc.php';
header('Content-Type: application/json');

$order_id = $_GET['id'] ?? null;

if (!$order_id) {
    echo json_encode(["success" => false, "message" => "ID mungon"]);
    exit;
}

try {
    $pdo->beginTransaction();

    // Fshij detajet e porosise
    $st1 = $pdo->prepare("DELETE FROM order_details WHERE order_id = ?");
    $st1->execute([$order_id]);

    // Fshij porosine kryesore
    $st2 = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $st2->execute([$order_id]);

    $pdo->commit();
    echo json_encode(["success" => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}