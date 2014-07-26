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
    $modules[$i]['code']    = 'NBepay';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'NBepay';

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

// 贝宝支付模型
require_once(VENDOR_PATH.'payment3/Payment.class.php');
class NBepayPayment implements Payment {
	public $config = array(
	    'nbepay_MerchantID'=>'',
        'nbepay_verifyKey'=>'',
		'nbepay_currency'=>'',
		'nbepay_country'=>''
	);	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		$data_return_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=NBepay';


		
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		
		if($payment_log['rec_module']=='Order'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
			$user_id = $GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
			$user_id = $GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
		}		
		
		$user_info = $GLOBALS['db']->getRow("select id,user_name,email,mobile_phone from ".DB_PREFIX."user where id=".intval($user_id));
		$code = md5($money.$payment_info['config']['nbepay_MerchantID'].$payment_log_id.$payment_info['config']['nbepay_verifyKey']);

		
		$url ="amount=".$money."&";
		$url .="orderid=".$payment_log_id."&";
		$url .="bill_name=".$user_info['user_name']."&";
		$url .="bill_email=".$user_info['email']."&";
		$url .="bill_mobile=".$user_info['mobile_phone']."&";
		$url .="bill_desc=".$data_sn."&";
		$url .="cur=".strtoupper($payment_info['config']['nbepay_currency'])."&";
		$url .="returnurl=".urlencode($data_return_url)."&";
		$url .="vcode=".$code."&";
		$url .="country=".$payment_info['config']['nbepay_country'];
		
		//定义 各接口的URL
		//VISA & MASTERCARD CREDIT CARD (Credit Payment)
		$VISA = "https://www.onlinepayment.com.my/NBepay/pay/".$payment_info['config']['nbepay_MerchantID']."/?";
		//MAYBANK2U FUND TRANSFER (Debit Payment))
		$MAYBANK2U = "https://www.onlinepayment.com.my/NBepay/pay/".$payment_info['config']['nbepay_MerchantID']."/maybank2u.php?";
		//MOBILE MONEY (Credit Payment)
		$MOBILE = "https://www.onlinepayment.com.my/NBepay/pay/".$payment_info['config']['nbepay_MerchantID']."/mobilemoney.php?";
		//POSPAY (Debit Payment)
		$POSPAY = "https://www.onlinepayment.com.my/NBepay/pay/".$payment_info['config']['nbepay_MerchantID']."/pospay.php?";
		//MEPS FPX (Debit Payment from internet banking: PBeBank, Hong Leong Bank, Bank Islam, CIMBClick, Maybank2e, Maybank2u)
		$MEPS = "https://www.onlinepayment.com.my/NBepay/pay/".$payment_info['config']['nbepay_MerchantID']."/fpx.php?";
		//AmBank Online / AmOnline (Debit Payment)
		$AMBANK = "https://www.onlinepayment.com.my/NBepay/pay/".$payment_info['config']['nbepay_MerchantID']."/amb.php?";
		//Alliance Online / iBayar (Debit Payment)
		$ALLIANCE = "https://www.onlinepayment.com.my/NBepay/pay/".$payment_info['config']['nbepay_MerchantID']."/alb.php?";
		//WEBCASH (Debit Payment)
		$WEBCASH = "https://www.onlinepayment.com.my/NBepay/pay/".$payment_info['config']['nbepay_MerchantID']."/webcash.php?";
		//RHB Online (Debit Payment)
		$RHB = "https://www.onlinepayment.com.my/NBepay/addPayment/rhb.php?merchantID=".$payment_info['config']['nbepay_MerchantID']."&";
		//EON Bank Online (Debit Payment)
		$EONBANK = "https://www.onlinepayment.com.my/NBepay/addPayment/eon.php?merchantID=".$payment_info['config']['nbepay_MerchantID']."&";
		//Mepscash Online (Debit Payment)
		$MEPSCASH = "https://www.onlinepayment.com.my/NBepay/addPayment/mepscash.php?merchantID=".$payment_info['config']['nbepay_MerchantID']."&";
		//China online banking (Debit Payment in RMB)
		$CHINABANK = "https://www.onlinepayment.com.my/NBepay/addPayment/paymentasia.php?merchantID=".$payment_info['config']['nbepay_MerchantID']."&";
		
		
		
