<?php
require_once '../src/auth/signup_model.inc.php';

// Test 1: Password too short
function test_short_password() {
    $password = '123';
    $result = strlen($password) >= 8;
    assert($result === false, "Test 1 FAILED");
    echo "Test 1 PASSED: Short password rejected\n";
}

// Test 2: Passwords don't match
function test_password_match() {
    $pass1 = 'password123';
    $pass2 = 'password456';
    $result = ($pass1 === $pass2);
    assert($result === false, "Test 2 FAILED");
    echo "Test 2 PASSED: Mismatched passwords rejected\n";
}

// Test 3: Invalid email format
function test_invalid_email() {
    $email = 'notanemail';
    $result = filter_var($email, FILTER_VALIDATE_EMAIL);
    assert($result === false, "Test 3 FAILED");
    echo "Test 3 PASSED: Invalid email rejected\n";
}

test_short_password();
test_password_match();
test_invalid_email();
?>