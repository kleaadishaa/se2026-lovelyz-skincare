// PUT /api/clients/update_user - Updates user profile data
<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/jwt_helper.inc.php';
require_once '../../includes/response.inc.php';

// 1. Verifikojmë përdoruesin përmes Token-it
$user_id = validateJWT();

// 2. Lexojmë të dhënat e reja nga Body (JSON)
$data = json_decode(file_get_contents("php://input"), true);

$new_username = $data['username'] ?? null;
$new_email = $data['email'] ?? null;

if (!$new_username && !$new_email) {
    sendResponse(false, "Asnjë e dhënë nuk u dërgua për përditësim.", 400);
    exit;
}

try {
    // 3. Kontrollojmë nëse emaili i ri është i zënë nga dikush tjetër
    if ($new_email) {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $checkStmt->execute([$new_email, $user_id]);
        if ($checkStmt->fetch()) {
            sendResponse(false, "Ky email është i zënë nga një përdorues tjetër.", 400);
            exit;
        }
    }

    // 4. Ekzekutojmë përditësimin (Update), përdor COALESCE që nëse një fushë vjen bosh, të mbajmë vlerën ekzistuese
    $query = "UPDATE users 
              SET username = COALESCE(?, username), 
                  email = COALESCE(?, email) 
              WHERE id = ?";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$new_username, $new_email, $user_id]);

    sendResponse(true, "Profili u përditësua me sukses!", 200);
} catch (PDOException $e) {
    sendResponse(false, "Gabim teknik: " . $e->getMessage(), 500);
}
