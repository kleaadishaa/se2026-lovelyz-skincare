<?php

include '../../includes/dbh.inc.php';
include '../../includes/jwt_helper.inc.php';
include '../../includes/response.inc.php';

header('Content-Type: application/json');

/**
 * STEP 1: Read raw input safely
 */
$rawInput = file_get_contents("php://input");
error_log("RAW INPUT: " . $rawInput);

/**
 * STEP 2: Decode JSON safely
 */
$data = json_decode($rawInput, true);

/**
 * STEP 3: Validate JSON
 */
if (!is_array($data)) {
    sendResponse(false, "Invalid JSON input", 400);
    exit;
}

/**
 * STEP 4: Extract and sanitize input
 */
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

/**
 * STEP 5: Basic validation
 */
if ($email === '' || $password === '') {
    sendResponse(false, "Email and password are required", 400);
    exit;
}

/**
 * STEP 6: Fetch user from database
 */
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

error_log("USER FROM DB: " . print_r($user, true));

/**
 * STEP 7: Check user + password
 */
if (!$user) {
    sendResponse(false, "User not found", 401);
    exit;
}

if (!password_verify($password, $user['pwd'])) {
    sendResponse(false, "Incorrect password", 401);
    exit;
}

/**
 * STEP 8: Generate JWT token
 */
$token = generateJWT($user);

/**
 * STEP 9: Return success response
 */
sendResponse(true, "Login successful", 200, [
    "token" => $token
]);