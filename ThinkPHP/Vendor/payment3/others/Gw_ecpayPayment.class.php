<?php
// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = 'Gw_ecpay';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '進行線上信用卡付款';

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
class Gw_ecpayPayment implements Payment  {
	public $config = array(
	    'gw_ecpay_account'=>'3',
        'gw_ecpay_checkcode'=>'94499380',
		'gw_ecpay_language'=>'0',
	);	
		
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
	    $PaymentLog = M("PaymentLog");
    	$payment_log_vo = $PaymentLog->getById ($payment_log_id);
    	
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);

		$c_mid		= trim($payment_info['config']['gw_ecpay_account']); 
		//$c_order	= $order['order_sn'];
		$c_order	= $payment_log_id;
		
    	if ($payment_log_vo['rec_module'] == 'Order'){//订单
    		$order_vo = M("Order")->getById($payment_log_vo['rec_id']);
			$c_name		= trim($order_vo['consignee']);		
			$c_address	= trim($order_vo['address']);	
			$c_tel		= trim($order_vo['mobile_phone']);	
			//$c_post		= trim($order_vo['zip']);
			$c_email	= trim($order_vo['email']); 		
    	}elseif ($payment_log_vo['rec_module'] == 'UserIncharge'){//在线冲值
    		$vo = M("UserIncharge")->getById($payment_log_vo['rec_id']);

			$sql = "select max(id) as maxid from ".C("DB_PREFIX")."user_consignee where user_id = ".intval($vo['user_id']);
			$tmp = M()->query($sql);
			$consignee_id = intval($tmp[0]['maxid']);
    		$consignee_info = D("UserConsignee")->getConsigneeItem($consignee_id);
    	    		
			$c_name		= trim($consignee_info['consignee']);		
			$c_address	= trim($consignee_info['address']);	
			$c_tel		= trim($consignee_info['tel']);	
			//$c_post		= trim($consignee_info['zip']);
			$c_email	= trim($consignee_info['email']);
    	}	

		$c_orderamount = trim(intval($money)); //只能使用整数支付
		$c_ymd		= date('Ymd',time());
		//$c_moneytype= "0";
		//$c_retflag	= "1";
		$c_returl	= 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Gw_ecpay';
		//$notifytype	= "0";
		//$c_language	= $payment_info['config']['gw_ecpay_language'];

		//$srcStr = $c_mid . $c_order . $c_orderamount . $c_ymd . $c_moneytype . $c_retflag . $c_returl . $c_paygate . $c_memo1 . $c_memo2 . $notifytype . $c_language . $c_pass;
		//$c_signstr	= md5($srcStr);

		$def_url  = '<br /><form style="text-align:center;" method=post action="https://ecpay.com.tw/form_Sc_to5.php">';
		$def_url .= "<input type='hidden' name='client' value='".$c_mid."'>";
		$def_url .= "<input type='hidden' name='act' value='auth'>";
		$def_url .= "<input type='hidden' name='od_sob' value='".$c_order."'>";
		$def_url .= "<input type='hidden' name='c_name' value='".$c_name."'>";
		$def_url .= "<input type='hidden' name='c_address' value='".$c_address."'>";
		$def_url .= "<input type='hidden' name='c_tel' value='".$c_tel."'>";
		//$def_url .= "<input type='hidden' name='c_post' value='".$c_post."'>";
		$def_url .= "<input type='hidden' name='email' value='".$c_email."'>";
		$def_url .= "<input type='hidden' name='amount' value='".$c_orderamount."'>";
		$def_url .= "<input type='hidden' name='c_ymd' value='".$c_ymd."'>";
		//$def_url .= "<input type='hidden' name='c_moneytype' value='".$c_moneytype."'>";
		//$def_url .= "<input type='hidden' name='c_retflag' value='".$c_retflag."'>";
		$def_url .= "<input type='hidden' name='roturl' value='".$c_returl."'>";
		//$def_url .= "<input type='hidden' name='c_language' value='".$c_language."'>";
		//$def_url .= "<input type='hidden' name='c_memo1' value='".$c_memo1."'>";
		//$def_url .= "<input type='hidden' name='c_memo2' value='".$c_memo2."'>";
		//$def_url .= "<input type='hidden' name='notifytype' value='".$notifytype."'>";
		//$def_url .= "<input type='hidden' name='c_signstr' value='".$c_signstr."'>";
		$def_url .= "<input type='submit' class='paybutton' value='進行線上信用卡付款'>";
        $def_url .= "</form><br />";
        return $def_url;        
	}
	
	public function dealResult($get,$post,$request)
	{
		
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
				
		//dump($post);
		//dump($request);
		if($post['succ']=='1') { $post['c_succmark']='Y'; }
		if($post['succ']=='0') { $post['c_succmark']='N'; }
    	
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where class_name='Gw_ecpay'");  
    	$payment['config'] = unserialize($payment['config']);
    	    	
		//print_r($_REQUEST);
		//$c_mid			= $post['c_mid'];		
		$c_order		= $post['od_sob'];		//訂單編號
		$c_orderamount	= $post['amount'];//商户提供的订单总金额，
		//$c_ymd			= $post['process_date'];		//商户传输过来的订单产生日期，格式为"yyyymmdd"，如20050102
		//$c_transnum		= $post['gwsr'];	//云网支付网关提供的该笔订单的交易流水号，
		//$c_succmark		= $post['c_succmark'];	//交易成功标志，Y-成功 N-失败			
		//$c_moneytype	= $post['c_moneytype'];	//支付币种，0为人民币
		//$c_cause		= $post['response_msg]'];		//如果订单支付失败，则该值代表失败原因		
		//$c_memo1		= $post['c_memo1'];		//商户提供的需要在支付结果通知中转发的商户参数一
		//$c_memo2		= $post['c_memo2'];		//商户提供的需要在支付结果通知中转发的商户参数二
		//$c_signstr		= $post['inspect'];	//云网支付网关对已上信息进行MD5加密后的字
		$c_checkcode	= trim($payment['config']['gw_ecpay_checkcode']);
		
        //开始初始化参数
        $payment_log_id = $c_order;
    	$money = $c_orderamount;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency']; 

    	
		function gwSpcheck($s,$U) { //算出認證用的字串
				$a = substr($U,0,1).substr($U,2,1).substr($U,4,1); //取出檢查碼的跳字組合 1,3,5 字元
				$b = substr($U,1,1).substr($U,3,1).substr($U,5,1); //取出檢查碼的跳字組合 2,4,6 字元
				$c = ( $s % $U ) + $s + $a + $b; //取餘數 + 檢查碼 + 奇位跳字組合 + 偶位跳字組合
				return $c; 
		}
	
		$TOkSi = $post['process_time'] + $post['gwsr'] + $post['amount'];
		$my_spcheck = gwSpcheck($c_checkcode,$TOkSi); 
	
		if($my_spcheck!= $post['spcheck']  || $post['succ']!='1' ){
	       $return_res['info'] = a_L("VALID_ERROR");
	       return $return_res;
		} else {
           return s_order_paid($payment_log_id,$money,$payment_id,$currency_id);
    	}
	}	
}
?>