<?php
//团购服务接口文件
//require('init.php');
//require_once('system_init.php');
//require_once('com_function.php');

//error_reporting(E_ALL ^ E_NOTICE);

if(!defined('ROOT_PATH'))
	define('ROOT_PATH', str_replace('services/admin.php', '', str_replace('\\', '/', __FILE__)));

if(!defined("AUTO_SEND_LOCK"))
{
	define("AUTO_SEND_LOCK",substr(getcwd(),0,-8)."Public/autosend.lock");
}

// 定义重置队列群发
function resetAutoSendIng()
{
	@unlink(AUTO_SEND_LOCK);
}

	
//require_once('init.php');
//require_once('system_init.php');
//require_once('com_function.php');
require ROOT_PATH.'app/source/db_init.php';

if($_REQUEST['act']=='ajaxGoodsBondRun')
{
	@session_write_close();
	require ROOT_PATH.'app/source/comm_init.php';
   	require ROOT_PATH.'app/source/func/com_func.php';
   	require ROOT_PATH.'app/source/func/com_order_pay_func.php';
    require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	require ROOT_PATH.'services/Sms/SmsPlf.class.php';
	require ROOT_PATH.'services/Mail/Mail.class.php';
			
	
	$order_id = intval($_REQUEST['order_id']);
	if ($order_id > 0){
		s_sendOrderGroupBonds($order_id); 
	}else{
		$goods_id = intval($_REQUEST['goods_id']);
		$sql = "select distinct o.id from ".DB_PREFIX."order as o "
			   ."left join ".DB_PREFIX."order_goods  as og on og.order_id = o.id where og.rec_id = '$goods_id' and o.money_status = 2";
		//echo $sql;
		
		$order_list = $GLOBALS['db']->getAll($sql);
			   
		foreach($order_list as $order){
			s_sendOrderGroupBonds($order['id']);   	
		}		
	}
			
	echo 1;	
}

if($_REQUEST['act']=='checkAjaxSendRun')
{
	@session_write_close();
	/*
	$time = a_gmtTime();
	$count = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."sms_send where status = 0");
	//if(intval($count) == 0)
	//$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ajax_send");
		
	//echo "select count(*) from ".DB_PREFIX."mail_list where status = 0 and send_time<=".$time."<br>";	
	if(a_fanweC("MAIL_ON")==1){	
		if(intval($count) == 0)
			$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mail_list where status = 0 and send_time<=".$time);
	
		//if(intval($count) == 0)
		//	$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mail_send_list where status = 0 and send_time<=".$time);
	}
	*/
	if(intval($count) == 0)
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."send_list where status = 0");
	
	echo $count;
}


