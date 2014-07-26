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
    $modules[$i]['code']    = 'Paydollar';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '联付通';

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

// 网银支付模型
require_once(VENDOR_PATH.'payment3/Payment.class.php');
class PaydollarPayment implements Payment {
	public $config = array(
	    'member_id'=>'',  //客户号
        //'PrivateKey'=>'', //私钥
		'CurrencyCode'=>'',//货币代码
		'LanguageCode'=>'',//支付界面语言
	);	
	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$member_id          = trim($payment_info['config']['member_id']);
		$CurrencyCode 		= trim($payment_info['config']['CurrencyCode']);
		$LanguageCode 		= trim($payment_info['config']['LanguageCode']);
		$ikey       = trim($payment_info['config']['PrivateKey']);
		$data_vreturnurl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Paydollar';
		
        $text="merchant_id=".$member_id."&orderid=".$payment_log_id."&amount=".$money."&merchant_url=".$data_vreturnurl."&merchant_key=".$ikey;
        $mac = strtoupper(md5($text));

        //https://www.paydollar.com/b2c2/eng/payment/payForm.jsp
        //https://test.paydollar.com/b2cDemo/eng/payment/payForm.jsp
        $def_url  = '<form style="text-align:center;" method=post action="https://www.paydollar.com/b2c2/eng/payment/payForm.jsp" target="_blank">';
        $def_url .= "<input type=HIDDEN name='merchantId' value='".$member_id."'>";
        $def_url .= "<input type=HIDDEN name='orderRef' value='".$payment_log_id."'>";
        $def_url .= "<input type=HIDDEN name='amount' value='".$money."'>";
        $def_url .= "<input type=HIDDEN name='currCode'  value='".$CurrencyCode."'>";
        $def_url .= "<input type=HIDDEN name='lang'  value='".$LanguageCode."'>";
        $def_url .= "<input type=HIDDEN name='successUrl' value='".$data_vreturnurl."'>";
        $def_url .= "<input type=HIDDEN name='failUrl' value='".$data_vreturnurl."'>";
        $def_url .= "<input type=HIDDEN name='cancelUrl' value='".$data_vreturnurl."'>";
        $def_url .= "<input type=HIDDEN name='payType' value='N'>";
        $def_url .= "<input type=HIDDEN name='payMethod' value='CC'>";
        $def_url .= "<input type=HIDDEN name='remark' value=''>";
        $def_url .= "<input type='submit' class='paybutton' value='" .a_L("PAYDOLLAR_PAYMENT_BUTTON"). "'>";
        $def_url .= "</form>";
        $def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_fanweC('BASE_CURRENCY_UNIT')." ".number_format($money,2)."</span>";
        return $def_url;       
	}
	
	public function dealResult($get,$post,$request)
	{			
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where class_name='Paydollar'");  
    	$payment['config'] = unserialize($payment['config']);
    	    	
    	
		$v_oid          = trim($post['Ref']);
        $successcode      = trim($post['successcode']);
		$money = floatval($post['Amt']);        
        
        //开始初始化参数
        $payment_log_id = $v_oid;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency'];    
        
		/* 检查秘钥是否正确 */
	    if ($successcode==0)
	    {
	       return s_order_paid($payment_log_id,$money,$payment_id,$currency_id);
	    }
	    else
	   {
	       $return_res['info'] = a_L("VALID_ERROR");
	        return $return_res; 
	   }
               
	}
}
?>