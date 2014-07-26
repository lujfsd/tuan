<?php
define('BASE_INC_PATH', str_replace('common.php', '', str_replace('\\', '/', __FILE__)));
include_once('./global/constant.php');

function setBaseMoney($money,$currency_id)
{
	return $money;
}
function getBaseMoney($money,$currency_id)
{
	return str_replace(",","", number_format(round($money,2),2));
}

//用于加载读取相应语言包中的语言变量，前台语言
function load_lang($key,$lang_id)
{
		$lang_set = S("CACHE_LANG_SET");
		if($currency_radio===false)
		{
			//$lang_set = D("LangConf")->where("id=".$lang_id)->getField("lang_name"); ==SQL优化==
			$lang_set = M()->query("select lang_name from ".C("DB_PREFIX")."lang_conf where id=".$lang_id);
			$lang_set = $lang_set[0]['lang_name'];
			S("CACHE_LANG_SET",$lang_set);
		}		
		//加载当前语言的语言包
		L(include './app/Lang/'.$lang_set.'/common.php');		
		return L($key);
}
	//记录会员预存款变化明细
	//$memo 格式为 #LANG_KEY#memos  ##之间所包含的是语言包的变量
	function user_money_log($user_id, $rec_id, $rec_module, $money, $memo, $onlylog = false){
		$user_id = intval($user_id);
		$money = floatval($money);
		//$langs = D("LangConf")->findAll();  ==SQL优化==
		$langs = M()->query("select id from ".C("DB_PREFIX")."lang_conf");
		
		$log_data = array();
		$log_data['user_id'] = $user_id;
		$log_data['money'] = $money;
		$log_data['rec_id'] = $rec_id;
		$log_data['rec_module'] = $rec_module;
		$log_data['create_time'] = gmtTime();
		foreach($langs as $lang)
		{
			$lang_memo = $memo;
			preg_match_all("/#([^#]*)#/",$memo,$keys);
			foreach($keys[1] as $key)
			{				
				
				$lang_memo = preg_replace("/#[^#]*#/",load_lang($key,$lang['id']),$lang_memo);
			}
			$log_data['memo_'.$lang['id']]= $lang_memo;
		}		
		//记录会员预存款变化明细
		M("UserMoneyLog")->add($log_data);
		if ($onlylog == false){
			//增加会员的预存款金额
			$sql_str = 'update '.C("DB_PREFIX").'user set money = money + '.floatval($money).' where id = '.$user_id;
			D()->execute($sql_str);	
		}
		return true;
	}

	/**
	 * 记录帐户资金变化明细
	 * @param unknown_type $payment_id 支付id fanwe_payment.id
	 * @param unknown_type $operator_id 会员ID或管理员ID
	 * @param unknown_type $operator_module User或Admin
	 * @param unknown_type $money 变更金额
	 * @param unknown_type $memo 备注
	 * @param unknown_type $onlylog 仅插入备注，而不变更fanwe_payment.money
	 * @param unknown_type $payment_name fanwe_payment.name
	 * @param unknown_type $operator_name 会员名或管理员名称
	 * @return unknown
	 */
	function payment_money_log($payment_id, $operator_id, $rec_id, $rec_module, $money, $memo, $onlylog = false, $operator_module = 'User', $payment_name = '', $operator_name = ''){
		$payment_id = intval($payment_id);
		$operator_id = intval($operator_id);
		$money = floatval($money);
		
		if (empty($payment_name)){
			
				$payment_name = S("CACHE_PAYMENT_NAME_".$payment_id);
				if($payment_name===false)
				{
					//$payment_name = M('Payment')->where("id=".$payment_id)->getField("name_1"); ==SQL优化==
					$payment_name = M()->query("select name_1 from ".C("DB_PREFIX")."payment where id=".$payment_id);
					$payment_name = $payment_name[0]['name_1'];
					S("CACHE_PAYMENT_NAME_".$payment_id,$payment_name);
				}

		}
		
		if (empty($operator_name)){
			if ($operator_module == 'User')
			{
			  //$operator_name = M('User')->where("id=".$operator_id)->getField("user_name"); ==SQL优化==
			  $operator_name = M()->query("Select user_name from ".C("DB_PREFIX")."user where id = ".$operator_id);
			  $operator_name = $operator_name[0]['user_name'];
			}
			elseif ($operator_module == 'Admin') 
			{
			  //$operator_name = M('Admin')->where("id=".$operator_id)->getField("adm_name"); ==SQL优化==
			  $operator_name = M()->query("select adm_name from ".C("DB_PREFIX")."admin where id =".$operator_id);
			  $operator_name = $operator_name[0]['adm_name'];
			}
		}		
		
		$log_data = array();
		$log_data['payment_id'] = $payment_id;
		
		$log_data['payment_name'] = $payment_name;
		
		$log_data['operator_id'] = $operator_id; 
		$log_data['operator_name'] = $operator_name;
		
		$log_data['money'] = $money;
		$log_data['operator_module'] = $operator_module;
		
		$log_data['rec_id'] = $rec_id;
		$log_data['rec_module'] = $rec_module;
				
		$log_data['log_msg']= $memo;
		$log_data['create_time'] = gmtTime();
		$log_data['ip']= get_client_ip();
		//dump($log_data);
		M("PaymentMoneyLog")->add($log_data);
		if ($onlylog == false){
			$sql_str = 'update '.C("DB_PREFIX").'payment set money = money + '.floatval($money).' where id = '.$payment_id;
			M()->execute($sql_str);	
		}
		return true;
	}	
	//记录会员预存款变化明细
	//$memo 格式为 #LANG_KEY#memos#LANG_KEY#  ##之间所包含的是语言包的变量
	function user_score_log($user_id, $rec_id, $rec_module, $score, $memo, $onlylog = false){
		$user_id = intval($user_id);
		//$langs = D("LangConf")->findAll(); ==SQL优化==
		$langs = M()->query("select id from ".C("DB_PREFIX")."lang_conf");
		$log_data = array();
		$log_data['user_id'] = $user_id;
		$log_data['score'] = $score;
		$log_data['rec_id'] = $rec_id;
		$log_data['rec_module'] = $rec_module;
		$log_data['create_time'] = gmtTime();
		foreach($langs as $lang)
		{
			$lang_memo = $memo;
			preg_match_all("/#([^#]*)#/",$memo,$keys);
			foreach($keys[1] as $key)
			{				
				
				$lang_memo = preg_replace("/#[^#]*#/",load_lang($key,$lang['id']),$lang_memo);
			}
			$log_data['memo_'.$lang['id']]= $lang_memo;
		}		
		//记录会员预存款变化明细
		M("UserScoreLog")->add($log_data);
		if ($onlylog == false){
			$sql_str = 'update '.C("DB_PREFIX").'user set score = score + '.intval($score).' where id = '.$user_id;
			M()->execute($sql_str);	
			if($score < 0)
				M()->execute('update '.C("DB_PREFIX").'user set score = 0 where score <0 and id = '.$user_id);
		}
		
		return true;
	}
	
	//为充值发送短信, $order_incharge_id : 收款单ID
	//修改 by hc
	function send_userincharge_sms($user_incharge_id,$send = false)
	{
	    //开始短信通知
	    
    	if(fanweC("IS_SMS")==1&&fanweC("PAYMENT_SMS")==1)
    	{
    		//$user_incharge_vo = M("UserIncharge")->getById($user_incharge_id);==SQL优化==
    		$user_incharge_vo = M()->query("select id,sn,user_id,money from ".C("DB_PREFIX")."user_incharge where id = ".$user_incharge_id);
    		$user_incharge_vo = $user_incharge_vo[0];
    		
    		//获取定单号
    		$payment_notify['order_sn'] = $user_incharge_vo['sn'];
    		$user_id =  $user_incharge_vo['user_id'];
    		//$user = D("User")->getById($user_id);==SQL优化==
    		$user = M()->query("select mobile_phone from ".C("DB_PREFIX")."user where id = ".$user_id);
    		$user = $user[0];

    		//$currency = M( "Currency" )->getById(intval(M("Payment")->where("id=".$user_incharge_vo['payment'])->getField("currency")));		
			//$payment_notify['money'] = $currency['unit']. (round(($user_incharge_vo['money'] * $currency['radio']),2));
			$payment_notify['money'] = formatPrice($user_incharge_vo['money']);
			
			//模板解析
			//$payment_sms_tmpl = M("MailTemplate")->where("name='payment_sms'")->getField("mail_content");==SQL优化==
			$payment_sms_tmpl = M()->query("select mail_content from ".C("DB_PREFIX")."mail_template where name = 'payment_sms'");
			$payment_sms_tmpl = $payment_sms_tmpl[0]['mail_content'];
			$tpl = Think::instance('ThinkTemplate');
			ob_start();
			eval('?' . '>' .$tpl->parse($payment_sms_tmpl));
			$content = ob_get_clean();
			if(!empty($user['mobile_phone']))
			{

					$sendData['dest'] = $user['mobile_phone'];
					$sendData['title'] = '';
					$sendData['content'] = $content;
					$sendData['create_time'] = gmtTime();
					$sendData['send_type'] = 1;  //短信
					M("SendList")->add($sendData);				
			}
    	}

		//开始邮件通知
	    
    	if(fanweC("MAIL_ON")==1&&fanweC("SEND_PAID_MAIL")==1)
    	{
    		//$user_incharge_vo = M("UserIncharge")->getById($user_incharge_id);==SQL优化==
    		$user_incharge_vo = M()->query("select id,sn,user_id,money from ".C("DB_PREFIX")."user_incharge where id = ".$user_incharge_id);
    		$user_incharge_vo = $user_incharge_vo[0];
    		
    		//获取定单号
    		$payment_notify['order_sn'] = $user_incharge_vo['sn'];
    		$user_id =  $user_incharge_vo['user_id'];
    		//$user = D("User")->getById($user_id);==SQL优化==
    		$user = M()->query("select id,email,user_name from ".C("DB_PREFIX")."user where id =".$user_id);
    		$user = $user[0];

    		//$currency = M( "Currency" )->getById(intval(M("Payment")->where("id=".$user_incharge_vo['payment'])->getField("currency")));		
			//$payment_notify['money'] = $currency['unit']. (round(($user_incharge_vo['money'] * $currency['radio']),2));
			$payment_notify['money'] = formatPrice($user_incharge_vo['money']);
			
			//模板解析
			//$payment_tmpl = M("MailTemplate")->where("name='payment_mail'")->find();==SQL优化==
			$payment_tmpl = M()->query("select mail_content,mail_title from ".C("DB_PREFIX")."mail_template where name ='payment_mail'");
			$payment_tmpl = $payment_tmpl[0];
			
			$tpl = Think::instance('ThinkTemplate');
			ob_start();
			eval('?' . '>' .$tpl->parse($payment_tmpl['mail_content']));
			$content = ob_get_clean();


				$sendData['dest'] = $user['email'];
				$sendData['title'] = $payment_tmpl['mail_title'];
				$sendData['content'] = $content;
				$sendData['create_time'] = gmtTime();
				$sendData['send_type'] = 0;  
				M("SendList")->add($sendData);				
    	}
	}
	//发货短信通知   发货邮件通知也补在里面
	function send_delivery_sms($delivery_id,$send = false)
	{
		if(fanweC("IS_SMS")==1&&fanweC("DELIVERY_SMS")==1)
    	{
    		//$delivery_vo = M("OrderConsignment")->getById($delivery_id);==SQL优化==
    		$delivery_vo = M()->query("select id,order_id,delivery_code,express_id from ".C("DB_PREFIX")."order_consignment where id = ".$delivery_id);
    		$delivery_vo = $delivery_vo[0];     		
    		
    		//获取定单号
    		//$delivery_notify['order_sn'] = M("Order")->where("id=".$delivery_vo['order_id'])->getField("sn");==SQL优化==
    		$order = M()->query("select sn,user_id,mobile_phone_sms from ".C("DB_PREFIX")."order where id = ".$delivery_vo['order_id']);
    		//var_dump($order);
    		$order_sn = $order[0]['sn'];
    		$user_id = intval($order[0]['user_id']);
    		$mobile_phone_sms = $order[0]['mobile_phone_sms'];
    		$delivery_notify['order_sn'] = $order_sn;
    		//$user_id =  M("Order")->where("id=".$delivery_vo['order_id'])->getField("user_id");==SQL优化==
    		//$user = D("User")->getById($user_id);==SQL优化==
    		if ($mobile_phone_sms == ''){
				$user = M()->query("select mobile_phone from ".C("DB_PREFIX")."user where id = ".$user_id);
    			$mobile_phone_sms = $user[0]['mobile_phone'];    			
    		}
    		
			$delivery_notify['delivery_code'] = $delivery_vo['delivery_code'];
			$delivery = M()->query("select `name` from ".C("DB_PREFIX")."express where id='".$delivery_vo['express_id']."'");
			$delivery_notify['delivery_name'] = $delivery[0]['name'];
			
			//模板解析
			//$payment_sms_tmpl = M("MailTemplate")->where("name='delivery_sms'")->getField("mail_content");==SQL优化==
			$payment_sms_tmpl = M()->query("select mail_content from ".C("DB_PREFIX")."mail_template where name='delivery_sms'");
			$payment_sms_tmpl = $payment_sms_tmpl[0]['mail_content'];
			
			$tpl = Think::instance('ThinkTemplate');
			ob_start();
			eval('?' . '>' .$tpl->parse($payment_sms_tmpl));
			$content = ob_get_clean();


			$sendData['dest'] = $mobile_phone_sms;
			$sendData['title'] = '';
			$sendData['content'] = $content;
			$sendData['create_time'] = gmtTime();
			$sendData['send_type'] = 1;  //短信
			$sendData['user_id'] = $user_id;
			$sendData['order_id'] = $delivery_vo['order_id'];//订单id 2012-6-1(chh)
			
			M("SendList")->add($sendData);						
    	}
		if(fanweC("MAIL_ON")==1&&fanweC("SEND_DELIVERY_MAIL")==1)
    	{
    		//$delivery_vo = M("OrderConsignment")->getById($delivery_id);==SQL优化==
    		$delivery_vo = M()->query("select order_id,delivery_code,express_id from ".C("DB_PREFIX")."order_consignment where id =".$delivery_id);
    		$delivery_vo = $delivery_vo[0];
    		
    		//获取定单号
    		//$delivery_notify['order_sn'] = M("Order")->where("id=".$delivery_vo['order_id'])->getField("sn");==SQL优化==
    		$order = M()->query("select sn,user_id,user_email from ".C("DB_PREFIX")."order where id =".$delivery_vo['order_id']);
    		$order_sn = $order[0]['sn'];
    		$user_id = intval($order[0]['user_id']);
    		$user_email = $order[0]['user_email'];
    		$delivery_notify['order_sn'] = $order_sn;
    		
    		//$user_id =  M("Order")->where("id=".$delivery_vo['order_id'])->getField("user_id");==SQL优化==
    		//$user = D("User")->getById($user_id);==SQL优化==
    		if ($user_email == ''){
				$user = M()->query("select email from ".C("DB_PREFIX")."user where id = ".$user_id);
    			$user_email = $user[0]['email'];    			
    		}
    		
			$delivery_notify['delivery_code'] = $delivery_vo['delivery_code'];
			$delivery = M()->query("select `name` from ".C("DB_PREFIX")."express where id='".$delivery_vo['express_id']."'");
			$delivery_notify['delivery_name'] = $delivery[0]['name'];
			//模板解析
			//$payment_tmpl = M("MailTemplate")->where("name='delivery_mail'")->find();==SQL优化==
			$payment_tmpl = M()->query("select mail_title,mail_content from ".C("DB_PREFIX")."mail_template where name ='delivery_mail'");
			$payment_tmpl = $payment_tmpl[0];
			
			$tpl = Think::instance('ThinkTemplate');
			ob_start();
			eval('?' . '>' .$tpl->parse($payment_tmpl['mail_content']));
			$content = ob_get_clean();


			$sendData['dest'] = $user_email;
			$sendData['title'] = $payment_tmpl['mail_title'];
			$sendData['content'] = $content;
			$sendData['create_time'] = gmtTime();
			$sendData['send_type'] = 0;  
			$sendData['user_id'] = $user_id;
			$sendData['order_id'] = $delivery_vo['order_id'];//订单id 2012-6-1(chh)
			
			M("SendList")->add($sendData);					
    	}
	}
	
	//处理成功返回true，处理失败返回，错误消息
    
    
	//增加已收金额 modfiy by chenfq 2010-06-5 添加 $onlinepay 在线支付参数
	
	
	
	
	
	
	//支付返利
	function payReferrals($id)
	{
		$referrals = M("Referrals")->getById($id);
		if ($referrals)
		{

				//现金返利
				$user = D("User")->getById($referrals['user_id']);
				if($referrals['money'] > 0)
				{					
					$msg = sprintf(L("PAY_REFERRALS_MONEY_INFO"),$user['user_name']);
					$sql_str = 'insert into '.C("DB_PREFIX")."user_money_log(user_id, rec_id,money,create_time,rec_module,memo_1) values($referrals[parent_id],$id,$referrals[money],".gmtTime().",'Referrals','$msg')";																																						
					M()->execute($sql_str);	
					$sql_str = 'update '.C("DB_PREFIX").'user set money = money + '.$referrals['money'].' where id = '.$referrals['parent_id'];
					M()->execute($sql_str);
				}
				
				if($referrals['score'] > 0)
				{
					$msg = sprintf(L("PAY_REFERRALS_SCORE_INFO"),$user['user_name']);
					$sql_str = 'insert into '.C("DB_PREFIX")."user_score_log(user_id, rec_id,score,create_time,rec_module,memo_1) values($referrals[parent_id],$id,$referrals[score],".gmtTime().",'Referrals','$msg')";																																						
					M()->execute($sql_str);	
					$sql_str = 'update '.C("DB_PREFIX").'user set score = score + '.$referrals['score'].' where id = '.$referrals['parent_id'];
					M()->execute($sql_str);
				}

				$referrals['is_pay'] = 1;
				$referrals['pay_time'] = gmtTime(); 
				M("Referrals")->save($referrals);
				clear_user_order_cache(0);
		}
	}
	//退还返利
	function unPayReferrals($id)
	{

		$referrals = D("Referrals")->getById($id);
		if ($referrals)
		{

			//现金返利
			$user = D("User")->getById($referrals['user_id']);
			if($referrals['money'] > 0)
			{
					
					$msg = sprintf(L("UNPAY_REFERRALS_MONEY_INFO"),$user['user_name']);
					$sql_str = 'insert into '.C("DB_PREFIX")."user_money_log(user_id, rec_id,money,create_time,rec_module,memo_1) values($referrals[parent_id],$id,-$referrals[money],".gmtTime().",'Referrals','$msg')";																																						
					M()->execute($sql_str);	
					$sql_str = 'update '.C("DB_PREFIX").'user set money = money - '.$referrals['money'].' where id = '.$referrals['parent_id'];
					M()->execute($sql_str);
			}
				
			if($referrals['score'] > 0)
			{
					$msg = sprintf(L("UNPAY_REFERRALS_SCORE_INFO"),$user['user_name']);
					$sql_str = 'insert into '.C("DB_PREFIX")."user_score_log(user_id, rec_id,money,create_time,rec_module,memo_1) values($referrals[parent_id],$id,-$referrals[score],".gmtTime().",'Referrals','$msg')";																																						
					M()->execute($sql_str);	
					$sql_str = 'update '.C("DB_PREFIX").'user set score = score - '.$referrals['score'].' where id = '.$referrals['parent_id'];
					M()->execute($sql_str);
			}
			$referrals['create_time'] = 0;
			$referrals['is_pay'] = 0;
			$referrals['pay_time'] = 0;
			D("Referrals")->save($referrals);
		}
		
	}	
		
	//由数据库取出系统的配置
	function fanweC($name)
	{
		if($name == 'SYS_ADMIN'){
			$sql = "SELECT val FROM ".C("DB_PREFIX")."sys_conf WHERE name = 'SYS_ADMIN' limit 1";
			$val = M()->query($sql);
			return $val[0]['val'];
		}		
		if($name == 'INTEGRATE_CONFIG'){
			$sql = "SELECT val FROM ".C("DB_PREFIX")."sys_conf WHERE name = 'INTEGRATE_CONFIG' limit 1";
			$val = M()->query($sql);
			return $val[0]['val'];
		}
		
		if($name == 'DEFAULT_LANG'){
			$sql = "SELECT lang_name FROM ".C("DB_PREFIX")."lang_conf WHERE id = 1";
			$val = M()->query($sql);
			return $val[0]['lang_name'];
		}
				
		if(!file_exists(getcwd()."/Public/sys_config.php"))
		{
			//重新生成配置文件
			$sys_configs = M()->query("select name,val from ".C("DB_PREFIX")."sys_conf");
			$config_str = "<?php\n";
			$config_str .= "return array(\n";
			foreach($sys_configs as $k=>$v)
			{
				$config_str.="'".$v['name']."'=>'".str_replace("'","\\'",$v['val'])."',\n";
			}
			$config_str.=");\n ?>";
			@file_put_contents(getcwd()."/Public/sys_config.php",$config_str);
		}
		static $config = array();
		$config = require BASE_INC_PATH .'../Public/sys_config.php';
		if($name != 'SHOP_URL')
		{
			$val = S("SYS_CONF_".$name);
			if($val===false)
			{
				if($name=='INTEGRATE_CODE')
				{
					//$val = M("SysConf")->where("name='".$name."'")->getField("val");
					$val = stripslashes($config[$name]);
					if(!$val)
					$val = 'fanwe';
				}
				else
				{
					$val = stripslashes($config[$name]);
				}
				S("SYS_CONF_".$name,$val);			
			} 
		}
		
		//$val = M("SysConf")->where("name='".$name."'")->getField("val");
		if($name == 'SHOP_URL')
			return "http://".$_SERVER['HTTP_HOST'].__ROOT__;
		elseif($val!='')
		{
			return  $val;
		}
		else
		{
			return C($name);
		}

	}

	//已发货数量 统计
	function order_send_num($order_id) {	
		$sql_str = 'UPDATE  '.C("DB_PREFIX").'ORDER_GOODS AS A'.
					'   SET A.SEND_NUMBER = IFNULL((SELECT SUM(B.NUMBER)'.
					'                       FROM  '.C("DB_PREFIX").'ORDER_CONSIGNMENT_GOODS AS B'.
					'                      WHERE B.ORDER_GOODS_ID = A.ID),0) -'.
					'                    IFNULL((SELECT SUM(B.NUMBER)'.
					'                       FROM  '.C("DB_PREFIX").'ORDER_RE_CONSIGNMENT_GOODS AS B'.
					'                      WHERE B.ORDER_GOODS_ID = A.ID),0)'.
					' WHERE A.ORDER_ID = '.$order_id;
		$sql_str = strtolower($sql_str);
		$Model = new Model();
		$Model->execute($sql_str);							 
	}

	//减库存
	function order_dec_stock($order_consignment_id) {	

//		$sql_str =	'UPDATE '.C("DB_PREFIX").'GOODS G'.
//					'   SET G.STOCK = G.STOCK - IFNULL('.
//					'                        (SELECT SUM(A.NUMBER)'.
//					'                           FROM '.C("DB_PREFIX").'ORDER_CONSIGNMENT_GOODS A'.
//					'                           LEFT OUTER JOIN '.C("DB_PREFIX").'ORDER_GOODS B ON B.ID = A.ORDER_GOODS_ID'.
//					'                          WHERE G.ID = B.rec_id'.
//					'                            AND A.ORDER_CONSIGNMENT_ID = '.$order_consignment_id.'), 0)'.
//					' WHERE G.ID IN'.
//					'       (SELECT B.rec_id'.
//					'          FROM '.C("DB_PREFIX").'ORDER_CONSIGNMENT_GOODS A'.
//					'          LEFT OUTER JOIN '.C("DB_PREFIX").'ORDER_GOODS B ON B.ID = A.ORDER_GOODS_ID'.
//					'         WHERE A.ORDER_CONSIGNMENT_ID = '.$order_consignment_id.')';
//		$sql_str = strtolower($sql_str);
//		$Model = new Model();
//		$Model->execute($sql_str);		
	}		

	//增加库存
	function order_inc_stock($order_re_consignment_id) {	
//		$sql_str =	'UPDATE '.C("DB_PREFIX").'GOODS G'.
//					'   SET G.STOCK = G.STOCK + IFNULL('.
//					'                        (SELECT SUM(A.NUMBER)'.
//					'                           FROM '.C("DB_PREFIX").'ORDER_RE_CONSIGNMENT_GOODS A'.
//					'                           LEFT OUTER JOIN '.C("DB_PREFIX").'ORDER_GOODS B ON B.ID = A.ORDER_GOODS_ID'.
//					'                          WHERE G.ID = B.rec_id'.
//					'                            AND A.ORDER_RE_CONSIGNMENT_ID = '.$order_re_consignment_id.'), 0)'.
//					' WHERE G.ID IN'.
//					'       (SELECT B.rec_id'.
//					'          FROM '.C("DB_PREFIX").'ORDER_RE_CONSIGNMENT_GOODS A'.
//					'          LEFT OUTER JOIN '.C("DB_PREFIX").'ORDER_GOODS B ON B.ID = A.ORDER_GOODS_ID'.
//					'         WHERE A.ORDER_RE_CONSIGNMENT_ID = '.$order_re_consignment_id.')';
//		//dump($sql_str);
//		$sql_str = strtolower($sql_str);
//		$Model = new Model();
//		$Model->execute($sql_str);		
	}
	
	
	/**
	 * 生成优惠卡号
	 *
	 * @param integer $id fanwe_promote_card.id
	 * @return string
	 */	
	function buildCard($id){
		$tmp = String::keyGen();
		$tmp = substr($tmp, 0, 16 - strlen($id)).$id;
		return $tmp;
	}

	function formatMoney($money,$currency_id)
	{
//		$currency = D("Currency")->where("id=".$currency_id)->find();
//		if(!$currency) 
//			$currency_radio = 1;
//		else
//			$currency_radio = $currency['radio'];
//		$money = number_format($money * $currency_radio,2);
//		return $currency['unit']." ".$money;
		return formatPrice($money);
	}
	
	function get_all_files( $path )
	{
	    $list = array();
	    foreach( glob( $path . '/*') as $item ){
	        if( is_dir( $item ) ){
	         $list = array_merge( $list , get_all_files( $item ) );
	        }
	        else{
	         //if(eregi(".php",$item)){}//这里可以增加判断文件名或其他。changed by:edlongren
	         $list[] = $item;
	        }
	    }
	    return $list;
	}
	
	// 定义重置队列群发
	function reset_auto_runing()
	{
		//$lock_file = getcwd()."/Public/autorun.lock";
		//@unlink($lock_file);
		S("CACHE_LOCK_AUTO_RUN",NULL);
	}
	

	function autoSend()
	{
		//清空10小时前的发送队列
		M("SendList")->where("status=1 and ".gmtTime()."-send_time>36000")->delete();
		set_time_limit(0);
		ignore_user_abort(true);
		//服务端的全量变量
		if (M("SysConf")->where("name='AUTO_SEND_ING'")->getField('val')==0){

			D()->query("update ".C("DB_PREFIX")."sys_conf set val ='1' where status = 1 and name = 'AUTO_SEND_ING'");
			D()->query("update ".C("DB_PREFIX")."sys_conf set val ='".gmtTime()."' where status = 1 and name = 'AUTO_SEND_BEGIN_TIME'");

		
			send_msg_list();		
			D()->query("update ".C("DB_PREFIX")."sys_conf set val ='0' where status = 1 and name = 'AUTO_SEND_ING'");
			
		}else{
			//在自动执行中....
			$auto_begin_time = intval(M("SysConf")->where("name='AUTO_SEND_BEGIN_TIME'")->getField('val'));
			if ( gmtTime() - $auto_begin_time > 600 ){//(10分钟)超时后，自动把状态改为：false
//				D("SysConf")->where("status=1 and name='AUTO_RUN_ING'")->setField("val",0);
				D()->query("update ".C("DB_PREFIX")."sys_conf set val ='0' where status = 1 and name = 'AUTO_SEND_ING'");
			}
		}
	}
	
	function autoSendMail()
	{
		//清空10小时前的发送队列
		M("MailSendList")->where("status=1 and ".gmtTime()."-send_time>36000")->delete();
		set_time_limit(0);
		ignore_user_abort(true);
		//服务端的全量变量
		if (M("SysConf")->where("name='AUTO_SEND_MAIL_ING'")->getField('val')==0){

			D()->query("update ".C("DB_PREFIX")."sys_conf set val ='1' where status = 1 and name = 'AUTO_SEND_MAIL_ING'");
			D()->query("update ".C("DB_PREFIX")."sys_conf set val ='".gmtTime()."' where status = 1 and name = 'AUTO_SEND_MAIL_BEGIN_TIME'");

		
			send_mail_list();		
			D()->query("update ".C("DB_PREFIX")."sys_conf set val ='0' where status = 1 and name = 'AUTO_SEND_MAIL_ING'");
			
		}else{
			//在自动执行中....
			$auto_begin_time = intval(M("SysConf")->where("name='AUTO_SEND_MAIL_BEGIN_TIME'")->getField('val'));
			if ( gmtTime() - $auto_begin_time > 1800 ){//群发邮件半小时后超时，自动把状态改为：false
//				D("SysConf")->where("status=1 and name='AUTO_RUN_ING'")->setField("val",0);
				D()->query("update ".C("DB_PREFIX")."sys_conf set val ='0' where status = 1 and name = 'AUTO_SEND_MAIL_ING'");
			}
		}
	}
	function autoSendGroupBond(){
		//is_group_fail:0、团购中....;1、表示团购失败;2、表示团购成功
		//group_user：最低团购人数,设为 0 则不限制团购人数; max_bought：用户最大购买数量,设为 0 则不限制用户最大购买数量; user_count:购买商品的人数
		
		//团购时间结束 或 购买人数 大于 最低团购人数 时，就自动放发方维卷
//		$goods_list = S("CACHE_SUCCESS_GOODS_LIST");		
//		if($goods_list === false)
//		{
//			$goods_list = D("Goods")->where("is_group_fail = 0 and buy_count >= 0 and (promote_end_time <".gmtTime()." or buy_count >= group_user) ")->findAll();
//			if(!$goods_list)
//			{
//				$goods_list = array();
//			}
//			S("CACHE_SUCCESS_GOODS_LIST",$goods_list);
//		}
		
		$goods_list = D("Goods")->where("is_group_fail = 0 and buy_count >= 0 and (promote_end_time <".gmtTime()." or buy_count >= group_user) ")->findAll();
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
					$goods['complete_time'] = gmtTime();
					$goods['fail_buy_count'] = $goods['buy_count'];
					D("Goods")->save($goods);						
				}else{
					//if ($goods['promote_end_time'] <gmtTime()){ //add by chenfq 2010-05-30 判断时间是否结束
						if($goods['type_id']==0||$goods['type_id']==2)		
								
						sendGroupBond($goods['id']);

						$goods['is_group_fail'] = 2;
						$goods['complete_time'] = gmtTime();
						D("Goods")->save($goods);
					//}
				}
			}
			clear_cache();
		}		
	}
	
	//修改 by hc ， 去除原有补全功能， 在该函数执行时执行操作：1. 将要发下去的团购券的is_valid改为1, 2. 需要短信和邮件通知时通知下去
    function sendGroupBond($goods_id)
	{
		$goodsID = intval($goods_id);
		
		$goods = D("Goods")->where("id = '$goodsID'")->find();
		$time = gmtTime();
		$typeID = $goods['type_id'];
		
		
		if($goods['is_group_fail'] == 1)
		{
			
		}
		elseif((intval($goods['promote_end_time']) < $time) || (($goods['is_group_fail'] == 0) && ($goods['buy_count'] >= $goods['group_user'])))
		{
			
			if($typeID == 0 || $typeID == 2)
			{
				
				$langItem = S("CACHE_LANG_ITEM");
				if($langItem===false)
				{
					$langItem = D("LangConf")->where("lang_name='".fanweC('DEFAULT_LANG')."'")->find();
					S("CACHE_LANG_ITEM",$langItem);
				}
				$default_lang_id = $langItem['id'];  //默认语言的ID
				//$select_dispname = "name_".$default_lang_id;

				
				$sql = "select o.*,og.number,og.attr from ".C("DB_PREFIX")."order as o left join ".C("DB_PREFIX")."order_goods  as og on og.order_id = o.id where og.rec_id = '$goodsID' and o.money_status = 2";
				$orderList = M()->query($sql);
				

				$groupBond_m = D ("GroupBond");
				
				foreach($orderList as $order)
				{
					$sql_update = "update ".C("DB_PREFIX")."group_bond set status = 1, buy_time =".$order['create_time'].",create_time =".gmtTime().",is_valid=1  where goods_id=".$goodsID." and order_id='".$order['sn']."'";	
					M()->execute($sql_update);
					
					//修改 by hc 不再查询所有的团购券进行分发，以免错位，仅查询当前订单的团购券进行分发有效性
					$groupBonds = D("GroupBond")->where("goods_id = '$goodsID' and order_id = '".$order['sn']."' and is_valid = 1")->findAll();
					foreach($groupBonds as $gbdata)
					{
						//发放团购卷时，自动短信通知
						if (fanweC('AUTO_SEND_SMS')==1&&M("Goods")->where("id=".$goodsID)->getField("allow_sms")==1){
							send_sms($order['user_id'], $gbdata['id']);
							//dump('AUTO_SEND_SMS');			
						}
						
						if(fanweC("MAIL_ON")==1&&fanweC("SEND_GROUPBOND_MAIL") ==1)
						{
							send_grounp_bond_mail($order['user_id'],$gbdata['id']);
						}
					}
					
					$order['goods_status'] = 5;//不需配送的商品，直接设置成：无需配送  add by chenfq 2010-05-06
					$order['status']=0;
					
					D("Order")->save($order);
				}
			}
		}
	}
		
	//$send 为 true时默认为直接发送, 为false时为存储到数据库的发送队列  修改 by hc
	function send_sms($user_id, $groupbond_id,$send = false)
	{
		$is_valid = intval(M("GroupBond")->where("id=".$groupbond_id)->getField("is_valid"));  //修改by hc 当无效时不发送
		if(fanweC("IS_SMS") != 1||$is_valid==0)
			return;
			
		$userid = intval($user_id);
		$id = intval($groupbond_id);
		$user = D("User")->where("id =".$userid)->find();
		/*
		//开始判断是否发送给其他人
		if(fanweC("SMS_SEND_OTHER") == 1)
		{
			$order_sn = M("GroupBond")->where("id=".$id)->getField('order_id');
			$mobile_other = M("Order")->where("sn='".$order_sn."'")->getField("mobile_phone_sms");
			if($mobile_other && strlen(trim($mobile_other)) > 6)
			{
				$user['mobile_phone'] = $mobile_other;
			}
		}
		*/
		$order_sn = M("GroupBond")->where("id=".$id)->getField('order_id');
		$mobile_other = M("Order")->where("sn='".$order_sn."'")->getField("mobile_phone_sms");
		if($mobile_other && strlen(trim($mobile_other)) > 6)
		{
			$user['mobile_phone'] = $mobile_other;
		}
		
		Log::record("send_sms_$user_id:".$userid.";groupbond_id:".$groupbond_id);
		Log::save();
		//return;
		
		if(! empty($user['mobile_phone']))
		{
			
			$bond = D("GroupBond")->where("id = $id")->find();
			$promote_begin_time = M("Goods")->where("id=".$bond['goods_id'])->getField("promote_begin_time");
			$goods_short_name = M("Goods")->where("id=".$bond['goods_id'])->getField("goods_short_name");
			$seller_info_id = M("Goods")->where("id=".$bond['goods_id'])->getField("suppliers_id");
			$seller_info = M("SuppliersDepart")->where("supplier_id=".$seller_info_id." and is_main=1")->find();
			$bond_orderId = M("Order")->where("sn='".$bond['order_id']."'")->getField("id");//订单id 2012-6-1(chh)
			
			$smsObjs = array(
							 	"user_name"=>$user['user_name'],
								"bond"=>array(
											"goods_name"=>$bond['goods_name'],
											"goods_short_name"	=>	$goods_short_name,
											"name"=>fanweC('GROUPBOTH'),
											"sn"=>$bond['sn'],
											"password"=>$bond['password'],
											"order_sn" =>	$bond['order_id'],
											"id"	=> $bond['id'],
											"tel"	=>	$seller_info['tel'],
											"address"	=>	$seller_info['address'],
											"starttime"	=>	toDate($promote_begin_time,'Ymd'),
											"endtime"	=>	toDate($bond['end_time'],'Ymd')
										)
							);
			$mail_template = M("MailTemplate")->where("name='group_bond_sms'")->find();
			
			if($mail_template)
				$str = templateFetch($mail_template['mail_content'],$smsObjs);
			/*
			//2010/6/7 awfigq 自动发送团购券成功后，标记团购券为已发送
			if($send)
			{		
				if($sms->sendSMS($user['mobile_phone'],$str))	
				{
					$bond = D("GroupBond")->where("id = $id")->setField("is_send_msg",1);
					M("GroupBond")->setInc("send_count","id = $id",1);
					Log::record("SendSMSStatus:".$sms->message);
					Log::save();
					return true;
				}
				else{
					return false;
				}
								
			}
			else
			{
*/
				$sendData['dest'] = $user['mobile_phone'];
				$sendData['title'] = '';
				$sendData['content'] = $str;
				$sendData['create_time'] = gmtTime();
				$sendData['send_type'] = 1;  //短信
				$sendData['bond_id'] = $groupbond_id;
				$sendData['order_id'] = $bond_orderId;//订单id 2012-6-1(chh)
				if(M("SendList")->where("bond_id=".$groupbond_id." and dest='".$user['mobile_phone']."' and status = 0")->count()==0)
				M("SendList")->add($sendData);
				D("GroupBond")->where("id = $id")->setField("is_send_msg",1);
				return true;
			//}
				
						
		}
	}

	function send_grounp_bond_mail($user_id, $groupbond_id,$send = false)
	{
		$is_valid = intval(M("GroupBond")->where("id=".$groupbond_id)->getField("is_valid"));  //修改by hc 当无效时不发送
		if($is_valid==0)
		return;
		$userid = intval($user_id);
		$id = intval($groupbond_id);
		$user = D("User")->getById($userid);
		//开始判断是否发送给其他人
		if(fanweC("SMS_SEND_OTHER") == 1)
		{
			$order_sn = M("GroupBond")->where("id=".$id)->getField('order_id');
			$email_other = M("Order")->where("sn='".$order_sn."'")->getField("user_email");
			if($email_other)
			{
				$user['email'] = $email_other;
			}
		}
		$bond_data = D("GroupBond")->where("id = $id")->find();
		$promote_end_time = M("Goods")->where("id=".$bond_data['goods_id'])->getField("promote_end_time");
		$goods_short_name = M("Goods")->where("id=".$bond_data['goods_id'])->getField("goods_short_name");
		$seller_info_id = M("Goods")->where("id=".$bond_data['goods_id'])->getField("suppliers_id");
		$seller_info = M("SuppliersDepart")->where("supplier_id=".$seller_info_id." and is_main=1")->find();
		$bond_orderId = M("Order")->where("sn='".$bond_data['order_id']."'")->getField("id");//订单id 2012-6-1(chh)
	
		
		//开始模板赋值
		$user_name = $user['user_name'];							
		$bond = array(
											"goods_name"=>$bond_data['goods_name'],
											"goods_short_name"	=>	$goods_short_name,
											"name"=>fanweC('GROUPBOTH'),
											"sn"=>$bond_data['sn'],
											"password"=>$bond_data['password'],
											"order_sn" =>	$bond_data['order_id'],
											"id"	=> $bond_data['id'],
											"tel"	=>	$seller_info['tel'],
											"address"	=>	$seller_info['address'],
											"starttime"	=>	toDate($promote_end_time),
											"endtime"	=>	toDate($bond_data['end_time'])
										);
	
		//模板解析
		$payment_tmpl = M("MailTemplate")->where("name='group_bond_mail'")->find();
		$tpl = Think::instance('ThinkTemplate');
		ob_start();
		eval('?' . '>' .$tpl->parse($payment_tmpl['mail_content']));
		$content = ob_get_clean();	
			
		
		if($send)
		{			
			
			$mail = new Mail();		
			$mail->AddAddress($user['email'],$user['user_name']);
			$mail->IsHTML(0); 
			$mail->Subject = $payment_tmpl['mail_title']; // 标题
			$mail->Body = $content; // 内容
			$mail->Send();
		}
		else
		{
				$sendData['dest'] = $user['email'];
				$sendData['title'] = $payment_tmpl['mail_title'];
				$sendData['content'] = $content;
				$sendData['create_time'] = gmtTime();
				$sendData['send_type'] = 0;  //邮件
				$sendData['bond_id'] = $groupbond_id;
				$sendData['order_id'] = $bond_orderId;//订单id 2012-6-1(chh)
				if(M("SendList")->where("bond_id=".$groupbond_id." and dest='".$user['email']."' and status = 0")->count()==0)
				M("SendList")->add($sendData);
		}

		
	}
	
	function utf8ToGB($str)
	{
		Vendor('iconv');
		$chinese = new Chinese();
		return $chinese->Convert("UTF-8","GBK",$str);
	}
	
	function gbToUTF8($str)
	{
		Vendor('iconv');
		$chinese = new Chinese();
		return $chinese->Convert("GBK","UTF-8",$str);
	}
	
	function templateFetch($templateContent,$templateVars = '',$isFile = false)
	{
		if(is_array($templateVars))
		{
			foreach($templateVars as $key => $var)
			{
				$$key=$var;
			}
		}
		
		if($isFile)
		{
			$templateContent = FANWE_LANG_TMPL."@".$templateContent;
			
			if(strpos($templateContent,'@')){
				$templateContent   =   TMPL_PATH.str_replace(array('@',':'),'/',$templateContent).C('TMPL_TEMPLATE_SUFFIX');
			}elseif(strpos($templateContent,':')){
				$templateContent   =   TEMPLATE_PATH.'/'.str_replace(':','/',$templateContent).C('TMPL_TEMPLATE_SUFFIX');
			}elseif(!is_file($templateContent))    {
				$templateContent =  dirname(C('TMPL_FILE_NAME')).'/'.$templateContent.C('TMPL_TEMPLATE_SUFFIX');
			}	
			
			$templateContent = file_get_contents($templateContent);
		}
		
		$tpl = Think::instance('ThinkTemplate');
		ob_start();
		ob_implicit_flush(0);
		eval('?' . '>' . $tpl->parse($templateContent));
		$content = ob_get_clean();
		return $content;	
	}
	
	
	
	function getCol($sql, $field_name)
    {
        //$res = $this->query($sql);
        $item_list = M()->query($sql);
        if ($item_list !== false)
        {
            $arr = array();

			foreach ($item_list AS $item){
				$arr[] = $item[$field_name];
			}
            
            return $arr;
        }
        else
        {
            return false;
        }
    }
	/**
	 * 创建像这样的查询: "IN('a','b')";
	 *
	 * @access   public
	 * @param    mix      $item_list      列表数组或字符串
	 * @param    string   $field_name     字段名称
	 *
	 * @return   void
	 */
	function db_create_in($item_list, $field_name = '')
	{
	    if (empty($item_list))
	    {
	        return $field_name . " IN ('') ";
	    }
	    else
	    {
	        if (!is_array($item_list))
	        {
	            $item_list = explode(',', $item_list);
	        }
	        $item_list = array_unique($item_list);
	        $item_list_tmp = '';
	        foreach ($item_list AS $item)
	        {
	            if ($item !== '')
	            {
	                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
	            }
	        }
	        if (empty($item_list_tmp))
	        {
	            return $field_name . " IN ('') ";
	        }
	        else
	        {
	            return $field_name . ' IN (' . $item_list_tmp . ') ';
	        }
	    }
	}

	/**
	 * 初始化会员数据整合类
	 *
	 * @access  public
	 * @return  object
	 */
	function &init_users()
	{
	    $set_modules = false;
	    static $cls = null;
	    if ($cls != null)
	    {
	        return $cls;
	    }
	    $code = fanweC('INTEGRATE_CODE');
	    if (empty($code)) $code = 'fanwe';
	    
	    include_once(VENDOR_PATH . 'integrates/' . $code . '.php');
	    $cfg = unserialize(fanweC('INTEGRATE_CONFIG'));
	    $cls = new $code($cfg);
	
	    return $cls;
	}	
	
	/**
	 * 调用UCenter的函数
	 *
	 * @param   string  $func
	 * @param   array   $params
	 *
	 * @return  mixed
	 */
	function uc_call($func, $params=null)
	{
	    restore_error_handler();
	    if (!function_exists($func))
	    {
	        include_once(VENDOR_PATH . 'uc_client/client.php');
	    }
	    $res = call_user_func_array($func, $params);
	    set_error_handler('exception_handler');
	
	    return $res;
	}		
	
	//全站通用的清除所有缓存的方法
	function clear_cache()
	{
		Dir::delDir(getcwd()."/admin/Runtime/Cache/");
		Dir::delDir(getcwd()."/admin/Runtime/Data/");  
		Dir::delDir(getcwd()."/admin/Runtime/Temp/");  
		@unlink(getcwd()."/admin/Runtime/~app.php");
		@unlink(getcwd()."/admin/Runtime/~runtime.php");
		@unlink(getcwd()."/Public/sys_config.php");
		
		//修改 by hc 清除缓存不清除静态缓存。
		//Dir::delDir(getcwd()."/app/Runtime/Cache/");
		//Dir::delDir(getcwd()."/app/Runtime/Data/");  
		Dir::delDir(getcwd()."/app/Runtime/Temp/",true,false); 
		//Dir::delDir(getcwd()."/app/Runtime/caches/"); 
		//Dir::delDir(getcwd()."/app/Runtime/compiled/");  
		//Dir::delDir(getcwd()."/app/Runtime/".HTML_DIR.'/'); 
		@unlink(getcwd()."/app/Runtime/~app.php");
		@unlink(getcwd()."/app/Runtime/~runtime.php");
		//@unlink(getcwd()."/app/Runtime/js_lang.js");
		
		
		
	}
	
	function clear_all_cache()
	{
		Dir::delDir(getcwd()."/admin/Runtime/Logs/");
		Dir::delDir(getcwd()."/admin/Runtime/Cache/");
		Dir::delDir(getcwd()."/admin/Runtime/Data/");  
		Dir::delDir(getcwd()."/admin/Runtime/Temp/");  
		@unlink(getcwd()."/admin/Runtime/~app.php");
		@unlink(getcwd()."/admin/Runtime/~runtime.php");
		@unlink(getcwd()."/Public/sys_config.php");
				
		Dir::delDir(getcwd()."/app/Runtime/Logs/");
		Dir::delDir(getcwd()."/app/Runtime/Cache/");
		Dir::delDir(getcwd()."/app/Runtime/Data/");  
		Dir::delDir(getcwd()."/app/Runtime/Temp/"); 
		Dir::delDir(getcwd()."/app/Runtime/caches/"); 
		Dir::delDir(getcwd()."/app/Runtime/compiled/");  
		Dir::delDir(getcwd()."/app/Runtime/".HTML_DIR.'/'); 
		@unlink(getcwd()."/app/Runtime/~app.php");
		@unlink(getcwd()."/app/Runtime/~runtime.php");
		@unlink(getcwd()."/app/Runtime/js_lang.js");
		@unlink(getcwd()."/app/Runtime/lang.php");
		
		
		Dir::delDir(getcwd()."/install/Runtime/Cache/");
		Dir::delDir(getcwd()."/install/Runtime/Data/");  
		Dir::delDir(getcwd()."/install/Runtime/Temp/");  
		@unlink(getcwd()."/install/Runtime/~app.php");
		@unlink(getcwd()."/install/Runtime/~runtime.php");	

		Dir::delDir(getcwd()."/mobile/Runtime/Cache/");
		Dir::delDir(getcwd()."/mobile/Runtime/Data/");  
		Dir::delDir(getcwd()."/mobile/Runtime/Temp/");
		Dir::delDir(getcwd()."/mobile/Runtime/compiled/");
		Dir::delDir(getcwd()."/mobile/Runtime/caches/");
		Dir::delDir(getcwd()."/mobile/Runtime/sessionid/");
		@unlink(getcwd()."/mobile/Runtime/~app.php");
		@unlink(getcwd()."/mobile/Runtime/~runtime.php");
		
		Dir::delDir(getcwd()."/update/Runtime/Cache/");
		Dir::delDir(getcwd()."/update/Runtime/Data/");  
		Dir::delDir(getcwd()."/update/Runtime/Temp/");  
		@unlink(getcwd()."/update/Runtime/~app.php");
		@unlink(getcwd()."/update/Runtime/~runtime.php");
		
	}
	
	//过滤请求
	function filter_request(&$request)
	{
		if(MAGIC_QUOTES_GPC)
		{
			foreach($request as $k=>$v)
			{
				if(is_array($v))
				{
					filter_request($v);
				}
				else
				{
					$request[$k] = stripslashes(trim($v));
				}
			}
		}	
	}
	
	/**
	 * 获得当前格林威治时间的时间戳
	 *
	 * @return  integer
	 */
	function gmtTime()
	{
	    return (time() - date('Z'));
	}
        function to_timespan($str, $format = 'Y-m-d H:i:s')
        {
                $timezone = intval(fanweC('TIME_ZONE'));
                //$timezone = 8; 
                $time = intval(strtotime($str));
                if($time!=0)
                $time = $time - $timezone * 3600;
            return $time;
        }
	
	function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$timezone = intval(fanweC('TIME_ZONE'));
	$time = $time + $timezone * 3600; 
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
	}
	function write_timezone()
	{
		$var = array(
			'0'	=>	'UTC',
			'8'	=>	'PRC',
		);
		
		//开始将$db_config写入配置
	    $timezone_config_str 	 = 	"<?php\r\n";
	    $timezone_config_str	.=	"return array(\r\n";
	    $timezone_config_str.="'DEFAULT_TIMEZONE'=>'".$var[fanweC('TIME_ZONE')]."',\r\n";
	    
	    $timezone_config_str.=");\r\n";
	    $timezone_config_str.="?>";
	   
	    @file_put_contents(getcwd()."/Public/global_config.php",$timezone_config_str);
	}
