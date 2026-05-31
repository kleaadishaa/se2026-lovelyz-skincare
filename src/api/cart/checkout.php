<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/jwt_helper.inc.php';
require_once '../../includes/rate_limit.inc.php';
header('Content-Type: application/json');
rateLimit($pdo, 30, 60);
$user_id = validateJWT();

$data = json_decode(file_get_contents("php://input"), true);

try {
    // get cart items
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Shporta është bosh."]);
        exit;
    }

    // calculate total
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $pdo->beginTransaction();

    // create the order header
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, status, total_price, shipping_street, shipping_city, shipping_country)
                           VALUES (?, 'pending', ?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $total,
        $data['shipping_street'],
        $data['shipping_city'],
        $data['shipping_country']
    ]);

    $order_id = $pdo->lastInsertId();

    // insert each cart item into order_details
    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_details (order_id, product_id, product_name, price, discount_percent, quantity)
                               VALUES (?,?,?,?,?,?)");
        $stmt->execute([
            $order_id,
            $item['product_id'],
            $item['product_name'],
            $item['price'],
            0,
            $item['quantity']
        ]);
    }

    // clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $pdo->commit();

    echo json_encode(["success" => true, "message" => "Porosia u krye me sukses.", "order_id" => $order_id]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}