if($_REQUEST['act'] == 'ajaxSendRun')
{
	@session_write_close();
    require ROOT_PATH.'app/source/comm_init.php';

			   
	//清空1天前的发送队列		
	$GLOBALS['db']->query("delete from ".DB_PREFIX."mail_send_list where status = 1 and ".a_gmtTime()."-send_time>".(3600*24));
	//$GLOBALS['db']->query("delete from ".DB_PREFIX."send_list where status = 1 and ".a_gmtTime()."-send_time>".(3600*24));	    
	$auto_begin_time = @file_get_contents(AUTO_SEND_LOCK);	
	$auto_begin_time = intval($auto_begin_time);	
	if (!file_exists(AUTO_SEND_LOCK)){
		@file_put_contents(AUTO_SEND_LOCK,a_gmtTime());
		register_shutdown_function("resetAutoSendIng");	
   		require ROOT_PATH.'app/source/func/com_func.php';
    	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
		require ROOT_PATH.'services/Sms/SmsPlf.class.php';
		require ROOT_PATH.'services/Mail/Mail.class.php';
		
		$user_id = intval($_REQUEST['user_id']);
		/*
		if(a_fanweC("MAIL_ON")==1){
			pushMail(); //插入队列
			send_mail_list();		
		}
		
		send_sms_list();
		*/
		send_list($user_id);
		
		resetAutoSendIng();
	}else{
		//在自动执行中....
		if ( a_gmtTime() - $auto_begin_time > 300 ){//(5分钟)超时后，自动把状态改为：false
			resetAutoSendIng();
		}
	}
    
	echo "ok";
}


	
function send_sms_list(){
	
	$time = a_gmtTime();	
	//status: 0:未发送 1: 发送中 2:已发送
	$sendList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."sms_send where send_time <= $time and status = 0");
	foreach($sendList as $smsSend)
	{
		$GLOBALS['db']->query("update ".DB_PREFIX."sms_send set status =1 where status = 0 and id = $smsSend[id]");
		if($GLOBALS['db']->affected_rows()==1){
			$goodsSendType = a_fanweC('GOODS_SMS_SEND_TYPE'); //新团购项目短信通知方式：0:只发送订阅手机,1:只发送会员,2:发送会员和订阅手机
			
			$message = $smsSend['send_content'];
			$mobiles = array();
				
			if($smsSend['type'] == 2) //商品通知短信
			{
				$goods = $GLOBALS['db']->getRow("select id,goods_short_name,name_1,promote_begin_time,city_id,all_show from ".DB_PREFIX."goods where id = ".intval($smsSend['rec_id']));
				$goods_name = empty($goods['goods_short_name']) ? $goods['name_1'] : $goods['goods_short_name'];
				$mail_template = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."mail_template where name = 'goods_sms'");
				if($mail_template)
				{
					$GLOBALS['tpl']->assign('goods_name',$goods_name);
					$GLOBALS['tpl']->assign('begin_time',a_toDate($goods['promote_begin_time'],'Y-m-d'));
					$message = $GLOBALS['tpl']->fetch_str($mail_template['mail_content']);
					$message = $GLOBALS['tpl']->_eval($message);
				}
			}
				
			if($smsSend['send_type'] == 1) //发送方式： 1:按会员分组发送,2:自定义发送会员
			{
				$where = " mobile_phone <> '' and (LENGTH(mobile_phone) = 11 or LENGTH(mobile_phone) = 10) and LEFT(mobile_phone,1) = '1'";
					
				if($smsSend['type'] == 2)
					$where .= " and city_id = $goods[city_id] and is_receive_sms = 1";
					 
				if($smsSend['user_group'] > 0)
					$where .= " and group_id = ".$smsSend['user_group'];
					
				if($smsSend['type'] == 2 && $goodsSendType == 0)
					$user_mobiles = array();
				else
					$user_mobiles = $GLOBALS['db']->getCol("select mobile_phone from ".DB_PREFIX."user where $where");
			}
			else
			{
				$where  = a_db_create_in($smsSend['custom_users'],"id");
					
				$user_mobiles = $GLOBALS['db']->getCol("select mobile_phone from ".DB_PREFIX."user where $where");
			}
				
			$mobiles = $user_mobiles;
				
			if(!empty($smsSend['custom_mobiles']))
			{
				$custom_mobiles = explode(",",$smsSend['custom_mobiles']);
				$mobiles = array_merge($mobiles,$custom_mobiles);
			}
				
			//goodsSendType 新团购项目短信通知方式：0:只发送订阅手机,1:只发送会员,2:发送会员和订阅手机
			if($smsSend['type'] == 2 && $goodsSendType != 1)
			{
				if($goods['all_show']==1)
					$sms_subscribe = $GLOBALS['db']->getCol("select mobile_phone from ".DB_PREFIX."sms_subscribe where goods_id = 0 and status = 1");
				else
					$sms_subscribe = $GLOBALS['db']->getCol("select mobile_phone from ".DB_PREFIX."sms_subscribe where goods_id = 0 and city_id = ".intval($goods['city_id'])." and status = 1");
					
				if(count($sms_subscribe) > 0)
					$mobiles = array_merge($mobiles,$sms_subscribe);
			}
				
			$mobiles = array_unique($mobiles);
				
			if(count($mobiles) > 0 && !empty($message))
			{
				$sms= new SmsPlf();
				$sms->sendSMS($mobiles,$message);
			}	

			$GLOBALS['db']->query("update ".DB_PREFIX."sms_send set status =2, send_content = '$message' where id = $smsSend[id]");
		}
	}
}

	
	// 发送邮件群发队列 by hc
	function send_mail_list()
	{
		
		$msg_list = $GLOBALS['db']->getAll("select `id`,`mail_address`,`mail_title`,`mail_content`,`send_time`,`status`,`rec_module`,`rec_id` from ".DB_PREFIX."mail_send_list where status = 0 and send_time<=".a_gmtTime()." limit 10");
		$mail = new Mail();	
		foreach($msg_list as $k=>$msg)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."mail_send_list set status = 1 where id=".$msg['id']);
			
			if(a_fanweC("MAIL_ON")==1)
			{
				$mail->ClearAddresses();
				$mail->AddAddress($msg['mail_address']);
				$mail->IsHTML(1); 
				$mail->Subject = $msg['mail_title']; // 标题
				$mail->Body = $msg['mail_content']; // 内容
				$mail->Send();	
			}
		}
	}

