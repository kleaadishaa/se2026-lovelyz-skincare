<?php
/*
$host ='localhost';
$dbname = 'shop';
$dbusername = 'root';
$dbpassword = '';
*/
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername,
     $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed : " . $e->getMessage());
}

//degjon përgjigjet JSON, që të mos përsërisësh http_response_code dhe echo json_encode çdo herë
//
function sendResponse($success, $message, $code = 200, $data = []) {
    http_response_code($code);
    echo json_encode(array_merge(
        ["success" => $success, "message" => $message],
        $data
    ));
    exit;
}