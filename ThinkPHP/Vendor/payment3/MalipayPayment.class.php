<?php
/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = 'Malipay';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '支付宝手机安全支付';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '2.0';

    /* 插件的作者 */
    $modules[$i]['author']  = 'FANWE R&D TEAM';

    /* 支付方式：1：在线支付；0：线下支付 */
    $modules[$i]['online_pay'] = '1';
        
    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.fanwe.com';

    return;
}


require_once(VENDOR_PATH.'payment3/Payment.class.php');
class MalipayPayment implements Payment
{
	public $config = array(
		malipay_partner => '',//合作商户ID
		malipay_seller => '',//账户ID
		//malipay_rsa_private => '',//商户(RSA)私钥
		malipay_rsa_alipay_public => '',//支付宝(RSA)公钥	
	);
		
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{		
		
		$money = round($money,2);
		
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		
		
		/*
		//合作商户ID。用签约支付宝账号登录ms.alipay.com后，在账户信息页面获取。
		public static final String PARTNER = "2088501953685772";
		//账户ID。用签约支付宝账号登录ms.alipay.com后，在账户信息页面获取。
		public static final String SELLER = "2088501953685772";
		//私钥: genrsa -out d:\openssl\prv.pem 1024
		//公钥: rsa -in d:\openssl\prv.pem -pubout -out d:\openssl\pub.pem 要上传到 ms.alipay.com 上
		//openssl pkcs8 -topk8 -inform PEM -in d:\openssl\prv.pem -outform PEM -nocrypt
		//商户（RSA）私钥
		public static final String RSA_PRIVATE = "";
		//支付宝（RSA）公钥  用签约支付宝账号登录ms.alipay.com后，在密钥管理页面获取。
		public static final String RSA_ALIPAY_PUBLIC = "";
		
		$malipay['PARTNER'] = "";//合作商户ID。用签约支付宝账号登录ms.alipay.com后，在账户信息页面获取。
		$malipay['SELLER'] = "";//账户ID。用签约支付宝账号登录ms.alipay.com后，在账户信息页面获取。
		//商户（RSA）私钥
		$malipay['RSA_PRIVATE'] = "";
		//支付宝（RSA）公钥  用签约支付宝账号登录ms.alipay.com后，在密钥管理页面获取。
		$malipay['RSA_ALIPAY_PUBLIC'] = "";
*/
		
		$sql = "select a.data_name, a.attr, a.number ".
						  "from ".DB_PREFIX."order_goods a ".
						  "left outer join ".DB_PREFIX."goods b on b.id = a.rec_id ".
						 "where a.order_id =". intval($payment_log['rec_id']);
		$order_goods_list = $GLOBALS['db']->getAll($sql);
		foreach($order_goods_list as $k => $goods){
			$index = intval($k) + 1;
			if (empty($goods['attr'])){
				$body .= $index ."、".$goods['data_name'].';数量:'.$goods['number'].';';
			}else{
				$body .= $index ."、".$goods['data_name'].'('.$goods['attr'].') 数量:'.$goods['number'].';';
			}
		}
		
		if (strlen($body) > 1000){
			$body = a_msubstr($body, 0, 1000);
		}	
		
		if (strlen($body) > 50){
			$subject = a_msubstr($body, 0, 50);
		}else{
			$subject = $body;
		}
		

				
		$root_path = str_replace( "/mapi", "", dirname(__ROOT__));
		
		$pay = array();
		$pay['subject'] = $subject;
		$pay['body'] = $body;
		$pay['total_fee'] = $money;
		$pay['total_fee_format'] = a_formatPrice($money);
		$pay['out_trade_no'] = $payment_log_id;
		$pay['notify_url'] = 'http://'.$_SERVER['HTTP_HOST'].$root_path.'/index.php?m=Payment&a=response&payment_name=Malipay';
		$pay['notify_url'] = str_replace("\\","",$pay['notify_url']);
		
		$pay['partner'] = $payment_info['config']['malipay_partner'];//合作商户ID
		$pay['seller'] = $payment_info['config']['malipay_seller'];//账户ID
		//$pay['rsa_private'] = $payment_info['config']['malipay_rsa_private'];//商户(RSA)私钥
		//$pay['rsa_alipay_public'] = $payment_info['config']['malipay_rsa_alipay_public'];//支付宝(RSA)公钥
		
		
		$pay['pay_code'] = 'malipay';//,支付宝;mtenpay,财付通;mcod,货到付款
			
		$order_spec = '';
		$order_spec .= 'partner="'.$pay['partner'].'"';//合作商户ID
		$order_spec .= '&seller="'.$pay['seller'].'"';//账户ID
		$order_spec .= '&out_trade_no="'.$pay['out_trade_no'].'"';
		$order_spec .= '&subject="'.$pay['subject'].'"';
		$order_spec .= '&body="'.$pay['body'].'"';
		$order_spec .= '&total_fee="'.$pay['total_fee'].'"';
		$order_spec .= '&notify_url="'.$pay['notify_url'].'"';
		
		
		$pay['order_spec'] = $order_spec;
		$sign = $this->sign($order_spec);
		$pay['sign'] = urlencode($sign);
		$pay['sign_type'] = 'RSA';
		
		return $pay;
	}
	
