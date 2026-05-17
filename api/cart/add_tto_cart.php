<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/jwt_helper.inc.php';
require_once '../../includes/rate_limit.inc.php';
header('Content-Type: application/json');
rateLimit($pdo, 30, 60);
$user_id = validateJWT();

$data = json_decode(file_get_contents("php://input"), true);

try {
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, product_name, price, quantity) 
                           VALUES (?,?,?,?,?)
                           ON DUPLICATE KEY UPDATE quantity=quantity+?");

    $stmt->execute([
        $user_id,
        $data['product_id'],
        $data['product_name'],
        $data['price'],
        $data['quantity'],
        $data['quantity']
    ]);

    echo json_encode(["success" => true, "message" => "Artikulli u shtua në shportë."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Gabim gjatë shtimit."]);
}