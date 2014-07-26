<?php
$ma = $_REQUEST ['m'] . "_" . strtolower ( $_REQUEST ['a'] );
$ma ();

function Ajax_smssubscribe() {
	
	if(intval(a_fanweC("SMS_SUBSCRIBE"))==0)
		exit();
	
	//短信订阅
	$result = array ("type" => 0, "message" => "" );
	
	if(!check_referer())
	{
		$result ["message"] = a_L('_OPERATION_FAIL_');
		echo json_encode ( $result );
		exit ();
	}
	
	$isSmsSubscribe = stripslashes ( a_fanweC ( "SMS_SUBSCRIBE" ) );
	
	$city_id = intval ( $_REQUEST ['city'] );
	$mobile_phone = trim ( $_REQUEST ['mobile'] );
	$verify = trim ( $_REQUEST ['verify'] );
	//$isMobile = preg_match ( "/^(13\d{9}|14\d{9}|18\d{9}|15\d{9})|(0\d{9}|9\d{8})$/", $mobile_phone );
	$isMobile = preg_match ( "/^(\d+)$/", $mobile_phone );
	if (intval ( $isSmsSubscribe ) == 1 && $city_id > 0 && $isMobile == 1) {
		if (md5 ( $verify ) != $_SESSION ["smsSubscribe"] && ! empty ( $_SESSION ["smsSubscribe"] )) {
			$result ["message"] = $GLOBALS ['lang'] ['XY_VERIFY_ERROR'];
			echo json_encode ( $result );
			exit ();
		}
		unset($_SESSION ["smsSubscribe"]);
		if(isset($_SESSION['smsSubscribePhone'])&&$_SESSION['smsSubscribePhone'] == $mobile_phone)
		{
			if (!check_ip_operation ( $_SESSION['CLIENT_IP'], "Ajax_smssubscribe", 60, $mobile_phone )) 
			{//防刷60秒
				$result ["message"] = sprintf(a_l("PLEASE_WAIT_TIME"),60);
				echo json_encode ( $result );
				exit ();
			}
		}
		
		$smsSubscribe = $GLOBALS ['db']->getRow ( "select `id`,`mobile_phone`,`city_id`,`code`,`status`,`user_id`,`add_time`,`send_count` from " . DB_PREFIX . "sms_subscribe where mobile_phone = '$mobile_phone' and city_id = '$city_id'" );
		
		if (isset ( $smsSubscribe ['status'] ) && intval ( $smsSubscribe ['status'] ) == 1) {
			$result ["type"] = 2;
		} else {
			$user_id = intval ( $_SESSION ['user_id'] );
			$add_time = a_gmtTime ();
			$tempcode = unpack ( 'H4', str_shuffle ( md5 ( uniqid () ) ) );
			$code = $tempcode [1];
			
			require (ROOT_PATH . 'services/Sms/SmsPlf.class.php');
			
			$mail_template = $GLOBALS ['db']->getRow ( "select `id`,`name`,`mail_title`,`mail_content`,`is_html` from " . DB_PREFIX . "mail_template where name = 'sms_subscribe_code'" );
			
			if ($mail_template) {
				$GLOBALS ['tpl']->assign ( 'code', $code );
				$message = $GLOBALS ['tpl']->fetch_str ( $mail_template ['mail_content'] );
				$message = $GLOBALS ['tpl']->_eval ( $message );
			}
			
			if (! empty ( $message )) {
				$mobiles [] = $mobile_phone;
				
				$sms = new SmsPlf ( );
				
				if ($sms->sendSMS ( $mobiles, $message )) {
					$result ["type"] = 1;
					
					if (isset ( $smsSubscribe ['id'] ) && intval ( $smsSubscribe ['id'] ) > 0) {
						$sql = "update " . DB_PREFIX . "sms_subscribe set code = '$code' where id = '$smsSubscribe[id]'";
						$GLOBALS ['db']->query ( $sql );
					} else {
						$sql = "insert into " . DB_PREFIX . "sms_subscribe  (mobile_phone,city_id,code,status,user_id,add_time,send_count) values('$mobile_phone','$city_id','$code',0,'$user_id','$add_time',0)";
						$GLOBALS ['db']->query ( $sql );
					}
				} else
					$result ["message"] = $GLOBALS ['lang'] ['XY_SMS_SEND_ERROR'];
			} else
				$result ["message"] = $GLOBALS ['lang'] ['XY_SMS_SEND_ERROR'];
		}
		$_SESSION['smsSubscribePhone'] = $mobile_phone;
		echo json_encode ( $result );
	}
}

