<?php
// 用于分离框架的程序所需的引用
if(version_compare(PHP_VERSION,'6.0.0','<') ) {
    @set_magic_quotes_runtime (0);
    define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?True:False);
}

//过滤请求
function a_filter_request(&$request)
{
	if(MAGIC_QUOTES_GPC)
	{
		foreach($request as $k=>$v)
		{
			if(is_array($v))
			{
				a_filter_request($v);
			}
			else
			{
				$request[$k] = stripslashes(trim($v));
			}
		}
	}	
}

a_filter_request($_REQUEST);
a_filter_request($_POST);
a_filter_request($_GET);

if(!defined('IS_CGI'))
define('IS_CGI',substr(PHP_SAPI, 0,3)=='cgi' ? 1 : 0 );
//定义__ROOT__常量
 if(!defined('_PHP_FILE_')) {
        if(IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp  = explode('.php',$_SERVER["PHP_SELF"]);
            define('_PHP_FILE_',  rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
        }else {
            define('_PHP_FILE_',    rtrim($_SERVER["SCRIPT_NAME"],'/'));
        }
    }
   
if(!defined('__ROOT__')) {
        // 网站URL根目录
        $_root = dirname(_PHP_FILE_);
        $_root = (($_root=='/' || $_root=='\\')?'':$_root);
        $_root = str_replace("/services","",$_root);
        define('__ROOT__', $_root  );
}
//引入数据库的系统配置及定义配置函数

//定义缓存
//require ROOT_PATH.'services/Utils/Cache.php';
//$cache = CacheService::getInstance("File");
//end 定义缓存

$timezone = a_fanweC("TIME_ZONE");

if(!is_dir(ROOT_PATH.'mobile/Runtime/caches/'))
	mkdir(ROOT_PATH.'mobile/Runtime/caches/');
	
if(!is_dir(ROOT_PATH.'mobile/Runtime/compiled/'))
	mkdir(ROOT_PATH.'mobile/Runtime/compiled/');

if(!is_dir(ROOT_PATH.'mobile/Runtime/sessionid/'))
	mkdir(ROOT_PATH.'mobile/Runtime/sessionid/');
	
	
require ROOT_PATH.'app/source/class/template.php';

$tpl = new template;

define("TMPL_PATH",ROOT_PATH ."mobile/Tpl/default");

if(!defined("CND_URL"))
	define("CND_URL",a_fanweC("CND_URL")!=''? a_fanweC("CND_URL") :"http://".$_SERVER['HTTP_HOST'].__ROOT__);

if (! defined ( "HTTP_URL" ))	
	define ( "HTTP_URL",str_replace("wap","",str_replace("3g","",str_replace("m.","",$_SERVER ['HTTP_HOST'] . __ROOT__))) );

define('VENDOR_PATH', ROOT_PATH.'ThinkPHP/Vendor/');

$tpl->template_dir   = ROOT_PATH ."mobile/Tpl/default";
$tpl->cache_dir      = ROOT_PATH . 'mobile/Runtime/caches';
$tpl->compile_dir    = ROOT_PATH . 'mobile/Runtime/compiled';
$tpl->caching = TRUE;
$tpl->cache_lifetime = 3600;
$tpl->direct_output = false;
$tpl->force_compile = false;

//end 定义模板引擎
$tpl->assign('TMPL_PATH', "mobile/Tpl/default/");
$tpl->assign('TIME',a_gmtTime());
$tpl->assign("lang",$GLOBALS['Ln']);
$tpl->assign("ROOT_PATH",__ROOT__);
$tpl->assign('__ROOT__',__ROOT__);
$tpl->assign('CND_URL',CND_URL);
$tpl->assign ( 'HTTP_URL', HTTP_URL );


		//输出用于POST提交的表单URL	
if(a_fanweC("URL_ROUTE")>0)
	$tpl->assign("POST_URL",__ROOT__.'/');
else
	$tpl->assign("POST_URL",__ROOT__.'/index.php');
$tpl->assign('CFG',$GLOBALS['sys_config']);

?>