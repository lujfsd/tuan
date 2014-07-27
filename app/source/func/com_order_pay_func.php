<?php 
		//获取订单的支付接口
	function getPayment($order_id,$user_money = 0,$amount=0,$payment_type='')
	{		
		
		if($user_money==1)
		{
			//会员充值
			$incharge_info = $GLOBALS['db']->getRow("select id, payment, money from ".DB_PREFIX."user_incharge where id=".intval($order_id));
			$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id=".intval($incharge_info['payment']));
			$currency_info = $GLOBALS['db']->getRowCached("select id, radio from ".DB_PREFIX."currency where id=".intval($incharge_info['currency']));
			
			$money = $incharge_info['money'];
			
			if($payment_info['fee_type'] == 0)
			{
				//定额手续费
				$money = $money + $payment_info['fee'];
			}
			else 
			{
				$money = $money + ($money * $payment_info['fee'] / 100);
			}

			
			//生成支付日志
			if($payment_info['online_pay']==1)
			{
				$payment_log_data = array();
				$payment_log_data['rec_id'] = $incharge_info['id'];
				$payment_log_data['payment_id'] = $payment_info['id'];
				$payment_log_data['currency_id'] = $payment_info['currency'];
				$payment_log_data['rec_module'] = 'UserIncharge';
				$payment_log_data['create_time'] = a_gmtTime();
				$payment_log_data['money'] = $money;	
						
				$GLOBALS['db']->autoExecute(DB_PREFIX."payment_log", addslashes_deep($payment_log_data), 'INSERT');
				$payment_log_id = $GLOBALS['db']->insert_id();
			}
			else
			{
				$payment_log_id = 0;
			}
			
			
			$payment_id = $payment_info['id'];
			$currency_id = $currency_info['id'];
			
			require_once(VENDOR_PATH.'payment3/'.$payment_info['class_name'].'Payment.class.php');
			$payment_class = $payment_info['class_name']."Payment";
			$payment_model = new $payment_class;
			
			$code =  $payment_model->getPaymentCode($payment_log_id, $money, $payment_id, $currency_id);
			return $code;
		}
		else 
		{
			//默认的订单支付接口获取
			$order_info = $GLOBALS['db']->getRow("select id, payment, order_total_price,order_incharge from ".DB_PREFIX."order where id=".intval($order_id));
			
			//完成订单信息的获取
			if(empty($payment_type))
				$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id=".intval($order_info['payment']));
			else
				$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where class_name = 'Accountpay'");
			
			if (intval($payment_info['currency']) == 0){
				$payment_info['currency'] = 1;
			}	
			$currency_info = $GLOBALS['db']->getRowCached("select id, radio from ".DB_PREFIX."currency where id=".intval($payment_info['currency']));
			if($amount==0)
			{
				$money = ($order_info['order_total_price'] - $order_info['order_incharge']) * $currency_info['radio'];
			}
			else
			{
				$money = $amount * $currency_info['radio'];
			}
			
			//生成支付日志		
			if($payment_info['online_pay']==1)
			{
				$payment_log_data = array();
				$payment_log_data['rec_id'] = $order_info['id'];
				$payment_log_data['payment_id'] = $payment_info['id'];
				$payment_log_data['currency_id'] = $payment_info['currency'];
				$payment_log_data['rec_module'] = 'Order';		
				$payment_log_data['create_time'] = a_gmtTime();
				$payment_log_data['money'] = $money;		
				$GLOBALS['db']->autoExecute(DB_PREFIX."payment_log", addslashes_deep($payment_log_data), 'INSERT');
				$payment_log_id =$GLOBALS['db']->insert_id();				
			}
			else
			{
				$payment_log_id = 0;
			}
			
			$payment_id = $payment_info['id'];
			$currency_id = $currency_info['id'];
			
			require_once(VENDOR_PATH.'payment3/'.$payment_info['class_name'].'Payment.class.php');
			$payment_class = $payment_info['class_name']."Payment";
			
			$payment_model = new $payment_class;
			
			$code =  $payment_model->getPaymentCode($payment_log_id, $money, $payment_id, $currency_id);
			return $code;
		}
	}	
	
function payment_response()
{
    require_once(VENDOR_PATH.'payment3/'.$_REQUEST['payment_name'].'Payment.class.php');
	$payment_name = $_REQUEST['payment_name']."Payment";
	if(class_exists($payment_name))
	{
	    $payment_model = new $payment_name;
	    $res = $payment_model->dealResult($_GET,$_POST,$_REQUEST);
	    if($res['status'])
	    {
	    	//响应处理成功
	    	if (intval($res['order_id']) > 0){ //add by chenfq 2010-05-17 如果是订单的话，支付成功，则自动跳转到订单支付成功页面
	    		if ($GLOBALS['db']->getOne("select money_status from ".DB_PREFIX."order where id = ".intval($res['order_id'])) == 2){
	    			redirect2(__ROOT__."/index.php?m=Order&a=pay_success&id=".intval($res['order_id']));
	    		}else{
	    			redirect2(__ROOT__."/index.php?m=Order&a=pay&id=".intval($res['order_id']));
	    		}
	    	}else{
	    		success(a_L("PAY_SUCCESS")."<br />".$res['info'],'',__ROOT__."/index.php");
	    	}
	    }
	    else 
	    {
	    	//响应处理失败
	    	a_error(a_L("PAY_FAILED")."<br />".$res['info']);
	    }
	}else 
	{
		//响应处理失败
	    a_error(a_L("PAY_FAILED"));
	}
}
	
function KuaiqianIndex()
{
    require_once(VENDOR_PATH.'payment3/KuaiqianPayment.class.php');
	$payment_name = "KuaiqianPayment";
	if(class_exists($payment_name))
	{
	    $payment_model = new $payment_name;
	    $res = $payment_model->dealResult($_GET,$_POST,$_REQUEST);

	    	if($res['status'])
		    {
		    	$rtnOk = 1;
		    	//响应处理成功
		    	if (intval($res['order_id']) > 0){ //add by chenfq 2010-05-17 如果是订单的话，支付成功，则自动跳转到订单支付成功页面
		    		if ($GLOBALS['db']->getOne("select money_status from ".DB_PREFIX."order where id = ".intval($res['order_id'])) == 2){
		    			$rtnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__."/index.php?m=Order&a=pay_success&id=".intval($res['order_id']);
		    		}else{
		    			$rtnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__."/index.php?m=Order&a=pay&id=".intval($res['order_id']);
		    		}
		    	}else{
		    		//success(a_L("PAY_SUCCESS")."<br />".$res['info'],'',__ROOT__."/index.php");
		    		$rtnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Kuaiqian';
		    	}
		    }
		    else 
		    {
		    	$rtnOk = 0;
		    	//响应处理失败
		    	//a_error(a_L("PAY_FAILED")."<br />".$res['info']);
		    	$rtnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Kuaiqian';
		    }	
		    echo "<result>{$rtnOk}</result><redirecturl>{$rtnUrl}</redirecturl>";    	
	    
	}else 
	{
		//响应处理失败
		$rtnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Kuaiqian';
	    echo "<result>0</result><redirecturl>{$rtnUrl}</redirecturl>"; 
	}
}


function SdoIndex()
{
    require_once(VENDOR_PATH.'payment3/SdoPayment.class.php');
	$payment_name = "SdoPayment";
	if(class_exists($payment_name))
	{
	    $payment_model = new $payment_name;
	    $res = $payment_model->dealResult($_GET,$_POST,$_REQUEST);

	    if($res['status'])
		{
		    echo 'OK';
		}
		else 
		{
		    echo 'error';
		}	
	}else 
	{
		echo 'error';
	}
}

function AlipayIndex()
{
    require_once(VENDOR_PATH.'payment3/AlipayPayment.class.php');
	$payment_name = "AlipayPayment";
	if(class_exists($payment_name))
	{
	    $payment_model = new $payment_name;
	    $res = $payment_model->dealResult($_GET,$_POST,$_REQUEST);

	    if($res['status'])
		{
		    echo 'success';
		}
		else 
		{
		    echo 'fail';
		}	
	}else 
	{
		echo 'fail';
	}
}

function chinaBankIndex()
{
    require_once(VENDOR_PATH.'payment3/ChinabankPayment.class.php');
	$payment_name = "ChinabankPayment";
	if(class_exists($payment_name))
	{
	    $payment_model = new $payment_name;
	    $res = $payment_model->dealResult($_GET,$_POST,$_REQUEST);

	    if($res['status'])
		{
		    echo 'ok';
		}
		else 
		{
		    echo 'error';
		}	
	}else 
	{
		echo 'error';
	}
}
	
