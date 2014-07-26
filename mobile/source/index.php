<?php
require ROOT_PATH.'mobile/source/db_init.php';
require ROOT_PATH.'mobile/source/com_init.php';
require ROOT_PATH.'app/source/func/com_func.php';
require ROOT_PATH.'app/source/func/com_order_pay_func.php';
require ROOT_PATH.'app/source/func/com_send_sms_func.php';
require ROOT_PATH.'mobile/source/func/m_func.php';
if(a_fanweC('SHOP_CLOSED'))
{
	$GLOBALS['tpl']->display("Page/close.html");
	exit();
}
define("C_CITY_ID",getCurrentCityID());
$_SESSION['C_CITY_ID'] = C_CITY_ID;

if (!isset($_REQUEST['m']) || empty($_REQUEST['m'])){
	$_REQUEST['m'] = 'Index';
}
	      		
if (!isset($_REQUEST['a']) || empty($_REQUEST['a'])){
	$_REQUEST['a'] = 'index';
}
global $user_info;
$user_info = initSession();

if(is_array($user_info))
{
	$tpl->assign("user_info",$user_info);
}

define("USER_ID",intval($user_info['id']));
define("GROUP_ID",intval($user_info['group_id']));

$city_list = getGroupCityList();
$GLOBALS['tpl']->assign("city_list",$city_list);

$currentCity = $GLOBALS['db']->getRowCached("SELECT id,py,name,notice,qq_1,qq_2,qq_3,qq_4,qq_5,qq_6 FROM ".DB_PREFIX."group_city where id = ".C_CITY_ID);
$default_city = $GLOBALS['db']->getRowCached("SELECT id,py,name,notice FROM ".DB_PREFIX."group_city where verify = 1 and is_defalut =1");
$GLOBALS['tpl']->assign("currentCity",$currentCity);
$GLOBALS['tpl']->assign("default_city",$default_city);
$GLOBALS['tpl']->assign("lang",$GLOBALS['Ln']);
$tpl->assign('SHOP_NAME',SHOP_NAME);
$tpl->assign("referralsMoney",a_formatPrice(a_fanweC('REFERRALS_MONEY')));

$shop_title = str_replace("{\$city_name}",$currentCity['name'],$GLOBALS['langItem']['shop_title']);;

$GLOBALS['tpl']->assign('shop_title',$shop_title);
$ma = strtolower(htmlspecialchars($_REQUEST['m']).'_'.$_REQUEST['a']);
switch ($ma)
{
	case "cart_index":
	case "cart_check":
	case "cart_done":
			require ROOT_PATH.'mobile/source/cart.php';
		break;
	case "user_login":
	case "user_dologin":
	case "user_dologinout":
			require ROOT_PATH.'mobile/source/user.php';
		break;
	case "usercenter_index":
	case "usercenter_order":
	case "usercenter_account":
			require ROOT_PATH.'mobile/source/usercenter.php';
		break;
	case "coupon_index":
	case "coupon_check":
	case "coupon_bus":
			require ROOT_PATH.'mobile/source/coupon.php';
		break;
	default:
		if (is_file(ROOT_PATH.'mobile/source/'.$ma.'.php')){
			require ROOT_PATH.'mobile/source/'.$ma.'.php';
		}else{
			echo 'App "'.$ma.'" not exists!';
		}
		break;
}
?>