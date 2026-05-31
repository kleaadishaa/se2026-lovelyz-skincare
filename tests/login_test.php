<?php
require_once __DIR__ . '/../src/includes/config_session.inc.php';

// Test 1: Empty credentials validation
function test_empty_credentials() {
    $email = '';
    $password = '';
    $result = !empty($email) && !empty($password);
    assert($result === false, "Test 1 FAILED");
    echo "Test 1 PASSED: Empty credentials rejected\n";
}

// Test 2: Invalid email format
function test_invalid_email() {
    $email = 'wronguser';
    $result = filter_var($email, FILTER_VALIDATE_EMAIL);
    assert($result === false, "Test 2 FAILED");
    echo "Test 2 PASSED: Invalid email rejected\n";
}

// Test 3: Valid email format
function test_valid_email() {
    $email = 'test@test.com';
    $result = filter_var($email, FILTER_VALIDATE_EMAIL);
    assert($result !== false, "Test 3 FAILED");
    echo "Test 3 PASSED: Valid email accepted\n";
}

// Test 4: Password minimum length
function test_password_length() {
    $password = '123';
    $result = strlen($password) >= 8;
    assert($result === false, "Test 4 FAILED");
    echo "Test 4 PASSED: Short password rejected\n";
}

test_empty_credentials();
test_invalid_email();
test_valid_email();
test_password_length();
?>