function Ajax_smssubscribecode() {
	//验证短信认识码
	
	if(intval(a_fanweC("SMS_SUBSCRIBE"))==0)
		exit();
	

	$result = array ("type" => 0, "message" => "" );
	
	if(!check_referer())
	{
		$result ["message"] = a_L('_OPERATION_FAIL_');
		echo json_encode ( $result );
		exit ();
	}
	
	$isSmsSubscribe = stripslashes ( a_fanweC ( "SMS_SUBSCRIBE" ) );
	
	$city_id = intval ( $_REQUEST ['city'] );
	$mobile_phone = trim ( $_REQUEST ['mobile'] );
	$code = trim ( $_REQUEST ['code'] );
	//$isMobile = preg_match ( "/^(13\d{9}|14\d{9}|18\d{9}|15\d{9})|(0\d{9}|9\d{8})$/", $mobile_phone );
	$isMobile = preg_match ( "/^(\d+)$/", $mobile_phone );
	if (intval ( $isSmsSubscribe ) == 1 && $city_id > 0 && $isMobile == 1 && ! empty ( $code )) {
		$smsSubscribe = $GLOBALS ['db']->getRow ( "select `id`,`mobile_phone`,`city_id`,`code`,`status`,`user_id`,`add_time`,`send_count` from " . DB_PREFIX . "sms_subscribe where mobile_phone = '$mobile_phone' and city_id = '$city_id' and code = '$code'" );
		
		if ($smsSubscribe !== false) {
			$sql = "update " . DB_PREFIX . "sms_subscribe set status = 1 where id = '$smsSubscribe[id]'";
			$GLOBALS ['db']->query ( $sql );
			$result ["type"] = 1;
		} else
			$result ["message"] = $GLOBALS ['lang'] ['XY_CODE_ERROR'];
		
		echo json_encode ( $result );
	}
}

