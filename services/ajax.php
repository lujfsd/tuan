<?php

error_reporting(E_ALL ^ E_NOTICE);

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('services/ajax.php', '', str_replace('\\', '/', __FILE__)));
	
require ROOT_PATH.'app/source/db_init.php';



// 生成soso文件 by awfigq 2010/07/23
//require_once('sdd.php');
/*
if(!defined('SHOP_NAME'))   
{
	if(isset($_SESSION['langItem']))
	{
		$langItem = $_SESSION['langItem'];
	}
	else 
	{
		$langItem = $db->getRow("SELECT `id`,`lang_name`,`show_name`,`time_zone`,`tmpl`,`seokeyword`,`seocontent`,`shop_title`,`shop_name`,`default`,`currency` FROM ".$db_config['DB_PREFIX']."lang_conf WHERE lang_name='$langSet'");
		$_SESSION['langItem'] = $langItem;
	}
	define("SHOP_NAME",$langItem['shop_name']);
}
   */

if($_REQUEST['run']=='changeMobile')
{
	header("Content-Type:text/html; charset=utf-8");
    $order_id = intval($_REQUEST['order_id']);
    $mobile = $_REQUEST['mobile'];
    if(a_fanweC("SMS_SEND_OTHER")==1)
    {
    	$GLOBALS['db']->query("update ".DB_PREFIX."order set mobile_phone_sms='".$mobile."' where id=".$order_id." and user_id=".intval($_SESSION['user_id']));
    	//M()->execute("update ".C("DB_PREFIX")."order set mobile_phone_sms='".$mobile."' where id=".$order_id." and user_id=".intval($_SESSION['user_id']));
    	echo '1';
    }
    else
    {
    	echo '0';
    }
}

if($_REQUEST['run']=='sendDemo')
{
	header("Content-Type:text/html; charset=utf-8");
	
	if (intval($_SESSION[a_fanweC('USER_AUTH_KEY')]) == 0){
			echo 0;
			exit();		
	}
	if(isset($_SESSION['sendDemo_time']))
	{
		if(a_gmtTime() - intval($_SESSION['sendDemo_time']) < 20)
		{
			echo 2;
			exit();
		}
	}	
	require ROOT_PATH.'app/source/func/com_func.php';
	require ROOT_PATH.'services/Sms/SmsPlf.class.php';
	$number = $_REQUEST['number'];
	$number = array($number);
	$smsobj = new SmsPlf();
	$info = $_REQUEST['info'];
	if (empty($info)){
		$info = '测试短信发送成功';
		$status = $smsobj->sendSMS($number,$info);
	}else{
		if ($_REQUEST['f']==1){
			$info = ':16测试短信发送成功';
			$info = $info."<br>1".$info."<br>2".$info."<br>3".$info."<br>4".$info."<br>5".$info."<br>6".$info."<br>7".$info."<br>8".$info;
			$info .= $info."<br>9".$info."<br>10".$info."<br>11".$info."<br>12".$info."<br>13".$info."<br>14".$info."<br>15".$info."<br>16".$info;
		}
		$status = $smsobj->sendSMS($number,$info);
	}
	if (intval($status) == 1){
		$_SESSION['sendDemo_time'] = a_gmtTime();
	}
	echo $status;
}

