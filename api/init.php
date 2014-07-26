<?php

if (__FILE__ == '')
{
    die('Fatal error code: 0');
}


/* 取得当前fanwe所在的根目录 */
define('THINK_PATH',true);
define('ROOT_PATH', str_replace('api', '', str_replace('\\', '/', dirname(__FILE__))));
define('VENDOR_PATH', ROOT_PATH.'ThinkPHP/Vendor/');

if (file_exists(ROOT_PATH . 'db_config.php'))
{
   $dbcfg = include(ROOT_PATH . 'db_config.php');
}
else
{
   $dbcfg = include(ROOT_PATH . 'Public/db_config.php');
}

if (PHP_VERSION >= '5.1' && !empty($timezone))
{
    date_default_timezone_set($timezone);
}

define('TEMP_PATH', ROOT_PATH.'ThinkPHP/Vendor/');
$config = include(ROOT_PATH.'ThinkPHP/Common/convention.php');
$sys_config = array_merge($config, $dbcfg);

//print_r($sys_config);
//exit;


define('DB_PREFIX', $dbcfg['DB_PREFIX']);
/* 初始化数据库类 */
include_once(ROOT_PATH."app/source/class/mysql_db.php");

$db = new mysql_db($dbcfg['DB_HOST'], $dbcfg['DB_USER'], $dbcfg['DB_PWD'], $dbcfg['DB_NAME'], 'utf8');

init_constant();

	/**
	 * 初始化UC常量
	 */
	function init_constant()
	{
	    $sql = "select val from ".DB_PREFIX."sys_conf where name = 'INTEGRATE_CONFIG' limit 1";
	    
	    //print_r($sql);
	    
	    $cfg = unserialize($GLOBALS['db']->getOne($sql));
	    
	    //print_r($cfg);
	    
        define('UC_CONNECT', isset($cfg['uc_connect'])?$cfg['uc_connect']:'');
        define('UC_DBHOST', isset($cfg['db_host'])?$cfg['db_host']:'');
        define('UC_DBUSER', isset($cfg['db_user'])?$cfg['db_user']:'');
        define('UC_DBPW', isset($cfg['db_pass'])?$cfg['db_pass']:'');
        define('UC_DBNAME', isset($cfg['db_name'])?$cfg['db_name']:'');
        define('UC_DBCHARSET', isset($cfg['db_charset'])?$cfg['db_charset']:'utf8');
        define('UC_DBTABLEPRE', $cfg['db_pre']);
        define('UC_DBCONNECT', '0');
        define('UC_KEY', isset($cfg['uc_key'])?$cfg['uc_key']:'');
        define('UC_API', isset($cfg['uc_url'])?$cfg['uc_url']:'');
        define('UC_CHARSET', isset($cfg['uc_charset'])?$cfg['uc_charset']:'');
        define('UC_IP', isset($cfg['uc_ip'])?$cfg['uc_ip']:'');
        define('UC_APPID', isset($cfg['uc_id'])?$cfg['uc_id']:'');
        define('UC_PPP', '20');
	}
	
	// 获取客户端IP地址
	function get_client_ip(){
	   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
	       $ip = getenv("HTTP_CLIENT_IP");
	   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
	       $ip = getenv("HTTP_X_FORWARDED_FOR");
	   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
	       $ip = getenv("REMOTE_ADDR");
	   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
	       $ip = $_SERVER['REMOTE_ADDR'];
	   else
	       $ip = "unknown";
	   return($ip);
	}	
	
	function utf8ToGB($str)
	{
		include_once(ROOT_PATH."ThinkPHP/Vendor/iconv.php");
		$chinese = new Chinese();
		return $chinese->Convert("UTF-8","GBK",$str);
	}	
	
	function gbToUTF8($str)
	{
		include_once(ROOT_PATH."ThinkPHP/Vendor/iconv.php");
		$chinese = new Chinese();
		return $chinese->Convert("GBK","UTF-8",$str);
	}	
?>