if ($_REQUEST['act'] == 'ajaxSendMail'){
	$result['id'] = 0;
	$result['html'] = "";
    require ROOT_PATH.'app/source/comm_init.php';
    require ROOT_PATH.'app/source/func/com_func.php';
   	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
   	require ROOT_PATH.'services/Mail/Mail.class.php';

	if(a_fanweC("MAIL_ON")==1){
		if(intval($_REQUEST['id'])==0)
		{
			pushMail(); //插入队列
		}
		if(intval($_REQUEST['id'])>0)
			$sql = "select `id`,`mail_address`,`mail_title`,`mail_content`,`send_time`,`status`,`rec_module`,`rec_id` from ".DB_PREFIX."mail_send_list where status = 0 and `id`=".intval($_REQUEST['id']);
		else
			$sql = "select `id`,`mail_address`,`mail_title`,`mail_content`,`send_time`,`status`,`rec_module`,`rec_id` from ".DB_PREFIX."mail_send_list where status = 0 order by id asc";
		$msg = 	$GLOBALS['db']->getRow($sql);
		
		if($msg)
		{
			if(admin_send_mail_list($msg))
				$result['html'] = $msg['mail_address']." Send Success!<br>";
			else
				$result['html'] = $msg['mail_address']." Send <font color='red'>Error</font>!<br>";
			//$result['html'] .= "select `id` from ".DB_PREFIX."mail_send_list where status = 0 and `id` >{$msg['id']}";
			$result['id'] = $GLOBALS['db']->getOne("select `id` from ".DB_PREFIX."mail_send_list where status = 0 and `id` >{$msg['id']}");
		}
		
	}
	echo json_encode($result);
	die();
}

if ($_REQUEST['act'] == 'ajaxSendSMS'){
	$result['id'] = 0;
	$result['html'] = "";
    require ROOT_PATH.'app/source/comm_init.php';
    require ROOT_PATH.'app/source/func/com_func.php';
   	require ROOT_PATH.'app/source/func/com_send_sms_func.php';
	require ROOT_PATH.'services/Sms/SmsPlf.class.php';
	if(intval($_REQUEST['id'])>0)
		$sql =  "select * from ".DB_PREFIX."sms_send where status = 0 and id=".intval($_REQUEST['id']);
	else
		$sql = "select * from ".DB_PREFIX."sms_send where status = 0  order by id asc";
		
	$smsSend = $GLOBALS['db']->getRow($sql);
	if($smsSend)
	{
		if(admin_send_sms_list($smsSend))
			$result['html'] = $smsSend['custom_mobiles']." Send Success!<br>";
		else
			$result['html'] = $smsSend['custom_mobiles']." Send Error!<br>";
		$result['id'] = $GLOBALS['db']->getOne("select `id` from ".DB_PREFIX."sms_send where status = 0 and `id` >{$smsSend['id']} order by id asc ");
	
	}
	echo json_encode($result);
	die();
}

