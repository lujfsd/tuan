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
    $modules[$i]['code']    = 'Chinabank';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '网银在线';

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
class ChinabankPayment implements Payment {
	public $config = array(
	    'chinabank_account'=>'',  //商户编号
        'chinabank_key'=>'',  	  //MD5密钥
	);	
	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$data_vid           = trim($payment_info['config']['chinabank_account']);
        $data_orderid       = $payment_log_id;
        $data_vamount       = $money;
        $data_vmoneytype    = 'CNY';
        $data_vpaykey       = trim($payment_info['config']['chinabank_key']);
		$data_vreturnurl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Chinabank';

		$check_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Chinabank&a=index';
		
        $MD5KEY =$data_vamount.$data_vmoneytype.$data_orderid.$data_vid.$data_vreturnurl.$data_vpaykey;
        $MD5KEY = strtoupper(md5($MD5KEY));

        $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$data_orderid' where id = ".$payment_log_id);
        $def_url  = '<form style="text-align:center;" method=post action="https://pay3.chinabank.com.cn/PayGate" target="_blank">';
        $def_url .= "<input type=HIDDEN name='v_mid' value='".$data_vid."'>";
        $def_url .= "<input type=HIDDEN name='v_oid' value='".$data_orderid."'>";
        $def_url .= "<input type=HIDDEN name='v_amount' value='".$data_vamount."'>";
        $def_url .= "<input type=HIDDEN name='v_moneytype'  value='".$data_vmoneytype."'>";
        $def_url .= "<input type=HIDDEN name='v_url'  value='".$data_vreturnurl."'>";
        $def_url .= "<input type=HIDDEN name='v_md5info' value='".$MD5KEY."'>";
        $def_url .= "<input type=HIDDEN name='remark1' value=''>";
        $def_url .= "<input type=HIDDEN name='remark2' value='[url:=".$check_url."]'>";
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
        $def_url .= "<input type='submit' class='paybutton' value='前往网银在线支付'>";
        $def_url .= "</form>";
        $def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";
        return $def_url;       
	}
	
	public function dealResult($get,$post,$request)
	{			
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		
		$v_oid          = trim($post['v_oid']);
        $v_pmode        = trim($post['v_pmode']);
        $v_pstatus      = trim($post['v_pstatus']);
        $v_pstring      = trim($post['v_pstring']);
        $v_amount       = trim($post['v_amount']);
        $v_moneytype    = trim($post['v_moneytype']);
        $remark1        = trim($post['remark1' ]);
        $remark2        = trim($post['remark2' ]);
        $v_md5str       = trim($post['v_md5str' ]);
        		
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($v_oid));
        $payment_id = intval($payment_id);
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
    			

        /**
         * 重新计算md5的值
         */
        $key            = $payment['config']['chinabank_key'];

        $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
		
        //开始初始化参数
        $payment_log_id = $v_oid;
    	$money = $v_amount;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency'];    
        
		/* 检查秘钥是否正确 */
	        if ($v_md5str==$md5string)
	        {
	            if ($v_pstatus == '20')
	            {
	                return s_order_paid($payment_log_id,$money,$payment_id,$currency_id);   
	            }
	        }
	        else
	        {
	            $return_res['info'] = a_L("VALID_ERROR");
	            return $return_res; 
	        }
               
	}
}
?>