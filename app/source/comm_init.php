<?php
// 用于分离框架的程序所需的引用
@set_magic_quotes_runtime ( 0 );
define ( 'MAGIC_QUOTES_GPC', get_magic_quotes_gpc () ? True : False );

//过滤请求
function a_filter_request(&$request) {
	if(MAGIC_QUOTES_GPC)
	{
		foreach ( $request as $k => $v ) {
			if (is_array ( $v )) {
				a_filter_request ( $v );
			} else {
				
				if ((strtolower($_REQUEST ['m']) == 'message'||strtolower ( $_REQUEST ['m'] ) == 'forum') && (strtolower ($_REQUEST ['a'] ) == 'insert'|| strtolower ($_REQUEST ['a'] ) == 'addcomment' || strtolower ( $_REQUEST ['a'] ) == 'addgroupmessage') && ($k == 'content'||$k == 'tg_content')) {
					$request [$k] = stripslashes(trim($v));
				} else {
					$request [$k] = htmlspecialchars ($v);
				}
			}
		}
	}
}
//add by chenfq 2011-08-09 手机使用支付宝支付时，不过滤  
if (isset($_REQUEST ['payment_name']) && $_REQUEST ['payment_name'] == 'Malipay'){
	
}else{
	a_filter_request ( $_REQUEST );
	a_filter_request ( $_POST );
	a_filter_request ( $_GET );	
}



//定义缓存
//require ROOT_PATH.'services/Utils/Cache.php';
//$cache = CacheService::getInstance("File");
//end 定义缓存

if (! defined ( '__ROOT__' )) {
	// 网站URL根目录
	$_root = dirname ( _PHP_FILE_ );
	$_root = (($_root == '/' || $_root == '\\') ? '' : $_root);
	if ($_root == '.'){
		$_root = "";
	}	
	define ( '__ROOT__', $_root );
}

if (__ROOT__ == "."){
	define ( '__ROOT2__', "");
}else{
	define ( '__ROOT2__', __ROOT__);
}

$timezone = a_fanweC ( "TIME_ZONE" );
//定义模板引擎
define ( 'VENDOR_PATH', ROOT_PATH . 'ThinkPHP/Vendor/' );

if (! is_dir ( ROOT_PATH . 'app/Runtime/caches/' ))
	mkdir ( ROOT_PATH . 'app/Runtime/caches/' );

if (! is_dir ( ROOT_PATH . 'app/Runtime/compiled/' ))
	mkdir ( ROOT_PATH . 'app/Runtime/compiled/' );

require ROOT_PATH . 'app/source/class/template.php';

$tpl = new template ( );
if (APP_NAME != 'admin' && APP_NAME != 'mobile')
	define ( 'TMPL_PATH', 'app/Tpl/' );

if (! defined ( "CND_URL" ))
	define ( "CND_URL", a_fanweC ( "CND_URL" ) != '' ? a_fanweC ( "CND_URL" ) : "http://" . $_SERVER ['HTTP_HOST'] . __ROOT2__ );

if (! defined ( "HTTP_URL" ))	
	define ( "HTTP_URL", "http://" . $_SERVER ['HTTP_HOST'] . __ROOT2__ );
	
$tpl->template_dir = ROOT_PATH . TMPL_PATH . FANWE_TMPL; //$langItem['tmpl'];
$tpl->cache_dir = ROOT_PATH . 'app/Runtime/caches';
$tpl->compile_dir = ROOT_PATH . 'app/Runtime/compiled';
$tpl->caching = TRUE;
$tpl->cache_lifetime = 3600;
$tpl->direct_output = false;
$tpl->force_compile = false;
//end 定义模板引擎
$tpl->assign ( 'TMPL_PATH', TMPL_PATH . FANWE_TMPL . '/' );
$tpl->assign ( 'TIME', a_gmtTime () );
$tpl->assign ( "lang", $GLOBALS ['Ln'] );
$tpl->assign ( "ROOT_PATH", __ROOT2__ );
$tpl->assign ( '__ROOT__', __ROOT2__ );
$tpl->assign ( 'CND_URL', CND_URL );
$tpl->assign ( 'HTTP_URL', HTTP_URL );

//输出用于POST提交的表单URL	
if (a_fanweC ( "URL_ROUTE" ) > 0)
	$tpl->assign ( "POST_URL", __ROOT2__ . '/' );
else
	$tpl->assign ( "POST_URL", __ROOT2__ . '/index.php' );
$tpl->assign ( 'CFG', $GLOBALS ['sys_config'] );
//print_r($tpl); exit;


?>