<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../vendor/autoload.php';

$jwt_secret = "72364112fdb0b389d1c75dda8d1d606dbe76c923f0384b3376bd30182bf3910c";
$jwt_issuer = "localhost";
$jwt_expire = 3600;