		$def_url = "<script type='text/javascript'>".
				   "var urlIdx = {".
				   "'VISA':'".$VISA."',".
				   "'MAYBANK2U':'".$MAYBANK2U."',".
				   "'MOBILE':'".$MOBILE."',".
				   "'POSPAY':'".$POSPAY."',".
		           "'MEPS':'".$MEPS."',".
		           "'AMBANK':'".$AMBANK."',".
		           "'ALLIANCE':'".$ALLIANCE."',".
		           "'WEBCASH':'".$WEBCASH."',".
				   "'RHB':'".$RHB."',".
		           "'EONBANK':'".$EONBANK."',".
				   "'MEPSCASH':'".$MEPSCASH."',".
				   "'CHINABANK':'".$CHINABANK."'".
				   "};".
				   "function goPay(){ var KEY = $(\"input[name='GATEWAY']:checked\").val();  window.open(urlIdx[KEY]+'".$url."');}".
				   "</script>";
		$def_url .="<style type='text/css'>.gateWay{ float:left; display:block; padding:5px; width:500px; overflow:hidden; text-align:left; padding-left:100px; font-family:verdana; font-size:12px; font-weight:bold;}</style>";
		$def_url .= "<label class='gateWay'>VISA & MASTERCARD CREDIT CARD (Credit Payment):<input type='radio' name='GATEWAY' value='VISA' checked /></label>"
				   ."<label class='gateWay'>MAYBANK2U FUND TRANSFER (Debit Payment)):<input type='radio' name='GATEWAY' value='MAYBANK2U' /></label>"
				   ."<label class='gateWay'>MOBILE MONEY (Credit Payment):<input type='radio' name='GATEWAY' value='MOBILE' /></label>"
				   ."<label class='gateWay'>POSPAY (Debit Payment):<input type='radio' name='GATEWAY' value='POSPAY' /></label>"
				   ."<label class='gateWay'>MEPS FPX (Debit Payment from internet banking: PBeBank, Hong Leong Bank, Bank Islam, CIMBClick, Maybank2e, Maybank2u):<input type='radio' name='GATEWAY' value='MEPS' /></label>"
				   ."<label class='gateWay'>AmBank Online / AmOnline (Debit Payment):<input type='radio' name='GATEWAY' value='AMBANK' /></label>"
				   ."<label class='gateWay'>Alliance Online / iBayar (Debit Payment):<input type='radio' name='GATEWAY' value='ALLIANCE' /></label>"
				   ."<label class='gateWay'>WEBCASH (Debit Payment):<input type='radio' name='GATEWAY' value='WEBCASH' /></label>"
				   ."<label class='gateWay'>RHB Online (Debit Payment):<input type='radio' name='GATEWAY' value='RHB' /></label>"
				   ."<label class='gateWay'>EON Bank Online (Debit Payment):<input type='radio' name='GATEWAY' value='EONBANK' /></label>"
				   ."<label class='gateWay'>Mepscash Online (Debit Payment):<input type='radio' name='GATEWAY' value='MEPSCASH' /></label>"
				   ."<label class='gateWay'>China online banking (Debit Payment in RMB):<input type='radio' name='GATEWAY' value='CHINABANK' /></label>"
				   ."<div class='blank'></div>"				   
				   ;
		
		
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
        $def_url .= "<a href='javascript:;' onclick='goPay();' ><input type='submit' class='paybutton' value='Directing to Payment' /></a>";                      // 按钮
		$def_url .="<br /><br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_fanweC('BASE_CURRENCY_UNIT')." ".number_format($money,2)."</span>";
		return $def_url;
	}
	
	public function dealResult($get,$post,$request)
	{
		if (!empty($post))
        {
            foreach($post as $key => $data)
            {
                $get[$key] = $data;
            }
        }
        $orderid =$get['orderid'];
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($orderid));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
    	        
		//$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where class_name='NBepay'");  
    	//$payment['config'] = unserialize($payment['config']);
    	    	
		$vkey=$payment['config']['nbepay_verifyKey'];  //密钥
		//------ below don't change ---------------
		$tranID =$get['tranID'];
		//$orderid =$get['orderid'];
		$status =$get['status'];
		$domain =$get['domain'];
		$amount =$get['amount'];
		$currency =$get['currency'];
		$appcode =$get['appcode'];
		$paydate =$get['paydate'];
		$skey =$get['skey'];
		
		
		// All undeclared variables below are coming from POST method
		$key0 = md5( $tranID.$orderid.$status.$domain.$amount.$currency );
		$key1 = md5( $paydate.$domain.$key0.$appcode.$vkey );
		if( $skey != $key1 ) $status= -1; // invalid transaction
		//-------------------------------------------

		$payment_log_id = $orderid;
		$money = $amount; 
		$payment_id = $payment['id'];
    	$currency_id = $payment['currency']; 
    	
		If ( $status == "00" ){
			 return s_order_paid($payment_log_id,$money,$payment_id,$currency_id);
		} else {
			return false;
		}
        	
	}
}
?>