<?php
if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('alipay_login_address.php', '', str_replace('\\', '/', __FILE__)));
	
$_REQUEST['m'] = 'cart';	
$_REQUEST['a'] = 'check';
//$_REQUEST['m'] = 'user';	
//$_REQUEST['a'] = 'login_alipay_address';
$_REQUEST['alipay_address'] ='alipay_address';
if (! defined ( 'IS_CGI' ))
	define ( 'IS_CGI', substr ( PHP_SAPI, 0, 3 ) == 'cgi' ? 1 : 0 );
	//定义__ROOT__常量
if (! defined ( '_PHP_FILE_' )) {
	if (IS_CGI) {
		//CGI/FASTCGI模式下
		$_temp = explode ( '.php', $_SERVER ["PHP_SELF"] );
		define ( '_PHP_FILE_', rtrim ( str_replace ( $_SERVER ["HTTP_HOST"], '', $_temp [0] . '.php' ), '/' ) );
	} else {
		define ( '_PHP_FILE_', rtrim ( $_SERVER ["SCRIPT_NAME"], '/' ) );
	}
}

if (! defined ( '__ROOT__' )) {
	// 网站URL根目录
	$_root = dirname ( _PHP_FILE_ );
	$_root = (($_root == '/' || $_root == '\\') ? '' : $_root);
	$_root = str_replace('/api', '', $_root);
	define ( '__ROOT__', $_root );
}

include_once(ROOT_PATH."app/source/index.php");
	
?>