	public function dealResult($get,$post,$request)
	{
		/**
		 * 4.1     服务器通知服务 

		通知参数：notify_data,sign 
		
		签名原始字符串： 
		notify_data=<notify> 
		    <trade_status>TRADE_FINISHED</trade_status> 
		    <total_fee>25.00</total_fee> 
		    <subject>product24</subject> 
		    <out_trade_no>500000020113134</out_trade_no> 
		    <notify_reg_time>2010-09-20 15:26:51.000</notify_reg_time> 
		    <trade_no>2010092000164773</trade_no> 
		</notify> 
		
		签名结果： 
		sign=590e7b2b1faf573847008d0234992066 
		
		TRADE_FINISHED 表示交易成功； 
		WAIT_BUYER_PAY 等待买家付款。 

		 */
		require_once(VENDOR_PATH.'payment3/classes/xml.php');
		$sign = $_REQUEST['sign'];
		$notify_data = $_REQUEST['notify_data'];
		$config_str = $sign.";notify_data=".$notify_data;
		
		//file_put_contents(VENDOR_PATH."/payment3/ealipay_".date("Y-m-d H:i:s").".txt",$config_str);
		
		$para_data = @XML_unserialize($notify_data);
		$payment_log_id = intval($para_data['notify']['out_trade_no']);
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
        $payment_id = intval($payment_id);
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
		
    	$payment['config'] = unserialize($payment['config']);
    	$pubkey = $payment['config']['malipay_rsa_alipay_public'];
    					
		$pubkey = $this->getPublicKeyFromX509($pubkey);
		
		$res = openssl_pkey_get_public($pubkey);
		
		$sign = base64_decode($sign);
		$verify = openssl_verify("notify_data=".$notify_data, $sign, $res);
		if ($verify == 1)
		{
			$trade_status = $para_data['notify']['trade_status'];
			
	    	$money = $para_data['notify']['total_fee'];
	    	$payment_id = $payment['id'];
	    	$currency_id = $payment['currency']; 
			$pay_back_code = $para_data['notify']['trade_no'];
		
			if ($trade_status == 'TRADE_SUCCESS' || $trade_status == 'TRADE_FINISHED' || $trade_status == 'WAIT_SELLER_SEND_GOODS'){
			   $result = s_order_paid($payment_log_id,$money,$payment_id,$currency_id,$pay_back_code);
			   if ($result['status'] == true){
			   	  echo "success";
			   }else{
			   	  echo "fail";
			   }
			}else{
			   echo "fail";
			} 			
		}else{
		    echo "fail";
		}		
		exit; 
	}
	
	function getPublicKeyFromX509($certificate)  
	{  
	    $publicKeyString = "-----BEGIN PUBLIC KEY-----\n" .  
	          wordwrap($certificate, 64, "\n", true) .  
	          "\n-----END PUBLIC KEY-----";     
	    return $publicKeyString;  
	}	
	
	/**RSA签名
	 * $data待签名数据
	 * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
	 * 最后的签名，需要用base64编码
	 * return Sign签名
	 */
	function sign($data) {
		//读取私钥文件
		$priKey = file_get_contents(ROOT_PATH.'/mapi/key/rsa_private_key.pem');
		//print_r($priKey); exit;
		//转换为openssl密钥，必须是没有经过pkcs8转换的私钥
		$res = openssl_get_privatekey($priKey);

		//调用openssl内置签名方法，生成签名$sign
		openssl_sign($data, $sign, $res);

		//释放资源
		openssl_free_key($res);
		
		//base64编码
		$sign = base64_encode($sign);
		return $sign;
	}		
}
?>