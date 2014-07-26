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
    $modules[$i]['code']    = 'Yeepay';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '易宝支付';

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

// 易宝农行在线支付模型
require_once(VENDOR_PATH.'payment3/Payment.class.php');
class YeepayPayment implements Payment {
	public $config = array(
	    'yeepay_account'=>'',  //商户编号
        'yeepay_key'=>'',  	  //商户密钥
		
	);

		
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		
		$data_merchant_id =  trim($payment_info['config']['yeepay_account']);
        $data_order_id    = $payment_log_id;
        $data_amount      = $money;
        $message_type     = 'Buy';
        $data_cur         = 'CNY';
        $product_id       = '';
        $product_cat      = '';
        $product_desc     = '';
        $address_flag     = '0';

        $data_return_url    = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Yeepay';
		
        $data_pay_key     = trim($payment_info['config']['yeepay_key']);
        $data_pay_account = trim($payment_info['config']['yeepay_account']);
        $mct_properties   = $payment_log_id;
        $def_url = $message_type . $data_merchant_id . $data_order_id . $data_amount . $data_cur . $product_id . $product_cat
                             . $product_desc . $data_return_url . $address_flag . $mct_properties ;
        $MD5KEY = $this->fw_hmac($def_url, $data_pay_key);

        $def_url  = "\n<form action='https://www.yeepay.com/app-merchant-proxy/node' method='post' target='_blank'>\n";
        $def_url .= "<input type='hidden' name='p0_Cmd' value='".$message_type."'>\n";
        $def_url .= "<input type='hidden' name='p1_MerId' value='".$data_merchant_id."'>\n";
        $def_url .= "<input type='hidden' name='p2_Order' value='".$data_order_id."'>\n";
        $def_url .= "<input type='hidden' name='p3_Amt' value='".$data_amount."'>\n";
        $def_url .= "<input type='hidden' name='p4_Cur' value='".$data_cur."'>\n";
        $def_url .= "<input type='hidden' name='p5_Pid' value='".$product_id."'>\n";
        $def_url .= "<input type='hidden' name='p6_Pcat' value='".$product_cat."'>\n";
        $def_url .= "<input type='hidden' name='p7_Pdesc' value='".$product_desc."'>\n";
        $def_url .= "<input type='hidden' name='p8_Url' value='".$data_return_url."'>\n";
        $def_url .= "<input type='hidden' name='p9_SAF' value='".$address_flag."'>\n";
        $def_url .= "<input type='hidden' name='pa_MP' value='".$mct_properties."'>\n";
        $def_url .= "<input type='hidden' name='pd_FrpId' value=''>\n";
        $def_url .= "<input type='hidden' name='pd_NeedResponse' value='1'>\n";
        $def_url .= "<input type='hidden' name='hmac' value='".$MD5KEY."'>\n";
		
        $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$payment_log_id' where id = ".$payment_log_id);
        
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
        $def_url .= "<input type='submit' class='paybutton' value='前往易宝在线支付'>";
		
        $def_url .= "</form>\n";
		
		$def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";

        return $def_url;
        
	}
	
	private function fw_hmac($data, $key)
    {
        // RFC 2104 HMAC implementation for php.
        // Creates an md5 HMAC.
        // Eliminates the need to install mhash to compute a HMAC
        // Hacked by Lance Rushing(NOTE: Hacked means written)

//        $key  = iconv('GB2312', 'UTF8', $key);
//        $data = iconv('GB2312', 'UTF8', $data);

        $b = 64; // byte length for md5
        if (strlen($key) > $b)
        {
            $key = pack('H*', md5($key));
        }

        $key    = str_pad($key, $b, chr(0x00));
        $ipad   = str_pad('', $b, chr(0x36));
        $opad   = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack('H*', md5($k_ipad . $data)));
    }
	
	public function dealResult($get,$post,$request)
	{	
		$orderid        = trim($request['r6_Order']);  // 获取订单ID
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($orderid));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);		
		//$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where class_name='Yeepay'");  
    	//$payment['config'] = unserialize($payment['config']);
    			
        $merchant_id    = $payment['config']['yeepay_account'];       // 获取商户编号
        $merchant_key   = $payment['config']['yeepay_key'];           // 获取秘钥

        $message_type   = trim($request['r0_Cmd']);
        $succeed        = trim($request['r1_Code']);   // 获取交易结果,1成功,-1失败
        $trxId          = trim($request['r2_TrxId']);
        $amount         = trim($request['r3_Amt']);    // 获取订单金额
        $cur            = trim($request['r4_Cur']);    // 获取订单货币单位
        $product_id     = trim($request['r5_Pid']);    // 获取产品ID
        //$orderid        = trim($request['r6_Order']);  // 获取订单ID
        $userId         = trim($request['r7_Uid']);    // 获取产品ID
        $merchant_param = trim($request['r8_MP']);     // 获取商户私有参数
        $bType          = trim($request['r9_BType']);  // 获取订单ID

        $mac            = trim($request['hmac']);      // 获取安全加密串

        ///生成加密串,注意顺序
        $ScrtStr  = $merchant_id . $message_type . $succeed . $trxId . $amount . $cur . $product_id .
                      $orderid . $userId . $merchant_param . $bType;
        $mymac    = $this->fw_hmac($ScrtStr, $merchant_key);

        $return_res = array(
			'info'=>'',
			'status'=>false,
		);

		$payment_log_id = $orderid;
    	$money = $amount;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency']; 
    	
        if (strtoupper($mac) == strtoupper($mymac))
        {
            if ($succeed == '1')
            {
                //支付成功
                $return_res['status'] = true;
                return s_order_paid($payment_log_id,$money,$payment_id,$currency_id); 
            }
        }
        return $return_res;       
          
	}
}
?>