function Ajax_unsmssubscribe() {
	
	if(intval(a_fanweC("SMS_SUBSCRIBE"))==0)
		exit();
	
	//短信退订
	
	$result = array ("type" => 0, "message" => "" );
	
	if(!check_referer())
	{
		$result ["message"] = a_L('_OPERATION_FAIL_');
		echo json_encode ( $result );
		exit ();
	}
	
	$isSmsSubscribe = stripslashes ( a_fanweC ( "SMS_SUBSCRIBE" ) );
	
	$city_id = intval ( $_REQUEST ['city'] );
	$mobile_phone = trim ( $_REQUEST ['mobile'] );
	$verify = trim ( $_REQUEST ['verify'] );
	//$isMobile = preg_match ( "/^(13\d{9}|14\d{9}|18\d{9}|15\d{9})|(0\d{9}|9\d{8})$/", $mobile_phone );
	$isMobile = preg_match ( "/^(\d+)$/", $mobile_phone );
	if (intval ( $isSmsSubscribe ) == 1 && $city_id > 0 && $isMobile == 1) {
		if (md5 ( $verify ) != $_SESSION ["smsSubscribe"] && ! empty ( $_SESSION ["smsSubscribe"] )) {
			$result ["message"] = $GLOBALS ['lang'] ['XY_VERIFY_ERROR'];
			echo json_encode ( $result );
			exit ();
		}
		unset($_SESSION ["smsSubscribe"]);
		if(isset($_SESSION['unsubscribePhone'])&&$_SESSION['unsubscribePhone'] == $mobile_phone)
		{
			if (!check_ip_operation ( $_SESSION['CLIENT_IP'], "Ajax_unsmssubscribe", 60, $mobile_phone ))
			{//防刷60秒
				$result ["message"] = sprintf(a_l("PLEASE_WAIT_TIME"),60);
				echo json_encode ( $result );
				exit ();
			}
		}
		
		$smsSubscribe = $GLOBALS ['db']->getRow ( "select `id`,`mobile_phone`,`city_id`,`code`,`status`,`user_id`,`add_time`,`send_count` from " . DB_PREFIX . "sms_subscribe where mobile_phone = '$mobile_phone' and city_id = '$city_id'" );
		
		if (isset ( $smsSubscribe ['status'] ) && intval ( $smsSubscribe ['id'] ) > 0) {
			$tempcode = unpack ( 'H4', str_shuffle ( md5 ( uniqid () ) ) );
			$code = $tempcode [1];
			
			require (ROOT_PATH . 'services/Sms/SmsPlf.class.php');
			
			$mail_template = $GLOBALS ['db']->getRow ( "select `id`,`name`,`mail_title`,`mail_content`,`is_html` from " . DB_PREFIX . "mail_template where name = 'sms_unsubscribe_code'" );
			
			if ($mail_template) {
				$GLOBALS ['tpl']->assign ( 'code', $code );
				$message = $GLOBALS ['tpl']->fetch_str ( $mail_template ['mail_content'] );
				$message = $GLOBALS ['tpl']->_eval ( $message );
			}
			
			if (! empty ( $message )) {
				$mobiles [] = $mobile_phone;
				
				$sms = new SmsPlf ( );
				
				if ($sms->sendSMS ( $mobiles, $message )) {
					$result ["type"] = 1;
					
					$sql = "update " . DB_PREFIX . "sms_subscribe set code = '$code' where id = '$smsSubscribe[id]'";
					$GLOBALS ['db']->query ( $sql );
				} else
					$result ["message"] = $GLOBALS ['lang'] ['XY_SMS_SEND_ERROR'];
			} else
				$result ["message"] = $GLOBALS ['lang'] ['XY_SMS_SEND_ERROR'];
		} else {
			$cityName = $GLOBALS ['db']->getOneCached ( "select name from " . DB_PREFIX . "group_city where id = '$city_id'" );
			$result ["message"] = sprintf ( $GLOBALS ['lang'] ['XY_SMS_SUBSCRIBE_NO'], $cityName );
		}
		$_SESSION['unsubscribePhone'] = $mobile_phone;
		echo json_encode ( $result );
	}
}
function Ajax_unsmssubscribecode() {
	//验证短信退订码
	
	if(intval(a_fanweC("SMS_SUBSCRIBE"))==0)
		exit();

	$result = array ("type" => 0, "message" => "" );
	
	if(!check_referer())
	{
		$result ["message"] = a_L('_OPERATION_FAIL_');
		echo json_encode ( $result );
		exit ();
	}
	
	$isSmsSubscribe = stripslashes ( a_fanweC ( "SMS_SUBSCRIBE" ) );
	
	$city_id = intval ( $_REQUEST ['city'] );
	$mobile_phone = trim ( $_REQUEST ['mobile'] );
	$code = trim ( $_REQUEST ['code'] );
	//$isMobile = preg_match ( "/^(13\d{9}|14\d{9}|18\d{9}|15\d{9})|(0\d{9}|9\d{8})$/", $mobile_phone );
	$isMobile = preg_match ( "/^(\d+)$/", $mobile_phone );
	if (intval ( $isSmsSubscribe ) == 1 && $city_id > 0 && $isMobile == 1 && ! empty ( $code )) {
		$smsSubscribe = $GLOBALS ['db']->getRow ( "select `id`,`mobile_phone`,`city_id`,`code`,`status`,`user_id`,`add_time`,`send_count` from " . DB_PREFIX . "sms_subscribe where mobile_phone = '$mobile_phone' and city_id = '$city_id' and code = '$code'" );
		
		if ($smsSubscribe !== false) {
			$sql = "delete from " . DB_PREFIX . "sms_subscribe where id = '$smsSubscribe[id]'";
			$GLOBALS ['db']->query ( $sql );
			$result ["type"] = 1;
		} else
			$result ["message"] = $GLOBALS ['lang'] ['XY_UNCODE_ERROR'];
		
		echo json_encode ( $result );
	}
}
function Ajax_showmap() {
	$id = $_REQUEST ['id'];
	$cached_id = C_CITY_ID . "_Ajax_showmap#" . md5 ( $id );
	if (! $GLOBALS ['tpl']->is_cached ( "Page/big_map.moban", $cached_id )) {
		$supplier = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "suppliers where id={$id}" );
		$supplier ['main'] = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "suppliers_depart where supplier_id=" . $supplier ['id'] . " and is_main=1" );
		$GLOBALS ['tpl']->assign ( "supplier", $supplier );
	}
	$GLOBALS ['tpl']->display ( "Page/big_map.moban", $cached_id );
}
function Ajax_ecvverify() {
	
	$result = check_ecvverify(trim ($_REQUEST ['sn']), trim ($_REQUEST ['pwd']));
	header ( "Content-Type:text/html; charset=utf-8" );
	echo json_encode ( $result );
	/*	
	if (intval ( $_SESSION ['user_id'] ) < 1) {
		echo json_encode ( array ("type" => 0, "msg" => a_L ( 'PLEASE_LOGIN' ), "ecv" => "" ) );
		exit ();
	}
	$result = array ("type" => 0, "msg" => "", "ecv" => "" );
	$sn = trim ( $_REQUEST ['sn'] );
	$password = trim ( $_REQUEST ['pwd'] );
	$result = array ("type" => 0, "msg" => "", "ecv" => "" );
	$ecv = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "ecv where sn='{$sn}' and password='{$password}' and type=0" );
	$ecv ['ecvType'] = $GLOBALS ['db']->getRowCached ( "select `money`,`use_start_date`,`use_end_date`,`status`,use_count from " . DB_PREFIX . "ecv_type where id='{$ecv['ecv_type']}'" );
	if ($ecv) {
		//计算会员，已经获得的同类代金券数量 add by chenfq 2011-03-09
		$sql = "select count(*) from " . DB_PREFIX . "ecv where id <> ".$ecv['id']." and use_user_id = ".intval ( $_SESSION ['user_id'] )." and ecv_type =" .intval ( $ecv['ecv_type'] );
		$use_count = intval($GLOBALS ['db']->getOne($sql));
		if ($ecv['ecvType']['use_count'] <= $use_count && intval($ecv['user_id']) != intval($_SESSION ['user_id'])){ //
			$result ['msg'] = a_L ( "INVALID_VOUCHER" );
		}else{
			$time = a_gmtTime ();
			if (intval ( $ecv ['user_id'] ) > 0 && intval ( $ecv ['user_id'] ) != intval ( $_SESSION ['user_id'] ))
				$result ['msg'] = a_L ( "HC_ECV_HAS_DELIVERY_TO_OTHER_USER" );
			elseif (intval ( $ecv ['use_date_time'] ) > 0)
				$result ['msg'] = sprintf ( a_L ( "HC_ECV_HAS_USE_STR" ), $ecv ['useUser'] ['user_name'], a_toDate ( $ecv ['use_date_time'], a_L ( "HC_DATETIME_FORMAT" ) ) );
			elseif (intval ( $ecv ['ecvType'] ['status'] ) == 0)
				$result ['msg'] = a_L ( "HC_ECV_HAS_FORBID" );
			elseif ($time < intval ( $ecv ['ecvType'] ['use_start_date'] ))
				$result ['msg'] = sprintf ( a_L ( "HC_ECV_NOT_BEGIN_STR" ), a_toDate ( $ecv ['ecvType'] ['use_start_date'], a_L ( "HC_DATETIME_SHORT_FORMAT" ) ) );
			elseif ($time > intval ( $ecv ['ecvType'] ['use_end_date'] ) && intval ( $ecv ['ecvType'] ['use_end_date'] ) > 0)
				$result ['msg'] = sprintf ( a_L ( "HC_ECV_EXPIRED_STR" ), a_toDate ( $ecv ['ecvType'] ['use_end_date'], a_L ( "HC_DATETIME_SHORT_FORMAT" ) ) );
			else {
				$ecv ['money'] = a_formatPrice ( floatval ( $ecv ['ecvType'] ['money'] ) );
				$ecv ['use_start_date'] = (intval ( $ecv ['ecvType'] ['use_start_date'] ) > 0) ? a_toDate ( $ecv ['ecvType'] ['use_start_date'], a_L ( "HC_DATETIME_SHORT_FORMAT" ) ) : a_L ( "HC_NOT_LIMIT" );
				$ecv ['use_end_date'] = (intval ( $ecv ['ecvType'] ['use_end_date'] ) > 0) ? a_toDate ( $ecv ['ecvType'] ['use_end_date'], a_L ( "HC_DATETIME_SHORT_FORMAT" ) ) : a_L ( "HC_NOT_LIMIT" );
				$result ['msg'] = "";
				$result ['type'] = 1;
				$result ['ecv'] = $ecv;
			}			
		}
	} else {
		$result ['msg'] = a_L ( "HC_ECV_NOT_EXIST" );
	}
	header ( "Content-Type:text/html; charset=utf-8" );
	echo json_encode ( $result );
*/
}
function Ajax_verify() {
	require (ROOT_PATH . "app/source/class/Image.class.php");
	Image::buildImageVerify ( 4, 3, 'png', 50, 24, 'smsSubscribe' ,'123456789');
}

