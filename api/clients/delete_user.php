<?php
require_once '../../includes/dbh.inc.php';
require_once '../../includes/jwt_helper.inc.php';
require_once '../../includes/response.inc.php';

// 1. Verifikojmë kush është përdoruesi përmes Token-it
$user_id = validateJWT();

try {
    // 2. kontolloj nqs ka porosi pending, i numeroj dhe nuk lejoj fshirjen nqs ka porosi pending
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'pending'");
    $checkStmt->execute([$user_id]);
    $pendingCount = $checkStmt->fetchColumn();

    if ($pendingCount > 0) {
        // Nëse ka porosi, ndalojmë procesin dhe dërgojmë error
        sendResponse(false, "Nuk mund ta fshini llogarinë! Keni $pendingCount porosi në pritje.", 400);
        exit;
    }

    // 3. FSHIRJA: Nëse nuk ka porosi pending, vazhdojmë me fshirjen
    // Shënim: Nëse ke lidhje Foreign Key, sigurohu që orders janë fshirë ose janë 'SET NULL'
    $deleteStmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $deleteStmt->execute([$user_id]);

    // 4. Përgjigjja e suksesit
    sendResponse(true, "Llogaria juaj u fshi me sukses. Na vjen keq që po largoheni!", 200);

} catch (PDOException $e) {
    sendResponse(false, "Gabim teknik: " . $e->getMessage(), 500);
}