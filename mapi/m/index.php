<?php 

require './system/common.php';

define('BASE_PATH','./');
define('THINK_PATH', './admin/ThinkPHP');

define('APP_NAME', 'admin');
define('APP_PATH', './admin');

require(THINK_PATH."/ThinkPHP.php");

$AppWeb = new App(); 

$AppWeb->run();

?>