<?php

require_once('config.php');
require_once('cls_mysql.php');
require_once('function.php');
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name );
$db_host = $db_user = $db_pass = $db_name = NULL;

?>