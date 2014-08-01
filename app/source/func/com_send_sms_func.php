<?php 
	//修正by hc 20100804, 将autoRun的文件锁模式改为缓存模式
	function s_autoRun(){
		$autosendlock =	ROOT_PATH."Public/autosend.lock";
		$fix_status_lock =	ROOT_PATH."Public/fix_status.lock";
		$auto_begin_time = intval(@file_get_contents($autosendlock));
		// 服务端的全量变量		
		if ($auto_begin_time==0){
			@file_put_contents($autosendlock,a_gmtTime());
			//自动发放团购卷
			
			s_autoSendGroupBond();

			//开始自动发放返利
			if(a_fanweC("AUTO_REFERRAL")==1)
			{
					$sql = "select * from ".DB_PREFIX."referrals where is_pay=0 and create_time<>0";
					$referrals = $GLOBALS['db']->getAll($sql);
					foreach($referrals as $k=>$v)
					{
						if(a_gmtTime() - $v['create_time'] >= a_fanweC("REFERRALS_LIMIT_TIME") * 3600)
						{
							s_payReferrals($v['id']);
						}
					}
			}

			$fix_status_time = intval(@file_get_contents($fix_status_lock));
			// 5分钟修正一次
			if(a_gmtTime() - $fix_status_time > 300)
			{
				//自动修正团购状态 
				// 1、将团购未结束的 且 标识团购失败的，自动更新为：团购进行中
				$sql = "update ".DB_PREFIX."goods set is_group_fail = 0, complete_time = 0 where is_group_fail = 1 and promote_end_time > ".a_gmtTime();
				$GLOBALS['db']->query($sql);
				// 2、 将  标识团购成功的 and 当前购买人数等于0 and group_user > 0 and  团购未结束的  ; 自动更新为：团购进行中  
				$sql = "update ".DB_PREFIX."goods set is_group_fail = 0, complete_time = 0 where is_group_fail = 2 and buy_count = 0 and group_user > 0 and promote_end_time > ".a_gmtTime();
				$GLOBALS['db']->query($sql); 
				//注：团购成功后，当前购买人数大于0时，不能再自动改为团购进行中了，因为团购成功后，会自动发放团购卷			
				//D()->query("update ".C("DB_PREFIX")."sys_conf set val ='0' where status = 1 and name = 'AUTO_RUN_ING'");
				@file_put_contents($fix_status_lock,a_gmtTime());
			}
			@unlink($autosendlock);
		}else{
			//在自动执行中....
			//$auto_begin_time = intval(M("SysConf")->where("name='AUTO_RUN_BEGIN_TIME'")->getField('val')); 
			if ( a_gmtTime() - $auto_begin_time > 300 ){//(5分钟)超时后，自动把状态改为：false
				@unlink($autosendlock);
			}
		}		
	}
	
	function a_templateFetch($templateContent,$templateVars = '')
	{
		foreach($templateVars as $k=>$v)
		{
			$GLOBALS['tpl']->assign($k,$v);
		}
		$templateContent = $GLOBALS['tpl']->fetch("str:".$templateContent);
		return $templateContent;
	}
	
	function s_autoSendGroupBond(){
		//is_group_fail:0、团购中....;1、表示团购失败;2、表示团购成功
		//group_user：最低团购人数,设为 0 则不限制团购人数; max_bought：用户最大购买数量,设为 0 则不限制用户最大购买数量; user_count:购买商品的人数
		
		//团购时间结束 或 购买人数 大于 最低团购人数 时，就自动放发方维卷
		
		//$goods_list = D("Goods")->where("is_group_fail = 0 and buy_count >= 0 and (promote_end_time <".gmtTime()." or buy_count >= group_user) ")->findAll();
		
		$sql = "select id,group_user,buy_count,is_group_fail,complete_time,fail_buy_count,type_id from ".DB_PREFIX."goods where is_group_fail = 0 and buy_count >= 0 and (promote_end_time <".a_gmtTime()." or buy_count >= group_user)";
		$goods_list = $GLOBALS['db']->getAll($sql);
		if(!$goods_list)
		{
			$goods_list = array();
		}
		if(count($goods_list)>0)
		{
			foreach($goods_list as $goods){
				
				//group_user：最低团购人数,不为0时：购买人数小于最低限定人数；
				if (($goods['group_user'] >= 0 && $goods['group_user'] > $goods['buy_count'])){
					$goods['is_group_fail'] = 1;
					$goods['complete_time'] = a_gmtTime();
					$goods['fail_buy_count'] = $goods['buy_count'];
					//D("Goods")->save($goods);

					$GLOBALS['db']->autoExecute(DB_PREFIX."goods", addslashes_deep($goods), 'UPDATE', "id = ".intval($goods['id']));
				}else{
					//if ($goods['promote_end_time'] <gmtTime()){ //add by chenfq 2010-05-30 判断时间是否结束
						if($goods['type_id']==0||$goods['type_id']==2||$goods['type_id']==3)		
								
						s_sendGroupBond(intval($goods['id']));

						$goods['is_group_fail'] = 2;
						$goods['complete_time'] = a_gmtTime();
						$GLOBALS['db']->autoExecute(DB_PREFIX."goods", addslashes_deep($goods), 'UPDATE', "id = ".intval($goods['id']));
					//}
				}
			}
		}		
	}	

	//修改 by hc ， 去除原有补全功能， 在该函数执行时执行操作：1. 将要发下去的团购券的is_valid改为1, 2. 需要短信和邮件通知时通知下去
    function s_sendGroupBond($goods_id)
	{
		$goodsID = intval($goods_id);
		
		$sql = "select id,type_id,is_group_fail,promote_end_time,buy_count,group_user,allow_sms from ".DB_PREFIX."goods where id = ".$goodsID;
		$goods = $GLOBALS['db']->getRow($sql);
		$time = a_gmtTime();
		$typeID = $goods['type_id'];
		
		if($goods['is_group_fail'] == 1)
		{
			
		}
		elseif((intval($goods['promote_end_time']) < $time) || (($goods['is_group_fail'] == 0) && ($goods['buy_count'] >= $goods['group_user'])))
		{
			
			if($typeID == 0 || $typeID == 2 || $typeID == 3)
			{
				$sql = "select o.id,o.create_time,o.sn,o.user_id,og.number,og.attr from ".DB_PREFIX."order as o left join ".DB_PREFIX."order_goods  as og on og.order_id = o.id where og.rec_id = '$goodsID' and o.money_status = 2";
				$orderList = $GLOBALS['db']->getAll($sql);

				foreach($orderList as $order)
				{
					$sql_update = "update ".DB_PREFIX."group_bond set status = 1, buy_time =".$order['create_time'].",create_time =".a_gmtTime().",is_valid=1  where goods_id=".$goodsID." and order_id='".$order['sn']."'";	
					$GLOBALS['db']->query($sql_update);
					
					//修改 by hc 不再查询所有的团购券进行分发，以免错位，仅查询当前订单的团购券进行分发有效性
					
					$sql = "select id from ".DB_PREFIX."group_bond where goods_id = '$goodsID' and order_id = '".$order['sn']."' and is_valid = 1"; 
					$groupBonds = $GLOBALS['db']->getAll($sql);
					foreach($groupBonds as $gbdata)
					{
						//发放团购卷时，自动短信通知
						if (a_fanweC('AUTO_SEND_SMS')==1 && $goods['allow_sms']==1)
						{
							s_send_sms($order['user_id'], $gbdata['id']);
						}
						
						if(a_fanweC("MAIL_ON")==1 && a_fanweC("SEND_GROUPBOND_MAIL") ==1)
						{
							s_send_grounp_bond_mail($order['user_id'],$gbdata['id']);
						}
					}
					
					//不需配送的商品，直接设置成：无需配送  add by chenfq 2010-05-06
					$sql_update = "update ".DB_PREFIX."order set status = 1, goods_status  = 5 where id=".intval($order['id']);	
					$GLOBALS['db']->query($sql_update);
				}
			}
		}
	}
  //cart_func.php	
	//为充值发送短信, $order_incharge_id : 收款单ID
	//修改 by hc
	function s_send_userincharge_sms($user_incharge_id,$send = false)
	{
	    //开始短信通知
    	if(a_fanweC("IS_SMS")==1&&a_fanweC("PAYMENT_SMS")==1)
    	{
    		$user_incharge_vo = $GLOBALS['db']->getRow("select id,sn,user_id,money from ".DB_PREFIX."user_incharge where id =".intval($user_incharge_id));
    		$payment_notify['money'] = a_formatPrice($user_incharge_vo['money']);
    		$payment_notify['order_sn'] = $user_incharge_vo['sn'];
    		$mobile_phone = $GLOBALS['db']->getOne("select mobile_phone from ".DB_PREFIX."user where id = ".intval($user_incharge_vo['user_id']));    		

			//模板解析
			$mail_content = $GLOBALS['db']->getOne("select mail_content from ".DB_PREFIX."mail_template where name='payment_sms'");
			$content = a_templateFetch($mail_content,array('payment_notify'=>$payment_notify));
			
			if(!empty($mobile_phone))
			{
				if($send)
				{
					require_once(ROOT_PATH.'/services/Sms/SmsPlf.class.php');
					$sms= new SmsPlf();	
					$sms->sendSMS($mobile_phone,$content);
				}
				else
				{
					$sendData = array();
					$sendData['user_id'] = intval($user_incharge_vo['user_id']); //add by chenfq 2010-12-6 添加用户ID
					$sendData['dest'] = $mobile_phone;
					$sendData['title'] = '';
					$sendData['content'] = $content;
					$sendData['create_time'] = a_gmtTime();
					$sendData['send_type'] = 1;  //短信
					$GLOBALS['db']->autoExecute(DB_PREFIX."send_list", addslashes_deep($sendData), 'INSERT');			
				}
			}
    	}

		//开始邮件通知
	    
    	if(a_fanweC("MAIL_ON")==1&&a_fanweC("SEND_PAID_MAIL")==1)
    	{
    		$user_incharge_vo = $GLOBALS['db']->getRow("select id,sn,user_id,money from ".DB_PREFIX."user_incharge where id =".intval($user_incharge_id));
    		$payment_notify['money'] = a_formatPrice($user_incharge_vo['money']);
    		//获取定单号
    		$payment_notify['order_sn'] = $user_incharge_vo['sn'];
			$user = $GLOBALS['db']->getRow("select id,email,user_name from ".DB_PREFIX."user where id = ".intval($user_incharge_vo['user_id']));
			
			//模板解析
			$payment_tmpl = $GLOBALS['db']->getRow("select mail_content,mail_title from ".DB_PREFIX."mail_template where name ='payment_mail'");
			$content = a_templateFetch($payment_tmpl['mail_content'], array('payment_notify'=>$payment_notify));

			if($send)
			{
				require ROOT_PATH.'services/Mail/Mail.class.php';
				$mail = new Mail();		
				$mail->AddAddress($user['email'],$user['user_name']);
				$mail->IsHTML(0); 
				$mail->Subject = $payment_tmpl['mail_title']; // 标题
				$mail->Body = $content; // 内容
				$mail->Send();
			}
			else
			{
				$sendData = array();
				$sendData['user_id'] = intval($user['id']); //add by chenfq 2010-12-6 添加用户ID
				$sendData['dest'] = $user['email'];
				$sendData['title'] = $payment_tmpl['mail_title'];
				$sendData['content'] = $content;
				$sendData['create_time'] = a_gmtTime();
				$sendData['send_type'] = 0;  
				$GLOBALS['db']->autoExecute(DB_PREFIX."send_list", addslashes_deep($sendData), 'INSERT');				
			}
    	}
	}

	
	function s_send_grounp_bond_mail($user_id, $groupbond_id,$send = false)
	{
		$is_valid = $GLOBALS['db']->getOne("select is_valid from ".DB_PREFIX."group_bond where id = '$groupbond_id'");//修改by hc 当无效时不发送
		if($is_valid==0)
			return;
					
		$id = intval($groupbond_id);
		
		$user = $GLOBALS['db']->getRow("select id,email,user_name from ".DB_PREFIX."user where id =".$user_id);
		
		//开始判断是否发送给其他人
		if(a_fanweC("SMS_SEND_OTHER") == 1)
		{
			$order_sn =$GLOBALS['db']->getOne("select order_id from ".DB_PREFIX."group_bond where id = '$id'");
			$email_other = $GLOBALS['db']->getOne("select user_email from ".DB_PREFIX."order where sn = '$order_sn'");
			if($email_other)
			{
				$user['email'] = $email_other;
			}
		}
		
		$bond_data = $GLOBALS['db']->getRow("select id,sn,goods_id,goods_name,order_id,end_time from ".DB_PREFIX."group_bond where id = '$id'");
		
		$goods = $GLOBALS['db']->getRowCached("select goods_short_name, promote_end_time ,suppliers_id from ".DB_PREFIX."goods where id = ".intval($bond_data['goods_id']));
		$seller_info = $GLOBALS['db']->getRowCached("select tel, address, supplier_id from ".DB_PREFIX."suppliers_depart where supplier_id = ".intval($goods['suppliers_id'])." and is_main=1");
		
		//开始模板赋值
		$bond = array(
						"goods_name"=>$bond_data['goods_name'],
						"goods_short_name"	=>	$goods['goods_short_name'],
						"name"=>a_fanweC('GROUPBOTH'),
						"sn"=>$bond_data['sn'],
						"order_sn" =>	$bond_data['order_id'],
						"id"	=> $bond_data['id'],
						"tel"	=>	$seller_info['tel'],
						"address"	=>	$seller_info['address'],
						"starttime"	=>	a_toDate($goods['promote_end_time']),
						"endtime"	=>	a_toDate($bond_data['end_time'])
						);

		$obj = array('user_name'=>$user['user_name'],'bond'=>$bond);
		//模板解析
		$payment_tmpl = $GLOBALS['db']->getRowCached("select mail_title, mail_content from ".DB_PREFIX."mail_template where name='group_bond_mail'");
		
		$content = a_templateFetch($payment_tmpl['mail_content'],$obj);
		
		$bond_orderId=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."order where sn='".$bond_data['order_id']."'");//2012-6-1(chh)
		if($send)
		{			
			require ROOT_PATH.'services/Mail/Mail.class.php';
			$mail = new Mail();		
			$mail->AddAddress($user['email'],$user['user_name']);
			$mail->IsHTML(0); 
			$mail->Subject = $payment_tmpl['mail_title']; // 标题
			$mail->Body = $content; // 内容
			$mail->Send();
		}
		else
		{
			$sendData = array();
			$sendData['user_id'] = intval($user['id']); //add by chenfq 2010-12-6 添加用户ID
			$sendData['dest'] = $user['email'];
			$sendData['title'] = $payment_tmpl['mail_title'];
			$sendData['content'] = $content;
			$sendData['create_time'] = a_gmtTime();
			$sendData['send_type'] = 0;  //邮件
			$sendData['bond_id'] = $groupbond_id;
			$sendData['order_id'] = $bond_orderId;//订单id 2012-6-1(chh)
				
			$sql = "select count(*) as num from ".DB_PREFIX."send_list where bond_id=".$groupbond_id." and dest='".$user['email']."' and status = 0";
			if($GLOBALS['db']->getOne($sql)==0)
				$GLOBALS['db']->autoExecute(DB_PREFIX."send_list", addslashes_deep($sendData), 'INSERT');
		}
	}

		//$send 为 true时默认为直接发送, 为false时为存储到数据库的发送队列  修改 by hc
	function s_send_sms($user_id, $groupbond_id,$send = false)
	{
		$is_valid = $GLOBALS['db']->getOne("select is_valid from ".DB_PREFIX."group_bond where id = '$groupbond_id' ");//修改by hc 当无效时不发送
		if(a_fanweC("IS_SMS") != 1||$is_valid==0)
			return;

		$userid = intval($user_id);
		$id = intval($groupbond_id);
		
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id =".$userid);
		//开始判断是否发送给其他人
		if(a_fanweC("SMS_SEND_OTHER") == 1)
		{
			$order_sn =$GLOBALS['db']->getOne("select order_id from ".DB_PREFIX."group_bond where id = '$id'");
			$mobile_other = $GLOBALS['db']->getOne("select mobile_phone_sms from ".DB_PREFIX."order where sn = '$order_sn'");
			if($mobile_other && strlen(trim($mobile_other)) > 6)
			{
				$user['mobile_phone'] = $mobile_other;
			}
		}
		
		if(! empty($user['mobile_phone']))
		{	
			$bond = $GLOBALS['db']->getRow("select id,sn,goods_id,goods_name,order_id,end_time from ".DB_PREFIX."group_bond where id = '$id'");
			$goods = $GLOBALS['db']->getRowCached("select goods_short_name, suppliers_id,is_order_sms,promote_end_time,promote_begin_time from ".DB_PREFIX."goods where id = ".intval($bond['goods_id']));
			$seller_info = $GLOBALS['db']->getRowCached("select tel, address, supplier_id from ".DB_PREFIX."suppliers_depart where supplier_id = ".intval($goods['suppliers_id'])." and is_main=1");
			
			$count = 1;
			if ($goods['is_order_sms'] == 1){
				$sql = "select sum(og.number) from ".DB_PREFIX."order as o left join ".DB_PREFIX."order_goods  as og on og.order_id = o.id where o.sn = '".$bond['order_id']."' and og.rec_id = ".intval($bond['goods_id'])." and o.money_status = 2";
				$count = $GLOBALS['db']->getOne($sql);
			}
			
			$smsObjs = array(
							 	"user_name"=>$user['user_name'],
							 	"shop_name" => SHOP_NAME,
								"bond"=>array(
											"goods_name"=>$bond['goods_name'],
											"goods_short_name"	=>	$goods['goods_short_name'],
											"name"=>a_fanweC('GROUPBOTH'),
											"sn" => $bond['sn'],											
											"order_sn" =>	$bond['order_id'],
											"id"	=> $bond['id'],
											"tel"	=>	$seller_info['tel'],
											"address"	=>	$seller_info['address'],
											"starttime"	=>	a_toDate($goods['promote_begin_time'],'Y-m-d'),
											"endtime"	=>	a_toDate($bond['end_time'],'Y-m-d'),
											"count"	=>	$count,
										)
							);
							
			$mail_content = $GLOBALS['db']->getOneCached("select mail_content from ".DB_PREFIX."mail_template where name='group_bond_sms'");
			
			$str = a_templateFetch($mail_content,$smsObjs);
			$bond_orderId=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."order where sn='".$bond['order_id']."'");//2012-6-1(chh)
			//2010/6/7 awfigq 自动发送团购券成功后，标记团购券为已发送
			if($send)
			{	
				require_once(ROOT_PATH.'/services/Sms/SmsPlf.class.php');
				$sms= new SmsPlf();	
				if($sms->sendSMS($user['mobile_phone'],$str))	
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."group_bond set is_send_msg = 1, send_count = send_count + 1 where id='$id'");
					//Log::record("SendSMSStatus:".$sms->message);
					//Log::save();
					return true;
				}
				else
					return false;				
			}
			else
			{
				$sendData = array();
				$sendData['user_id'] = intval($user['id']); //add by chenfq 2010-12-6 添加用户ID
				$sendData['dest'] = $user['mobile_phone'];
				$sendData['title'] = '';
				$sendData['content'] = $str;
				$sendData['create_time'] = a_gmtTime();
				$sendData['send_type'] = 1;  //短信
				$sendData['bond_id'] = $groupbond_id;
				$sendData['order_id'] = $bond_orderId;//订单id 2012-6-1(chh)

				$sql = "select count(*) as num from ".DB_PREFIX."send_list where bond_id=".$groupbond_id." and dest='".$user['mobile_phone']."' and status = 0";
				if($GLOBALS['db']->getOne($sql)==0)
					$GLOBALS['db']->autoExecute(DB_PREFIX."send_list", addslashes_deep($sendData), 'INSERT');
				
				$GLOBALS['db']->query("update ".DB_PREFIX."group_bond set is_send_msg = 1 where id='$id'");
				return true;
			}
		}
	}
	
	
	//为定单收款发送短信, $order_incharge_id : 收款单ID  //增加的邮件收款通知补充在里面
	//修改 by hc 默认send 为false 存入DB
	function s_send_orderpaid_sms($order_incharge_id,$send = false)
	{
	    //开始短信通知
    	if(a_fanweC("IS_SMS")==1&&a_fanweC("PAYMENT_SMS")==1)
    	{
    		$order_incharge_vo = $GLOBALS['db']->getRow("select order_id,money from ".DB_PREFIX."order_incharge where id =".intval($order_incharge_id));
    		$payment_notify['money'] = a_formatPrice($order_incharge_vo['money']);
    		//获取定单号
    		$payment_notify['order_sn'] = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."order where id =".intval($order_incharge_vo['order_id']));
    		
    		$sql = "select u.mobile_phone, o.user_id from ".DB_PREFIX."order as o left join ".DB_PREFIX."user as u on o.user_id = u.id where o.id = ".$order_incharge_vo['order_id'];
    		//$mobile_phone = $GLOBALS['db']->getOne($sql);
			$user = $GLOBALS['db']->getRow($sql);
			$mobile_phone = $user['mobile_phone'];
			//模板解析
			$mail_content = $GLOBALS['db']->getOneCached("select mail_content from ".DB_PREFIX."mail_template where name='payment_sms'");
			$content = a_templateFetch($mail_content,array('payment_notify'=>$payment_notify));
			
			if(!empty($mobile_phone))
			{
				if($send)
				{
					require_once(ROOT_PATH.'/services/Sms/SmsPlf.class.php');
					$sms= new SmsPlf();	
					$sms->sendSMS($mobile_phone,$content);
				}
				else
				{
					$sendData = array();
					$sendData['user_id'] = intval($user['user_id']); //add by chenfq 2010-12-6 添加用户ID
					$sendData['dest'] = $mobile_phone;
					$sendData['title'] = '';
					$sendData['content'] = $content;
					$sendData['create_time'] = a_gmtTime();
					$sendData['send_type'] = 1;  //短信
					$sendData['order_id'] = $order_incharge_vo['order_id'];//付款的订单id 2012-6-1(chh)
					$GLOBALS['db']->autoExecute(DB_PREFIX."send_list", addslashes_deep($sendData), 'INSERT');					
				}
			}
    	}
		//开始邮件通知
	    
    	if(a_fanweC("MAIL_ON")==1&&a_fanweC("SEND_PAID_MAIL")==1)
    	{
    		$order_incharge_vo = $GLOBALS['db']->getRow("select order_id,money from ".DB_PREFIX."order_incharge where id =".intval($order_incharge_id));
    		$payment_notify['money'] = a_formatPrice($order_incharge_vo['money']);
    		//获取定单号
    		$payment_notify['order_sn'] = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."order where id =".intval($order_incharge_vo['order_id']));
    		
    		$sql = "select u.email,u.user_name, o.user_id from ".DB_PREFIX."order as o left join ".DB_PREFIX."user as u on o.user_id = u.id where o.id = ".$order_incharge_vo['order_id'];
    		$user = $GLOBALS['db']->getRow($sql);
			
			//模板解析
			$payment_tmpl = $GLOBALS['db']->getRowCached("select mail_title, mail_content from ".DB_PREFIX."mail_template where name='payment_mail'");
			$content = a_templateFetch($payment_tmpl['mail_content'],array('payment_notify'=>$payment_notify));
			
			if($send)
			{			
				require ROOT_PATH.'services/Mail/Mail.class.php';
				$mail = new Mail();		
				$mail->AddAddress($user['email'],$user['user_name']);
				$mail->IsHTML(0); 
				$mail->Subject = $payment_tmpl['mail_title']; // 标题
				$mail->Body = $content; // 内容
				$mail->Send();
			}
			else
			{
				$sendData = array();
				$sendData['user_id'] = intval($user['user_id']); //add by chenfq 2010-12-6 添加用户ID
				$sendData['dest'] = $user['email'];
				$sendData['title'] = $payment_tmpl['mail_title'];
				$sendData['content'] = $content;
				$sendData['create_time'] = a_gmtTime();
				$sendData['send_type'] = 0;
				$sendData['order_id'] = $order_incharge_vo['order_id'];//付款的订单id  2012-6-1(chh)
				$GLOBALS['db']->autoExecute(DB_PREFIX."send_list", addslashes_deep($sendData), 'INSERT');
			}
    	}
	}
	
	//$send 为 true时默认为直接发送, 为false时为存储到数据库的发送队列  修改 by hc
	function s_send_groupbond_use_sms($groupbond_id,$send = false)
	{
		if(a_fanweC("IS_SMS") != 1|| a_fanweC("SMS_GROUPBOND_USE") != 1)
			return;
			
		$bond = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."group_bond where id = '$groupbond_id' and is_valid=1");//修改by hc 当无效时不发送
	
		if(!isset($bond))
			return;
			
		$userid = intval($bond['user_id']);
		$id = intval($groupbond_id);
		
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id =".$userid);
		
		$order_sn =$bond['order_sn'];
		$mobile_other = $GLOBALS['db']->getOne("select mobile_phone_sms from ".DB_PREFIX."order where sn = '$order_sn'");
		
		if ($mobile_other != ''){
			$user['mobile_phone'] = $mobile_other;
		}
		
		/*			
		//开始判断是否发送给其他人
		if(a_fanweC("SMS_SEND_OTHER") == 1)
		{
			$order_sn =$bond['order_sn'];
			$mobile_other = $GLOBALS['db']->getOne("select mobile_phone_sms from ".DB_PREFIX."order where sn = '$order_sn'");
			if($mobile_other && strlen(trim($mobile_other)) > 6)
			{
				$user['mobile_phone'] = $mobile_other;
			}
			else
			{
				$user['mobile_phone'] = '';
			}
		}
		*/
		if(!empty($user['mobile_phone']))
		{
			
			//$bond = $GLOBALS['db']->getRow("select id,sn,goods_id,goods_name,password,order_id,end_time from ".DB_PREFIX."group_bond where id = '$id'");
			$bond = $GLOBALS['db']->getRow("select id,sn,goods_id,goods_name,order_id,end_time from ".DB_PREFIX."group_bond where id = '$id'");
			$goods = $GLOBALS['db']->getRowCached("select goods_short_name, suppliers_id,is_order_sms,promote_end_time from ".DB_PREFIX."goods where id = ".intval($bond['goods_id']));
			$seller_info = $GLOBALS['db']->getRowCached("select tel, address, supplier_id from ".DB_PREFIX."suppliers_depart where supplier_id = ".intval($goods['suppliers_id'])." and is_main=1");
			
			$smsObjs = array(
							 	"user_name"=>$user['user_name'],
								"time"	=>	a_toDate(a_gmtTime(),'Ymd H:i'),
								"bond"=>array(
											"goods_name"=>$bond['goods_name'],
											"goods_short_name"	=>	$goods['goods_short_name'],
											"name"=>a_fanweC('GROUPBOTH'),
											"sn"=>$bond['sn'],											
											"order_sn" =>	$bond['order_id'],
											"id"	=> $bond['id'],
											"tel"	=>	$seller_info['tel'],
											"address"	=>	$seller_info['address']
										)
							);
			$mailinfo = $GLOBALS['db']->getRowCached("select mail_title,mail_content from ".DB_PREFIX."mail_template where name='group_bond_use_sms'");
			$mail_content = $mailinfo['mail_content'];
			$str = a_templateFetch($mail_content,$smsObjs);
			
			$bond_orderId=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."order where sn='".$bond['order_id']."'");//2012-6-1(chh)
			if($send)
			{	
				require_once(ROOT_PATH.'/services/Sms/SmsPlf.class.php');
				$sms= new SmsPlf();	
				if($sms->sendSMS($user['mobile_phone'],$str))	
				{
					return true;
				}
				else
					return false;				
			}
			else
			{
				$sendData = array();
				$sendData['user_id'] = intval($user['id']); //add by chenfq 2010-12-6 添加用户ID
				$sendData['dest'] = $user['mobile_phone'];
				$sendData['title'] = $mailinfo['mail_title'];
				$sendData['content'] = $str;
				$sendData['create_time'] = a_gmtTime();
				$sendData['send_type'] = 1;  //短信
				$sendData['bond_id'] = $groupbond_id;
				$sendData['order_id'] = $bond_orderId;//订单id 2012-6-1(chh)
				
				$sql = "select count(*) as num from ".DB_PREFIX."send_list where bond_id=".$groupbond_id." and dest='".$user['mobile_phone']."' and status = 0 and title='".$mailinfo['mail_title']."'";
				if($GLOBALS['db']->getOne($sql)==0)
					$GLOBALS['db']->autoExecute(DB_PREFIX."send_list", addslashes_deep($sendData), 'INSERT');
					
				return true;
			}
		}
	}

	function s_sendOrderGroupBonds($orderID)
	{
		//$sql = "select o.*,og.number,og.attr,og.data_name,og.rec_id,g.group_bond_end_time,g.goods_short_name,g.name_1 as goods_name from ".DB_PREFIX."order as o left join ".DB_PREFIX."order_goods as og on og.order_id = o.id left join ".DB_PREFIX."goods as g on g.id = og.rec_id where o.id = '$orderID' and o.money_status = 2";
		$sql = "select a.id,o.sn,".
                        "a.balance_total_price,".
                        "a.balance_unit_price,".
	       			"o.user_id,".
	       			"o.create_time,".
	       			"a.number,".
	       			"a.attr,".
	       			"a.data_name,".
	       			"a.rec_id,".
					"b.is_order_sms,". //add by chenfq 2010-12-1 1：按单发团购券
					"b.is_group_fail,".
	       			"b.group_bond_end_time,".
	       			"b.goods_short_name,".
					"b.bond_pw_prefix,". //add by chenfq 2011-03-30  团购券密码前缀
                     "b.bond_sn_prefix,".//团购券序号前缀
	       			"b.name_1 as goods_name".
  				" from ".DB_PREFIX."order_goods a".
  				" left outer join ".DB_PREFIX."goods b on b.id = a.rec_id".
  				" left outer join ".DB_PREFIX."order o on o.id = a.order_id".
 				" where (b.type_id = 0 or b.type_id = 2 or b.type_id = 3) and a.order_id = ".intval($orderID)." order by a.id";
		//type_id 0:团购券，序列号+密码;1:实体商品，需要配送;2:线下订购商品
		//echo "<br>".$sql."<br>";
		$order_list = $GLOBALS['db']->getAll($sql);
		//$sql = "insert into fanwe_test_log(val1,val2) values(1,'".$sql."')";
		//$GLOBALS['db']->query($sql);
		
		foreach($order_list as $order){
			$goodsID = intval($order['rec_id']);
			$order_goods_id = intval($order['id']);
			
			if($order['attr']!='')
			{
				if($order['goods_short_name']!='')  //修改 by hc
					$goodsName = $order['goods_short_name']."(".str_replace("\n",",",$order['attr']).")";
				else
					$goodsName = $order['goods_name']."(".str_replace("\n",",",$order['attr']).")";
			}
			else
			{
				if($order['goods_short_name']!='')  //修改 by hc
					$goodsName = $order['goods_short_name'];
				else
					$goodsName = $order['goods_name'];
			}
			$send_count = intval($order['number']); //本订单需要发放的方维卷
			//add by chenfq 2010-12-1 1：按单发团购券
			if (intval($order['is_order_sms']) == 1){
				$send_count = 1;
			}
			
			//计算本单已经生成过的团购券数量add by chenfq 2011-3-1
			$sql = "select count(id) from ".DB_PREFIX."group_bond where (order_goods_id = '$order_goods_id' or order_goods_id = 0 or order_goods_id = '' or order_goods_id is null) and goods_id = '$goodsID' and order_id = '".$order['sn']."'";
			$order_bonds_count = intval($GLOBALS['db']->getOne($sql));//已经生成的数量
			$send_count = $send_count - $order_bonds_count;	//实际需要新生成的数量
			//==========================add by chenfq 2011-3-1 end========
			//echo "<br>send_count:".$send_count."<br>";		
			//echo "number:".intval($order['number'])."<br>";	
			//修改 by hc ， 购买时发送团购券不再自动补全， 以免造成本单补全的被其他人团购时占用，改为直接下单直接发放，发放失败的自动生成. 
			//$sql = "select id from ".DB_PREFIX."group_bond where arr='".$order['attr']."' and goods_id = '$goodsID' and ((order_id = '') or (order_id = '".$order['sn']."')) order by sn";
			//未分配的团购券modfiy by chenfq 2011-3-1
			$sql = "select id from ".DB_PREFIX."group_bond where goods_id = '$goodsID' and ((order_id = '') or (order_id is null)) order by sn";
			$groupBonds = $GLOBALS['db']->getAll($sql);
			
			//修改 by hc 增加验证发放下的团购券是否有效 is_valid, 存在问题，在此处验证无效时，有可能团购生成被另一进程更改，需要在autoRun中再次修复is_valid值
			$is_group_fail = $order['is_group_fail'];
			$is_valid = $is_group_fail==2?1:0;  //团购成功时，有效性为1.否则为0
			for ($i = 0; $i < $send_count; $i++)
			{
				$groupBond = $groupBonds[$i];
				/*
				//修改 by hc 修正了当同时生成团购券时，团购券被另一会员占用的BUG，在发放时再次增加验证，被占时重新生成新的， 产生的问题， 将团购券数量将有可能超出购买数量， 超出的团购券无用处.并修改buy_time的更新为当前时间。 为保证有必要的修改
				$sql_update = "update ".DB_PREFIX."group_bond set user_id=".$order['user_id'].", order_id='".$order['sn']."',is_valid=".$is_valid.", status = 1, buy_time =".$order['create_time'].", create_time =".a_gmtTime().", goods_name = '".$goodsName
							 ."' where arr='".$order['attr']."' and goods_id=".$goodsID." and (order_id='' or order_id='".$order['sn']."') and id = ".intval($groupBond['id']);
				*/
				$sql_update = "update ".DB_PREFIX."group_bond set user_id=".$order['user_id'].", order_id='".$order['sn']."',is_valid=".$is_valid.", status = 1, buy_time =".$order['create_time']
							  .",order_goods_id = '$order_goods_id',arr='".$order['attr']."', goods_name = '".$goodsName
							 ."' where  goods_id=".$goodsID." and (order_id='' or order_id is null) and id = ".intval($groupBond['id']);
				$GLOBALS['db']->query($sql_update);
				$is_updated = $GLOBALS['db']->affected_rows();
				if($is_updated==0)
				{
					if(intval($groupBonds[$i+1]['id'])!=0)
					{
						//修改by hc, 下张团购券有ID. 直接进入下轮循环，直到所有预设团购券都被人分配光，重新生成团购券。
						continue;
					}
					//被占用时再，或没更新成功，即团购券不足时
					$groupBond_new = array();
					$groupBond_new['user_id'] = $order['user_id'];
					$groupBond_new['order_id'] = $order['sn'];
					$groupBond_new['goods_id'] = $goodsID;
					$groupBond_new['goods_name'] = $goodsName;
					$groupBond_new['send_count'] = 0;
                                        if($order['is_order_sms']==1)
                                            {
                                            //按单
                                            $groupBond_new['profit'] = $order['balance_total_price'];
                                            }
                                            else
                                            {
                                            //按件
                                            $groupBond_new['profit'] = $order['balance_unit_price'];
                                            }
					$groupBond_new['order_goods_id'] = $order_goods_id;//add by chenfq 2011-03-1  记录商品属性
					$groupBond_new['arr'] = $order['attr']; //add by chenfq 2011-02-23 记录商品属性
					//去掉密码生成
					//$password = unpack('H8',str_shuffle(md5(uniqid())));
					//$groupBond_new['password'] = $order['bond_pw_prefix'].$password[1];//add by chenfq 2011-03-30  团购券密码前缀
                                        
                    $groupBond_new['create_time'] = a_gmtTime();
					$groupBond_new['end_time'] = $order['group_bond_end_time'];
					/*
					if (!empty($order['group_bond_end_time'])){
						$groupBond_new['end_time'] = $order['group_bond_end_time'];
					}else{
						$groupBond_new['end_time'] = a_gmtTime() + 3600 * 24 * 30; //设置一个月后过期
					}
					*/
					
					$groupBond_new['status'] = 1;
					$groupBond_new['buy_time'] = $order['create_time'];
					$groupBond_new['is_valid'] = $is_valid;  //修改 by hc,新生成团购券时生效有效状态
					
					$do_count = 0;
				    do
				    {
				        $groupBond_new['sn'] = s_gen_groupbond_sn($goodsID,$order['bond_sn_prefix']);
				        if ($GLOBALS['db']->autoExecute(DB_PREFIX."group_bond", addslashes_deep($groupBond_new), 'INSERT'))
				        {
				            break;
				        }
				        $do_count = $do_count + 1;
				    }
				    while ($do_count < 10); // 防止订单号重复
				    
					if ($do_count >= 10){
				    	$bondID = 0;	    	
				    }else{
				    	$bondID = $GLOBALS['db']->insert_id();	
				    }			    
								
				}else{
					$bondID = $groupBond['id'];
				}
				//发放团购卷时，自动短信通知
				/*
				//修改 by hc 增加团购券的是否发短信的设置
				if (a_fanweC('AUTO_SEND_SMS')==1 && $GLOBALS['db']->getOneCached("select allow_sms from ".DB_PREFIX."goods where id = '$goodsID'")==1){
					s_send_sms($order['user_id'], $bondID);	
				}
				
				if(a_fanweC("MAIL_ON")==1&&a_fanweC("SEND_GROUPBOND_MAIL") ==1)
				{
					s_send_grounp_bond_mail($order['user_id'],$bondID);
				}
				*/
			}
			
			
			//发送团购券
			if ($is_valid == 1){
				//计算本单已经生成过的团购券数量add by chenfq 2011-3-1
				$sql = "select id from ".DB_PREFIX."group_bond where send_count = 0 and goods_id = '$goodsID' and order_id = '".$order['sn']."'";
				$order_bonds = $GLOBALS['db']->getAll($sql);
				//==========================add by chenfq 2011-3-1 end========				
				foreach($order_bonds as $groupBond){
					$bondID = intval($groupBond['id']);
					
					$groupBond = $groupBonds[$i];
					//修改 by hc 修正了当同时生成团购券时，团购券被另一会员占用的BUG，在发放时再次增加验证，被占时重新生成新的， 产生的问题， 将团购券数量将有可能超出购买数量， 超出的团购券无用处.并修改buy_time的更新为当前时间。 为保证有必要的修改
					$sql_update = "update ".DB_PREFIX."group_bond set is_valid=1, status = 1 where id = ".$bondID;				
					$GLOBALS['db']->query($sql_update);
									
					if (a_fanweC('AUTO_SEND_SMS')==1 && $GLOBALS['db']->getOneCached("select allow_sms from ".DB_PREFIX."goods where id = '$goodsID'")==1){
						s_send_sms($order['user_id'], $bondID);	
					}
					
					if(a_fanweC("MAIL_ON")==1&&a_fanweC("SEND_GROUPBOND_MAIL") ==1)
					{
						s_send_grounp_bond_mail($order['user_id'],$bondID);
					}					
				}
			}			
		}

		
		//不需配送的商品，直接设置成：无需配送  add by chenfq 2010-05-06
		//$sql_update = "update ".DB_PREFIX."order set goods_status = 6, status = 0, zip = 'aaa' where id = ".$orderID;
		//$GLOBALS['db']->query($sql_update);
	}


