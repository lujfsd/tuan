<?php

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/inc.php', '', str_replace('\\', '/', __FILE__)));
	
require ROOT_PATH.'app/source/db_init.php';
header("Content-Type:text/html; charset=utf-8");
$uCode = trim($_REQUEST['uCode']);
$mType = intval($_REQUEST['mType']);

$sql = "select val from ".DB_PREFIX."sys_conf where name = 'KUAIDI_APP_KEY'";	
$inc_uCode = $GLOBALS['db']->getOneCached($sql);
if ($inc_uCode == ''){
	$inc_uCode = 'fanwe';//防止别人恶意刷，所以添加一个参数
}

if($uCode=='' || $uCode != $inc_uCode)
{
  die('Error:请求类型错误!');
}

$time = a_gmtTime();
$sql = "update ".DB_PREFIX."goods set virtual_count = virtual_count + interval_num, buy_count = buy_count + interval_num where status = 1 and promote_begin_time <= $time and promote_end_time >= $time and interval_num > 0 and is_group_fail =2";
$GLOBALS['db']->query($sql);
$rs = $GLOBALS['db']->affected_rows();
if ($mType >0){
	$sql = "update ".DB_PREFIX."goods set buy_count = virtual_count + (select count(*) as number from ".DB_PREFIX."lottery_no where goods_id= ".DB_PREFIX."goods.id)"
			."where score_goods = 2 and status = 1 and promote_begin_time <= $time and promote_end_time >= $time and interval_time >0 and interval_num > 0 and is_group_fail =2";
	$GLOBALS['db']->query($sql);
	$sql = "update ".DB_PREFIX."goods set buy_count = virtual_count + (select sum(og.number) as number from ".DB_PREFIX."order as o left join ".DB_PREFIX."order_goods  as og on og.order_id = o.id where og.rec_id = ".DB_PREFIX."goods.id and o.money_status = 2)"
			."where score_goods != 2 and status = 1 and promote_begin_time <= $time and promote_end_time >= $time and interval_time >0 and interval_num > 0 and is_group_fail =2";
	$GLOBALS['db']->query($sql);			
}

echo "更新商品记录数:".$rs;
	
?>