/*
	// 发送邮件/短信消息队列 by hc
	function send_msg_list()
	{
		$msg_list = M("SendList")->where("status=0")->findAll();
		$sms= D("SmsPlf");
		
		foreach($msg_list as $msg)
		{
			$msg['status'] = 1;
			$msg['send_time'] = gmtTime();
			M("SendList")->save($msg);
			//默认为已发送
			
			if($msg['send_type'] == 1)
			{
				if(fanweC("IS_SMS")==1)
				{
					if(empty($msg['dest']))
					{
						M("SendList")->where("id = ".$msg['id'])->delete();
					}
					else
					{
						if($sms->sendSMS($msg['dest'],$msg['content']))
						{
							if($msg['bond_id']>0) //团购券的发送，记录发送状态
							{
								D("GroupBond")->where("id =".$msg['bond_id'])->setField("is_send_msg",1);
								M("GroupBond")->setInc("send_count","id =".$msg['bond_id'],1);
								Log::record("SendSMSStatus:".$sms->message);
								Log::save();
							}
						}
						else
						{
							$msg['status'] = 0;
							M("SendList")->save($msg);
							if($msg['bond_id']>0) //团购券的发送，记录发送状态
							{
								D("GroupBond")->where("id =".$msg['bond_id'])->setField("is_send_msg",0);
								Log::record("SendSMSStatus:".$sms->message);
								Log::save();
							}
						}
					}
				}				
			}
			if($msg['send_type'] == 0)
			{
				if(fanweC("MAIL_ON")==1)
				{
					$mail = new Mail();		
					$mail->AddAddress($msg['dest']);
					$mail->IsHTML(1); 
					$mail->Subject = $msg['title']; // 标题
					$mail->Body = $msg['content']; // 内容
					$mail->Send();
//					if($mail->ErrorInfo!='')
//					{
//						$msg['status'] = 0;
//						M("SendList")->save($msg);
//					}
				}
			}
		}
	}	
	*/
	// 发送邮件群发队列 by hc
	function send_mail_list()
	{
		$msg_list = M("MailSendList")->where("status=0 and send_time <=".gmtTime())->findAll();
		foreach($msg_list as $msg)
		{
			$msg['status'] = 1;			
			M("MailSendList")->save($msg);
			if($msg['rec_module']=='Email')
			{
				M("MailList")->where("id=".$msg['rec_id'])->setField("status",1);  //设为已发送
			}
			//默认为已发送
			
			if(fanweC("MAIL_ON")==1)
			{
					$mail = new Mail();		
					$mail->AddAddress($msg['mail_address']);
					$mail->IsHTML(1); 
					$mail->Subject = $msg['mail_title']; // 标题
					$mail->Body = $msg['mail_content']; // 内容
					$mail->Send();
//					if($mail->ErrorInfo!='')
//					{
//						$msg['status'] = 0;			
//						M("MailSendList")->save($msg);
//					}
			}
		}
	}
	
	function pushMail()
	{
			$time = gmtTime();
   			$mail_list = D("MailList")->where('send_time<='.$time.' and status=0')->findAll();	
			$allmail_list = D("MailList")->findAll();	
			//先删除邮件的发送人
			foreach($allmail_list as $mail_item)
			{
				M("MailSendList")->where("status=0 and rec_module='Email' and rec_id=".$mail_item['id'])->delete();
			}
			
			foreach($mail_list as $mail_item)
			{	
				$address_send_list = D("MailAddressSendList")->where("mail_id=".$mail_item['id'])->findAll();
				foreach($address_send_list as $address_item)
				{
					$address_item = D("MailAddressList")->where("status=1 and id='".$address_item['mail_address_id']."'")->find();
					if($address_item)
					{
						$userinfo = D("User")->getById($address_item['user_id']);
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
//						$mail = new Mail();	
//						$mail->IsHTML(1); // 设置邮件格式为 HTML
						$mail_title = $mail_item['mail_title'];
						
						//开始为邮件内容赋值
						if($mail_item['goods_id']==0)
						$mail_content = $mail_item['mail_content'];
						else
						{
								$tpl = Think::instance('ThinkTemplate');
								$mail_tpl = file_get_contents(getcwd()."/Public/mail_template/".fanweC("GROUP_MAIL_TMPL")."/".fanweC("GROUP_MAIL_TMPL").".html");  //邮件群发的模板				
								$mail_tpl = str_replace(fanweC("GROUP_MAIL_TMPL")."_files/",fanweC("SHOP_URL").__ROOT__."/Public/mail_template/".fanweC("GROUP_MAIL_TMPL")."/".fanweC("GROUP_MAIL_TMPL")."_files/",$mail_tpl);
			
								//开始定义模板变量
								$v = M("Goods")->getById($mail_item['goods_id']);
								
								//$city_name
								$city_name = M("GroupCity")->where("id=".$v['city_id'])->getField("name");
								
								//$shop_name
								$shop_name = SHOP_NAME;
								
								//$cancel_url
								$cancel_url = fanweC("SHOP_URL").__ROOT__."/index.php?m=Index&a=unSubScribe&email=".$address_item['mail_address'];
								
								//$sender_email
								$sender_email = fanweC("REPLY_ADDRESS");
								
								//$send_date 
								$send_date = toDate(gmtTime(),'Y年m月d日');
								$weekarray = array("日","一","二","三","四","五","六");
								$send_date .= " 星期".$weekarray[toDate(gmtTime(),"w")];
								
								
								//$shop_url
								$shop_url = fanweC("SHOP_URL").__ROOT__;
								
								//$tel_number
								$tel_number = fanweC("TEL");
								
								//$tg_info
								$tg_info = D("Goods")->getGoodsItem($v['id'],$v['city_id']);
								$tg_info['title'] = $tg_info['name_1'];
								$tg_info['price'] = $tg_info['shop_price_format'];
								$tg_info['origin_price'] = $tg_info['market_price_format'];
								$tg_info['discount'] = $tg_info['discountfb'];
								$tg_info['save_money'] = $tg_info['save'];
								$tg_info['big_img'] = fanweC("SHOP_URL").__ROOT__.$tg_info['big_img'];
								$tg_info['desc'] = str_replace("./Public/",fanweC("SHOP_URL").__ROOT__."/Public/",$tg_info['goods_desc_1']);
								
								//$sale_info
								$sale_info['title'] = $tg_info['suppliers']['name'];
								$sale_info['url'] = $tg_info['suppliers']['web'];
								$sale_info['tel_num'] = $tg_info['suppliers']['tel'];
								$sale_info['map_url'] = $tg_info['suppliers']['map'];
								
								//$referral
								$referral['amount'] = fanweC("REFERRALS_MONEY");
								
								if(fanweC("REFERRAL_TYPE") == 0)
								{
									$referral['amount'] = formatPrice($referral['amount']);
								}
								else
								{
									$referral['amount'] = $referral['amount']."".L("SCORE_UNIT");
								}
							
								if(fanweC("URL_ROUTE")==0)
								$referral['url'] = fanweC("SHOP_URL").__ROOT__."/index.php?m=Referrals&a=index";
								else
								$referral['url'] = fanweC("SHOP_URL").__ROOT__."/Referrals-index.html";
								
								
								ob_start();
								eval('?' . '>' .$tpl->parse($mail_tpl));
								$content = ob_get_clean();	
								
								$mail_content = $content;
						}//end 通知模板的赋值
						
						//$cancel_url
						$cancel_url = fanweC("SHOP_URL").__ROOT__."/index.php?m=Index&a=unSubScribe&email=".$address_item['mail_address'];
						
						$mail_content = "如不想继续收".SHOP_NAME."的邮件，您可随时<a href='".$cancel_url."' title='取消订阅'>取消订阅</a><br /><br />".$mail_content;
						
						$mail_title = str_replace("{\$username}",$username,$mail_title);
						$mail_content = str_replace("{\$username}",$username,$mail_content);
						
//						$mail->Subject = $mail_title; // 标题					
//						$mail->Body =  $mail_content; // 内容
//						$mail->AddAddress($address_item['mail_address'],$username);	
//						if(!$mail->Send())
//						{
//							$this->error($mail->ErrorInfo,$ajax);
//						}	

						// 修改为插入邮件群发队列
						if(M("MailSendList")->where("status=0 and mail_address='".$address_item['mail_address']."' and rec_module='Email' and rec_id=".$mail_item['id'])->count()==0)
						{
							$sendData['mail_address'] = $address_item['mail_address'];
							$sendData['mail_title'] = $mail_title;
							$sendData['mail_content'] = $mail_content;
							$sendData['send_time'] = $mail_item['send_time'];
							$sendData['rec_module'] = 'Email';
							$sendData['rec_id'] = $mail_item['id'];
							M("MailSendList")->add($sendData);
						} //为避免重复插入队列						
					}			
				}								
			}
	}
	
	function gen_groupbond_sn($goodsID)
	{		
		do
		{
			$r_sn = rand(100000,999999);
			$sn = str_pad($r_sn, 6,'0',STR_PAD_LEFT);
		}
		while(M("GroupBond")->where("sn='".$sn."' and goods_id=".$goodsID)->count()>0);
		return $sn;
	}
	
	
	//订单相关操作时的缓存更新
	function clear_user_order_cache($order_id)
	{
		$user_id  = intval($_SESSION['user_id']);
				
		//更新配送缓存
		$sql = "select max(id) as maxid from ".C("DB_PREFIX")."user_consignee where user_id = ".$user_id;
		$tmp = M()->query($sql);
		$consignee_id = $tmp[0]['maxid'];
		S("CACHE_CONSIGNEE_".$consignee_id,NULL);
		
		//更新商品缓存
		$goods_id = M("OrderGoods")->where("order_id=".$order_id)->getField("rec_id");
		S("CACHE_GOODS_CACHE_".$goods_id,NULL);
		S("CACHE_USER_BUY_COUNT_".intval($_SESSION['user_id'])."_".$goods_id,NULL);
		
		S("CACHE_CART_GOODS_CACHE_".$goods_id,NULL);
		
		
		//更新用户数据
		S("CACHE_USER_INFO_".$user_id,NULL);
		
		//更新需发团购券商品
		S("CACHE_SUCCESS_GOODS_LIST",NULL);
		
		S("CACHE_ORDER_DELIVERYS_".$user_id,NULL);
		
		Dir::delDir(getcwd()."/app/Runtime/Temp/user_order_cache_".$user_id."/");
	}
	
	//当会员登录修改时更新用户缓存
	function upd_user_cache()
	{
		$user_id  = intval($_SESSION['user_id']);
						
		//更新用户数据
		S("CACHE_USER_INFO_".$user_id,NULL);
		
		//更新需发团购券商品
		S("CACHE_SUCCESS_GOODS_LIST",NULL);
	}	
	
?>