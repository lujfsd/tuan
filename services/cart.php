<?php
//购物处理程序
//@session_start();
//require_once('services_init.php');
//require_once('common.php');
//require_once('system_init.php');
//require_once('com_function.php');
error_reporting(E_ALL ^ E_NOTICE);

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('services/cart.php', '', str_replace('\\', '/', __FILE__)));
	
require ROOT_PATH.'app/source/db_init.php';
require ROOT_PATH.'app/source/comm_init.php';
require ROOT_PATH.'app/source/func/com_func.php';
require ROOT_PATH.'app/source/func/com_order_pay_func.php';

//处理购物车统计
if($_REQUEST['m']=='Cart'&&$_REQUEST['a']=='getCartTotal')
{
	$_REQUEST['payment_id'] = trim($_REQUEST['payment_id']);
	$ilen = strpos($_REQUEST['payment_id'],'-');
	$bank_id = '';
	if ($ilen > 0){
		$bank_id = substr($_REQUEST['payment_id'],0,$ilen);
		$payment_id = substr($_REQUEST['payment_id'],$ilen + 1, strlen($_REQUEST['payment_id']) - $ilen);
	}else{
		$payment_id = intval($_REQUEST['payment_id']);
	}
	   	
	//echo 'back_id:'.$back_id;
	//echo '<br>payment_id:'.$payment_id;
	//exit;
	
   		$delivery_id = intval($_REQUEST['delivery_id']);
   		$is_protect = intval($_REQUEST['is_protect']);
   		$delivery_region = array(
   			'region_lv1'=>intval($_REQUEST['region_lv1']),
   			'region_lv2'=>intval($_REQUEST['region_lv2']),
   			'region_lv3'=>intval($_REQUEST['region_lv3']),
   			'region_lv4'=>intval($_REQUEST['region_lv4'])
   		);
   		$tax = intval($_REQUEST['tax']);
		$credit = floatval($_REQUEST['credit']);
		$isCreditAll = intval($_REQUEST['isCreditAll']);
		$ecvSn = trim($_REQUEST['ecvSn']);
		$ecvPassword = trim($_REQUEST['ecvPassword']);
   		$cart_total = s_countCartTotal($payment_id,$delivery_id,$is_protect,$delivery_region,$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
   		
   		if (!empty($bank_id)){
   			$cart_total['payment_name'] = a_L(strtoupper('TENCENT_'.$bank_id));
   		}
   		$GLOBALS['tpl']->assign("cart_total",$cart_total);
   		$cart_total['html'] = dotran($GLOBALS['tpl']->fetch("Inc/cart/cart_total.moban"));
   		//$cart_total['html'] = a_L('TENCENT_'.$bank_id);
   		header("Content-Type:text/html; charset=utf-8");
		echo json_encode($cart_total);
}

//处理订单购物统计
if($_REQUEST['m']=='Order'&&$_REQUEST['a']=='getOrderTotal')
{
	$_REQUEST['payment_id'] = trim($_REQUEST['payment_id']);
	$ilen = strpos($_REQUEST['payment_id'],'-');
	$bank_id = '';
	if ($ilen > 0){
		$bank_id = substr($_REQUEST['payment_id'],0,$ilen);
		$payment_id = substr($_REQUEST['payment_id'],$ilen + 1, strlen($_REQUEST['payment_id']) - $ilen);
	}else{
		$payment_id = intval($_REQUEST['payment_id']);
	}
		//$payment_id = intval($_REQUEST['payment_id']);
		
   		$delivery_id = intval($_REQUEST['delivery_id']);
   		$is_protect = intval($_REQUEST['is_protect']);
   		$delivery_region = array(
   			'region_lv1'=>intval($_REQUEST['region_lv1']),
   			'region_lv2'=>intval($_REQUEST['region_lv2']),
   			'region_lv3'=>intval($_REQUEST['region_lv3']),
   			'region_lv4'=>intval($_REQUEST['region_lv4'])
   		);
   		$tax = intval($_REQUEST['tax']);
		$credit = floatval($_REQUEST['credit']);
		$isCreditAll = intval($_REQUEST['isCreditAll']);
		$ecvSn = trim($_REQUEST['ecvSn']);
		$ecvPassword = trim($_REQUEST['ecvPassword']);
		$order_id = intval($_REQUEST['id']);
		
		$cart_total = s_countOrderTotal($order_id,$payment_id,$delivery_id,$is_protect,$delivery_region,$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
   		if (!empty($bank_id)){
   			$cart_total['payment_name'] = a_L(strtoupper('TENCENT_'.$bank_id));
   		}		
		$GLOBALS['tpl']->assign("cart_total",$cart_total);
   		$cart_total['html'] = dotran($GLOBALS['tpl']->fetch("Inc/cart/cart_total.moban"));
   		//$cart_total['html'] = $delivery_region['region_lv4'];
   		header("Content-Type:text/html; charset=utf-8");
		echo json_encode($cart_total);

}



//订单提交
if($_REQUEST['m']=='Cart'&&$_REQUEST['a']=='done')
{
	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	cart_done();
}

//订单提交
if($_REQUEST['m']=='Order'&&$_REQUEST['a']=='done')
{
	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	order_done();
}

//抽奖提交
if($_REQUEST['m']=='Lottery'&&$_REQUEST['a']=='done')
{
	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	lottery_done();
}

//读取配送方式
if($_REQUEST['m']=='Cart'&&$_REQUEST['a']=='loadDelivery')
{
	$region_id = intval($_REQUEST['id']);
	//输出配送列表
	$delivery_ids = loadDelivery($region_id);

	$delivery_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."delivery where status = 1");
    	
    foreach($delivery_list as $k=>$v)
    {
    	if(!in_array($v['id'],$delivery_ids))
    	{
    		unset($delivery_list[$k]);
    	}
    	else
    		$delivery_list[$k]['protect_radio'] = floatval($v['protect_radio'])."%";
    }
		
    $GLOBALS['tpl']->assign('delivery_list',$delivery_list);
    echo $GLOBALS['tpl']->fetch("Inc/cart/cart_delivery.moban");
}

//读取货到付款支持
if($_REQUEST['m']=='Cart'&&$_REQUEST['a']=='checkCod')
{
		$region_id = intval($_REQUEST['region_id']);
   		$delivery_id = intval($_REQUEST['delivery_id']);
   		$ids = '';
   		
		$delivery_info = $GLOBALS['db']->getRowCached("select id,allow_default,allow_cod from ".DB_PREFIX."delivery where status = 1 and id = ".$delivery_id);
   		$allow_cod = 0;

   		if($delivery_info)
   		{
   			$delivery_region_count = $GLOBALS['db']->getOneCached("select count(*) from ".DB_PREFIX."delivery_region where delivery_id = ".$delivery_info['id']);
   			if($delivery_info['allow_default'] == 1&&$delivery_region_count==0)
   			{
   				//允许默认
   				$allow_cod = $delivery_info['allow_cod'];
   			}
   			else 
   			{
				$delivery_region = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."delivery_region where delivery_id = ".$delivery_info['id']);
   				$tag = true;
   				$region_conf_child_ids = new ChildIds("region_conf");
   				foreach($delivery_region as $vv)
   				{
   					$region_ids = explode(",",$vv['region_ids']);
   					$tmp_ids = array();
   					foreach($region_ids as $vvv)
   					{
   						$tmp_ids = array_merge($tmp_ids,$region_conf_child_ids->getChildIds($vvv));
   					}
   					$region_ids = array_merge($region_ids,$tmp_ids);
   					if(in_array($region_id,$region_ids))
   					{
   						$allow_cod = $vv['allow_cod'];
   						$tag = false;
   						break;
   					}
   				}
   				if($tag)
   				{
   					if($v['allow_default'] == 1)
   					{
   						//允许默认
   						$allow_cod = $delivery_info['allow_cod'];
   					}
   				} 				
   			}
   		}
   		header("Content-Type:text/plain;charset=utf-8");
   		echo $allow_cod;
}

