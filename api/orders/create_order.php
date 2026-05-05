<?php
include '../includes/dbh.inc.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));
$user_id = $data->user_id;
$products = $data->products; 

if (!$user_id || empty($products)) {
    echo json_encode(["success" => false, "message" => "Të dhëna të gabuara"]);
    exit;
}

try {
    $pdo->beginTransaction();
    //  Marrja e Detajeve të Produkteve
    $product_ids = array_map(fn($p) => intval($p->product_id), $products);
    $ids_string = implode(',', $product_ids);

    $res = $pdo->query("SELECT id, name, price FROM products WHERE id IN ($ids_string)");
    $product_lookup = [];
    
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $product_lookup[$row['id']] = $row;
    }

    // Llogaritja e Totalit
    $total = 0;
    foreach ($products as $p) {
        $total += $product_lookup[$p->product_id]['price'] * $p->quantity;
    }

    // INSERT-i i Porosisë
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, status, total_price) VALUES (?, 'pending', ?)");
    $stmt->execute([$user_id, $total]);
    
    $order_id = $pdo->lastInsertId();

    $stmt_detail = $pdo->prepare("INSERT INTO order_details (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($products as $p) {
        $p_id = $p->product_id;
        $name = $product_lookup[$p_id]['name'];
        $price = $product_lookup[$p_id]['price'];
        $qty = $p->quantity;
        
        $stmt_detail->execute([$order_id, $p_id, $name, $price, $qty]);
    }

    $pdo->commit();
    echo json_encode(["success" => true, "order_id" => $order_id]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}