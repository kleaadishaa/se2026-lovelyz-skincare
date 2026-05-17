<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/jwt_helper.inc.php';
require_once '../../includes/response.inc.php';

// Verifikojmë token-in dhe marrim ID-në e përdoruesit
$user_id = validateJWT(); 

try {
    $query = "SELECT id, username, email, role, created_at FROM users WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        sendResponse(true, "Të dhënat e profilit.", 200, ["profile" => $user]);
    } else {
        sendResponse(false, "Përdoruesi nuk u gjet.", 404);
    }
} catch (PDOException $e) {
    sendResponse(false, "Gabim: " . $e->getMessage(), 500);
}