//读取货到付款支持
if($_REQUEST['m']=='Cart'&&$_REQUEST['a']=='checkCod2')
{
		$region_id = intval($_REQUEST['region_id']);
   		$delivery_id = intval($_REQUEST['delivery_id']);
   		$ids = '';
   		
		$delivery_info = $GLOBALS['db']->getRowCached("select id,allow_default,allow_cod,is_smzq from ".DB_PREFIX."delivery where status = 1 and id = ".$delivery_id);
   		$allow_cod = 0;
		$is_smzq = 0;
   		if($delivery_info)
   		{
   			$is_smzq = intval($delivery_info['is_smzq']);
   			$delivery_region_count = $GLOBALS['db']->getOneCached("select count(*) from ".DB_PREFIX."delivery_region where delivery_id = ".$delivery_info['id']);
   			if($delivery_info['allow_default'] == 1&&$delivery_region_count==0)
   			{
   				//允许默认
   				$allow_cod = $delivery_info['allow_cod'];
   			}
   			else 
   			{
				$delivery_region = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."delivery_region where delivery_id = ".$delivery_info['id']);
   				$tag = true;
   				$region_conf_child_ids = new ChildIds("region_conf");
   				foreach($delivery_region as $vv)
   				{
   					$region_ids = explode(",",$vv['region_ids']);
   					$tmp_ids = array();
   					foreach($region_ids as $vvv)
   					{
   						$tmp_ids = array_merge($tmp_ids,$region_conf_child_ids->getChildIds($vvv));
   					}
   					$region_ids = array_merge($region_ids,$tmp_ids);
   					if(in_array($region_id,$region_ids))
   					{
   						$allow_cod = $vv['allow_cod'];
   						$tag = false;
   						break;
   					}
   				}
   				if($tag)
   				{
   					if($v['allow_default'] == 1)
   					{
   						//允许默认
   						$allow_cod = $delivery_info['allow_cod'];
   					}
   				} 				
   			}
   		}
   		$result  =  array();
		$result['allow_cod']  =  $allow_cod;
		$result['is_smzq'] = $is_smzq;
   		header("Content-Type:text/html; charset=utf-8");
		echo json_encode($result);   		
}
?>