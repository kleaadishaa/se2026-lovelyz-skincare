<?php
include '../includes/dbh.inc.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));
$allowed_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

if (!isset($data->id) || !isset($data->status) || !in_array($data->status, $allowed_statuses)) {
    echo json_encode(["success" => false, "message" => "Të dhëna të pavlefshme"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    
    if ($stmt->execute([$data->status, $data->id])) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Përditësimi dështoi"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}