function EctonIndex()
{
    require_once(VENDOR_PATH.'payment3/EctonPayment.class.php');
	$payment_name = "EctonPayment";
	if(class_exists($payment_name))
	{
	    $payment_model = new $payment_name;
	    $res = $payment_model->dealResult($_GET,$_POST,$_REQUEST);

	    if($res['status'])
		{
		    echo 'ok';
		}
		else 
		{
		    echo 'error';
		}	
	}else 
	{
		echo 'error';
	}
}
	
	   	   	//完成订单
   function order_done($return_array = false)
   {
   		require_once ROOT_PATH.'app/source/func/com_order_done_func.php';
   		return order_done_2($goods_id,$return_array);
   }

   	function s_order_paid($payment_log_id, $money, $payment_id, $currency_id,$pay_back_code=''){
        $result  =  array();
        $result['order_id'] = 0;
        $payment_log_vo = $GLOBALS['db']->getRow("select id,is_paid,rec_module,rec_id,payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
    	//Log::record("order_paid:".$payment_log_id.";".$money); 
    	//Log::save();
    	
    	if ($payment_log_vo == false){
			$result['status'] = false;
        	$result['info'] = a_L('INVALID_PAY_LOG_ID').$payment_log_id;
        	$result['data'] = a_L('INVALID_PAY_LOG_ID').$payment_log_id;        	
    		return $result;    		
    	}
    	//add by chenfq 2010-04-05
        if ($payment_log_vo['is_paid'] == 1){
			/*
        	$result['status'] = false;
        	$result['info'] = a_L('PAY_LOG_ID').$payment_log_id.a_L('PAY_LOG_ID_INVALID');
        	$result['data'] = a_L('PAY_LOG_ID').$payment_log_id.a_L('PAY_LOG_ID_INVALID');
        	*/
			$result['status']  =  true;
        	$result['info'] = a_L('PAY_LOG_ID').':'.$payment_log_id.a_L('PAY_MONEY').':'. $money .a_L('PAY_SUCCESS');
        	$result['data'] = a_L('PAY_LOG_ID').':'.$payment_log_id.a_L('PAY_MONEY').':'. $money .a_L('PAY_SUCCESS');   

        	if ($payment_log_vo['rec_module'] == 'Order'){
        		$result['order_id'] = $payment_log_vo['rec_id'];
        	}
        	
    		return $result;
    	}  	

		if (!empty($pay_back_code)){//支付序列号
			$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_back_code = '$pay_back_code' where id = ".$payment_log_id); 
		}	   	
    	if ($payment_log_vo['rec_module'] == 'Order'){
    		$status = s_order_paid_in($payment_log_id, $money, $payment_id, $currency_id);
    	}elseif ($payment_log_vo['rec_module'] == 'UserIncharge'){//在线冲值
    	    
			/* 修改此次支付操作的状态为已付款  add by chenfq 2010-04-05*/
			//,update_time = ".a_gmtTime()."  add by chenfq 2011-07-12 ,update_time = ".a_gmtTime()."
        	$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set is_paid = 1 where is_paid = 0 and id = ".$payment_log_id);
			$rs = $GLOBALS['db']->affected_rows();
			if($rs == 0) //添加判断，防止重复收款
				return a_L('PAY_LOG_ID').$payment_log_id.a_L('PAY_LOG_ID_INVALID');
			        	
    		$vo = $GLOBALS['db']->getRow("select id,user_id,money from ".DB_PREFIX."user_incharge where id=".intval($payment_log_vo['rec_id']));
        	if ($vo == false){
    			$status = a_L('INVALID_USER_INCHAREG_ID');
    		} 

    		$GLOBALS['db']->query("update ".DB_PREFIX."user_incharge set update_time =".a_gmtTime().",status = 1  where id = ".intval($payment_log_vo['rec_id']));
			$status = s_user_money_log($vo['user_id'], $vo['id'], 'UserIncharge', $vo['money'], a_L("ORDER_CHARGE_MEMO_3"));
			
			//add by chenfq 2010-06-5  记录帐户资金变化明细 begin
			s_payment_money_log($payment_log_vo['payment_id'], 
							  $vo['user_id'], 
							  $vo['id'], 
							  'UserIncharge', 
							  $vo['money'], 
							  '会员在线冲值：'.$vo['money'], 
							  false, 
							  'User', 
							  '', 
							  '');
			//add by chenfq 2010-06-5  记录帐户资金变化明细 end
			s_send_userincharge_sms($vo['id']);
    	}
		
    	
		if ($status === true){
			$result['status']  =  true;
        	$result['info'] = a_L('PAY_LOG_ID').':'.$payment_log_id.a_L('PAY_MONEY').':'. $money .a_L('PAY_SUCCESS');
        	$result['data'] = a_L('PAY_LOG_ID').':'.$payment_log_id.a_L('PAY_MONEY').':'. $money .a_L('PAY_SUCCESS');
		    if ($payment_log_vo['rec_module'] == 'Order'){
        		$result['order_id'] = $payment_log_vo['rec_id'];
        	}        	
		}else{
			$result['status'] = false;
        	$result['info'] = $status;
        	$result['data'] = $status;			
		}
		
		return $result;
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
	function s_payment_money_log($payment_id, $operator_id, $rec_id, $rec_module, $money, $memo, $onlylog = false, $operator_module = 'User', $payment_name = '', $operator_name = ''){
		$payment_id = intval($payment_id);
		$operator_id = intval($operator_id);
		$money = floatval($money);
		
		if (empty($payment_name)){
			$payment_name = $GLOBALS['db']->getOneCached("select name_1 from ".DB_PREFIX."payment where id =".intval($payment_id));
		}
		
		if (empty($operator_name)){
			if ($operator_module == 'User')
			{
			  $operator_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id =".intval($operator_id));
			}
			elseif ($operator_module == 'Admin') 
			{
			  $operator_name = $GLOBALS['db']->getOne("select adm_name from ".DB_PREFIX."admin where id =".intval($operator_id));
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
		
		$log_data['create_time'] = a_gmtTime();
		$log_data['ip'] = $_SESSION['CLIENT_IP'];
		//dump($log_data);
		$GLOBALS['db']->autoExecute(DB_PREFIX."payment_money_log", addslashes_deep($log_data), 'INSERT');
		if ($onlylog == false){
			$sql_str = 'update '.DB_PREFIX.'payment set money = money + '.floatval($money).' where id = '.$payment_id;
			$GLOBALS['db']->query($sql_str);	
		}
		return true;
	}	
		
	//增加已收金额 modfiy by chenfq 2010-06-5 添加 $onlinepay 在线支付参数
	function s_inc_order_incharge($order_incharge_id, $onlinepay=false)
	{
		//记录不存在，或status不等于1时，返回出错 add by chenfq 2010-12-1 begin
		$GLOBALS['db']->query("update ".DB_PREFIX."order_incharge set status = 1 where id = ".$order_incharge_id); 
		$rs = $GLOBALS['db']->affected_rows();		
		if($rs == 0)
			return false;
		//返回出错 add by chenfq 2010-12-1 end		
		
		$incharge_vo = $GLOBALS['db']->getRow("select order_id,money,cost_payment_fee,payment_id from ".DB_PREFIX."order_incharge where id = ".$order_incharge_id);
    			
		$order_vo = $GLOBALS['db']->getRow("select id, order_incharge, order_total_price, cost_payment_fee, user_id, money_status, parent_id,offline,order_score,sn,payment from ".DB_PREFIX."order where id = ".intval($incharge_vo['order_id']));
		
		$order_vo['order_incharge'] = $order_vo['order_incharge'] + $incharge_vo['money'];
		$order_vo["cost_payment_fee"] = floatval($order_vo["cost_payment_fee"]) + $incharge_vo['cost_payment_fee'];
	
	    $payment = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."payment where id =".intval($incharge_vo['payment_id']));
		
		if ($payment['class_name'] == 'Accountpay' && $order_vo['user_id'] > 0){//会员使用预存款支付，减少预存款
			//记录会员预存款变化明细
			s_user_money_log($order_vo['user_id'], $order_incharge_id, 'OrderIncharge', $incharge_vo['money'] * -1, a_L("ORDER_CHARGE_MEMO_1"));
		} 
		
		//add by chenfq 2010-06-5  记录帐户资金变化明细 begin
		if ($payment){
			if ($onlinepay){
				s_payment_money_log($payment['id'], 
								  $order_vo['user_id'], 
								  $order_incharge_id, 
								  'OrderIncharge', 
								  $incharge_vo['money'], 
								  a_L('ORDER_PAID_IN_9').$incharge_vo['money'], 
								  false, 
								  'User', 
								  '', 
								  '');
			}else{
				s_payment_money_log($payment['id'], 
								  $_SESSION[a_fanweC('USER_AUTH_KEY')], 
								  $order_incharge_id, 
								  'OrderIncharge', 
								  $incharge_vo['money'], 
								  $_SESSION['adm_name'].a_L('ORDER_PAID_IN_10').$incharge_vo['money'], 
								  false, 
								  'Admin', 
								  $payment['name_1'], 
								  $_SESSION['adm_name']);				
			}							  		
		}
		//add by chenfq 2010-06-5  记录帐户资金变化明细 end
		
		$r = s_order_incharge_handle($order_vo, 0.0, true);
		if ($r){
			//发送短信
			s_send_orderpaid_sms($order_incharge_id);
		}
		return $r;			
	}
	
	    function s_ecv_order_incharge($order_id)
	{
		$order_vo = $GLOBALS['db']->getRow("select id, order_incharge, order_total_price, cost_payment_fee, user_id, money_status, parent_id,offline,order_score,sn,ecv_money from ".DB_PREFIX."order where id = ".intval($order_id));
		return s_order_incharge_handle($order_vo, $order_vo['ecv_money'], false);
	}     

	//记录会员预存款变化明细
	//$memo 格式为 #LANG_KEY#memos  ##之间所包含的是语言包的变量
	function s_user_money_log($user_id, $rec_id, $rec_module, $money, $memo, $onlylog = false){
		$user_id = intval($user_id);
		$money = floatval($money);
		
		$log_data = array();
		$log_data['user_id'] = $user_id;
		$log_data['money'] = $money;
		$log_data['rec_id'] = $rec_id;
		$log_data['rec_module'] = $rec_module;
		$log_data['create_time'] = a_gmtTime();
		$log_data['memo_1']= $memo;		
		//记录会员预存款变化明细
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_money_log", addslashes_deep($log_data), 'INSERT');
		
		if ($onlylog == false){
			//增加会员的预存款金额
			$sql_str = 'update '.DB_PREFIX.'user set money = money + '.floatval($money).' where id = '.$user_id;
			$GLOBALS['db']->query($sql_str);	
		}
		return true;
	}	
	
		//记录会员预存款变化明细
	//$memo 格式为 #LANG_KEY#memos#LANG_KEY#  ##之间所包含的是语言包的变量
	function s_user_score_log($user_id, $rec_id, $rec_module, $score, $memo, $onlylog = false){
		$user_id = intval($user_id);

		$log_data = array();
		$log_data['user_id'] = $user_id;
		$log_data['score'] = $score;
		$log_data['rec_id'] = $rec_id;
		$log_data['rec_module'] = $rec_module;
		$log_data['create_time'] = a_gmtTime();
		$log_data['memo_1']= $memo;		
		//记录会员预存款变化明细
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_score_log", addslashes_deep($log_data), 'INSERT');
		if ($onlylog == false){
			$sql_str = 'update '.DB_PREFIX.'user set score = score + '.intval($score).' where id = '.$user_id;
			$GLOBALS['db']->query($sql_str);	
			if($score < 0)
				$GLOBALS['db']->query('update '.DB_PREFIX.'user set score = 0 where score <0 and id = '.$user_id);
		}
		
		return true;
	}	


function s_countCartTotal($payment_id=0,$delivery_id=0,$is_protect=0,$delivery_region=array(),$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword)
{
		$delivery_free = 0;
		$delivery_fee = 0;
		$session_id = session_id();
		$ecvFee = 0;
		$ecvID = 0;
				
		if(!empty($ecvSn))
		{
			//add by chenfq 2011-06-16 添加代金券判断
			$chk = check_ecvverify($ecvSn,$ecvPassword);
			if ($chk['type'] == 1){
				$ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where sn='".$ecvSn."' and password = '".$ecvPassword."' limit 1");
				$ecv['ecvType'] = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."ecv_type where id=".intval($ecv['ecv_type']));
				$ecv['useUser'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".intval($ecv['use_user_id']));
				if($ecv)
				{
					$time = a_gmtTime();
					if(intval($ecv['use_date_time']) == 0 && intval($ecv['ecvType']['status']) == 1 && $time > intval($ecv['ecvType']['use_start_date']) && ($time < intval($ecv['ecvType']['use_end_date']) ||  intval($ecv['ecvType']['use_end_date']) == 0))
					{
						$ecvFee = round(floatval($ecv['ecvType']['money']),2);					
						$ecvID  = $ecv['id'];
					}
				}				
			}
		}
		
		$cart_item = getCartItem($session_id);

		
		if(floatval($cart_item['data_total_price']) > 0){
			$discount = $GLOBALS['db']->getOneCached("select discount from ".DB_PREFIX."user_group where id=".intval($_SESSION['group_id']));
			$discount = floatval($discount) > 0 ? floatval($discount) : 1;			
		}else{
			$discount = 1;
		}
				
	    $payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id =".$payment_id);
		$payment_currency = array('unit'=>a_fanweC("BASE_CURRENCY_UNIT"),'radio'=>1);
		
		$temp_total_price = round(floatval($cart_item['data_total_price']) * $discount,2);
		
		$discount_price = round(floatval($cart_item['data_total_price']) - $temp_total_price,2);
		
		$fee = s_countFee(floatval($cart_item['data_total_price']),$payment_id,$cart_item['data_total_weight'],
						  $delivery_id,$is_protect,$delivery_region,$tax,$delivery_fee,$cart_item['goods_type'],
						  $cart_item['is_inquiry'],$isCreditAll,$credit,$ecvFee,floatval($cart_item['fix_delivery_money']));
		
		$total_price = round($temp_total_price+$fee['delivery_fee']+$fee['payment_fee']+$fee['tax_money']+$fee['protect_fee'], 2);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($_SESSION['user_id']));
		
		$userMoney = floatval($user_info['money']);
		if($total_price > 0 && $ecvFee > $total_price)
			$ecvFee = $total_price;
			
		$total_price = $total_price - $ecvFee;
		
		if ($total_price > 0){
			if($credit > $total_price || $isCreditAll == 1)
				$credit = $total_price;
				
			if($credit > $userMoney)
				$credit = $userMoney;
				
			$total_price = $total_price - $credit;			
		}else{
			$credit = 0;
		}
		
	    return array(
			'goods_total_price' => $cart_item['data_total_price'],
			'goods_total_price_format' => a_formatPrice($cart_item['data_total_price']),	
			'delivery_fee' =>	$fee['delivery_fee'],
			'delivery_fee_format' => a_formatPrice($fee['delivery_fee']),
			'protect_fee'	=> $fee['protect_fee'],
			'protect_fee_format' => a_formatPrice($fee['protect_fee']),
			'payment_fee' =>	$fee['payment_fee'],
			'payment_fee_format' => a_formatPrice($fee['payment_fee']),
			'total_price' =>	$total_price,
			'total_price_format' =>	a_formatPrice($total_price),
			'payment_name' =>	$payment_info['name_1'],
			'payment_total_price_format'	=>	sprintf(a_fanweC("BASE_CURRENCY_UNIT"),$total_price*$payment_currency['radio']),
			'promote_card' =>	$promote_card,
			//税款
			'tax' => $tax,
			'tax_money' =>	$fee['tax_money'],
			'tax_money_format' => a_formatPrice($fee['tax_money']),
			'delivery_free' => $delivery_free,
			//积分
	    	'total_add_score' => $cart_item['data_total_score'],
	    	'total_add_score_format' => $cart_item['data_total_score'],
	    	'total_referral_money' => $cart_item['data_total_referral_money'],//add by chenfq 2011-03-05 返利金额
			//总重
			'total_weight' => $cart_item['data_total_weight'],
			'credit' => $credit,
			'credit_format' =>	a_formatPrice($credit),
			'all_fee' => $total_price + $credit + $ecvFee + $discount_price,
			'all_fee_format' => a_formatPrice($total_price + $credit + $ecvFee + $discount_price),
			'is_inquiry' => $cart_item['is_inquiry'],
			'goods_type' => $cart_item['goods_type'],
			'discount_price' => $discount_price,
			'discount_price_format' => a_formatPrice($discount_price),
			'ecvFee' => $ecvFee,
			'ecvFee_format' => a_formatPrice($ecvFee),
			'ecvID' => $ecvID
		);
} 

//计算订单所有总额（包含促销计算，运费与支付手续费）
function s_countOrderTotal($id,$payment_id=0,$delivery_id=0,$is_protect=0,$delivery_region=array(),$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword)
	{
		$delivery_free = 0;
		$delivery_fee = 0;
		
		$id = intval($id);
		$order = $GLOBALS['db']->getRow("select id,ecv_id,delivery,delivery_refer_order_id,offline,order_incharge,ecv_money,order_weight,total_price,order_score,order_referral_money from ".DB_PREFIX."order where id = ".$id);
		
		$order_count = getOrderItem($id);
		
		$ecvFee = 0;
		$ecvID = $order['ecv_id'];
		
		if(!empty($ecvSn))
		{
			$ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where sn ='".$ecvSn."' and password = '".$ecvPassword."' limit 1");
			$ecv['ecvType'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where id=".intval($ecv['ecv_type']));
			$ecv['useUser'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".intval($ecv['use_user_id']));
			if($ecv)
			{
				$time = a_gmtTime();
			
				if(intval($ecv['use_date_time']) == 0 && intval($ecv['ecvType']['status']) == 1 && $time > intval($ecv['ecvType']['use_start_date']) && ($time < intval($ecv['ecvType']['use_end_date']) ||  intval($ecv['ecvType']['use_end_date']) == 0))
				{
					$ecvFee = round(floatval($ecv['ecvType']['money']),2);
					$ecvID  = $ecv['id'];
				}
			}
		}
		elseif($ecvID > 0)
		{
			$ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where id = ".$ecvID);
			$ecv['ecvType'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where id = ".$ecv['ecv_type']);
			if($ecv)
			{
				$ecvFee = round(floatval($ecv['ecvType']['money']),2);
				$ecvID  = $ecv['id'];
			}
		}

		if(floatval($order['total_price']) > 0){
			$discount = $GLOBALS['db']->getOne("select discount from ".DB_PREFIX."user_group where id = ".intval($_SESSION['group_id']));
			$discount = floatval($discount) > 0 ? floatval($discount) : 1;			
		}else{
			$discount = 1;
		}
		
		$payment_info = $GLOBALS['db']->getRow("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id = ".$payment_id);
		$payment_currency = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."currency where id =".intval($payment_info['currency']));
		
		$temp_total_price = round(floatval($order['total_price']) * $discount,2);
		
		$discount_price = round(floatval($order['total_price']) - $temp_total_price,2);
		
		$goods_type=0;
		
		if($order['delivery'] > 0 || $order['delivery_refer_order_id'] > 0)
			$goods_type = 1;
			
		if($order['offline'] == 1)
			$goods_type = 2;
		
		$order_incharge = $order["order_incharge"] - $order["ecv_money"];
		
		
		$fee = s_countFee(floatval($order['total_price']),$payment_id,$order['order_weight'],$delivery_id,
						 $is_protect,$delivery_region,$tax,$delivery_fee,$goods_type,
						 $order_count['is_inquiry'],$isCreditAll,$credit,$ecvFee,floatval($order_count['fix_delivery_money']));
		
		$total_price = round($temp_total_price+$fee['delivery_fee']+$fee['payment_fee']+$fee['tax_money']+$fee['protect_fee'], 2);
		$userMoney = floatval($GLOBALS['db']->getOne("select money from ".DB_PREFIX."user where id = ".intval($_SESSION['user_id'])));
		
		if($total_price > 0 && $ecvFee > $total_price)
			$ecvFee = $total_price;
			
		$total_price = $total_price - $ecvFee - $order_incharge;
		
		if ($total_price > 0)
		{
			if($credit > $total_price || $isCreditAll == 1)
				$credit = $total_price;
				
			if($credit > $userMoney)
				$credit = $userMoney;
				
			$total_price = $total_price - $credit;			
		}
		else
		{
			$credit = 0;
		}
		
	    return array(
			'goods_total_price' => $order['total_price'],
			'goods_total_price_format' => a_formatPrice($order['total_price']),	
			'delivery_fee' =>	$fee['delivery_fee'],
			'delivery_fee_format' => a_formatPrice($fee['delivery_fee']),
			'protect_fee'	=> $fee['protect_fee'],
			'protect_fee_format' => a_formatPrice($fee['protect_fee']),
			'payment_fee' =>	$fee['payment_fee'],
			'payment_fee_format' => a_formatPrice($fee['payment_fee']),
			'total_price' =>	$total_price ,
			'total_price_format' =>	a_formatPrice($total_price),
			'payment_name' =>	$payment_info['name_1'],
			'payment_total_price_format'	=>	$payment_currency['unit']." ".number_format(round($total_price*$payment_currency['radio'],2),2),
			'promote_card' =>	$promote_card,
			//税款
			'tax' => $tax,
			'tax_money' =>	$fee['tax_money'],
			'tax_money_format' => a_formatPrice($fee['tax_money']),
			'delivery_free' => $delivery_free,
			//积分
	    	'total_add_score' => $order['order_score'],
	    	'total_add_score_format' => $order['order_score'],
	    	//返利金额
	    	'total_referral_money' => $order['order_referral_money'],
			//总重
			'total_weight' => $order['order_weight'],
			'credit' => $credit,
			'credit_format' =>	a_formatPrice($credit),
			'all_fee' => $total_price + $credit + $ecvFee + $discount_price + $order_incharge,
			'all_fee_format' => a_formatPrice($total_price + $credit + $ecvFee + $discount_price + $order_incharge),
			'is_inquiry' => $order['is_inquiry'],
			'goods_type' => $goods_type,
			'discount_price' => $discount_price,
			'discount_price_format' => a_formatPrice($discount_price),
			'ecvFee' => $ecvFee,
			'ecvFee_format' => a_formatPrice($ecvFee),
			'ecvID' => $ecvID,
			'incharge' => $order_incharge,
			'incharge_format' => a_formatPrice($order_incharge)
		);
	}
	
/**
	 * 计算相应的运费与手续费
	 *
	 * @param $total_price  总价
	 * @param $total_weight 总重
	 * @param $delivery_id  配送方式
	 * @param $payment_id   支付方式
	 * @param $is_protect   是否保价
	 * @param $delivery_region   配送地区 array('region_lv1'=>'','region_lv2'=>'','region_lv3'=>'','region_lv4'=>'')
	 * @param $tax 是否开票
	 * 
	 * 返回：支付手续费，运费,保价费, 税款 array('payment_fee'=>'','delivery_fee'=>'','protect_fee'=>'','tax_money'=>'')
	 */
	function s_countFee($total_price,$payment_id,$total_weight,$delivery_id,$is_protect,$delivery_region,$tax,$count_delivery_fee,
						$goods_type,$is_inquiry,$isCreditAll,$credit,$ecvFee,$fix_delivery_money)
	{
		//计算运费
		$order_delivery_region = 0;
		$delivery_fee = 0;  //用于返回的运费
		$protect_fee = 0;  //用于返回的保价费
		$tax_money = 0;
		$payment_fee = 0;
		
		$fix_delivery_money = floatval($fix_delivery_money);
		//if ($total_price <= 0) //add by chenfq 2010-05-12
		//	return array('payment_fee'=>10,'delivery_fee'=>20,'protect_fee'=>30,'tax_money'=>40);
		$delivery_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."delivery where id = ".intval($delivery_id));
		//is_inquiry 1：免运费；0：需要运费; is_smzq: 1：上门自取
		if(($goods_type == 1 || $goods_type == 3) && $is_inquiry == 0 && $total_price < a_fanweC("FREE_DELIVERY_LIMIT") && intval($delivery_info['is_smzq']) == 0)
		{
			
			if ($fix_delivery_money > 0){ //使用商品中的固定运费
				$delivery_fee = $fix_delivery_money;
			}else{
							
				if($delivery_region['region_lv4']>0)
				{
					$order_delivery_region = $delivery_region['region_lv4'];
				}
				elseif($delivery_region['region_lv3']>0)
				{
					$order_delivery_region = $delivery_region['region_lv3'];
				}
				elseif($delivery_region['region_lv2']>0)
				{
					$order_delivery_region = $delivery_region['region_lv2'];
				}
				elseif($delivery_region['region_lv1']>0)
				{
					$order_delivery_region = $delivery_region['region_lv1'];
				}
				//至此查询出当前订定所配送的地址ID
	
				
	
				if($delivery_info)
				{
					$delivery_regions = $GLOBALS['db']->getAllCached("select id,region_ids,first_price,continue_price,allow_cod,delivery_id from ".DB_PREFIX."delivery_region where delivery_id = ".$delivery_id);
								
					if($delivery_regions&&$order_delivery_region>0)
					{				
						//存在当前指定的配送地址
						$region_conf_child_ids = new ChildIds("region_conf");
						foreach($delivery_regions as $k=>$v)
						{
							$region_ids = explode(",",$v['region_ids']);
							$tmp_arr = array();
							foreach($region_ids as $vv)
							{
								$arr = $region_conf_child_ids->getChildIds($vv);
								if($arr != 0)
								$tmp_arr = array_merge($tmp_arr,$arr);
							}
							$region_ids = array_merge($tmp_arr,$region_ids);
							
							if(in_array($order_delivery_region,$region_ids))
							{
								//查询出存在的计算地区
								$region_info = $v;	
																
								if($total_weight>$delivery_info['first_weight'])
								{
									//超过首重
									$delivery_fee += $region_info['first_price'];
									//计算续重
									$delivery_fee += ceil(($total_weight-$delivery_info['first_weight'])/$delivery_info['continue_weight'])*$region_info['continue_price'];
								}
								else 
								{
									$delivery_fee += $region_info['first_price'];
										
								}
								break;
							}
						}
						if(!$region_info)
						{
							//未查询出相应的计算地区判断时否按默认
							if($delivery_info['allow_default']==1)
							{
								//使用默认
								if($total_weight>$delivery_info['first_weight'])
								{
									//超过首重
									$delivery_fee += $delivery_info['first_price'];
									//计算续重
									$delivery_fee += ceil(($total_weight-$delivery_info['first_weight'])/$delivery_info['continue_weight'])*$delivery_info['continue_price'];
								}
								else 
								{
									$delivery_fee += $delivery_info['first_price'];
								}
							}
						}
						
					}
					else 
					{
						//无指定地区时按当前配送方式的默认值计算
						if($delivery_info['allow_default']==1)
							{
								//使用默认
								if($total_weight>$delivery_info['first_weight'])
								{
									//超过首重
									$delivery_fee += $delivery_info['first_price'];
									//计算续重
									$delivery_fee += ceil(($total_weight-$delivery_info['first_weight'])/$delivery_info['continue_weight'])*$delivery_info['continue_price'];
								}
								else 
								{
									$delivery_fee += $delivery_info['first_price'];
								}
							}
					}
					
					if($is_protect==1)
					{
						if($total_price*$delivery_info['protect_radio']/100>$delivery_info['protect_price'])
						{					
							//超过保价底价
							$protect_fee = $total_price*$delivery_info['protect_radio']/100;					
						}
						else 
						{					
							$protect_fee = $delivery_info['protect_price'];
						}
					}
				}
			}
		}
		
		//税率
		if($tax==1)
		{
			$tax_money = $total_price*a_fanweC("TAX_RADIO");
		}
		
		$payment_total_price = $total_price + $delivery_fee + $tax_money + $protect_fee + $count_delivery_fee;
		$user_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."user where id =".intval($_SESSION['user_id']));
		$userMoney = floatval($user_info['money']);
		
		$pay_free_fee = 0;
		
		if($ecvFee > $pay_free_fee)
			$ecvFee = $pay_free_fee;
			
		$pay_free_fee = $payment_total_price - $ecvFee;
		
		if($isCreditAll == 1 || $credit > $pay_free_fee)
			$credit = $pay_free_fee;
		
		if($credit > $userMoney)
			$credit = $userMoney;
			
		$pay_free_fee = $pay_free_fee - $credit;
		
		if($pay_free_fee > 0)
		{
			$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id =".$payment_id);
			if($payment_info)
			{
				if($payment_info['fee_type']==0)
				{
					//定额
					$payment_fee = $payment_info['fee'];
				}
				else
				{
					$payment_fee = ($payment_total_price - $credit) * $payment_info['fee'] / 100;
				}
			}
		}
		
		return array('payment_fee'=>$payment_fee,'delivery_fee'=>$delivery_fee,'protect_fee'=>$protect_fee,'tax_money'=>$tax_money);
	}  

	
  	function s_order_incharge_handle(&$order_vo, $ecv_money = 0.0, $clear_cache = true)
	{
		//已收金额 > 订单总金额
		//收款状态：0:未收款; 1:部分收款; 2:全部收款; 3:部分退款; 4:全部退款
		/*if ($order_vo["order_incharge"] <= 0)
		{
			$order_vo["money_status"] = 0;
		}
		else*/
		$order_vo['order_incharge'] = $order_vo['order_incharge'] + floatval($ecv_money);
		
		if(abs($order_vo["order_incharge"] - $order_vo['order_total_price']) < 0.001)
		{
			$order_vo["money_status"] = 2;	
		}
		else if($order_vo["order_incharge"] < $order_vo['order_total_price'])
		{
			if($order_vo["order_incharge"]==0)
				$order_vo["money_status"] = 0;
			else
				$order_vo["money_status"] = 1;
		}
		else if($order_vo["order_incharge"] >= $order_vo['order_total_price'])
		{
			$order_vo["money_status"] = 2;
		}
		/*del by chenfq 2010-04-08
		//取款手续费
		$order_vo["cost_payment_fee"] = floatval($order_vo["cost_payment_fee"]) + $incharge_vo['cost_payment_fee'];
		*/
		if($GLOBALS['db']->autoExecute(DB_PREFIX."order", addslashes_deep($order_vo), 'UPDATE', "id = ".intval($order_vo['id'])))
		{
			if($order_vo["money_status"] == 2)
			{
				$userid = intval($order_vo['user_id']);
				
				$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$userid);
				
				$parentID = intval($order_vo['parent_id']);
				$referrals = array();
				$referrals['order_id'] = 0;
				$referrals_amount_new = 0;
				$referrals_amount_old = 0;
				
				/*
				$sql = "select count(*) as num from ". DB_PREFIX."referrals where user_id=".$userid." and parent_id=".$parentID;
				if ($GLOBALS['db']->getOne($sql) <> 0){
					$parentID = 0;
				}
				*/
				//判断用户是否第一次购买
				if ($user['buy_count'] > 0){
					$parentID = 0;
				}
				//判断订单表里的支付方式是不能货到付款
				$payment_name=$GLOBALS['db']->getOne("select class_name from ".DB_PREFIX."payment where id =".intval($order_vo['payment']));
				if($payment_name=="Cod")//cod是货到付款
				{
					$is_Cod=1;
				}
				else
				{
					$is_Cod=0;
				}
				//is_first_referral:不算第一次购买;0:否；1:是
				$is_first_referral = 1;
				$sql = "select a.data_total_referral_money as referral_money, b.id, b.buy_count, b.user_count, b.type_id, b.is_first_referral, b.close_referrals, b.referrals, b.city_id, a.data_total_score as score, b.goods_short_name from ".DB_PREFIX."order_goods a ".
					   "left outer join  ".DB_PREFIX."goods b on b.id = a.rec_id where a.order_id = ".intval($order_vo['id']);
				$goods_list = $GLOBALS['db']->getAll($sql);
				foreach($goods_list as $goods){
					//is_first_referral:不算第一次购买;0:否；1:是
					if ($goods['is_first_referral'] == 0)
						$is_first_referral = 0;
					
						//计算已经购买了几个商品
						$sql = "select sum(og.number) as number from ".DB_PREFIX."order as o left join ".DB_PREFIX."order_goods  as og on og.order_id = o.id where og.rec_id = ".$goods['id']." and (o.money_status = 2 or o.goods_status=2)";
						$goods['buy_count'] = $GLOBALS['db']->getOne($sql);
						
						$sql = "select count(*) as number from ".DB_PREFIX."order_goods where user_id = '$userid' and rec_id = ".$goods['id'];
						if($GLOBALS['db']->getOne($sql) > 0)
						{
							$sql = "select count(o.id) as number from ".DB_PREFIX."order as o left join ".DB_PREFIX."order_goods  as og on og.order_id = o.id where og.rec_id = ".$goods['id']." and (o.money_status = 2 or o.goods_status=2)";
							$goods['user_count'] = $GLOBALS['db']->getOne($sql);
						}
						
						if($is_Cod==0)//支付方式不是货到款时更新商品购买数量						
						{
							$sql = "update ". DB_PREFIX."goods set buy_count = virtual_count + ".$goods['buy_count'].", user_count =".$goods['user_count']." where id=".$goods['id'];
						}
						
						$GLOBALS['db']->query($sql);
						
						
						//add by chenfq 2010-04-07  会员积明细, 全额支付时，
						//type_id 0:团购券，序列号+密码;1:实体商品，需要配送;2:线下订购商品;3:实体,有配置,有团购券
						if ($order_vo['user_id'] > 0 && $goods['score'] <> 0 && (($goods["type_id"] == 0)||($goods["type_id"] == 1)||($goods["type_id"] == 3)))
						{
							if ($goods['score'] > 0)
								$Remark = a_L("ORDER_SCORE_MEMO_1").'(SN:'.$order_vo['sn'].';goods_id:'.$goods['id'].';score:'.$goods['score'].')';//订单获得积分
							else
								$Remark = a_L("ORDER_SCORE_MEMO_2").'(SN:'.$order_vo['sn'].';goods_id:'.$goods['id'].';score:'.$goods['score'].')';
								
							$sql = 'insert into '.DB_PREFIX.'user_score_log(user_id, create_time, score, rec_module,rec_id,memo_1) values('.$order_vo['user_id'].','.a_gmtTime().','.$goods['score'].',\'Order\','.$order_vo['id'].',\''.$Remark.'\')';
							$GLOBALS['db']->query($sql);
							
							//增加会员积分
							$sql = 'update '.DB_PREFIX.'user set score = score + '.$goods['score'].' where id = '.$order_vo['user_id'];
							$GLOBALS['db']->query($sql);		
						}
						
						//add by chenfq 2011-03-03 购买商品，金额
						$goods['referral_money'] = intval($goods['referral_money']);
						if ($order_vo['user_id'] > 0 && $goods['referral_money'] > 0 && (($goods["type_id"] == 0)||($goods["type_id"] == 1)||($goods["type_id"] == 3)))
						{
							$Remark = a_L("ORDER_MONEY_MEMO_1").'(SN:'.$order_vo['sn'].';goods_id:'.$goods['id'].';score:'.$goods['referral_money'].')';//订单获得积分
								
							$sql = 'insert into '.DB_PREFIX.'user_money_log(user_id, create_time, money, rec_module,rec_id,memo_1) values('.$order_vo['user_id'].','.a_gmtTime().','.$goods['referral_money'].',\'Order\','.$order_vo['id'].',\''.$Remark.'\')';
							$GLOBALS['db']->query($sql);
							
							//增加会员积分
							$sql = 'update '.DB_PREFIX.'user set money = money + '.$goods['referral_money'].' where id = '.$order_vo['user_id'];
							$GLOBALS['db']->query($sql);		
						}
						
						//如果购买车中有返利的话，取返利金额最大的一个
						if ($parentID > 0 && $parentID != $userid && $goods['close_referrals'] == 0 && (($goods["type_id"] == 0)||($goods["type_id"] == 1)||($goods["type_id"] == 3)) && a_gmtTime() - $user['create_time'] < (a_fanweC("REFERRAL_TIME") * 3600) ){
							$referrals_amount_new = $goods['referrals'] == 0?intval(a_fanweC("REFERRALS_MONEY")):$goods['referrals'];
							if ($referrals_amount_new > $referrals_amount_old){
								
								$referrals['user_id'] = $userid;
								$referrals['parent_id'] = $parentID;
								$referrals['order_id'] = $order_vo['id'];
								$referrals['goods_id'] = $goods['id'];
								if(a_fanweC("REFERRAL_TYPE")==0)
									$referrals['money'] = $referrals_amount_new;
								else
									$referrals['score'] = $referrals_amount_new;
								$referrals['is_pay'] = 0;
								$referrals['create_time'] = a_gmtTime();
								$referrals['city_id'] = intval($goods['city_id']);								
								
								
								$referrals_amount_old = $referrals_amount_new;
							}
						}
				}
				
				//is_first_referral:不算第一次购买;0:否；1:是
				if ($is_first_referral == 0){
					$sql = "update ".DB_PREFIX."user set buy_count = buy_count + 1 where id ='".$userid."'";// 用户购买次数加1
					$GLOBALS['db']->query($sql);
				}
								
				if (intval($referrals['order_id']) > 0){
					$GLOBALS['db']->autoExecute(DB_PREFIX."referrals", addslashes_deep($referrals), 'INSERT');
				}

				s_sendOrderGroupBonds($order_vo['id']);	
			}
		}		
		
		return true;
	}  	

	/**
	 * 发放团购券
	 * @param $goodsID  商品ID
	 * @param $prefix 团购券自定义前缀
	 *2014-7-27 增加到12位
	 */
	
	function s_gen_groupbond_sn($goodsID,$prefix="")
	{		
		do
		{
			$r_sn = rand(1000000,9999999);
			$tmp_sn = rand(1000,9999);
			$sn = str_pad($r_sn, 12,$tmp_sn,STR_PAD_BOTH);
		}
		while($GLOBALS['db']->getOne("select count(*) as num from ".DB_PREFIX."group_bond where sn='".$prefix.$sn."' and goods_id=".$goodsID)>0);
		return $prefix.$sn;
	}		

			//处理成功返回true，处理失败返回，错误消息
    function s_order_paid_in($payment_log_id, $money, $payment_id, $currency_id)
	{
		/* 修改此次支付操作的状态为已付款 */
		//,update_time = ".a_gmtTime()."  add by chenfq 2011-07-12,update_time = ".a_gmtTime()."
		$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set is_paid = 1 where is_paid = 0 and id = ".$payment_log_id);
		$rs = $GLOBALS['db']->affected_rows();
		if($rs == 0)
			return a_L('PAY_LOG_ID').$payment_log_id.a_L('PAY_LOG_ID_INVALID');
		
    	$payment_log_vo = $GLOBALS['db']->getRow("select id,is_paid,rec_id,payment_id,create_time from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
    	if ($payment_log_vo == false){
    		return a_L('INVALID_PAY_LOG_ID').$payment_log_id;
    	}
//        if ($payment_log_vo['is_paid'] == 1){
//    		return a_L('PAY_LOG_ID').$payment_log_id.a_L('PAY_LOG_ID_INVALID');
//    	}   
		
		$payment = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."payment where id=".intval($payment_id));
		
        if ($payment == false){
    		return a_L('INVALID_PAYMENT_ID');
    	}
    	        
    	$order_vo = $GLOBALS['db']->getRow("select id,user_id,sn,currency_id,money_status from ".DB_PREFIX."order where id=".intval($payment_log_vo['rec_id']));
        if ($order_vo == false){
    		return a_L('INVALID_ORDER_ID');
    	}else{
			if (intval($currency_id) == 0){
				$currency_id = $order_vo['currency_id'];
			}
			$currency = array();
			$currency['unit'] = a_fanweC("BASE_CURRENCY_UNIT");
		    $currency['radio'] = 1;
			
    		//add by chenfq 添加判断团购是否已经结束？商品是否已经销售完 2010-06-28

			$sql = "select b.promote_end_time,b.is_group_fail, b.stock, b.buy_count, a.number from ".DB_PREFIX."order_goods a ".
					"left outer join  ".DB_PREFIX."goods b on b.id = a.rec_id where a.order_id = ".intval($order_vo['id']);
			
			$goods_list = $GLOBALS['db']->getAll($sql);
			foreach($goods_list as $goods){			
			    if ($goods['promote_end_time'] < a_gmtTime() || $goods['is_group_fail'] == 1 || ($goods['stock'] > 0 && $goods['buy_count']+$goods['number'] > $goods['stock'])||$order_vo['money_status']==2){ //add by chenfq 2010-05-30 判断时间是否结束		
					if ($payment['class_name'] == 'Accountpay'){//会员使用预存款支付
					 	return a_L('ORDER_PAID_IN_1');
					}else{
						s_user_money_log($order_vo['user_id'], $order_vo['id'], 'Order', $money * $currency['radio'], $order_vo['sn'].a_L('ORDER_PAID_IN_2'));
						//add by chenfq 2010-06-30  记录帐户资金变化明细 begin
						s_payment_money_log($payment_log_vo['payment_id'], 
										  $order_vo['user_id'], 
										  $order_vo['id'], 
										  'Order', 
										  $money * $currency['radio'], 
										  $order_vo['sn'].a_L('ORDER_PAID_IN_3').$money * $currency['radio'], 
										  false, 
										  'User', 
										  '', 
										  '');
										  
						if($order_vo['money_status']==2)
						{
							$sql = "update ".DB_PREFIX."order set repay_status = 1 where id = ".intval($order_vo['id']);
							$GLOBALS['db']->query($sql);
							
							//添加一收款单
							$vo = array();
							$vo['order_id'] = $order_vo['id'];
							$vo['cost_payment_fee'] = 0;
							$vo['currency_id'] = $currency_id;
							$vo['currency_radio'] = $currency['radio'];
							$vo['money'] = $money * $currency['radio'];
							$vo['create_time'] = a_gmtTime();
							$vo['memo'] = a_L('ORDER_PAID_IN_4').$money;
							$vo['payment_id'] = $payment_id;
							$vo['payment_log_id'] = $payment_log_id;//add by  chenfq 2011-05-31 
							//修改 by hc 增加收款单时，存入支付单号
							$payment['config'] = unserialize($payment['config']);
							if($payment['class_name']=='TenpayBank'||$payment['class_name']=='Tencentpay')
							{				 
									 $today = a_toDate($payment_log_vo['create_time'],'Ymd');
							         /* 将商户号+年月日+流水号 */
							         $bill_no = str_pad($payment_log_vo['id'], 10, 0, STR_PAD_LEFT);
							         $vo['payment_log_sn'] = $payment['config']['tencentpay_id'].$today.$bill_no;			
							}
							elseif($payment['class_name']=='Alipay')
							{
								$vo['payment_log_sn'] = 'fw123456'.$payment_log_vo['id'];
							}
							else
								$vo['payment_log_sn'] = $payment_log_vo['id'];
							
							$GLOBALS['db']->autoExecute(DB_PREFIX."order_incharge", addslashes_deep($vo), 'INSERT');
							
							return a_L('ORDER_PAID_IN_5').'【'.$order_vo['sn'].'】'.a_L('ORDER_PAID_IN_6');
						}
						else
						{
							//add by chenfq 2010-06-30 记录帐户资金变化明细 end	
							$sql = "update ".DB_PREFIX."order set repay_status = 2 where id = ".intval($order_vo['id']);
							$GLOBALS['db']->query($sql);
													
							//添加一收款单
							$vo = array();
							$vo['order_id'] = $order_vo['id'];
							$vo['cost_payment_fee'] = 0;
							$vo['currency_id'] = $currency_id;
							$vo['currency_radio'] = $currency['radio'];
							$vo['money'] = $money * $currency['radio'];
							$vo['create_time'] = a_gmtTime();
							$vo['memo'] = a_L('ORDER_PAID_IN_7').$money;
							$vo['payment_id'] = $payment_id;
							$vo['payment_log_id'] = $payment_log_id;//add by  chenfq 2011-05-31
							//修改 by hc 增加收款单时，存入支付单号
							$payment['config'] = unserialize($payment['config']);
							if($payment['class_name']=='TenpayBank'||$payment['class_name']=='Tencentpay')
							{				 
									 $today = a_toDate($payment_log_vo['create_time'],'Ymd');
							         /* 将商户号+年月日+流水号 */
							         $bill_no = str_pad($payment_log_vo['id'], 10, 0, STR_PAD_LEFT);
							         $vo['payment_log_sn'] = $payment['config']['tencentpay_id'].$today.$bill_no;			
							}
							elseif($payment['class_name']=='Alipay')
							{
								$vo['payment_log_sn'] = 'fw123456'.$payment_log_vo['id'];
							}
							else
								$vo['payment_log_sn'] = $payment_log_vo['id'];
							
							$GLOBALS['db']->autoExecute(DB_PREFIX."order_incharge", addslashes_deep($vo), 'INSERT');
							
						 	return a_L('ORDER_PAID_IN_1').'【'.$order_vo['sn'].'】'.a_L('ORDER_PAID_IN_8');
						}
					}
			    }
			}			  		
    	}
    		
    	
		$user_id = intval($order_vo['user_id']);
    	if ($payment['class_name'] == 'Accountpay'){//会员使用预存款支付
    	  if ($user_id > 0){
    	     $user = $GLOBALS['db']->getRow("select id, money from ".DB_PREFIX."user where id = ".$user_id);
		  	 if (($user['money'] < 0) || ($money - $user['money'] > 0.01 )){
            	return a_L('USER_MONEY_DEFICIT');
		  	 }
    	  }else{
    	  	return a_L('INVALID_USER_ID');
    	  }
		}

		$cost_payment_fee = 0;
		if ($payment['cost_fee_type'] == 1){
			$cost_payment_fee = $payment['cost_fee'] * $currency['radio'];
		}else{
			$cost_payment_fee = $money * $payment['cost_fee'] / 100 / $currency['radio'];
		}
		
		//添加一收款单
		$vo = array();
		$vo['order_id'] = $order_vo['id'];
		$vo['cost_payment_fee'] = $cost_payment_fee;
		$vo['currency_id'] = $currency_id;
		$vo['currency_radio'] = $currency['radio'];
		$vo['money'] = $money * $currency['radio'];
		$vo['create_time'] = a_gmtTime();
		$vo['memo'] = a_L('ORDER_ONLINE_PAY').':'.$money;
		$vo['payment_id'] = $payment_id;
		$vo['payment_log_id'] = $payment_log_id;//add by  chenfq 2011-05-31
		//修改 by hc 增加收款单时，存入支付单号
		$payment['config'] = unserialize($payment['config']);
		if($payment['class_name']=='TenpayBank'||$payment['class_name']=='Tencentpay')
		{				 
			$today = a_toDate($payment_log_vo['create_time'],'Ymd');
		   /* 将商户号+年月日+流水号 */
		   $bill_no = str_pad($payment_log_vo['id'], 10, 0, STR_PAD_LEFT);
		   $vo['payment_log_sn'] = $payment['config']['tencentpay_id'].$today.$bill_no;			
		}
		elseif($payment['class_name']=='Alipay')
		{
			$vo['payment_log_sn'] = 'fw123456'.$payment_log_vo['id'];
		}
		else
			$vo['payment_log_sn'] = $payment_log_vo['id'];
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."order_incharge", addslashes_deep($vo), 'INSERT');
	    $id = $GLOBALS['db']->insert_id();		
		
	    //modfiy by chenfq 2010-06-5 添加 $onlinepay=true 参数
	    return s_inc_order_incharge($id, true);	   
    }	
	   
   function getOrderItem($order_id){
     		
   		$cart_item = $GLOBALS['db']->getRow("select sum(data_total_price) as data_total_price,sum(data_total_score) as data_total_score,sum(data_total_referral_money) as data_total_referral_money, 0 as is_inquiry, 1 as goods_type, sum(number) as number from ".DB_PREFIX."order_goods where order_id='".$order_id."'");
				
		//a_fanweC("FREE_DELIVERY_LIMIT") 系统配置上的：免运费金额限制(元)
		//number FREE_DELIVERY_NUM_LIMIT // 免运费数量限制 add by chenfq 2010-11-30
		//echo a_fanweC("FREE_DELIVERY_NUM_LIMIT"); exit;
		if ($cart_item['data_total_price'] < a_fanweC("FREE_DELIVERY_LIMIT")  && $cart_item['number'] < a_fanweC("FREE_DELIVERY_NUM_LIMIT")){ 
			$cart_item['is_inquiry'] = 0;//1:免运费; 0:需要运费
			//实体商品，需要配送, 免运费金额
			//修正:同一商品，不同属性组合后，达到免运费数量后，而不能免运费 add by chenfq 2011-06-10
			$sql = "select sum(a.data_total_price) as data_total_price, b.id,b.free_delivery_amount,b.is_inquiry from ".DB_PREFIX."order_goods a ".
				   " left outer join  ".DB_PREFIX."goods b on b.id = a.rec_id ".
				   " where b.is_inquiry = 1 and (b.type_id = 1 or b.type_id = 3) and a.order_id = '".$order_id."'  group by b.id, b.free_delivery_amount,b.is_inquiry";
			$goods_list = $GLOBALS['db']->getAll($sql);
			foreach($goods_list as $goods){
				if(floatval($goods['data_total_price']) >= $goods['free_delivery_amount'])
				{
					$cart_item['is_inquiry'] = 1;
					break;
				}				
			}
		}else{
			$cart_item['is_inquiry'] = 1;//1:免运费
		}
		
		$cart_item['fix_delivery_money'] = 0;//商品固定费用 add by chenfq 2011-03-05
		$cart_item['goods_type'] = 0;
		$sql = "select b.id, b.type_id, b.fix_delivery_money from ".DB_PREFIX."order_goods a ".
				   " left outer join ".DB_PREFIX."goods b on b.id = a.rec_id ".
				   " where b.id > 0 and (b.type_id = 1 or b.type_id = 2 or b.type_id = 3) and a.order_id = '".$order_id."'";
		$goods_list = $GLOBALS['db']->getAll($sql);
		foreach($goods_list as $goods){
			if ($goods['type_id'] == 2){
				$cart_item['goods_type'] = 2; //线下团购
				break;
			}else if($goods['type_id'] == 1 ||$goods['type_id'] == 3){
				$cart_item['goods_type'] = 1;//1:有配置方式；0：无配置方式
			}
			
			if ($goods['fix_delivery_money'] > $cart_item['fix_delivery_money']){
				$cart_item['fix_delivery_money'] = $goods['fix_delivery_money'];
			}			
			
		}

		return $cart_item;
   }

      function getCartItem($session_id){
   	
   		if (empty($session_id))
   			$session_id = session_id();
   		
   		$cart_item = $GLOBALS['db']->getRow("select sum(data_total_price) as data_total_price, sum(data_total_weight) as data_total_weight,sum(data_total_score) as data_total_score, sum(data_total_referral_money) as data_total_referral_money, 0 as is_inquiry, 1 as goods_type, sum(number) as number from ".DB_PREFIX."cart where session_id='".$session_id."'");
				
		//a_fanweC("FREE_DELIVERY_LIMIT") 系统配置上的：免运费金额限制(元)
		//number FREE_DELIVERY_NUM_LIMIT // 免运费数量限制 add by chenfq 2010-11-30
		//echo a_fanweC("FREE_DELIVERY_NUM_LIMIT"); exit;
		if ($cart_item['data_total_price'] < a_fanweC("FREE_DELIVERY_LIMIT") && $cart_item['number'] < a_fanweC("FREE_DELIVERY_NUM_LIMIT") ){ 
			$cart_item['is_inquiry'] = 0;//1:免运费; 0:需要运费
			//实体商品，需要配送, 免运费金额
			//修正:同一商品，不同属性组合后，达到免运费数量后，而不能免运费 add by chenfq 2011-06-10
			$sql = "select sum(a.data_total_price) as data_total_price, b.id, b.free_delivery_amount,b.is_inquiry from ".DB_PREFIX."cart a ".
				   " left outer join  ".DB_PREFIX."goods b on b.id = a.rec_id ".
				   " where b.is_inquiry = 1 and (b.type_id = 1 or b.type_id = 3) and a.session_id = '".$session_id."' group by b.id, b.free_delivery_amount,b.is_inquiry";
			$goods_list = $GLOBALS['db']->getAll($sql);
			foreach($goods_list as $goods){
				if(floatval($goods['data_total_price']) >= $goods['free_delivery_amount'])
				{
					$cart_item['is_inquiry'] = 1;
					break;
				}				
			}
		}else{
			$cart_item['is_inquiry'] = 1;//1:免运费
		}
		
		$cart_item['fix_delivery_money'] = 0; //商品固定费用 add by chenfq 2011-03-05
		$cart_item['goods_type'] = 0;
		$sql = "select b.id, b.type_id,b.fix_delivery_money from ".DB_PREFIX."cart a ".
				   " left outer join ".DB_PREFIX."goods b on b.id = a.rec_id ".
				   " where b.id > 0 and (b.type_id = 1 or b.type_id = 2 or b.type_id = 3) and a.session_id = '".$session_id."'";
		$goods_list = $GLOBALS['db']->getAll($sql);
		foreach($goods_list as $goods){
			if ($goods['type_id'] == 2){
				$cart_item['goods_type'] = 2; //线下团购
				break;
			}else if($goods['type_id'] == 1 || $goods['type_id'] == 3){
				$cart_item['goods_type'] = 1;//1:有配置方式；0：无配置方式
			}
			
			if ($goods['fix_delivery_money'] > $cart_item['fix_delivery_money']){
				$cart_item['fix_delivery_money'] = $goods['fix_delivery_money'];
			}
		}
		
		return $cart_item;
   }  
    
     function lottery_done(){
		$result  =  array();
		$result['status']  =  false;
		$result['error'] = '';
		$result['order_id'] = 0;
		
		$result['accountpay_str'] = '';
		$result['ecvpay_str'] = '';
		$result['money_status'] = 0;
		   	
   	    $user_id = intval($_SESSION['user_id']);
    	$code = trim($_REQUEST['code']);
    	$goods_id = intval($_REQUEST['goods_id']);
    	$session_id=session_id();
    	$now = a_gmtTime();
    	$number = 1;
    	$result['goods_id'] = $goods_id;
    	
    	
		if(isset($_SESSION['lottery_done_'.$goods_id]) && intval($_SESSION['lottery_done_'.$goods_id]) > 0)
		{
			if(a_gmtTime() - intval($_SESSION['lottery_done_'.$goods_id]) < 30)
			{
	   			$result['error'] = '点击操作过快';			
	   			header("Content-Type:text/html; charset=utf-8");
	   			echo json_encode($result);
				exit; 
			}
		}else{
			//记录时间
			$_SESSION['lottery_done_'.$goods_id] = a_gmtTime();
		}
	    	
    	//CLOSE_LOTTERY_SMS:0:抽奖时需要开启短信验证；1：抽奖时关闭短信验证
    	if (intval(a_fanweC("CLOSE_LOTTERY_SMS")) == 0){
    		$mobile_phone2 = trim($_REQUEST['mobile_phone2']);
    		if (empty($mobile_phone2)){
    			$sql = "select * from ".DB_PREFIX."sms_subscribe where status = 0 and goods_id = '$goods_id' and user_id = $user_id and code = '$code'";	
    		}else{
    			$sql = "select * from ".DB_PREFIX."sms_subscribe where status = 1 and goods_id > 0 and user_id = $user_id and mobile_phone = '$mobile_phone2'";
    		}
    		$sms = $GLOBALS['db']->getRow($sql);
    		if (empty($sms)){
    			//a_error('无效的验证码');
    			$_SESSION['lottery_done_'.$goods_id] = 0;
	   			$result['error'] = '无效的验证码';//$sql.$mobile_phone2;//$GLOBALS['Ln']['GOODS_LOSE_PLS_SELECT_OTHER'].$sql;			
	   			header("Content-Type:text/html; charset=utf-8");
	   			echo json_encode($result);
				exit;    			
    		}else{
    			$sql = "update ".DB_PREFIX."sms_subscribe set status = 1  where id = ".$sms['id'];
    			$GLOBALS['db']->query($sql);
    		}
    	}
    	

    	
    	$sql = "select * from ".DB_PREFIX."goods where id='".$goods_id."'";
    	$goods_info = $GLOBALS['db']->getRowCached($sql);
    	
    	
		if($goods_info['type_id'] == 2)
		  $unit_price = floatval($goods_info['earnest_money']);
		else
		  $unit_price = floatval($goods_info['shop_price']);

	
		//清空购买车
		$sql = "delete from ".DB_PREFIX."cart where session_id='".$session_id."'";  
		$GLOBALS['db']->query($sql);
		  
		//插入购买车
		$sql = "insert into ".DB_PREFIX."cart (`id`,`pid`,`rec_id`,`rec_module`,`session_id`,`user_id`,`number`,`data_unit_price`,`data_score`,`data_promote_score`,`data_total_score`,`data_total_price`,`create_time`,`update_time`,`data_name`,`data_sn`,`data_weight`,`data_total_weight`,`is_inquiry`,`goods_type`)".
						   " values (0,0,'".$goods_id."','Goods','".$session_id."','".intval($_SESSION['user_id'])."','".$number."','".floatval($unit_price)."','".$goods_info['score']."',0,'".(intval($goods_info['score'])*$number)."','".($unit_price*$number)."','".$now."','".$now."','".$goods_info['name_1']."','".$goods_info['sn']."','".$goods_info['weight']."','".(floatval($goods_info['weight'])*$number)."','".$goods_info['is_inquiry']."','".$goods_info['type_id']."')";
		$GLOBALS['db']->query($sql);		

		$result = cart_done($goods_id);
		if ($result['money_status'] == 2){
			
			$ii = 0;
			do{
				$ii = $ii + 1;
				//插入抽奖号码
				$sn = s_gen_lottery_no($goods_id);
				
				//给自己分配一个抽奖号
				$sql = "insert into ".DB_PREFIX."lottery_no (`id`,`goods_id`,`order_id`,`sn`,`user_id`,`invite_user_id`,`invite_time`,`status`)".
							   " select 0,'".$goods_id."','".$result['order_id']."','".$sn."','".$user_id."','".$user_id."','".$now."','0' from dual where not exists (select id from ".DB_PREFIX."lottery_no where goods_id = '".$goods_id."' and sn = '".$sn."') ";
				$GLOBALS['db']->query($sql); 				
				$rs = $GLOBALS['db']->affected_rows();				
			}while($rs == 0 && $ii < 10);

			
			
			//判断是否第一次购买
			$sql = "select parent_id from ".DB_PREFIX."user where parent_id <> id and buy_count <= 1 and id = ".$user_id;
			$invite_user_id = intval($GLOBALS['db']->getOne($sql));
			if ($invite_user_id > 0){
				//判断用户以前是否参加过抽奖 add by chenfq 2011-02-22
				$sql = "select count(*) as num from ".DB_PREFIX."lottery_no where goods_id <> ".$goods_id." and user_id =".$user_id;
				$num = intval($GLOBALS['db']->getOne($sql));
				if ($num == 0){
					$ii = 0;
					do{
						$ii = $ii + 1;					
						//有邀请人，则给邀请人分配一个抽奖号
						$sn = s_gen_lottery_no($goods_id);
						$sql = "insert into ".DB_PREFIX."lottery_no (`id`,`goods_id`,`order_id`,`sn`,`user_id`,`invite_user_id`,`invite_time`,`status`)".
										   " select 0,'".$goods_id."','".$result['order_id']."','".$sn."','".$invite_user_id."','".$user_id."','".$now."','0' from dual where not exists (select id from ".DB_PREFIX."lottery_no where goods_id = '".$goods_id."' and sn = '".$sn."')";
						$GLOBALS['db']->query($sql);					
									
						$rs = $GLOBALS['db']->affected_rows();	
										
					}while($rs == 0 && $ii < 10);
					
					//由于给 邀请人分配了一个抽奖号,所以要重新计算购买人数 add by chenfq 2011-03-09=====
					$sql = "select count(*) from ".DB_PREFIX."lottery_no where goods_id='".$goods_id."'";
					$lottery_count = intval($GLOBALS['db']->getOne($sql));	
					$sql = "update ".DB_PREFIX."goods set buy_count = ".$lottery_count." + virtual_count where id='".$goods_id."'";
					$GLOBALS['db']->query($sql);
					//===============end======					
				}
			}						
		}else{
			$_SESSION['lottery_done_'.$goods_id] = 0;
		}
		echo json_encode($result);
   }
   
   	function s_gen_lottery_no($goodsID)
	{		
		$goodsID = intval($goodsID);
		//购买人数
    	$sql = "select buy_count - 1 as sn from ".DB_PREFIX."goods where id='".$goodsID."'";
    	$buy_count = intval($GLOBALS['db']->getOne($sql));
    			
    	$sql = "select max(sn) from ".DB_PREFIX."lottery_no where goods_id='".$goodsID."'";
    	$sn = intval($GLOBALS['db']->getOne($sql));
    	
    	if ($buy_count >$sn){
    		$sn = $buy_count;
    	}

    	$sn = $sn + 1;
    	$sn = str_pad($sn, 6,'0',STR_PAD_LEFT);
    	return $sn;
    	/*
		do
		{
			$r_sn = rand(100000,999999);
			$sn = str_pad($r_sn, 6,'0',STR_PAD_LEFT);
		}
		while($GLOBALS['db']->getOne("select count(*) as num from ".DB_PREFIX."lottery_no where sn='".$sn."' and goods_id=".$goodsID)>0);
		return $sn;
*/
	}
	   
   	//完成订单 add by chenfq 2011-08-05 添加 $return_array 参数
   function cart_done($goods_id = 0,$return_array = false)
   { 
   		require_once ROOT_PATH.'app/source/func/com_order_done_func.php';
	  	return cart_done_2($goods_id,$return_array);
   }

   //services/index.php 的函数库
   function check_goods($session_id,$user_id,&$error){
		   	
		//$sql = "select rec_module,rec_id,number,data_name,data_sn,data_score,data_total_score,data_unit_price,data_total_price,data_promote_score,data_total_score,data_score,attr,is_inquiry,data_weight from ".DB_PREFIX."cart where session_id = '".$session_id."' and user_id=".$user_id;
		$sql = "select * from ".DB_PREFIX."cart where session_id = '".$session_id."' and user_id=".$user_id;
		$list = $GLOBALS['db']->getAll($sql);
		
		if ($list){
			foreach($list as $cart_item)
			{
			  if ($cart_item['rec_module'] == 'PromoteGoods' || $cart_item['rec_module'] == 'Goods'){ //
			  	
				$goods_info = getGoodsItem($cart_item['rec_id']);
	   					
			  	if ($goods_info){
			  		$bln = false;
					$err = "";
					$number = intval($cart_item['number']);
					
			  		if ($goods_info['promote_end_time'] < a_gmtTime() || $goods_info['is_group_fail'] == 1 || ($goods_info['stock'] > 0 && $goods_info['buy_count'] + $number > $goods_info['stock']))
					{
						if($goods_info['promote_end_time'] < a_gmtTime()|| $goods_info['is_group_fail'] == 1)
						{					
							//$this->assign("jumpUrl",u("Goods/show",array("id"=>$cart_item['rec_id'])));
							//$this->error("团购已结束");
							$error = $GLOBALS['Ln']['XY_GROUP_IS_END'];
				   			return false;		  		
						}
						if($goods_info['stock'] > 0 && $goods_info['buy_count']+$cart_item['number'] > $goods_info['stock'])
						{
							//$this->assign("jumpUrl",u("Goods/show",array("id"=>$cart_item['rec_id'])));
							//$this->error("已售光");
							$error = $GLOBALS['Ln']['XY_B_SORRY_SOLD_OUT'];
							return false;							
						}				
					}
					
					//modify chenfq by 2011-03-01 不统计作废订单数量		
					//$sql = "select sum(number) as num from ".DB_PREFIX."order_goods where rec_id = ".intval($cart_item['rec_id'])." and user_id=".intval($_SESSION['user_id']);
					$sql = "select sum(og.number) as num from ".DB_PREFIX."order_goods as og "
						  ." left outer join ".DB_PREFIX."order o on o.id = og.order_id "
						  ."where o.status <> 2 and og.rec_id = ".intval($cart_item['rec_id'])." and og.user_id=".intval($_SESSION['user_id']);					
					$userBuyCount = intval($GLOBALS['db']->getOne($sql));
								
					$maxBought    = intval($goods_info['max_bought']);
					$surplusCount = intval($goods_info['stock']) - intval($goods_info['buy_count']);
					$goodsStock   = intval($goods_info['stock']);
						
					if($number + $userBuyCount > $maxBought && $maxBought > 0)
					{
						$number = $maxBought - $userBuyCount;
						$bln = true;
					}
						
					if($number > $surplusCount && $goodsStock > 0)
					{
						$number = $surplusCount;
						$bln = true;
					}
				
					if($bln)
					{
						if($maxBought > 0)
							$err.=sprintf($GLOBALS['Ln']["HC_USER_MAX_BUYCOUNT"],$maxBought);				
							
						if($goodsStock > 0)
						
							$err.=sprintf($GLOBALS['Ln']["HC_ONLY_LESS_COUNT"],$surplusCount).(($err == "") ? $GLOBALS['Ln']["HC_GOODS"] : "")."，";
			
						$err.= sprintf($GLOBALS['Ln']["HC_HASBUYCOUNT_LESSCOUNT"],$userBuyCount,$number);
						
						$error = $err;
						return false;
					}		  		
			  	}else{
					//$this->assign("jumpUrl", U('Index/index'));
			  		//$this->error('选中商品丢失，请申请选择商品！');
					$error = $GLOBALS['Ln']['GOODS_LOSE_PLS_SELECT_OTHER'];
	   				return false;		  		
			  	}
			  }	
			}

			return true;
		}else{
			$error = $GLOBALS['Ln']['GOODS_LOSE_PLS_SELECT_OTHER'].$sql;
			return false;
		}
   }

function loadDelivery($id)
{
	require_once ROOT_PATH.'app/source/func/com_order_done_func.php';
	return loadDelivery_2($id,false);	
}
   
?>