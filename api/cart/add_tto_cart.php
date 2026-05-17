<?php
require_once '../../includes/config_session.inc.php';
require_once '../../includes/dbh.inc.php';
require_once '../../includes/jwt_helper.inc.php';
require_once '../../includes/response.inc.php';
require_once '../../includes/rate_limit.inc.php';

$data = json_decode(file_get_contents("php://input"),true);
