<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

// 前后台加载的系统配置文件
 
define('WWW_ROOT', rtrim((dirname(dirname(APP_ROOT_PATH))),'/'));//根目录

if(is_file(WWW_ROOT . '/include/configure/db.php')){
	define('SYS_DB', WWW_ROOT . '/include/configure/db.php');//最土数据库配置文件

	define('SYS_M','zuitu');//最土的系统
}elseif(is_file(WWW_ROOT . '/Public/db_config.php')){
	define('SYS_DB', WWW_ROOT.'/Public/db_config.php');///fanwe或易想 数据库配置文件

	if(is_file(WWW_ROOT . '/public/version.php') ){
		define('SYS_M', 'easethink');
	}else{
		define('SYS_M', 'fanwe');
	}
}elseif(is_file(WWW_ROOT . '/public/db_config.php')){
	define('SYS_DB', WWW_ROOT.'/public/db_config.php');///fanwe或易想 数据库配置文件
	if(is_file(WWW_ROOT . '/public/version.php') ){
		define('SYS_M', 'easethink');
	}else{
		define('SYS_M', 'fanwe');
	}
}
elseif(is_file(WWW_ROOT . '/data/config.php')){
	define('SYS_DB', WWW_ROOT . '/data/config.php');//ecshop数据库配置文件
	define('SYS_M','ecshop');//ecshop的系统
}

if (!is_file(SYS_DB)){
	header("Content-Type:text/html; charset=utf-8");
	echo '无法找到数据库配置文件';
	exit;
}

if (defined(SYS_M)){
	header("Content-Type:text/html; charset=utf-8");
	echo '无法确认网站类型';
	exit;
}



$db_cfg = require SYS_DB;
if (SYS_M == 'fanwe'){
	$db_config = $db_cfg;
	//$db_config['DB_HOST'] = $db_config['DB_HOST'].":".$db_config['DB_PORT'];
}else if (SYS_M == 'ecshop'){
	$db_config['DB_HOST'] = $db_host;//$db_config['db_host'];
	$db_config['DB_USER'] = $db_user;//$db_config['db_user'];
	$db_config['DB_PWD'] = $db_pass;//$db_config['db_pass'];
	$db_config['DB_PREFIX'] = $prefix;//$db_config['prefix'];
	$db_config['DB_NAME'] = $db_name;//$db_config['db_name'];
}else if (SYS_M == 'easethink'){
	//$db_config['DB_HOST'] = $db_config['DB_HOST'].":".$db_config['DB_PORT'];
}

//print_r($db_config); exit;
//echo SYS_DB; exit;
//加载时区信息
if(file_exists(APP_ROOT_PATH.'public/timezone_config.php'))
{
	$timezone	=	require APP_ROOT_PATH.'public/timezone_config.php';
}

if(is_array($timezone))
$config = array_merge($db_config,$timezone);
else
$config = $db_config;

return $config;
?>