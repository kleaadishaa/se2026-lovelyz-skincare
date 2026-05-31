<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$username = $_POST["username"];
$pwd = $_POST["pwd"];
$email = $_POST["email"];

  try {

    require_once 'dbh.inc.php';
    require_once 'signup_model.inc.php';
    require_once 'signup_contr.inc.php';

    //Error handlers
    $errors = []; 


    if (is_input_empty($username, $pwd, $email)) {
        $errors["empty_input"] = "Fill in all fields!";
    }
    if (is_email_invalid($email)) {
        $errors["invalid_email"] = "Invalid email used!";
    }
     if (is_username_taken($pdo, $username)) {
        $errors["username_taken"] = "Username already taken!";
    }
    if (is_email_registered($pdo, $email)) {
        $errors["email_used"] = "Email already registered!";
    }

    require_once 'config_session.inc.php';

    if ($errors) {
        $_SESSION["errors_signup"] = $errors;
        header("Location: ../index.php");
        die();
    }

    // Krijo userin dhe merr ID-ne
        $newUserId = create_user($pdo, $pwd, $username, $email);

session_unset();
session_regenerate_id(true);

$_SESSION['user_id'] = $newUserId;
$_SESSION['username'] = $username;
require_once 'jwt_helper.inc.php';
$newUser = [
    'user_id'  => $newUserId,
    'username' => $username,
    'email'    => $email,
    'role'     => 'user'
];
$token = generateJWT($newUser);
$_SESSION['pending_token'] = $token;


    header("Location: ../index.php");

    $pdo = null;
    $stmt = null;
    


    die();

  } catch (PDOException $e) {
    die("Query failed : " . $e->getMessage());
}
    

}else {
    header("Location: ../index.php");
    die();
}