function Ajax_smslottery() {
	//抽奖短信认证
	$result = array ("type" => 0, "message" => "" );
	if (isset ( $_SESSION ['smsLottery_time'] )) {
		if (a_gmtTime () - intval ( $_SESSION ['smsLottery_time'] ) < 300) {
			$result ['type'] = 3;
			echo json_encode ( $result );
			exit ();
		}
	}
	
	if(!check_referer())
	{
		$result ["message"] = a_L('_OPERATION_FAIL_');
		echo json_encode ( $result );
		exit ();
	}
	
	$mobile_phone = trim ( $_REQUEST ['mobile'] );
	$goods_id = intval ( $_REQUEST ['goods_id'] );
	//$isMobile = preg_match ( "/^(13\d{9}|14\d{9}|18\d{9}|15\d{9})|(0\d{9}|9\d{8})$/", $mobile_phone );
	$isMobile = preg_match ( "/^(\d+)$/", $mobile_phone );
	if ($isMobile == 1) {
		if (! isset ( $GLOBALS ['lang'] )) {
			global $lang;
			$lang = include (ROOT_PATH . 'app/Lang/' . LANG . '/xy_lang.php');
		}
		
		$smsSubscribe = $GLOBALS ['db']->getRow ( "select `id`,`mobile_phone`,`code`,`status`,`user_id`,`add_time`,`send_count` from " . DB_PREFIX . "sms_subscribe where mobile_phone = '$mobile_phone' and goods_id = '$goods_id'" );
		
		if (isset ( $smsSubscribe ) && $smsSubscribe ['status'] == 1) {
			$result ["type"] = 2;
		} else {
			$user_id = intval ( $_SESSION ['user_id'] );
			$add_time = a_gmtTime ();
			$tempcode = unpack ( 'H4', str_shuffle ( md5 ( uniqid () ) ) );
			$code = $tempcode [1];
			
			include ROOT_PATH . '/services/Sms/SmsPlf.class.php';
			
			$mail_template = $db->getRowCached ( "select `id`,`name`,`mail_title`,`mail_content`,`is_html` from " . DB_PREFIX . "mail_template where name = 'sms_lottery_code'" );
			$goods = $db->getRowCached ( "select `name_1`,`goods_short_name` from " . DB_PREFIX . "goods where id = '$goods_id' " );
			
			$goods_name = $goods ['goods_short_name'];
			if (empty ( $goods_name )) {
				$goods_name = $goods ['name_1'];
			}
			
			if ($mail_template) {
				$tpl->assign ( 'code', $code );
				$tpl->assign ( 'goods_name', $goods_name );
				$message = $tpl->fetch_str ( $mail_template ['mail_content'] );
				$message = $tpl->_eval ( $message );
			}
			
			if (! empty ( $message )) {
				$mobiles [] = $mobile_phone;
				
				$sms = new SmsPlf ( );
				
				if ($sms->sendSMS ( $mobiles, $message )) {
					$result ["type"] = 1;
					$_SESSION ['smsLottery_time'] = a_gmtTime ();
					$sql = "insert into " . DB_PREFIX . "sms_subscribe(mobile_phone,city_id,code,status,user_id,add_time,send_count,goods_id) values('$mobile_phone',0,'$code',0,'$user_id','$add_time',1,'$goods_id')";
					$GLOBALS ['db']->query ( $sql );
				
				} else
					$result ["message"] = $GLOBALS ['lang'] ['XY_SMS_SEND_ERROR'];
			} else
				$result ["message"] = $GLOBALS ['lang'] ['XY_SMS_SEND_ERROR'];
		}
		
		echo json_encode ( $result );
	}
}