function s_sendUserActiveMail($user_id,$shop_name='')
{
		if(a_fanweC("MAIL_ON")==1)
		{
			$userinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
			if($userinfo)
			{
				$activesn = strtoupper("U".md5(uniqid()));
				$userinfo['active_sn'] = $activesn;
				$GLOBALS['db']->query("update ".DB_PREFIX."user set active_sn = '".$activesn."' where id=".$user_id);
				$userinfo['active_url'] = a_getDomain().__ROOT__."/index.php?m=User&a=verify&sn=".$activesn;
				
				$mail_template = $GLOBALS['db']->getRowCached("select id,mail_title, mail_content,is_html from ".DB_PREFIX."mail_template where name='user_active'");
				if($mail_template)
				{
					$mail_title = a_templateFetch($mail_template['mail_title'],array("shop_name"=>$shop_name));
					$mail_content = a_templateFetch($mail_template['mail_content'],array("user"=>$userinfo,"shop_name"=>$shop_name));
					require ROOT_PATH.'services/Mail/Mail.class.php';
					$mail = new Mail();	
					$mail->IsHTML($mail_template['is_html']); // 设置邮件格式为 HTML
					$mail->FromName = $shop_name;
					$mail->Subject = $mail_title; // 标题					
					$mail->Body =  $mail_content; // 内容
					$mail->AddAddress($userinfo['email'],$userinfo['user_name']);	
					if(!$mail->Send())
					{
						return false;
					}
					else 
					{
						return true;
					}
				}
			}
			else
				return false;
		}
		else 
		{
			return false;
		}
}	
	
?>