if($_REQUEST['run']=='getGbDownData')
{
	header("Content-Type:text/html; charset=utf-8");
	$gb_id = intval($_REQUEST['gb_id']);	

	if(a_fanweC("SMS_SEND_OTHER")==1)
	{
		$gb_order_sn = $GLOBALS['db']->getOne("select `order_id` from ".DB_PREFIX."group_bond where id=".$gb_id);
	    $mobile = $GLOBALS['db']->getOne("select `mobile_phone_sms` from ".DB_PREFIX."order where sn='".$gb_order_sn."' and user_id=".intval($_SESSION['user_id']));
	    //$mobile = M("Order")->where("sn='".$gb_order_sn."' and user_id=".intval($_SESSION['user_id']))->getField("mobile_phone_sms");
	    if(!$mobile||$mobile=='')
	    {
	    	$mobile = $GLOBALS['db']->getOne("select `mobile_phone` from ".DB_PREFIX."user where id=".intval($_SESSION['user_id']));
	    	//$mobile = M("User")->where("id=".intval($_SESSION['user_id']))->getField("mobile_phone");
	    }
	}else{
	   //$mobile = M("User")->where("id=".intval($_SESSION['user_id']))->getField("mobile_phone");
	   $mobile = $GLOBALS['db']->getOne("select `mobile_phone` from ".DB_PREFIX."user where id=".intval($_SESSION['user_id']));
	}
	
	$data['mobile'] = $mobile;
	    	
	$goods_id = $GLOBALS['db']->getOne("select `goods_id` from ".DB_PREFIX."group_bond where id=".$gb_id);
	//$goods_id = M("GroupBond")->where("id=".$gb_id)->getField("goods_id");

	$supplier_id = $GLOBALS['db']->getOne("select `suppliers_id` from ".DB_PREFIX."goods where id=".intval($goods_id));
	//$supplier_id = M("Goods")->where("id=".intval($goods_id))->getField("suppliers_id");
	//$data['departs'] = M("SuppliersDepart")->where("supplier_id=".$supplier_id)->order("is_main desc")->findAll();
	$data['departs'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."suppliers_depart where supplier_id=".intval($supplier_id)." order by is_main desc");    
 	
	echo json_encode($data);
    /*		
    	$this->ajaxReturn($data);
    	    
    $mobile = $_REQUEST['mobile'];
    if(a_fanweC("SMS_SEND_OTHER")==1)
    {
    	$GLOBALS['db']->query("update ".DB_PREFIX."order set mobile_phone_sms='".$mobile."' where id=".$order_id." and user_id=".intval($_SESSION['user_id']));
    	//M()->execute("update ".C("DB_PREFIX")."order set mobile_phone_sms='".$mobile."' where id=".$order_id." and user_id=".intval($_SESSION['user_id']));
    	echo '1';
    }
    else
    {
    	echo '0';
    }
	*/
}


if($_REQUEST['run']=='getRemainTime')
{
	$goods_id = intval($_REQUEST['id']);
	$now = a_gmtTime();
	$goods_end_time = intval($GLOBALS['db']->getOneCached("select promote_end_time from ".DB_PREFIX."goods where id=".$goods_id));
	echo intval($goods_end_time - $now);
}
if($_REQUEST['run']=='getRemainBeginTime')
{
	$goods_id = intval($_REQUEST['id']);
	$now = a_gmtTime();
	$goods_begin_time = intval($GLOBALS['db']->getOneCached("select promote_begin_time from ".DB_PREFIX."goods where id=".$goods_id));
	echo intval($goods_begin_time-$now);
}

if($_REQUEST['run']=='getNow')
{
	echo (a_gmtTime()+ (intval(a_fanweC("TIME_ZONE"))*3600))."000";
}

if($_REQUEST['run']=='autoSendList')
{
	$user2_id = intval($_REQUEST['user_id']);
	require ROOT_PATH.'app/source/func/com_func.php';
	require ROOT_PATH.'services/Sms/SmsPlf.class.php';
	require ROOT_PATH.'services/Mail/Mail.class.php';
    send_list($user2_id);
}

if($_REQUEST['run']=='coupon_check')
{
	header("Content-Type:text/html; charset=utf-8");
	$time = a_gmtTime();
	
	$sn = trim($_REQUEST['sn']);
	
	$end_time = $GLOBALS['db']->getOne("select end_time from ".DB_PREFIX."group_bond where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and  sn = '".addslashes($sn)."'");
	
	if($end_time >0)
	{
		echo a_toDate($end_time);
	}
	else
		echo 0;
		
}

if($_REQUEST['run']=='coupon_bus')
{
	header("Content-Type:text/html; charset=utf-8");
	
	$time = a_gmtTime();
	$sn = trim($_REQUEST['sn']);
	//$pwd = trim($_REQUEST['pwd']);
	
	//$sql = "update ".DB_PREFIX."group_bond set use_time = ".$time ." where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
	$sql = "update ".DB_PREFIX."group_bond set use_time = ".$time ." where sn = '".addslashes($sn)."' and is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." ";

	$GLOBALS['db']->query($sql);
	$is_updated = $GLOBALS['db']->affected_rows();
	
	if($is_updated >0)
	{
		echo 1;
	}
	else
		echo 0;
}

if($_REQUEST['run']=='buy_count')
{
	header("Content-Type:text/html; charset=utf-8");

	$id = intval($_REQUEST['id']);
	$buy_count = $GLOBALS['db']->getOne("select buy_count from ".DB_PREFIX."goods where id = '".$id."'");
	echo intval($buy_count);
	//header();
	//echo "document.write('".$buy_count."');";
}

//add by chenfq 2010-11-30
if($_REQUEST['run']=='autoRun')
{
	@session_write_close();
    require ROOT_PATH.'app/source/comm_init.php';
    require ROOT_PATH.'app/source/func/com_func.php';
	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	s_autoRun();
}