function admin_send_mail_list($msg)
{
	$mail = new Mail();		
	if(a_fanweC("MAIL_ON")==1)
	{
		$GLOBALS['db']->query("update ".DB_PREFIX."mail_send_list set status = 1, send_time = ".a_gmtTime()." where id=".$msg['id']);
		$mail->ClearAddresses();
		$mail->AddAddress($msg['mail_address']);
		$mail->IsHTML(1); 
		$mail->Subject = $msg['mail_title']; // 标题
		$mail->Body = $msg['mail_content']; // 内容
		if($mail->Send())
			return true;
		else
			return false;
	}
	return false;
}

function admin_send_sms_list($smsSend)
{
	$GLOBALS['db']->query("update ".DB_PREFIX."sms_send set status =1 where status = 0 and id = $smsSend[id]");
	if($GLOBALS['db']->affected_rows()==1){
		$goodsSendType = a_fanweC('GOODS_SMS_SEND_TYPE'); //新团购项目短信通知方式：0:只发送订阅手机,1:只发送会员,2:发送会员和订阅手机
		
		$message = $smsSend['send_content'];
		$mobiles = array();
			
		if($smsSend['type'] == 2) //商品通知短信
		{
			$goods = $GLOBALS['db']->getRow("select id,goods_short_name,name_1,promote_begin_time,city_id,all_show from ".DB_PREFIX."goods where id = ".intval($smsSend['rec_id']));
			$goods_name = empty($goods['goods_short_name']) ? $goods['name_1'] : $goods['goods_short_name'];
			$mail_template = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."mail_template where name = 'goods_sms'");
			if($mail_template)
			{
				$GLOBALS['tpl']->assign('goods_name',$goods_name);
				$GLOBALS['tpl']->assign('begin_time',a_toDate($goods['promote_begin_time'],'Y-m-d'));
				$message = $GLOBALS['tpl']->fetch_str($mail_template['mail_content']);
				$message = $GLOBALS['tpl']->_eval($message);
			}
		}
			
		if($smsSend['send_type'] == 1) //发送方式： 1:按会员分组发送,2:自定义发送会员
		{
			$where = " mobile_phone <> '' and (LENGTH(mobile_phone) = 11 or LENGTH(mobile_phone) = 10) and LEFT(mobile_phone,1) = '1'";
				
			if($smsSend['type'] == 2)
				$where .= " and city_id = $goods[city_id] and is_receive_sms = 1";
				 
			if($smsSend['user_group'] > 0)
				$where .= " and group_id = ".$smsSend['user_group'];
				
			if($smsSend['type'] == 2 && $goodsSendType == 0)
				$user_mobiles = array();
			else
				$user_mobiles = $GLOBALS['db']->getCol("select mobile_phone from ".DB_PREFIX."user where $where");
		}
		else
		{
			$where  = a_db_create_in($smsSend['custom_users'],"id");
				
			$user_mobiles = $GLOBALS['db']->getCol("select mobile_phone from ".DB_PREFIX."user where $where");
		}
			
		$mobiles = $user_mobiles;
			
		if(!empty($smsSend['custom_mobiles']))
		{
			$custom_mobiles = explode(",",$smsSend['custom_mobiles']);
			$mobiles = array_merge($mobiles,$custom_mobiles);
		}
			
		//goodsSendType 新团购项目短信通知方式：0:只发送订阅手机,1:只发送会员,2:发送会员和订阅手机
		if($smsSend['type'] == 2 && $goodsSendType != 1)
		{
			if($goods['all_show']==1)
				$sms_subscribe = $GLOBALS['db']->getCol("select mobile_phone from ".DB_PREFIX."sms_subscribe where goods_id = 0 and status = 1");
			else
				$sms_subscribe = $GLOBALS['db']->getCol("select mobile_phone from ".DB_PREFIX."sms_subscribe where goods_id = 0 and city_id = ".intval($goods['city_id'])." and status = 1");
				
			if(count($sms_subscribe) > 0)
				$mobiles = array_merge($mobiles,$sms_subscribe);
		}
			
		$mobiles = array_unique($mobiles);
		//var_dump($mobiles);
		//echo "<br>".$message."<br>";
		$GLOBALS['db']->query("update ".DB_PREFIX."sms_send set status =2, send_content = '$message' where id = $smsSend[id]");
		if(count($mobiles) > 0 && !empty($message))
		{
			$sms= new SmsPlf();
			if($sms->sendSMS($mobiles,$message))
			{
				return  true;
			}
			else
			{
				return  false;
			}
			
		}	

		
	}
	return false;
}
	
