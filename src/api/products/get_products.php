<?php

include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/rate_limit.inc.php';

header('Content-Type: application/json');

rateLimit($pdo, 30, 60);

$user_id = validateJWT();

try {

    $stmt = $pdo->prepare(
        "SELECT 
            product_id,
            name,
            category,
            description,
            price,
            stock,
            image
         FROM products"
    );

    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(true, 'OK', 200, [
        'data' => $products,
        'count' => count($products)
    ]);

} catch (Exception $e) {

    sendResponse(false, 'Gabim gjatë leximit.', 500);
}