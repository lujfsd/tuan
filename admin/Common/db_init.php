<?php
//定义DB

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('admin/Common/db_init.php', '', str_replace('\\', '/', __FILE__)));
	
require ROOT_PATH.'app/source/class/mysql_db.php';

$db_config = require ROOT_PATH.'Public/db_config.php';
define('DB_PREFIX', $db_config['DB_PREFIX']);
if(isset($db_config['DB_PCONNECT']) && intval($db_config['DB_PCONNECT'])==1)	
	$pconnect = true;
else
	$pconnect = false;
$db = new mysql_db($db_config['DB_HOST'].":".$db_config['DB_PORT'], $db_config['DB_USER'],$db_config['DB_PWD'],$db_config['DB_NAME'],'utf8',$pconnect);

?>