function Ajax_getcartinfo()
{
	$data=array("CARTNUM"=>0,"CARTTOTAL"=>0);
	if(session_id())
	{
		$sql = "SELECT sum(`number`) AS tp_count, sum(`data_total_price`) as totalprice FROM `".DB_PREFIX."cart` WHERE session_id = '".session_id()."' ";
		$res = $GLOBALS['db']->getRow($sql);
		$data['CARTNUM'] = intval($res['tp_count']);
		$data['CARTTOTAL'] = a_formatPrice($res['totalprice']);
	}
	echo json_encode($data);
	exit();
}

function Ajax_close_top_adv() {
	$_SESSION ['close_ad_top'] = 1;
}
//获取下级城市
function Ajax_getsubcitys()
{
	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0 ;
	if($id > 0)
	{
		$sql="select id,name,py from ".DB_PREFIX."group_city where pid={$id} and status=1 and verify=1 order by sort asc";
		$rs = $GLOBALS['db']->getAllCached($sql);
		if($rs)
		{
			foreach($rs as $idx => $v)
			{
				if(C_CITY_ID == intval($v['id']))
				{
					echo "<li class='current'><a href='",a_u('Index/index','cityname-'.$v['py']),"' rel=",$id,">",$v['name'],"</a></li>";
				}
				else
				{
					echo "<li><a href='",a_u('Index/index','cityname-'.$v['py']),"' rel=",$id,">",$v['name'],"</a></li>";
				}
			}
		}
		else
			echo 0;
	}
	else
		echo 0;
}
function Ajax_gettypeattr() {
	
	$lang_envs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."lang_conf");
	$type_id = $_REQUEST['type_id'];
	$supplier_goods_id = intval($_REQUEST['supplier_goods_id']);
	
	$attr_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_type_attr where type_id=".$type_id);
	
	if($attr_list)
	{
		foreach($attr_list as $k=>$attr_item)
		{
			
			$value_list =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attr where attr_id=".$attr_item['id']." and supplier_goods_id=".$supplier_goods_id." and goods_id=0");
			//获取出当前属性下的所有属性值
			$attr_list[$k]['row_count'] = $GLOBALS['db']->getone("select count(*) from ".DB_PREFIX."goods_attr where attr_id=".$attr_item['id']." and supplier_goods_id=".$supplier_goods_id." and goods_id=0");
			foreach($value_list as $value_key => $value_row)
			{
				foreach($lang_envs as $lang_item)
				{
					//已有值
					$attr_list[$k]['value_'.$lang_item['id']][$value_key] = $value_row['attr_value_'.$lang_item['id']]?trim($value_row['attr_value_'.$lang_item['id']]):"";
					$attr_list[$k]['price'][$value_key] = $value_row['price']?$value_row['price']:"";
					//$attr_list[$k]['stock'][$value_key] = $value_row['stock']?$value_row['stock']:""; 
				}
			}
			
			foreach($lang_envs as $lang_item)
			{
				//可选值
				$attr_list[$k]['attr_value_'.$lang_item['id']] = explode("\n",$attr_item['attr_value_'.$lang_item['id']]);
				foreach($attr_list[$k]['attr_value_'.$lang_item['id']] as $kkk=>$vvv)
				{
					$attr_list[$k]['attr_value_'.$lang_item['id']][$kkk] = trim($vvv);	
				}
			}
		}
	}
	else 
	{
		$attr_list = array();
	}
	header("Content-Type:text/html; charset=utf-8");
	echo json_encode($attr_list);
}
?>