<?php

include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/rate_limit.inc.php';

header('Content-Type: application/json');

rateLimit($pdo, 30, 60);

$user_id = validateJWT();

try {

    
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        sendResponse(false, 'Method not allowed.', 405);
    }

    
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['product_id'])) {
        sendResponse(false, 'Product ID is required.', 400);
    }

    
    $check = $pdo->prepare("SELECT product_id FROM products WHERE product_id = :id");
    $check->execute([':id' => $data['product_id']]);

    if ($check->rowCount() === 0) {
        sendResponse(false, 'Product not found.', 404);
    }

    
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = :id");
    $stmt->execute([':id' => $data['product_id']]);

    sendResponse(true, 'Product deleted successfully.', 200);

} catch (Exception $e) {

    sendResponse(false, 'Error deleting product.', 500);
}