function pushMail()
{
    $time = a_gmtTime();						
    $mail_item = $GLOBALS['db']->getRow("select id,mail_title,mail_content,is_html,send_time,status,goods_id from ".DB_PREFIX."mail_list where status = 0 order by id desc");
   	if ($mail_item){
   				
			$mail_address_send_list = $GLOBALS['db']->getAll("select `id`,`mail_address_id`,`mail_id`,`is_push` from ".DB_PREFIX."mail_address_send_list where mail_id=".intval($mail_item['id'])." and is_push=0 ");
			//var_dump($address_send_list);
			$sql = "delete from ".DB_PREFIX."mail_send_list where status=0 and rec_module='Email' and rec_id=".$mail_item['id'];
			//echo $sql."<br>";
			$GLOBALS['db']->Query($sql);//清空未发的邮件
			
			foreach($mail_address_send_list as $mail_address_send_item)
			{				
				//$address_send_item = $address_item;
				$address_item = $GLOBALS['db']->getRow("select `id`,`mail_address`,`status`,`user_id`,`city_id` from ".DB_PREFIX."mail_address_list where status = 1 and id=".intval($mail_address_send_item['mail_address_id']));
				//var_dump($address_item);
				if($address_item)
				{
					//$userinfo = D("User")->getById($address_item['user_id']);
					$userinfo = $GLOBALS['db']->getRow("select `id`,`user_name`,`nickname` from ".DB_PREFIX."user where id=".$address_item['user_id']);
					if($userinfo)
					{
						$username = $userinfo['user_name'];
						if($userinfo['nickname']!='')
						{
							$username.="(".$userinfo['nickname'].")";
						}
					}
					else 
					{
						$username = '匿名用户';
					}
					$GLOBALS['tpl']->assign("username",$username);
					$GLOBALS['tpl']->assign("uesrinfo",$userinfo);
					$mail_title = $mail_item['mail_title'];
					$GLOBALS['tpl']->assign("mail_title",$mail_title);
					//$shop_url
					$shop_url = str_replace("services","",a_fanweC("SHOP_URL"));
					//开始为邮件内容赋值
					if($mail_item['goods_id']==0)
						$mail_content = $mail_item['mail_content'];
					else
					{
										//$tpl = Think::instance('ThinkTemplate');
										$mail_tpl = file_get_contents(getcwd()."/../Public/mail_template/".a_fanweC("GROUP_MAIL_TMPL")."/".a_fanweC("GROUP_MAIL_TMPL").".html");  //邮件群发的模板				
										$mail_tpl = str_replace(a_fanweC("GROUP_MAIL_TMPL")."_files/",$shop_url."/Public/mail_template/".a_fanweC("GROUP_MAIL_TMPL")."/".a_fanweC("GROUP_MAIL_TMPL")."_files/",$mail_tpl);
					
										//开始定义模板变量
										//$v = M("Goods")->getById($mail_item['goods_id']);
										$v = $GLOBALS['db']->getRow("select `id`,`name_1`,`sn`,`cate_id`,`city_id`,`suppliers_id`,`click_count`,`cost_price`,`shop_price`,`market_price`,`promote_price`,`promote_begin_time`,`promote_end_time`,`create_time`,`update_time`,`type_id`,`goods_type`,`stock`,`brief_1`,`brand_id`,`is_best`,`is_hot`,`is_new`,`status`,`sort`,`seokeyword_1`,`seocontent_1`,`goods_desc_1`,`small_img`,`big_img`,`origin_img`,`define_small_img`,`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,`weight_unit`,`score`,`web_reviews`,`goods_reviews`,`min_user_time`,`special_note`,`max_bought`,`is_group_fail`,`complete_time`,`buy_count`,`group_user`,`user_count`,`earnest_money`,`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,`goods_show_name`,`u_name`,`referrals`,`close_referrals`,`goods_short_name`,`fail_buy_count`,`free_delivery_amount`,`allow_combine_delivery`,`allow_sms` from ".DB_PREFIX."goods where id=".$mail_item['goods_id']);
										
										//$city_name
										//$city_name = M("GroupCity")->where("id=".$v['city_id'])->getField("name");
										$city_name = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."group_city where id=".$v['city_id']);
										$GLOBALS['tpl']->assign("city_name",$city_name);
										
										//$shop_name
										$shop_name = SHOP_NAME;
										$GLOBALS['tpl']->assign("shop_name",$shop_name);
										
										//$cancel_url
										$cancel_url = $shop_url."/index.php?m=Index&a=unSubScribe&email=".$address_item['mail_address'];
										$GLOBALS['tpl']->assign("cancel_url",$cancel_url);
										
										//$sender_email
										$sender_email = a_fanweC("REPLY_ADDRESS");
										$GLOBALS['tpl']->assign("sender_email",$sender_email);
										
										//$send_date 
										$send_date = a_toDate(a_gmtTime(),'Y年m月d日');
										$weekarray = array("日","一","二","三","四","五","六");
										$send_date .= " 星期".$weekarray[a_toDate(a_gmtTime(),"w")];
										$GLOBALS['tpl']->assign("send_date",$send_date);
										
										
										$GLOBALS['tpl']->assign("shop_url",$shop_url);
										
										//$tel_number
										$tel_number = a_fanweC("TEL");
										$GLOBALS['tpl']->assign("tel_number",$tel_number);
										
										//$tg_info
										//$tg_info = D("Goods")->getGoodsItem($v['id'],$v['city_id']);
										$tg_info = $GLOBALS['db']->getRow("select `id`,`name_1`,`sn`,`cate_id`,`city_id`,`suppliers_id`,`click_count`,`cost_price`,`shop_price`,`market_price`,`promote_price`,`promote_begin_time`,`promote_end_time`,`create_time`,`update_time`,`type_id`,`goods_type`,`stock`,`brief_1`,`brand_id`,`is_best`,`is_hot`,`is_new`,`status`,`sort`,`seokeyword_1`,`seocontent_1`,`goods_desc_1`,`small_img`,`big_img`,`origin_img`,`define_small_img`,`is_define_small_img`,`is_inquiry`,`weight`,`spec_type`,`weight_unit`,`score`,`web_reviews`,`goods_reviews`,`min_user_time`,`special_note`,`max_bought`,`is_group_fail`,`complete_time`,`buy_count`,`group_user`,`user_count`,`earnest_money`,`group_bond_end_time`,`expand1`,`expand2`,`expand3`,`expand4`,`u_name`,`referrals`,`close_referrals`,`goods_short_name`,`fail_buy_count`,`free_delivery_amount`,`allow_combine_delivery`,`allow_sms` from ".DB_PREFIX."goods where id=".$v['id']);
										$tg_info['title'] = $tg_info['goods_short_name']!=''?$tg_info['goods_short_name']:$tg_info['name_1'];
										$tg_info['price'] = a_formatPrice($tg_info['shop_price']);
										$tg_info['origin_price'] = a_formatPrice($tg_info['market_price']);
										if($tg_info['market_price']!=0)
										$tg_info['discount'] = round($tg_info['shop_price']/$tg_info['market_price'],2)*10;
										else 
										$tg_info['discount'] = 10;
										$tg_info['save_money'] = a_formatPrice($tg_info['market_price'] - $tg_info['shop_price']);
										$tg_info['big_img'] = $shop_url.$tg_info['big_img'];
										$tg_info['desc'] = str_replace("./Public/",$shop_url."/Public/",$tg_info['goods_desc_1']);
										
										if(a_fanweC("URL_ROUTE")==0)
											$tg_info['goods_url'] = $shop_url."/index.php?m=Goods&a=show&id={$tg_info['id']}";
										else
											$tg_info['goods_url'] = $shop_url."/tg-{$tg_info['id']}.html";
										
										$GLOBALS['tpl']->assign("tg_info",$tg_info);
										
										
										
										//$sale_info
										$sql = "select sd.*,(select s.web from ".DB_PREFIX."suppliers as s where s.id = sd.supplier_id) as url from ".DB_PREFIX."suppliers_depart as sd where sd.is_main=1 and sd.supplier_id= ".$v['suppliers_id'];
	
										$sale_info = $GLOBALS['db']->getRow($sql);
										$sale_info['map_url'] = $sale_info['map'];
										$sale_info['tel_num'] = $sale_info['tel'];
										$sale_info['title'] = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."suppliers where id =".$sale_info['supplier_id']);
				
										$GLOBALS['tpl']->assign("sale_info",$sale_info);
										
										//$referral
										$referral['amount'] = a_fanweC("REFERRALS_MONEY");
										
										if(a_fanweC("REFERRAL_TYPE") == 0)
										{
											$referral['amount'] = a_formatPrice(($referral['amount']));
										}
										else
										{
											$referral['amount'] = $referral['amount'];
										}
									
										if(a_fanweC("URL_ROUTE")==0)
										$referral['url'] = $shop_url."/index.php?m=Referrals&a=index";
										else
										$referral['url'] = $shop_url."/Referrals-index.html";
										$GLOBALS['tpl']->assign("referral",$referral);
										
		//								ob_start();
		//								eval('?' . '>' .$tpl->parse($mail_tpl));
		//								$content = ob_get_clean();	
										
										$content = $GLOBALS['tpl']->fetch_str($mail_tpl);
										$content = $GLOBALS['tpl']->_eval($content);
										
										$mail_content = $content;
										
										
								}//end 通知模板的赋值
								
								//$cancel_url
						$cancel_url = $shop_url."/index.php?m=Index&a=unSubScribe&email=".$address_item['mail_address'];
								
						//$mail_content = "如不想继续收".SHOP_NAME."的邮件，您可随时<a href='".$cancel_url."' title='取消订阅'>取消订阅</a><br /><br />".$mail_content;
						$mail_content = sprintf(a_L('MAIL_TOP_INFO'),SHOP_NAME,$cancel_url).'<br /><br />'.$mail_content;
						
						$mail_title = str_replace("{\$username}",$username,$mail_title);
						$mail_content = str_replace("{\$username}",$username,$mail_content);
	
						// 修改为插入邮件群发队列
						$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mail_send_list where status = 0 and mail_address='".$address_item['mail_address']."' and rec_module='Email' and rec_id=".$mail_item['id']);
						if($count==0)
						{
							$sql = "insert into ".DB_PREFIX."mail_send_list(id,mail_address,mail_title,mail_content,send_time,status,rec_module,rec_id) values(0,'".$address_item['mail_address']."','".$mail_title."','".addslashes($mail_content)."','".$mail_item['send_time']."',0,'Email','".$mail_item['id']."')";
							$GLOBALS['db']->query($sql);
							//echo $sql.'<br>';
							$sql = "update ".DB_PREFIX."mail_address_send_list set is_push = 1 where id =".$mail_address_send_item['id'];
							$GLOBALS['db']->query($sql);
						} //为避免重复插入队列						
				}	
			}			
					//验证插入队列数与关联数是否一样
				$address_list = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mail_address_send_list where mail_id=".intval($mail_item['id']));
				$push_address_list = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mail_address_send_list where mail_id=".intval($mail_item['id'])." and is_push=1");
				if($push_address_list == $address_list)
				{
					//将要插入队列的邮件设为已发送 
					if($address_list>0)
						$GLOBALS['db']->query("update ".DB_PREFIX."mail_list set status = 1 where id = ".$mail_item['id']);	
				}
					
   	}					
}	

	if($_REQUEST['act'] == 'sendDemo')
	{
		header("Content-Type:text/html; charset=utf-8");
		
		$t = intval($_REQUEST['t']);
		$url = $_REQUEST['url'];
		$mail_address = $_REQUEST['mail_address'];
		
		if ($t == 1)
			$info = '有Html代码1：<a href=http://www.163.com>点击连接</a>';
		else if($t == 2)
			$info = '有Html代码2：<a href=http://blu004047.chinaw3.com>点击连接</a>';
		else
			$info = '网址过滤测试：'.$url;
		
		$mail_content = $info;
		
		echo "<br>===========发送给：{$mail_address}===============<br>";
		echo "<br>===========发送内容===============<br>";
		echo $mail_content;
		echo "<br>===========发送内容===============<br>";
		
		require ROOT_PATH.'services/Mail/Mail.class.php';
		$mail = new Mail();		
		$mail->AddAddress($mail_address);
		$mail->IsHTML(1); 
		$mail->Subject = '测试邮件'; // 标题
		$mail->Body = $mail_content; // 内容
		$mail->Send();
		if($mail->ErrorInfo!='')
		{
			echo "<br>===========发送结果失败===============<br>";
				echo $mail->ErrorInfo;
			echo "<br>===========发送结果失败===============<br>";	
		}
		else
		{
			echo "<br>===========发送结果成功===============<br>";
			echo '发送测试邮件成功';
			echo "<br>===========发送结果成功===============<br>";
		}
	}	
	
	
	if ($_REQUEST['act'] == 'sendDemo2'){
    	require ROOT_PATH.'app/source/comm_init.php';
    	require ROOT_PATH.'app/source/func/com_func.php';
   		require ROOT_PATH.'app/source/func/com_send_sms_func.php';
   		require ROOT_PATH.'services/Mail/Mail.class.php';

		$sql = "select `id`,`mail_address`,`mail_title`,`mail_content`,`send_time`,`status`,`rec_module`,`rec_id` from ".DB_PREFIX."mail_send_list";
		$msg = 	$GLOBALS['db']->getRow($sql);
		
		if($msg)
		{
			echo $msg['mail_address']."<br>";
			echo $msg['mail_title']."<br>";
			var_dump($msg['mail_content'])."<br>";
			
			$mail = new Mail();	
			$mail->ClearAddresses();
			$mail->AddAddress($msg['mail_address']);
			$mail->IsHTML(1); 
			$mail->Subject = $msg['mail_title']; // 标题
			$mail->Body = $msg['mail_content']; // 内容
			$mail->Send();
			if($mail->ErrorInfo!='')
			{
					echo $mail->ErrorInfo;
			}
			else
			{
				echo '发送测试邮件成功';
			}
		}
	}	
?>