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

    // Read from $_POST (multipart/form-data)
    $name        = isset($_POST['name'])        ? trim($_POST['name'])        : null;
    $category    = isset($_POST['category'])    ? trim($_POST['category'])    : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $price       = isset($_POST['price'])       ? $_POST['price']             : null;
    $stock       = isset($_POST['stock'])       ? $_POST['stock']             : null;

    if (!$name || !$category || !$description || $price === null || $stock === null) {
        sendResponse(false, 'Missing required fields.', 400);
    }

    // Handle image upload
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(false, 'Image upload failed or missing.', 400);
    }

    $file     = $_FILES['image'];
    $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/avif'];
    $mimeType = mime_content_type($file['tmp_name']); // check actual bytes, not just extension

    if (!in_array($mimeType, $allowed)) {
        sendResponse(false, 'Invalid file type. Only JPG, PNG, WEBP, GIF, AVIF allowed.', 400);
    }

    // Build a safe filename and save to assets/images/
    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_', true) . '.' . strtolower($ext);
    $uploadDir = __DIR__ . '/../../assets/images/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        sendResponse(false, 'Failed to save image.', 500);
    }

    // Store relative path in DB
    $imagePath = 'assets/images/' . $filename;

    $stmt = $pdo->prepare("
        INSERT INTO products (name, category, description, price, stock, image)
        VALUES (:name, :category, :description, :price, :stock, :image)
    ");

    $stmt->execute([
        ':name'        => $name,
        ':category'    => $category,
        ':description' => $description,
        ':price'       => $price,
        ':stock'       => $stock,
        ':image'       => $imagePath,
    ]);

    sendResponse(true, 'Product uploaded successfully.', 201, [
        'product_id' => $pdo->lastInsertId(),
        'image'      => $imagePath,
    ]);

} catch (Exception $e) {
    sendResponse(false, 'Error uploading product.', 500);
}