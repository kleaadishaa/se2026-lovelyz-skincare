<?php
include '../../includes/dbh.inc.php';
include '../../includes/auth_check.inc.php';
header('Content-Type: application/json');

$user_id  = authenticate($pdo);
$data     = json_decode(file_get_contents("php://input"));

if ($data === null) {
    sendResponse(false, "Body i pavlefshëm.", 400);
}

$products = isset($data->products) ? $data->products : [];

if (empty($products)) {
    sendResponse(false, "Lista e produkteve është bosh.", 400);
}

foreach ($products as $p) {
    if (
        !isset($p->product_id, $p->quantity)
        || (int)$p->product_id <= 0
        || (int)$p->quantity   <= 0
    ) {
        sendResponse(false, "Produkt i pavlefshëm.", 400);
    }
}

try {
    $pdo->beginTransaction();

    $product_ids  = array_map(fn($p) => (int)$p->product_id, $products);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

    $stmt = $pdo->prepare(
        "SELECT id, name, price FROM products 
         WHERE id IN ($placeholders)"
    );
    $stmt->execute($product_ids);

    $product_lookup = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product_lookup[$row['id']] = $row;
    }

    // Kontrollo që të gjithë produktet ekzistojnë
    foreach ($product_ids as $pid) {
        if (!isset($product_lookup[$pid])) {
            $pdo->rollBack();
            sendResponse(false, "Produkti me ID $pid nuk ekziston.", 404);
        }
    }

    // Llogarit totalin
    $total = 0;
    foreach ($products as $p) {
        $total += $product_lookup[(int)$p->product_id]['price']
            * (int)$p->quantity;
    }

    // INSERT porosia
    $stmt = $pdo->prepare(
        "INSERT INTO orders (user_id, status, total_price) 
         VALUES (?, 'pending', ?)"
    );
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();

    // INSERT detajet
    $stmt_detail = $pdo->prepare(
        "INSERT INTO order_details 
            (order_id, product_id, product_name, price, quantity)
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
    sendResponse(true, "Porosia u krijua.", 201, [
        "order_id" => $order_id,
        "total"    => $total
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    sendResponse(false, "Gabim i brendshëm.", 500);
}