//add by chenfq 2010-11-30
if($_REQUEST['run']=='incOrderIncharge')
{
    require ROOT_PATH.'app/source/comm_init.php';
    require ROOT_PATH.'app/source/func/com_func.php';
    require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	require ROOT_PATH.'app/source/func/com_order_pay_func.php';	
	$order_incharge_id = intval($_REQUEST['order_incharge_id']);
	s_inc_order_incharge($order_incharge_id);
}

if($_REQUEST['run']=='sendUserInchargeSms')
{
    require ROOT_PATH.'app/source/comm_init.php';
    require ROOT_PATH.'app/source/func/com_func.php';
	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	$id = intval($_REQUEST['id']);
	s_send_userincharge_sms($order_incharge_id, true);	
}

if($_REQUEST['run'] == 'smsLottery'){
	
	header("Content-Type:text/html; charset=utf-8");
	//抽奖短信认证
	$result = array("type"=>0,"message"=>"");
	
	if(isset($_SESSION['smsLottery_time']))
	{
		if(a_gmtTime() - intval($_SESSION['smsLottery_time']) < 300)
		{
			$result['type'] = 3;
			echo json_encode($result);
			exit();
		}
	}
	
		$user_id = intval($_SESSION['user_id']);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
      	if(empty($user_info) || $user_info == false)
   		{   
   			$result['message'] = '请重新登陆!';			
   			echo json_encode($result);
			exit;
   		}
	
		$mobile_phone = trim($_REQUEST['mobile']);
		$goods_id = intval($_REQUEST['goods_id']);
		$isMobile = preg_match("/^(13\d{9}|14\d{9}|18\d{9}|15\d{9})|(0\d{9}|9\d{8})$/",$mobile_phone);
		if($isMobile == 1)
		{
			
			$smsSubscribe = $GLOBALS['db']->getRow("select `id`,`mobile_phone`,`code`,`status`,`user_id`,`add_time`,`send_count` from ".DB_PREFIX."sms_subscribe where mobile_phone = '$mobile_phone' and goods_id = '$goods_id'");
			
			if($smsSubscribe && $smsSubscribe['status'] == 0)
			{
				$code = $smsSubscribe['code'];
			}
			elseif($smsSubscribe && $smsSubscribe['status'] == 1){
				//已经验证的
				$result['type'] = 0;
				$result['message'] = '该手机号码已经参与过本次抽奖活动!';
   				echo json_encode($result);
				exit;					
			}
			else
			{
				$add_time = a_gmtTime();
				$tempcode = unpack('H4',str_shuffle(md5(uniqid())));
				$code = $tempcode[1];
			}
				
			require ROOT_PATH.'app/source/comm_init.php';
			require ROOT_PATH.'app/source/func/com_func.php';
			require('Sms/SmsPlf.class.php');
			
			$mail_template = $db->getRowCached("select `id`,`name`,`mail_title`,`mail_content`,`is_html` from ".DB_PREFIX."mail_template where name = 'sms_lottery_code'");
			$goods = $db->getRowCached("select `name_1`,`goods_short_name` from ".DB_PREFIX."goods where id = '$goods_id' ");
						
			$goods_name = $goods['goods_short_name'];
			if (empty($goods_name)){
				$goods_name = $goods['name_1'];
			}
				
			if($mail_template)
			{
				$tpl->assign('code',$code);
				$tpl->assign('goods_name',$goods_name);
				$message = $tpl->fetch_str($mail_template['mail_content']);
				$message = $tpl->_eval($message);
			}
				
			if(!empty($message))
			{
				$mobiles[] = $mobile_phone;
					
				$sms= new SmsPlf();
					
				if($sms->sendSMS($mobiles,$message))
				{
					$result["type"]=1;
					$_SESSION['smsLottery_time'] = a_gmtTime();
					if($smsSubscribe == false){
						$sql = "insert into ".DB_PREFIX."sms_subscribe(mobile_phone,city_id,code,status,user_id,add_time,send_count,goods_id) values('$mobile_phone',0,'$code',0,'$user_id','$add_time',1,'$goods_id')";
						$GLOBALS['db']->query($sql);
					}
				}
				else
					$result["message"]=$GLOBALS['lang']['XY_SMS_SEND_ERROR'];
			}
			else
				$result["message"]=$GLOBALS['lang']['XY_SMS_SEND_ERROR'];
			
		echo json_encode($result);
	}	
}

