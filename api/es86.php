<?php

$es86_uCode ='SjXPh_mZS';/***需要根据不同你的蜘蛛的设置修改的配置参数***/

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('api/es86.php', '', str_replace('\\', '/', __FILE__)));
	
require ROOT_PATH.'app/source/db_init.php';
//require ROOT_PATH.'app/source/comm_init.php';
//require ROOT_PATH.'app/source/func/com_func.php';

function a_toDate2($time, $format = 'Y-m-d H:i:s')
{
	if (empty ($time))
		return '';

	$time = $time + FANWE_TIME_ZONE * 3600;
	$format = str_replace ('#',':',$format );
	return date ($format,$time );
}
$uCode = trim($_REQUEST['uCode']);
$mType = trim($_REQUEST['mType']);
if($uCode=='' || $uCode != $es86_uCode)
{
  die('Error:请求类型错误!');
}
$xml ='';
$msg ='';
switch ($mType)
{
     //订单查询
	 case 'mOrderSearch':
		 $TimeStamp1 = intval($_REQUEST['TimeStamp1']-date('Z'));
	     $TimeStamp2 = intval($_REQUEST['TimeStamp2']-date('Z'));
		 $con ='';
		 if($TimeStamp1>0) $con .= " and create_time >='$TimeStamp1' ";
		 if($TimeStamp2>0) $con .= " and create_time <='$TimeStamp2' ";

		 $con .= " and goods_status ='6' ";//只查询出：配货中的订单数据
		 
		 $sql ='select sn as order_sn from ' . DB_PREFIX . "order where 1 $con order by id desc ";
		 
		$order_list = $GLOBALS['db']->getAll($sql);
		
		$str_order ='';
		$i=0;			   
		foreach($order_list as $order){
		    $i++;
			$str_order .= '<OrderNO>'.trim($order['order_sn']).'</OrderNO>' . chr(13);			
		}
		 $xml.='<OrderList>' . chr(13);
		 $xml.='<OrderCount>' . $i . '</OrderCount>' . chr(13);
		 $xml.=$str_order;
		 $xml.='</OrderList>' . chr(13);
		 break;
	 //订单下载
	 case 'mGetOrder':
		 $OrderNO = trim($_REQUEST['OrderNO']);
	     /* 查询 */
        $sql = "SELECT o.id as order_id, o.sn as order_sn, o.create_time as add_time," .
                    " o.consignee, o.address, o.zip as zipcode, o.email, o.mobile_phone as tel, o.memo as postscript, o.adm_memo as to_buyer, " .
                    "o.order_total_price AS total_fee, o.delivery_fee as shipping_fee, " .
                    "IFNULL(u.user_name, '匿名用户') AS buyer, r1.name as country_name, r2.name as province_name, r3.name as  city_name, r4.name as  district_name ".
                " FROM " . DB_PREFIX . "order AS o " .
                " LEFT JOIN " .DB_PREFIX. "user AS u ON u.id=o.user_id ". 
			    " LEFT JOIN " .DB_PREFIX. "region_conf AS r1 ON r1.id=o.region_lv1 ".
			    " LEFT JOIN " .DB_PREFIX. "region_conf AS r2 ON r2.id=o.region_lv2 ".
			    " LEFT JOIN " .DB_PREFIX. "region_conf AS r3 ON r3.id=o.region_lv3 ".
			    " LEFT JOIN " .DB_PREFIX. "region_conf AS r4 ON r4.id=o.region_lv4 ".
			    " where o.sn= '$OrderNO' ";
		 $order_info = $GLOBALS['db']->getRow($sql);
		 
		 if($order_info)
	     {
		     $xml.='<Order>' . chr(13);
			 $xml.='<OrderNO><![CDATA['.$order_info['order_sn'].']]></OrderNO>' . chr(13);
             $xml.='<DateTime><![CDATA['.a_toDate2($order_info['add_time'],'Y-m-d H:I').']]></DateTime>' . chr(13);
             $xml.='<BuyerID><![CDATA['.$order_info['buyer'].']]></BuyerID>' . chr(13);
			 $xml.='<BuyerName><![CDATA['.$order_info['consignee'].']]></BuyerName>' . chr(13);
			 $xml.='<Country><![CDATA['.$order_info['country_name'].']]></Country>' . chr(13);
			 $xml.='<Province><![CDATA['.$order_info['province_name'].']]></Province>' . chr(13);
			 $xml.='<City><![CDATA['.$order_info['city_name'].']]></City>' . chr(13);
			 $xml.='<Town><![CDATA['.$order_info['district_name'].']]></Town>' . chr(13);
			 $xml.='<Adr><![CDATA['.$order_info['address'].']]></Adr>' . chr(13);
			 $xml.='<Zip><![CDATA['.$order_info['zipcode'].']]></Zip>' . chr(13);
			 $xml.='<Email><![CDATA['.$order_info['email'].']]></Email>' . chr(13);
			 $xml.='<Phone><![CDATA['.$order_info['tel'].']]></Phone>' . chr(13);
			 $xml.='<Total><![CDATA['.$order_info['total_fee'].']]></Total>' . chr(13);
			 $xml.='<Postage><![CDATA['.$order_info['shipping_fee'].']]></Postage>' . chr(13);
			 $xml.='<CustomerRemark><![CDATA['.$order_info['postscript'].']]></CustomerRemark>' . chr(13);
			 $xml.='<Remark><![CDATA['.$order_info['to_buyer'].']]></Remark>' . chr(13);

			 $sql_g = ' select rec_id as goods_id, data_sn as goods_sn, data_name as goods_name, data_price as goods_price, attr as goods_attr, number as goods_number from ' . DB_PREFIX . "order_goods where order_id='$order_info[order_id]'";
			 $order_goods = $GLOBALS['db']->getAll($sql_g);
	     	 foreach($order_goods as $row_g){
			     $xml.='<Item>' . chr(13);
				 $xml.='<GoodsID><![CDATA['.$row_g['goods_sn'].']]></GoodsID>' . chr(13);
				 $xml.='<GoodsName><![CDATA['.$row_g['goods_name'].']]></GoodsName>' . chr(13);
				 $xml.='<Price><![CDATA['.$row_g['goods_price'].']]></Price>' . chr(13);
				 $xml.='<Count><![CDATA['.$row_g['goods_number'].']]></Count>' . chr(13);
				 $xml.='</Item>' . chr(13);					
			 }	
			 $xml.='</Order>' . chr(13);
		 }
		 else
	     {
		    die("无此订单信息");
		 }
		 break;

	 default :
		 $xml='';
	     break;
}

ob_clean();
if($xml =='')
{
  die('结果为空');
}
else
{
  $xml='<?xml version="1.0" encoding="utf-8"?>' . chr(13) . $xml;
}
die($xml);
	
?>