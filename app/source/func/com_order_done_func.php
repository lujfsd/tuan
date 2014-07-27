<?php 
	
	   	   	//完成订单
   function order_done_2($return_array = false)
   {
		$result  =  array();
		$result['status']  =  false;
		$result['error'] = '';
		$result['order_id'] = 0;
		$result['accountpay_str'] = '';
		$result['ecvpay_str'] = '';
		$result['money_status'] = 0;
		   	
   		$user_id = intval($_SESSION['user_id']);
		
		$now = a_gmtTime();
		
		//开始获取提交的数据
		$order_id = intval($_REQUEST['order_id']);
		$sql = "select * from ".DB_PREFIX."order where id = '".$order_id."'";
		$order = $GLOBALS['db']->getRow($sql);
		
		if(!$order || $user_id != $order['user_id'] || $order['money_status'] > 1)
		{ 
   			$result['error'] = a_L('VITIATION_DATA');		
   			if ($return_array){
   				return $result;
   			}else{
   				header("Content-Type:text/html; charset=utf-8");
   				echo json_encode($result);
				exit;   				
   			}
		}	

      	//判断用户是否够积分，换积分商品
      	$sql = "select sum(data_total_score) as num from ".DB_PREFIX."order_goods where data_total_score < 0 and order_id = '".$order_id."'";
      	$data_total_score = $GLOBALS['db']->getOne($sql);	
   		if($data_total_score < 0)
   		{   
   			$data_total_score = abs($data_total_score);
      		$sql = "select score from ".DB_PREFIX."user where id = '".$user_id."'";
      		$user_score = $GLOBALS['db']->getOne($sql);
      		if ($user_score < $data_total_score){
	   			$result['error'] =$GLOBALS['Ln']['COMMON_INFO_2']; // '积分不够，无法购物积分商品'; 			
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}
      		}
   		}
   				
		$result['order_id'] = $order_id;
		//开始获取提交的数据
   	   	$_REQUEST['payment_id'] = trim($_REQUEST['payment_id']);
		$ilen = strpos($_REQUEST['payment_id'],'-');
		$bank_id = '';
		if ($ilen > 0){
			$bank_id = substr($_REQUEST['payment_id'],0,$ilen);
			$payment_id = substr($_REQUEST['payment_id'],$ilen + 1, strlen($_REQUEST['payment_id']) - $ilen);
		}else{
			$payment_id = intval($_REQUEST['payment_id']);
		}
		
		//=======================add by chenfq 2011-06-29 begin========================
		$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id=".$payment_id);	
		$pay_file = VENDOR_PATH.'payment3/'.$payment_info['class_name'].'Payment.class.php';
		if (file_exists($pay_file)){
			require_once($pay_file);
			$payment_class = $payment_info['class_name']."Payment";
			if (class_exists($payment_class)){
				$payment_model = new $payment_class;
				if (method_exists($payment_model,'pre_confirmation_check')){
					$card_info = $payment_model->pre_confirmation_check();
					if ($card_info['result'] == false){
						$result['error'] = $card_info['error'];
			   			if ($return_array){
			   				return $result;
			   			}else{
			   				header("Content-Type:text/html; charset=utf-8");
			   				echo json_encode($result);
							exit;   				
			   			}			
					}else{
						$order['card_info'] = serialize($card_info['card_info']);
					}
				}
			}	
		}		
		//=======================add by chenfq 2011-06-29 end========================
				
		$delivery_id = intval($_REQUEST['delivery_id']);
		$credit = floatval($_REQUEST['credit']);
		$isCreditAll = empty($_REQUEST['iscreditall']) ? 0 : 1;
		
		//提交的地区
		$region_lv1 = intval($_REQUEST['region_lv1']);
		$region_lv2 = intval($_REQUEST['region_lv2']);
		$region_lv3 = intval($_REQUEST['region_lv3']);
		$region_lv4 = intval($_REQUEST['region_lv4']);
		//保价
		$is_protect = intval($_REQUEST['is_protect']);
		//是否开票
		$tax = intval($_REQUEST['tax']);
		
		$ecvSn = trim($_REQUEST['ecv_sn']);
		$ecvPassword = trim($_REQUEST['ecv_password']);
		
		//统计购物车
		$order_total = s_countOrderTotal($order_id,$payment_id,$delivery_id,$is_protect,array('region_lv1'=>$region_lv1,'region_lv2'=>$region_lv2,'region_lv3'=>$region_lv3,'region_lv4'=>$region_lv4),$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);
				
		//开始生成计单	   		
		$order['id'] = $order_id;
		$order['bank_id'] = $bank_id;
		$order['update_time'] = $now;
		$order['ecv_id'] = $order_total['ecvID'];
		$order['ecv_money'] = $order_total['ecvFee'];
		$order['tax'] = $tax;
		if($order_total['total_price'] > 0)
			$order['order_incharge'] =  $order_total['incharge'] + $order['ecv_money'];
		else
			$order['order_incharge'] =  $order_total['incharge'] + $order_total['total_price'] + $order['ecv_money'];
		
		$order['tax_title'] = $_REQUEST['tax_title']?htmlspecialchars($_REQUEST['tax_title'],ENT_QUOTES):'';//add by chenfq 2011-03-17 发票抬头	
		$order['tax_content'] = $_REQUEST['tax_content']?htmlspecialchars($_REQUEST['tax_content'],ENT_QUOTES):'';
		$order['tax_money'] = $order_total['tax_money'];
		$order['memo'] = $_REQUEST['memo']?htmlspecialchars($_REQUEST['memo'],ENT_QUOTES):'';
                //更新来路
                $order['referer'] =$GLOBALS['referer'];
		//发货日期 add by chenfq 2011-05-25
		$order['goods_send_date'] = empty($_REQUEST['goods_send_date']) ? a_gmtTime() : a_strtotime($_REQUEST['goods_send_date']);		
		if($order_total['goods_type'] == 1 || $order_total['goods_type'] == 3) //add by chenfq 2010-05-12 $cart_total['total_price']
		{
			$order['zip'] = $_REQUEST['zip']?$_REQUEST['zip']:'';
		
			//配送地区
			$order['region_lv1'] = $region_lv1;
			$order['region_lv2'] = $region_lv2;
			$order['region_lv3'] = $region_lv3;
			$order['region_lv4'] = $region_lv4;		
			$order['address'] = $_REQUEST['address']?htmlspecialchars($_REQUEST['address'],ENT_QUOTES):'';
			$order['fix_phone'] = $_REQUEST['fix_phone']?htmlspecialchars($_REQUEST['fix_phone'],ENT_QUOTES):'';
			//$order['fax_phone'] = $_POST['fax_phone']?htmlspecialchars($_POST['fax_phone'],ENT_QUOTES):'';
			$order['mobile_phone'] = $_REQUEST['mobile_phone']?htmlspecialchars($_REQUEST['mobile_phone'],ENT_QUOTES):'';	   		
			//$order['qq'] = $_POST['qq']?htmlspecialchars($_POST['qq'],ENT_QUOTES):'';
			//$order['msn'] = $_POST['msn']?htmlspecialchars($_POST['msn'],ENT_QUOTES):'';
			//$order['alim'] = $_POST['alim']?htmlspecialchars($_POST['alim'],ENT_QUOTES):'';
			$order['consignee'] = $_REQUEST['consignee']?htmlspecialchars($_REQUEST['consignee'],ENT_QUOTES):'';

			$order['delivery'] = $delivery_id;
			$order['protect'] = $is_protect;
			$order['delivery_refer_order_id'] = intval($_REQUEST['delivery_refer_order_id']);
		
			$order['delivery_fee'] = $order_total['delivery_free']==1?0:$order_total['delivery_fee'];
			$order['protect_fee'] = $order_total['protect_fee'];
			
			$order['order_weight'] = $order_total['total_weight'];
			
			$order['user_id'] = intval($_SESSION['user_id']);	
				
			
			//将相关订单配送方式保存到新单中
			$rec_order_id = intval($_REQUEST['delivery_refer_order_id']);
			$rec_order = $GLOBALS['db']->getRow("select region_lv1,region_lv2,region_lv3,region_lv4,address,fix_phone,fax_phone,mobile_phone,qq,msn,alim,zip,consignee,protect from ".DB_PREFIX."order where id=".$rec_order_id);
			if($rec_order)
			{
				$order['region_lv1'] = intval($rec_order['region_lv1']);
				$order['region_lv2'] = intval($rec_order['region_lv2']);
				$order['region_lv3'] = intval($rec_order['region_lv3']);
				$order['region_lv4'] = intval($rec_order['region_lv4']);
				
				$order['address'] = $rec_order['address']?$rec_order['address']:'';
				$order['fix_phone'] = $rec_order['fix_phone']?$rec_order['fix_phone']:'';
				$order['fax_phone'] = $rec_order['fax_phone']?$rec_order['fax_phone']:'';
				$order['mobile_phone'] = $rec_order['mobile_phone']?$rec_order['mobile_phone']:'';	   		
				$order['qq'] = $rec_order['qq']?$rec_order['qq']:'';
				$order['msn'] = $rec_order['msn']?$rec_order['msn']:'';
				$order['alim'] = $rec_order['alim']?$rec_order['alim']:'';
				$order['zip'] = $rec_order['zip']?$rec_order['zip']:'';
			
				$order['consignee'] = $rec_order['consignee']?$rec_order['consignee']:'';
	
				//$order['delivery'] = $rec_order['delivery'];
				$order['protect'] = intval($rec_order['protect']);
			}
			//保存本次收货地址到会员地址列表 add by chenfq 2010-04-21
			//dump($order['user_id']);
			if ($order['user_id'] > 0){		
				$num_count = $GLOBALS['db']->getOne("select count(*) as num from ".DB_PREFIX."user_consignee where consignee = '".addslashes($order['consignee']).
														"' and region_lv1 = ".intval($order['region_lv1'])." and region_lv2 = ".intval($order['region_lv2'])." and region_lv3 = ".intval($order['region_lv3'])." and region_lv4 = ".intval($order['region_lv4']).
														" and address = '".addslashes($order['address'])."' and zip = '".addslashes($order['zip'])."' and mobile_phone = '".addslashes($order['mobile_phone'])."' and fix_phone = '".addslashes($order['fix_phone'])."' and user_id=".intval($order['user_id'])." limit 1");
				if ($num_count == 0){
					$sql = "insert into ".DB_PREFIX."user_consignee(user_id,consignee,region_lv1,region_lv2,region_lv3,region_lv4,address,zip,mobile_phone,fix_phone) values(".
							intval($order['user_id']).",".
							"'".addslashes($order['consignee'])."',".
							intval($order['region_lv1']).",".
							intval($order['region_lv2']).",".
							intval($order['region_lv3']).",".
							intval($order['region_lv4']).",".
							"'".addslashes($order['address'])."',".
							"'".addslashes($order['zip'])."',".
							"'".addslashes($order['mobile_phone'])."',".
							"'".addslashes($order['fix_phone'])."')";	
					$GLOBALS['db']->query($sql);		
				}				
			}
		}
		else
		{
			$order['zip'] = '';
			$order['region_lv1'] = 0;
			$order['region_lv2'] = 0;
			$order['region_lv3'] = 0;
			$order['region_lv4'] = 0;
		
			$order['address'] = '';
			$order['fix_phone'] = '';
			$order['fax_phone'] = '';
			$order['mobile_phone'] = '';	   		
			$order['qq'] = '';
			$order['msn'] = '';
			$order['alim'] = '';
		
			$order['consignee'] = '';

			$order['delivery'] = 0;
			$order['protect'] = 0;
		
			$order['delivery_fee'] = 0;
			$order['protect_fee'] = 0;
			
			$order['order_weight'] = 0;
		}				
		
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);

		
		$order['email'] = $user_info['user_email'];
		$order['user_id'] = $user_id;	
		$order['payment'] = $payment_id;
		
		$order['promote_money'] = 0;
		$order['discount'] = $order_total['discount_price'];
		$order['payment_fee'] = $order_total['payment_fee'];
		
		$order['lang_conf_id'] = FANWE_LANG_ID;		
		
		$order['order_total_price'] = $order_total['all_fee'] - $order_total['discount_price'];
	
   		if($order_total['goods_type'] == 2)
		{
			$order['offline'] = 1;
			//$this->assign("goods_type",$cart_total['goods_type']);
			$result['goods_type'] = $order_total['goods_type'];
		}

   		//modify by chenfq 2010-06-02 $cart_total['total_price'] 改成：$order['order_total_price']
		//$cart_total['credit'] 余额支付， $cart_total['ecvFee'] 代金券支付, $order['order_incharge'] 已收金额
		if(($order['order_total_price'] - $order['order_incharge'] - $order_total['credit'] - $order_total['ecvFee']) > 0.001)
		{
			if($GLOBALS['db']->getOneCached("select count(*) as num from ".DB_PREFIX."payment where status=1")>0&&$order['payment']==0)
			{
				$result['error'] = a_L("SELECT_PAYMENT");//.$order['order_total_price'].';'.$order_total['credit'].';'.$order['order_incharge']; 
		  		header("Content-Type:text/html; charset=utf-8");
				echo json_encode($result);		
				exit;				
			}
		}
		else
		{
			//modify by chenfq 2010-06-02 $cart_total['total_price'] 改成：$order['order_total_price']
			if($order['order_total_price'] == 0)
			{
				//会员直接使用：余额支付或代金券支付
				$order['payment_fee'] = 0;
				$order['currency_radio'] = 0;
				$order['payment'] = 0;
			}
			else
			{
				$order['payment'] = 0;
				$order['payment_fee'] = 0;
				if($order_total['credit'] <> 0) //modify by chenfq 2010-05-12  $cart_total['credit'] > 0 ==> $cart_total['credit'] <> 0
				{
					$order['payment'] = $GLOBALS['db']->getOneCached("select id from ".DB_PREFIX."payment where class_name = 'Accountpay'");
					$order['currency_id'] = 1;
					$order['currency_radio'] = 1;
				}
				
				if(!$order['currency_radio'])
					$order['currency_radio'] = 0;
			}
		}

		$delivery_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."delivery where id = ".intval($delivery_id));
		//is_inquiry 1：免运费；0：需要运费; is_smzq: 1：上门自取	
   		//modify by chenfq 2010-06-02 $cart_total['goods_total_price'] 改成：$order['order_total_price']
		if(($order_total['goods_type'] == 1 || $order_total['goods_type'] == 3) && $order_total['order_total_price'] > 0 && intval($delivery_info['is_smzq']) == 0)// add by chenfq 2010-05-17  && $cart_total['goods_total_price'] > 0
		{
			
			if($GLOBALS['db']->getOneCached("select count(*) as num from ".DB_PREFIX."delivery where status=1")>0&&$order['delivery']==0)
			{
				$result['error'] = a_L("SELECT_DELIVERY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			//开始验证是否支持保价
			if($is_protect==1 && $GLOBALS['db']->getOneCached("select protect from ".DB_PREFIX."delivery where id=".intval($delivery_id))==0)
			{
				$result['error'] = a_L("PROTECT_NOT_SUPPORT");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			if($order['consignee']=="")
			{
				$result['error'] = a_L("CONSIGNEE_EMPTY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			if($order['email']=="")
			{
				$result['error'] = a_L("EMAIL_EMPTY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			if(!preg_match("/\w+@\w+\.\w{2,}\b/",$order['email']))
			{
				$result['error'] = a_L("EMAIL_FORMAT_ERROR");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			if($order['zip']=="")
			{
				$result['error'] = a_L("ZIP_EMPTY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			if($order['address']=="")
			{
				$result['error'] = a_L("ADDRESS_EMPTY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}					
			}
			
		}
		else
		{
			/**
			 * modify by chenfq 2010-06-03
			 * 修改订单都为：无需配送 bug 状态
			 * $order['goods_status'] = 5; 
			 */
			if($order_total['goods_type'] <> 1 && $order_total['goods_type'] <> 3)
				$order['goods_status'] = 5;  //团购券商品改为5
		}
		
		if(isset($_REQUEST['user_mobile_phone'])&& $_REQUEST['user_mobile_phone']!='')
		{
			if(a_fanweC("SMS_SEND_OTHER")==1)
				$order['mobile_phone_sms'] = $_REQUEST['user_mobile_phone'];	//发送给别人
			else 
			{
				if($GLOBALS['db']->getOne("select count(*) as num from ".DB_PREFIX."user where mobile_phone='".addslashes($_REQUEST['user_mobile_phone'])."' and id <>".$user_id." and status = 1") > 0)
				{
					$result['error'] = a_L("HC_MOBILE_NUMBER_EXISTS");  		
		   			if ($return_array){
		   				return $result;
		   			}else{
		   				header("Content-Type:text/html; charset=utf-8");
		   				echo json_encode($result);
						exit;   				
		   			}					
				}
			}
		}

		$GLOBALS['db']->autoExecute(DB_PREFIX."order", addslashes_deep($order), 'UPDATE', "id = ".intval($order['id']));
		/*
				$result['error'] = $order['card_info']."\n".$GLOBALS['db']->lastSql;
				header("Content-Type:text/html; charset=utf-8");
				echo json_encode($result);		  		
				exit;		
		*/
   		if($order_total['total_price'] <= 0)
			s_order_incharge_handle($order);
			
		//add by chenfq 2010-05-12 begin	
		if($order_total['total_price'] < 0 && $user_id > 0)
		{
			//记录会员预存款变化明细
			//$memo 格式为 #LANG_KEY#memos  ##之间所包含的是语言包的变量
			$memo = sprintf(a_L("HC_ORDER_INCHARGE_FORMAT"),$order['sn']);
			s_user_money_log(intval($_SESSION['user_id']), $order_id, 'UserIncharge', abs($order_total['total_price']), $memo);
		}
		//add by chenfq 2010-05-12 end
					
   		if($order_total['credit'] > 0)
		{
			$accountpay_str = getPayment($order_id,0,$order_total['credit'],'Accountpay');
			$result['accountpay_str'] = $accountpay_str;			
		}
		
		//==============add by chenfq 2011-06-29 begin=======================
		if (method_exists($payment_model,'pre_confirmation_check')){
			$accountpay_str = getPayment($order_id,0,0,'');
			$result['accountpay_str'] = $accountpay_str;			
		}
		//==============add by chenfq 2011-06-29 begin=======================		
		/*		
   		if($order_total['ecvFee'] > 0)
		{
			s_ecv_order_incharge($order_id);
			$result['ecvpay_str'] = a_L("HC_ECV_PAYMENT").$order_total['ecvFee_format'];
		}*/		
		
  		$result['status']  =  true;
  		$result['money_status'] = $GLOBALS['db']->getOne("select money_status from ".DB_PREFIX."order where id = ".$order_id);
   		if ($return_array){
   			return $result;
   		}else{
   			header("Content-Type:text/html; charset=utf-8");
   			echo json_encode($result);
			exit;   				
   		}		
	
   }
	   
   	//完成订单 add by chenfq 2011-08-05 添加 $return_array 参数
   function cart_done_2($goods_id = 0,$return_array = false)
   { 
   	    //set_time_limit(0);   		
		$result  =  array();
		$result['status']  =  false;
		$result['error'] = '';
		$result['order_id'] = 0;
		$result['accountpay_str'] = '';
		$result['ecvpay_str'] = '';
		$result['money_status'] = 0;
		$result['goods_id'] = $goods_id;
				
		$session_id = session_id();
		$user_id = intval($_SESSION['user_id']);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$user_id);
      	if(empty($user_info) || $user_info == false)
   		{   
   			$result['error'] = '请重新登陆!';			
   			if ($return_array){
   				return $result;
   			}else{
   				header("Content-Type:text/html; charset=utf-8");
   				echo json_encode($result);
				exit;   				
   			}
   		}
   				
   		$sql = "select count(*) as num from ".DB_PREFIX."cart where session_id = '".$session_id."'";		
   		if($GLOBALS['db']->getOne($sql)==0)
   		{   
   			$result['error'] = $GLOBALS['Ln']['GOODS_LOSE_PLS_SELECT_OTHER'];			
   			if ($return_array){
   				return $result;
   			}else{
   				header("Content-Type:text/html; charset=utf-8");
   				echo json_encode($result);
				exit;   				
   			}
   		}

   		//判断用户是否够积分，换积分商品
      	$sql = "select sum(data_total_score) as num from ".DB_PREFIX."cart where data_total_score < 0 and session_id = '".$session_id."'";
      	$data_total_score = $GLOBALS['db']->getOne($sql);	
   		if($data_total_score < 0)
   		{   
   			$data_total_score = abs($data_total_score);
      		$user_score = $user_info['score'];
      		if ($user_score < $data_total_score){
	   			$result['error'] =$GLOBALS['Ln']['COMMON_INFO_2']; // '积分不够，无法购物积分商品'; 			
	   			if ($return_array){
	   				return json_encode($result);
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}
      		}
   		}
   		   		
   		
		$now = a_gmtTime();
		
		$error = '';
		if (!check_goods($session_id,$user_id, $error)){
			$result['error'] = $error;
   			if ($return_array){
   				return $result;
   			}else{
   				header("Content-Type:text/html; charset=utf-8");
   				echo json_encode($result);
				exit;   				
   			}			
		}
		
		
	   	$_REQUEST['payment_id'] = trim($_REQUEST['payment_id']);
		$ilen = strpos($_REQUEST['payment_id'],'-');
		$bank_id = '';
		if ($ilen > 0){
			$bank_id = substr($_REQUEST['payment_id'],0,$ilen);
			$payment_id = substr($_REQUEST['payment_id'],$ilen + 1, strlen($_REQUEST['payment_id']) - $ilen);
		}else{
			$payment_id = intval($_REQUEST['payment_id']);
		}		
		

		//=======================add by chenfq 2011-06-29 begin========================
		$payment_info = $GLOBALS['db']->getRowCached("select id, currency, fee_type, fee, online_pay, class_name,name_1 from ".DB_PREFIX."payment where id=".$payment_id);
		$pay_file = VENDOR_PATH.'payment3/'.$payment_info['class_name'].'Payment.class.php';
		if (file_exists($pay_file)){
			require_once($pay_file);
			$payment_class = $payment_info['class_name']."Payment";
			if (class_exists($payment_class)){
				$payment_model = new $payment_class;
				if (method_exists($payment_model,'pre_confirmation_check')){
					$card_info = $payment_model->pre_confirmation_check();
					if ($card_info['result'] == false){
						$result['error'] = $card_info['error'];
			   			if ($return_array){
			   				return json_encode($result);
			   			}else{
			   				header("Content-Type:text/html; charset=utf-8");
			   				echo json_encode($result);
							exit;   				
			   			}				
					}else{
						$order['card_info'] = serialize($card_info['card_info']);
					}
				}
			}	
		}	
		

		//=======================add by chenfq 2011-06-29 end========================
						    			
		//开始获取提交的数据
		//$payment_id = intval($_REQUEST['payment_id']);
		$delivery_id = intval($_REQUEST['delivery_id']);
		$credit = floatval($_REQUEST['credit']);
		$isCreditAll = empty($_REQUEST['iscreditall']) ? 0 : 1;
		
		//提交的地区
		$region_lv1 = intval($_REQUEST['region_lv1']);
		$region_lv2 = intval($_REQUEST['region_lv2']);
		$region_lv3 = intval($_REQUEST['region_lv3']);
		$region_lv4 = intval($_REQUEST['region_lv4']);
		//保价
		$is_protect = intval($_REQUEST['is_protect']);
		//是否开票
		$tax = intval($_REQUEST['tax']);
		
		$ecvSn = trim($_REQUEST['ecv_sn']);
		$ecvPassword = trim($_REQUEST['ecv_password']);

		//add by chenfq 2011-06-16 添加代金券判断
		if (!empty($ecvSn) && trim($_REQUEST['ecv_sn'])!='' && trim($_REQUEST['ecv_password'])!=''){
			$chk = check_ecvverify_2($ecvSn,$ecvPassword);
			if ($chk['type'] == 0){
				$result['error'] = $chk['msg'];
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}		
			}			
		}
		
		//统计购物车
		$cart_total = s_countCartTotal($payment_id,$delivery_id,$is_protect,array('region_lv1'=>$region_lv1,'region_lv2'=>$region_lv2,'region_lv3'=>$region_lv3,'region_lv4'=>$region_lv4),$tax,$credit,$isCreditAll,$ecvSn,$ecvPassword);

		//print_r($cart_total); exit;
		//dump($cart_total); exit;
		
		//开始生成计单	   		
		$order['sn'] = a_toDate(a_gmtTime(),'ymdHis');
		$order['bank_id'] = $bank_id;
		$order['money_status'] = 0;
		$order['goods_status'] = 0;
		$order['status'] = 0;
		$order['create_time'] = $now;
		$order['update_time'] = $now;
		$order['promote_money'] = 0;
		$order['adm_memo'] = '';
		$order['ecv_id'] = $cart_total['ecvID'];
		$order['ecv_money'] = $cart_total['ecvFee'];
		$order['tax'] = $tax;
		$order['tax_title'] = $_REQUEST['tax_title']?htmlspecialchars($_REQUEST['tax_title'],ENT_QUOTES):'';//add by chenfq 2011-03-17 发票抬头
		$order['tax_content'] = $_REQUEST['tax_content']?htmlspecialchars($_REQUEST['tax_content'],ENT_QUOTES):'';
		$order['tax_money'] = $cart_total['tax_money'];
		$order['memo'] = $_REQUEST['memo']?htmlspecialchars($_REQUEST['memo'],ENT_QUOTES):'';	
                $order['referer'] =$GLOBALS['referer'];
		//发货日期 add by chenfq 2011-05-25
		$order['goods_send_date'] = empty($_REQUEST['goods_send_date']) ? a_gmtTime() : a_strtotime($_REQUEST['goods_send_date']);
		//订单总价
		$order['order_total_price'] = $cart_total['all_fee'] - $cart_total['discount_price'];
		
		//modify by chenfq 2010-06-02 订单总价（含支付费用等等）goods_total_price==>order_total_price
		//if($cart_total['goods_type'] == 1 && $order['order_total_price'] >= 0) 
		if($cart_total['goods_type'] == 1 || $cart_total['goods_type'] == 3) //修改by hc
		{			
			$order['zip'] = $_REQUEST['zip']?$_REQUEST['zip']:'';
		
			//配送地区
			$order['region_lv1'] = $region_lv1;
			$order['region_lv2'] = $region_lv2;
			$order['region_lv3'] = $region_lv3;
			$order['region_lv4'] = $region_lv4;		
			$order['address'] = $_REQUEST['address']?htmlspecialchars($_REQUEST['address'],ENT_QUOTES):'';
			$order['fix_phone'] = $_REQUEST['fix_phone']?htmlspecialchars($_REQUEST['fix_phone'],ENT_QUOTES):'';
			//$order['fax_phone'] = $_POST['fax_phone']?htmlspecialchars($_POST['fax_phone'],ENT_QUOTES):'';
			$order['mobile_phone'] = $_REQUEST['mobile_phone']?htmlspecialchars($_REQUEST['mobile_phone'],ENT_QUOTES):'';	   		
			//$order['qq'] = $_POST['qq']?htmlspecialchars($_POST['qq'],ENT_QUOTES):'';
			//$order['msn'] = $_POST['msn']?htmlspecialchars($_POST['msn'],ENT_QUOTES):'';
			//$order['alim'] = $_POST['alim']?htmlspecialchars($_POST['alim'],ENT_QUOTES):'';
			$order['consignee'] = $_REQUEST['consignee']?htmlspecialchars($_REQUEST['consignee'],ENT_QUOTES):'';

			$order['delivery'] = $delivery_id;
			$order['protect'] = $is_protect;
			$order['delivery_refer_order_id'] = intval($_REQUEST['delivery_refer_order_id']);
		
			$order['delivery_fee'] = $cart_total['delivery_free']==1?0:$cart_total['delivery_fee'];
			$order['protect_fee'] = $cart_total['protect_fee'];
			
			$order['order_weight'] = $cart_total['total_weight'];
			
			$order['user_id'] = $user_id;	
			
			//将相关订单配送方式保存到新单中
			$rec_order_id = intval($_REQUEST['delivery_refer_order_id']);
			$rec_order = $GLOBALS['db']->getRow("select region_lv1,region_lv2,region_lv3,region_lv4,address,fix_phone,fax_phone,mobile_phone,qq,msn,alim,zip,consignee,protect from ".DB_PREFIX."order where id=".$rec_order_id);
			if($rec_order)
			{
				$order['region_lv1'] = intval($rec_order['region_lv1']);
				$order['region_lv2'] = intval($rec_order['region_lv2']);
				$order['region_lv3'] = intval($rec_order['region_lv3']);
				$order['region_lv4'] = intval($rec_order['region_lv4']);
				
				$order['address'] = $rec_order['address']?$rec_order['address']:'';
				$order['fix_phone'] = $rec_order['fix_phone']?$rec_order['fix_phone']:'';
				$order['fax_phone'] = $rec_order['fax_phone']?$rec_order['fax_phone']:'';
				$order['mobile_phone'] = $rec_order['mobile_phone']?$rec_order['mobile_phone']:'';	   		
				$order['qq'] = $rec_order['qq']?$rec_order['qq']:'';
				$order['msn'] = $rec_order['msn']?$rec_order['msn']:'';
				$order['alim'] = $rec_order['alim']?$rec_order['alim']:'';
				$order['zip'] = $rec_order['zip']?$rec_order['zip']:'';
			
				$order['consignee'] = $rec_order['consignee']?$rec_order['consignee']:'';
	
				//$order['delivery'] = $rec_order['delivery'];
				$order['protect'] = intval($rec_order['protect']);
			}
			
			
			//保存本次收货地址到会员地址列表 add by chenfq 2010-04-21 
			//dump($order['user_id']);
			if ($order['user_id'] > 0){		
				$num_count = $GLOBALS['db']->getOne("select count(*) as num from ".DB_PREFIX."user_consignee where consignee = '".addslashes($order['consignee']).
														"' and region_lv1 = ".intval($order['region_lv1'])." and region_lv2 = ".intval($order['region_lv2'])." and region_lv3 = ".intval($order['region_lv3'])." and region_lv4 = ".intval($order['region_lv4']).
														" and address = '".addslashes($order['address'])."' and zip = '".addslashes($order['zip'])."' and mobile_phone = '".addslashes($order['mobile_phone'])."' and fix_phone = '".addslashes($order['fix_phone'])."' and user_id=".intval($order['user_id'])." limit 1");
				if ($num_count == 0){
					$sql = "insert into ".DB_PREFIX."user_consignee(user_id,consignee,region_lv1,region_lv2,region_lv3,region_lv4,address,zip,mobile_phone,fix_phone) values(".
							intval($order['user_id']).",".
							"'".addslashes($order['consignee'])."',".
							intval($order['region_lv1']).",".
							intval($order['region_lv2']).",".
							intval($order['region_lv3']).",".
							intval($order['region_lv4']).",".
							"'".addslashes($order['address'])."',".
							"'".addslashes($order['zip'])."',".
							"'".addslashes($order['mobile_phone'])."',".
							"'".addslashes($order['fix_phone'])."')";	
					$GLOBALS['db']->query($sql);		
				}				
			}			
			
		}
		else
		{
			$order['zip'] = '';
			$order['region_lv1'] = 0;
			$order['region_lv2'] = 0;
			$order['region_lv3'] = 0;
			$order['region_lv4'] = 0;
		
			$order['address'] = '';
			$order['fix_phone'] = '';
			$order['fax_phone'] = '';
			$order['mobile_phone'] = '';	   		
			$order['qq'] = '';
			$order['msn'] = '';
			$order['alim'] = '';
		
			$order['consignee'] = '';

			$order['delivery'] = 0;
			$order['protect'] = 0;
		
			$order['delivery_fee'] = 0;
			$order['protect_fee'] = 0;
			
			$order['order_weight'] = 0;
		}
		
		
		$order['email'] = $_SESSION['user_email'];
		$order['user_id'] = $user_id;	
		$order['payment'] = $payment_id;	
		$order['total_price'] = $cart_total['goods_total_price']; //商品总价
		$order['order_score'] = $cart_total['total_add_score'];  //计算订单最终产生的积分
		$order['order_referral_money'] = $cart_total['total_referral_money'];  //计算订单最终产生的返利金额
		//$card_code = D("CartCard")->where("session_id='".$session_id."' and user_id=".$user_id)->getField("card_code");
		$order['card_code'] = '';
		$order['cost_total_price'] = 0;
		$order['cost_delivery_fee'] = 0;
		$order['cost_protect_fee'] = 0;
		$order['cost_payment_fee'] = 0;
		$order['cost_other_fee'] = 0;
		$order['order_profit'] = 0;
		$order['is_paid'] = 0;
		$order['parent_id'] = intval($user_info['parent_id']);
		
		$order['promote_money'] = 0;
		$order['discount'] = $cart_total['discount_price'];
		$order['payment_fee'] = $cart_total['payment_fee'];
		
		$order['currency_id'] = 1;
		$order['currency_radio'] = 1;
		
		
		$order['order_incharge'] = 0;
		
		$order['lang_conf_id'] = FANWE_LANG_ID;
		
		if($cart_total['goods_type'] == 2)
		{
			$order['offline'] = 1;
			//$this->assign("goods_type",$cart_total['goods_type']);
			$result['goods_type'] = $cart_total['goods_type'];
		}
		
		//modify by chenfq 2010-06-02 $cart_total['total_price'] 改成：$order['order_total_price']
		//$cart_total['credit'] 余额支付， $cart_total['ecvFee'] 代金券支付
		if(($order['order_total_price'] - $cart_total['credit'] - $cart_total['ecvFee']) > 0.001)
		{
			if($GLOBALS['db']->getOneCached("select count(*) as num from ".DB_PREFIX."payment where status=1")>0&&$order['payment']==0)
			{
				$result['error'] = a_L("SELECT_PAYMENT");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
		}
		else
		{
			//modify by chenfq 2010-06-02 $cart_total['total_price'] 改成：$order['order_total_price']
			if($order['order_total_price'] == 0)
			{
				//会员直接使用：余额支付或代金券支付
				$order['payment_fee'] = 0;
				$order['currency_radio'] = 0;
				$order['payment'] = 0;
			}
			else
			{
				$order['payment'] = 0;
				$order['payment_fee'] = 0;
				if($cart_total['credit'] <> 0) //modify by chenfq 2010-05-12  $cart_total['credit'] > 0 ==> $cart_total['credit'] <> 0
				{
					$order['payment'] = $GLOBALS['db']->getOneCached("select id from ".DB_PREFIX."payment where class_name = 'Accountpay'");
					$order['currency_id'] = 1;
					$order['currency_radio'] = 1;
				}
				
				if(!$order['currency_radio'])
					$order['currency_radio'] = 0;
			}
		}
		
		$delivery_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."delivery where id = ".intval($delivery_id));
		
		//is_inquiry 1：免运费；0：需要运费; is_smzq: 1：上门自取
		//modify by chenfq 2010-06-02 $cart_total['goods_total_price'] 改成：$order['order_total_price']
		if(($cart_total['goods_type'] == 1 || $cart_total['goods_type'] == 3) && $cart_total['order_total_price'] > 0 && intval($delivery_info['is_smzq']) == 0)// add by chenfq 2010-05-17  && $cart_total['goods_total_price'] > 0
		{
			
			if($GLOBALS['db']->getOneCached("select count(*) as num from ".DB_PREFIX."delivery where status=1")>0&&$order['delivery']==0)
			{
				$result['error'] = a_L("SELECT_DELIVERY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			//开始验证是否支持保价
			if($is_protect==1 && $GLOBALS['db']->getOneCached("select protect from ".DB_PREFIX."delivery where id=".intval($delivery_id))==0)
			{
				$result['error'] = a_L("PROTECT_NOT_SUPPORT");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			if($order['consignee']=="")
			{
				$result['error'] = a_L("CONSIGNEE_EMPTY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}					
			}
			if($order['email']=="")
			{
				$result['error'] = a_L("EMAIL_EMPTY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			if(!preg_match("/\w+@\w+\.\w{2,}\b/",$order['email']))
			{
				$result['error'] = a_L("EMAIL_FORMAT_ERROR");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}				
			}
			if($order['zip']=="")
			{
				$result['error'] = a_L("ZIP_EMPTY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}					
			}
			if($order['address']=="")
			{
				$result['error'] = a_L("ADDRESS_EMPTY");  		
	   			if ($return_array){
	   				return $result;
	   			}else{
	   				header("Content-Type:text/html; charset=utf-8");
	   				echo json_encode($result);
					exit;   				
	   			}					
			}
			
		}
		else
		{
			/**
			 * modify by chenfq 2010-06-03
			 * 修改订单都为：无需配送 bug 状态
			 * $order['goods_status'] = 5; 
			 */
			if($cart_total['goods_type'] == 0 || $cart_total['goods_type'] == 2)
				$order['goods_status'] = 5;  //团购券商品改为5
		}
		if(isset($_REQUEST['user_mobile_phone'])&& $_REQUEST['user_mobile_phone']!='')
		{
			if(a_fanweC("SMS_SEND_OTHER")==1)
				$order['mobile_phone_sms'] = $_REQUEST['user_mobile_phone'];	//发送给别人
			else 
			{
				if($GLOBALS['db']->getOne("select count(*) as num from ".DB_PREFIX."user where mobile_phone='".addslashes($_REQUEST['user_mobile_phone'])."' and id <>".$user_id." and status = 1") > 0)
				{
					$result['error'] = a_L("HC_MOBILE_NUMBER_EXISTS");  		
		   			if ($return_array){
		   				return $result;
		   			}else{
		   				header("Content-Type:text/html; charset=utf-8");
		   				echo json_encode($result);
						exit;   				
		   			}					
				}
			}
		}
		
  		 if(isset($_REQUEST['user_email'])&& $_REQUEST['user_email']!='')
		{
			$order['user_email'] = $_REQUEST['user_email'];	//发送给别人
		}
			
	    /* 插入订单表 */
		$do_count = 0;
	    do
	    {
	        $order['sn'] = a_toDate(a_gmtTime(),'ymdHis').rand(0,9);
	        if ($GLOBALS['db']->autoExecute(DB_PREFIX."order", addslashes_deep($order), 'INSERT'))
	        {
	            break;
	        }
	        else
	        {
	            if ($GLOBALS['db']->errno() != 1062)
	            {
					$result['error'] = $GLOBALS['db']->errorMsg();  		
		   			if ($return_array){
		   				return $result;
		   			}else{
		   				header("Content-Type:text/html; charset=utf-8");
		   				echo json_encode($result);
						exit;   				
		   			}	                
	            }
	        }
	        $do_count = $do_count + 1;
	    }
	    while ($do_count < 10); // 防止订单号重复
	    
	    if ($do_count >= 10){
	    	$result['error'] = a_L('DATABASE_ERR_1'); 
   			if ($return_array){
   				return $result;
   			}else{
   				header("Content-Type:text/html; charset=utf-8");
   				echo json_encode($result);
				exit;   				
   			}	    	
	    }
	    
		$order_id = intval($GLOBALS['db']->insert_id());
	    $order['id'] = $order_id;
		$result['order_id'] = $order_id;
		
		if($order_id > 0) //提交成功后提交订单商品
		{			
			if (!empty($_REQUEST['user_mobile_phone'])&&a_fanweC("SMS_SEND_OTHER")==0){
				$sql = "update ".DB_PREFIX."user set mobile_phone ='".addslashes($_REQUEST['user_mobile_phone'])."' where id = ".intval($user_id);
				$GLOBALS['db']->query($sql);
			}
			
			$sql = "select * from ".DB_PREFIX."cart where session_id = '".$session_id."' and user_id=".$user_id;
			$list = $GLOBALS['db']->getAll($sql);
                        
                        
			foreach($list as $cart_item)
			{
                                $profit = $GLOBALS['db']->getOne("select profit from ".DB_PREFIX."goods where id=".$cart_item['rec_id']);
				$order_goods = array();
				$order_goods['pid'] = 0;
				$order_goods['user_id'] = $user_id;
				$order_goods['order_id'] = $order_id;
				$order_goods['rec_module'] = $cart_item['rec_module'];
				$order_goods['rec_id'] = $cart_item['rec_id'];
				$order_goods['data_name'] = $cart_item['data_name'];
				$order_goods['data_sn'] = $cart_item['data_sn'];
				$order_goods['data_score'] = $cart_item['data_score'];
				$order_goods['data_total_score'] = $cart_item['data_total_score'];
				$order_goods['data_price'] = $cart_item['data_unit_price'];
				$order_goods['data_total_price'] = $cart_item['data_total_price'];
				$order_goods['data_score'] = $cart_item['data_promote_score'];
				$order_goods['data_total_score'] = $cart_item['data_total_score'];
				$order_goods['data_score'] = $cart_item['data_score'];
				$order_goods['attr'] = $cart_item['attr'];
				$order_goods['number'] = $cart_item['number'];
				$order_goods['is_inquiry'] = $cart_item['is_inquiry'];
				$order_goods['create_time'] = $now;
				$order_goods['status'] = 0;
				$order_goods['data_weight'] = $cart_item['data_weight'];	
                                $order_goods['balance_unit_price'] = $profit;
                                $order_goods['balance_total_price'] = $profit * $cart_item['number'];
				//商品购买返现 add by chenfq 2011-03-04
				$order_goods['data_total_referral_money'] = $cart_item['data_total_referral_money'];
				$GLOBALS['db']->autoExecute(DB_PREFIX."order_goods", addslashes_deep($order_goods), 'INSERT');
			}
			
					//修改 by hc 当代金券金额大于1时不更新状态，表示可以继续使用。
			if (intval($order['ecv_id']) > 0){
				$ecvData = $GLOBALS['db']->getRow("select use_count,order_sn,goods_id,use_user_id,use_date_time from ".DB_PREFIX."ecv where id = ".intval($order['ecv_id']));
				if($ecvData['use_count']==1)
				{
					$ecvData['order_sn'] = $order['sn'];
					$ecvData['goods_id'] = $cart_item['rec_id'];
					$ecvData['use_user_id'] = intval($_SESSION['user_id']);
					$ecvData['use_date_time'] = $now;
					$ecvData['use_count'] = $ecvData['use_count'] - 1;
				}
				else
				{
					$ecvData['use_count'] = $ecvData['use_count'] - 1;
				}
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."ecv", addslashes_deep($ecvData), 'UPDATE', "id = ".intval($order['ecv_id']));
			}

			if($order['order_total_price'] <= 0)
				s_order_incharge_handle($order, 0.0, false);

	

			//add by chenfq 2010-05-12 begin	
			if($order['order_total_price'] < 0 && intval($_SESSION['user_id']) > 0)
			{
				//记录会员预存款变化明细
				//$memo 格式为 #LANG_KEY#memos  ##之间所包含的是语言包的变量
				$memo = $order['sn'].'订单冲值';
				s_user_money_log(intval($_SESSION['user_id']), $order_id, 'UserIncharge', abs($order['order_total_price']), $memo);
			} 	
			//add by chenfq 2010-05-12 end
		}
			
		$sql = "delete from ".DB_PREFIX."cart where session_id ='".$session_id."'";
		$GLOBALS['db']->query($sql);		
		
		//$this->assign("order_info",a_L("ORDER_SUBMIT_SUCCESS")." [&nbsp;&nbsp;".a_L("ORDER_SN")."：<span class='red'>".$order['sn']."</span>&nbsp;&nbsp;]");
				
		if($cart_total['credit'] > 0)//使用余额支付
		{
			$accountpay_str = getPayment($order_id,0,$cart_total['credit'],'Accountpay');
			//$this->assign("accountpay_str",a_L("HC_BALANCE").$accountpay_str);
			$result['accountpay_str'] = $accountpay_str;
		}

		
		if($cart_total['ecvFee'] > 0)
		{
			s_ecv_order_incharge($order_id);
			//$this->assign("ecvpay_str",a_L("HC_ECV_PAYMENT").$cart_total['ecvFee_format']);
			$result['ecvpay_str'] = a_L("HC_ECV_PAYMENT").$cart_total['ecvFee_format'];
		}

	//==============add by chenfq 2011-06-29 begin=======================
		if (method_exists($payment_model,'pre_confirmation_check')){
			$accountpay_str = getPayment($order_id,0,0,'');
			$result['accountpay_str'] = $accountpay_str;			
		}
		//==============add by chenfq 2011-06-29 begin=======================		

  		$result['status']  =  true;
  		$result['money_status'] = $GLOBALS['db']->getOne("select money_status from ".DB_PREFIX."order where id = ".$order_id);
  		$result['ecvpay_str'] = urlencode($result['ecvpay_str']);
  		$result['accountpay_str'] = urlencode($result['accountpay_str']);
     	
   		if ($return_array){
   			return $result;
   		}else{
	  		header("Content-Type:text/html; charset=utf-8");
	  		if ($goods_id >0){
	  			return $result;
	  		}else{
		  		echo json_encode($result);		
				exit;  			
	  		} 				
   		}     	
   }

   function loadDelivery_2($id,$status=true)
   {
   	
	   	$delivery = array();
	   	if ($status){
	   		$delivery_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."delivery where status = 1");
	   	}else{
	   		$delivery_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."delivery");
	   	}
	   	
	   	foreach($delivery_list as $v)
	   	{
	   		$region_count = $GLOBALS['db']->getOneCached("select count(*) from ".DB_PREFIX."delivery_region where delivery_id = ".$v['id']);
	   		if($v['allow_default'] == 1&&$region_count==0)
	   		{
	   			//允许默认
	   			array_push($delivery,$v['id']);
	   		}
	   		else
	   		{
	   			$delivery_region = $GLOBALS['db']->getAllCached("select id,region_ids,first_price,continue_price,allow_cod,delivery_id from ".DB_PREFIX."delivery_region where delivery_id = ".$v['id']);
	   
	   			$tag = true; //是否未查询到
	   			foreach($delivery_region as $vv)
	   			{
	   				$region_ids = explode(",",$vv['region_ids']);
	   				$tmp_id = $id;
	   
	   				while(intval($GLOBALS['db']->getOneCached("select region_level from ".DB_PREFIX."region_conf where id = ".$tmp_id))>0)
	   				{
	   					if(in_array($tmp_id,$region_ids))
	   					{
	   						array_push($delivery,$v['id']);
	   						$tag = false;
	   						break;
	   					}
	   					else
	   					{
	   						$tmp_id = intval($GLOBALS['db']->getOneCached("select pid from ".DB_PREFIX."region_conf where id = ".$tmp_id));
	   					}
	   				}
	   
	   			}
	   			if($tag)
	   			{
	   				if($v['allow_default'] == 1)
	   				{
	   					//允许默认
	   					array_push($delivery,$v['id']);
	   				}
	   			}
	   		}
   		}
   		return $delivery;
   		
   }

   function check_ecvverify_2($sn,$password){
   
	   	if (intval ( $_SESSION ['user_id'] ) < 1) {
	   		return array ("type" => 0, "msg" => a_L ( 'PLEASE_LOGIN' ), "ecv" => "" );
	   	}
	   	$sn = addslashes($sn);
	   	$password = addslashes($password);
	   	$result = array ("type" => 0, "msg" => "", "ecv" => "" );
	   	$ecv = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "ecv where sn='{$sn}' and password='{$password}' and type=0" );
	   	$ecv ['ecvType'] = $GLOBALS ['db']->getRowCached ( "select `money`,`use_start_date`,`use_end_date`,`status`,use_count from " . DB_PREFIX . "ecv_type where id='{$ecv['ecv_type']}'" );
	   	if ($ecv) {
	   		//计算会员，已经获得的同类代金券数量 add by chenfq 2011-03-09
	   		$sql = "select count(*) from " . DB_PREFIX . "ecv where id <> ".intval($ecv['id'])." and use_user_id = ".intval ( $_SESSION ['user_id'] )." and ecv_type =" .intval ( $ecv['ecv_type'] );
	   		$use_count = intval($GLOBALS ['db']->getOne($sql));
	   		if ($ecv['ecvType']['use_count'] <= $use_count && intval($ecv['user_id']) != intval($_SESSION ['user_id'])){
	   			//
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
	   
	   	return $result;
   }   
?>