if ($_REQUEST['run']=="checkUser")
	{
		header("Content-Type:text/html; charset=utf-8");
		$email=empty($_REQUEST['email'])? "" : $_REQUEST['email'];
		$user=empty($_REQUEST['user'])  ? "" : $_REQUEST['user'];
		$phone=empty($_REQUEST['phone'])? "" : $_REQUEST['phone'];
		$emailOrUser=empty($_REQUEST['emailOrUser'])? "" : $_REQUEST['emailOrUser'];
		$username=empty($_REQUEST['username'])? "" : $_REQUEST['username'];
		$password=empty($_REQUEST['password'])? "" : md5($_REQUEST['password']);
		
		if(!empty($email))
		{
			echo $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email='$email'");
			die();
		}
		elseif(!empty($user))
		{
			echo $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name='$user'");
			die();
		}
		elseif(!empty($phone))
		{
			echo $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile_phone='$phone'");
			die();
		}
		elseif(!empty($emailOrUser))
		{
			echo $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email='$emailOrUser' or user_name = '$emailOrUser' ");
			die();
		}
		else
		{
			echo $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where (email='$username' or user_name = '$username') and user_pwd='$password' ");
			die();
		}
	}
	
if($_REQUEST['run'] == 'sms_mobile_verify'){
	if (intval(a_fanweC('REGISTER_MOBILE_VERIFY'))==0)
		die();
	header("Content-Type:text/html; charset=utf-8");
	//抽奖短信认证
	$result = array("type"=>0,"message"=>"");
	
	if(isset($_SESSION['sms_mobile_verify_time']))
	{
		if(a_gmtTime() - intval($_SESSION['sms_mobile_verify_time']) < 100)
		{
			$result['type'] = 3;
			echo json_encode($result);
			exit();
		}
	}
	require ROOT_PATH.'app/source/func/com_func.php';//2012-7-25(chh)
	if(!check_referer())
	{
		$result ["message"] = a_L('_OPERATION_FAIL_');
		echo json_encode ( $result );
		exit ();
	}
	
	$mobile_phone = trim($_REQUEST['mobile']);
	$isMobile = preg_match("/^(13\d{9}|14\d{9}|18\d{9}|15\d{9})|(0\d{9}|9\d{8})$/",$mobile_phone);
	if($isMobile == 1)
	{
			
			$smsSubscribe = $GLOBALS['db']->getRow("select `id`,`mobile_phone`,`code`,`status`,`send_count` from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '$mobile_phone'");
			
			if($smsSubscribe && intval($smsSubscribe['send_count']) <= 3 && intval($smsSubscribe['status'])== 0)
			{
				$code = $smsSubscribe['code'];
			}
			elseif($smsSubscribe && intval($smsSubscribe['status']) == 1){
				//已经验证的
				$result['type'] = 0;
				$result['message'] = '该手机号码已经注册过!';
   				echo json_encode($result);
				exit;					
			}
			else
			{
				$add_time = a_gmtTime();
				$tempcode = unpack('H4',str_shuffle(md5(uniqid())));
				$code = $tempcode[1];
			}
				
			require ROOT_PATH.'app/source/comm_init.php';
			//require ROOT_PATH.'app/source/func/com_func.php';//2012-7-25(chh)注消，放上面了
			require('Sms/SmsPlf.class.php');
			
			$mail_template = $db->getRowCached("select `id`,`name`,`mail_title`,`mail_content`,`is_html` from ".DB_PREFIX."mail_template where name = 'sms_mobile_verify'");
				
			if($mail_template)
			{
				$tpl->assign('code',$code);
				$tpl->assign('shop_name',SHOP_NAME);
				$message = $tpl->fetch_str($mail_template['mail_content']);
				$message = $tpl->_eval($message);
			}
				
			if(!empty($message))
			{
				$mobiles[] = $mobile_phone;
					
				$sms= new SmsPlf();
					
				if($sms->sendSMS($mobiles,$message))
				{
					$result["type"]=1;
					$_SESSION['sms_mobile_verify_time'] = a_gmtTime();
					if($smsSubscribe == false){
						$sql = "insert into ".DB_PREFIX."sms_mobile_verify(mobile_phone,code,status,add_time,send_count) values('$mobile_phone','$code',0,'$add_time',1)";
						$GLOBALS['db']->query($sql);
					}else{
						$sql = "update ".DB_PREFIX."sms_mobile_verify set send_count = send_count + 1 where mobile_phone = '$mobile_phone'";
						$GLOBALS['db']->query($sql);						
					}
					
					$_SESSION['mobile_verify']= $code;
				}
				else
					$result["message"]=$GLOBALS['lang']['XY_SMS_SEND_ERROR'];
			}
			else
				$result["message"]=$GLOBALS['lang']['XY_SMS_SEND_ERROR'];
			
		echo json_encode($result);
	}	
}	
?>
