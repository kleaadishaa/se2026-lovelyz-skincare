<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/jwt_helper.inc.php';
require_once '../../includes/response.inc.php';

$user_id = validateJWT();
$data = json_decode(file_get_contents("php://input"), true);

$old_pwd = $data['old_password'] ?? null;
$new_pwd = $data['new_password'] ?? null;

if (!$old_pwd || !$new_pwd) {
    sendResponse(false, "Kërkohet fjalëkalimi i vjetër dhe i ri.", 400);
    exit;
}

if (strlen($new_pwd) < 8) {
    sendResponse(false, "Fjalëkalimi i ri duhet të ketë të paktën 8 karaktere.", 400);
    exit;
}

if ($old_pwd === $new_pwd) {
    sendResponse(false, "Fjalëkalimi i ri duhet të jetë i ndryshëm.", 400);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT pwd FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(false, "Përdoruesi nuk u gjet.", 404);
        exit;
    }

    if (!password_verify($old_pwd, $user['pwd'])) {
        sendResponse(false, "Fjalëkalimi aktual është i pasaktë.", 401);
        exit;
    }

    $hashedNewPwd = password_hash($new_pwd, PASSWORD_BCRYPT, ['cost' => 12]);
    $update = $pdo->prepare("UPDATE users SET pwd = ? WHERE id = ?");
    $update->execute([$hashedNewPwd, $user_id]);

    sendResponse(true, "Fjalëkalimi u ndryshua me sukses!", 200);
} catch (PDOException $e) {
    // error_log($e->getMessage()); // logo në server, jo te klienti
    sendResponse(false, "Gabim teknik.", 500);
}