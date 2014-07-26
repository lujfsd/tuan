<?php
if(!defined('ROOT_PATH'))
define('ROOT_PATH', str_replace('guofubao_frontback.php', '', str_replace('\\', '/', __FILE__)));

$_REQUEST['m']="Payment";
$_REQUEST['a']="response";
$_REQUEST['payment_name']="Guofubao";
include ROOT_PATH."app/source/index.php";
?>