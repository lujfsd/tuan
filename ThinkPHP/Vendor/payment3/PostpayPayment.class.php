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
    $modules[$i]['code']    = 'Postpay';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '汇款转帐';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '2.0';

    /* 插件的作者 */
    $modules[$i]['author']  = 'FANWE R&D TEAM';

    /* 支付方式：1：在线支付；0：线下支付 */
    $modules[$i]['online_pay'] = '0';
        
    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.fanwe.com';

    return;
}

// 邮局支付模型
require_once(VENDOR_PATH.'payment3/Payment.class.php');
class PostpayPayment implements Payment
{
	public $config = array(
		'postpay_account'=>'',
		'postpay_username'=>'',
	);	
		
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{		
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		$def_url = "";
		
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
		$def_url.= a_L("POSTPAY_ACCOUNT").":".$payment_info['config']['postpay_account'];
		$def_url.="<br /><br />".a_L("POSTPAY_USERNAME").":".$payment_info['config']['postpay_username'];
		$def_url.="<br /><br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";
		return $def_url;
	}
	public function dealResult($get,$post,$request)
	{
		return a_L("INVALID_OPERATION");
	}
}
?>