<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/sohu.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['a'] = 'sohu';
include_once(ROOT_PATH."api.php");
	
?>