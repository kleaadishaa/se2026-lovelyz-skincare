<?php
include '../includes/dbh.inc.php';
header('Content-Type: application/json');

$sql = "SELECT o.*, u.first_name, u.last_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";

$result = $pdo->query($sql);
echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));