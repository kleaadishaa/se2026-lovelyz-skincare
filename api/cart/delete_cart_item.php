<?php
include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/rate_limit.inc.php';
header('Content-Type: application/json');
rateLimit($pdo, 30, 60);
$user_id = validateJWT();

$cart_id = isset($_GET['cart_id']) ? (int)$_GET['cart_id'] : 0;

if ($cart_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ID e pavlefshme."]);
    exit;
}

try {
    $check = $pdo->prepare(
        "SELECT id FROM cart WHERE id = ? AND user_id = ?"
    );
    $check->execute([$cart_id, $user_id]);

    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Artikulli nuk u gjet."]);
        exit;
    }

    $stmt = $pdo->prepare(
        "DELETE FROM cart WHERE id = ? AND user_id = ?"
    );
    $stmt->execute([$cart_id, $user_id]);

    echo json_encode(["success" => true, "message" => "Artikulli u fshi nga shporta."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Gabim gjatë fshirjes."]);
}