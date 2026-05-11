<?php
include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/rate_limit.inc.php';

header('Content-Type: application/json');
rateLimit($pdo, 30, 60);
$user_id = validateJWT();

$data = json_decode(file_get_contents("php://input"));

if ($data === null) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Body i pavlefshëm."]);
    exit;
}

$products = isset($data->products) ? $data->products : [];

if (empty($products)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Lista e produkteve është bosh."]);
    exit;
}

foreach ($products as $p) {
    if (
        !isset($p->product_id, $p->quantity)
        || (int)$p->product_id <= 0
        || (int)$p->quantity   <= 0
    ) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Produkt i pavlefshëm."]);
        exit;
    }
}

$shipping_street  = isset($data->shipping_street)  ? trim($data->shipping_street)  : null;
$shipping_city    = isset($data->shipping_city)    ? trim($data->shipping_city)    : null;
$shipping_country = isset($data->shipping_country) ? trim($data->shipping_country) : null;

try {
    $pdo->beginTransaction();

    $product_ids  = array_map(fn($p) => (int)$p->product_id, $products);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

    $stmt = $pdo->prepare(
        "SELECT product_id, name, price FROM products 
         WHERE product_id IN ($placeholders)"
    );
    $stmt->execute($product_ids);

    $product_lookup = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product_lookup[$row['product_id']] = $row;
    }

    foreach ($product_ids as $pid) {
        if (!isset($product_lookup[$pid])) {
            $pdo->rollBack();
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Produkti me ID $pid nuk ekziston."]);
            exit;
        }
    }

    $total = 0;
    foreach ($products as $p) {
        $total += $product_lookup[(int)$p->product_id]['price'] * (int)$p->quantity;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO orders (user_id, status, total_price, shipping_street, shipping_city, shipping_country) 
         VALUES (?, 'pending', ?, ?, ?, ?)"
    );
    $stmt->execute([$user_id, $total, $shipping_street, $shipping_city, $shipping_country]);
    $order_id = $pdo->lastInsertId();

    $stmt_detail = $pdo->prepare(
        "INSERT INTO order_details (order_id, product_id, product_name, price, quantity)
         VALUES (?, ?, ?, ?, ?)"
    );
    foreach ($products as $p) {
        $pid = (int)$p->product_id;
        $stmt_detail->execute([
            $order_id,
            $pid,
            $product_lookup[$pid]['name'],
            $product_lookup[$pid]['price'],
            (int)$p->quantity
        ]);
    }

    $pdo->commit();
    http_response_code(201);
    echo json_encode([
        "success"  => true,
        "message"  => "Porosia u krijua.",
        "order_id" => $order_id,
        "total"    => $total
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Gabim i brendshëm."]);
}