<?php

include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/rate_limit.inc.php';

header('Content-Type: application/json');

rateLimit($pdo, 30, 60);

$user_id = validateJWT();

try {

    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(false, 'Method not allowed.', 405);
    }

    
    $data = json_decode(file_get_contents("php://input"), true);

    if (
        !isset($data['name']) ||
        !isset($data['category']) ||
        !isset($data['description']) ||
        !isset($data['price']) ||
        !isset($data['stock']) ||
        !isset($data['image'])
    ) {
        sendResponse(false, 'Missing required fields.', 400);
    }

    $stmt = $pdo->prepare("
        INSERT INTO products (name, category, description, price, stock, image)
        VALUES (:name, :category, :description, :price, :stock, :image)
    ");

    $stmt->execute([
        ':name' => $data['name'],
        ':category' => $data['category'],
        ':description' => $data['description'],
        ':price' => $data['price'],
        ':stock' => $data['stock'],
        ':image' => $data['image']
    ]);

    sendResponse(true, 'Product uploaded successfully.', 201, [
        'product_id' => $pdo->lastInsertId()
    ]);

} catch (Exception $e) {

    sendResponse(false, 'Error uploading product.', 500);
}