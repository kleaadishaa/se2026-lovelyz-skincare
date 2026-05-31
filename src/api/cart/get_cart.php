<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/jwt_helper.inc.php';
require_once '../../includes/rate_limit.inc.php';
header('Content-Type: application/json');
rateLimit($pdo, 30, 60);
$user_id = validateJWT();

try {
    $stmt = $pdo->prepare("
SELECT 
        c.product_id,
        c.product_name,
        c.price,
        c.quantity,
        p.image
    FROM cart c
    JOIN products p 
        ON c.product_id = p.product_id
    WHERE c.user_id = ?
");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($items)) {
        echo json_encode(["success" => true, "message" => "Shporta është bosh.", "data" => []]);
        exit;
    }

    echo json_encode(["success" => true, "message" => "Artikujt e shportës.", "data" => $items]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Gabim gjatë marrjes së shportës."]);
}