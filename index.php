<?php 
require_once 'includes/config_session.inc.php';
require_once 'includes/signup_view.inc.php';
require_once 'includes/login_view.inc.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3> 
        <?php 
        output_username();
        ?>
    </h3>



    <h3>Login </h3>

     <form action="includes/login.inc.php" method="post">
    <input type="text" name="username" placeholder="Username"><br><br>
    <input type="password" name="pwd" placeholder="Password"><br><br>
    <button>Login</button>

  </form>

  <h3>Logout </h3>

     <form action="includes/logout.inc.php" method="post">
    <button>Logout</button>

  </form>

  <?php 
  check_login_errors();
  
  ?>

  <h3> Signup </h3>

     <form action="includes/signup.inc.php" method="post">
    <input type="text" name="username" placeholder="Username"><br><br>
    <input type="password" name="pwd" placeholder="Password"><br><br>
    <input type="text" name="email" placeholder="Email"><br><br>
    <button>Signup</button>
    </form>
    <?php 
    check_signup_errors();
    
    ?>

    
</body>
</html>