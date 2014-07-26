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
    $modules[$i]['code']    = 'Kuaiqian';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '快钱人民币网关';

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
class KuaiqianPayment implements Payment {
	public $config = array(
	    'kq_account'=>'',
        'kq_key'=>'',
	);
	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
				
				
		$data_return_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Kuaiqian';
		
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		
		if($payment_log['rec_module']=='Order'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
		}
				
       $merchant_acctid    = trim($payment_info['config']['kq_account']);           //人民币账号 不可空
       $key                = trim($payment_info['config']['kq_key']);
       $input_charset      = 1;                                                		//字符集 默认1=utf-8
       $page_url           = $data_return_url;
       $bg_url             = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=Kuaiqian&payment_name=Kuaiqian&';
       $version            = 'v2.0';
       $language           = 1;
       $sign_type          = 1;                                           //签名类型 不可空 固定值 1:md5
       $payer_name         = '';
       $payer_contact_type = '';
       $payer_contact      = '';
       $order_id           = $data_sn;                                    //商户订单号 不可空
       $order_amount       = $money * 100;                        		  //商户订单金额 不可空
       $order_time         = a_toDate(a_gmtTime(), 'YmdHis');            	  //商户订单提交时间 不可空 14位
       $product_name       = '';
       $product_num        = '';
       $product_id         = '';
       $product_desc       = '';
       $ext1               = $payment_log_id;
       $ext2               = '';
       $pay_type           = '00';                                                //支付方式 不可空
       $bank_id            = '';
       $redo_flag          = '0';
       $pid                = '';

      
        /* 生成加密签名串 请务必按照如下顺序和规则组成加密串！*/
        $signmsgval = '';
        $signmsgval = $this->append_param($signmsgval, "inputCharset", $input_charset);
        $signmsgval = $this->append_param($signmsgval, "pageUrl", $page_url);
        $signmsgval = $this->append_param($signmsgval, "bgUrl", $bg_url);
        $signmsgval = $this->append_param($signmsgval, "version", $version);
        $signmsgval = $this->append_param($signmsgval, "language", $language);
        $signmsgval = $this->append_param($signmsgval, "signType", $sign_type);
        $signmsgval = $this->append_param($signmsgval, "merchantAcctId", $merchant_acctid);
        $signmsgval = $this->append_param($signmsgval, "payerName", $payer_name);
        $signmsgval = $this->append_param($signmsgval, "payerContactType", $payer_contact_type);
        $signmsgval = $this->append_param($signmsgval, "payerContact", $payer_contact);
        $signmsgval = $this->append_param($signmsgval, "orderId", $order_id);
        $signmsgval = $this->append_param($signmsgval, "orderAmount", $order_amount);
        $signmsgval = $this->append_param($signmsgval, "orderTime", $order_time);
        $signmsgval = $this->append_param($signmsgval, "productName", $product_name);
        $signmsgval = $this->append_param($signmsgval, "productNum", $product_num);
        $signmsgval = $this->append_param($signmsgval, "productId", $product_id);
        $signmsgval = $this->append_param($signmsgval, "productDesc", $product_desc);
        $signmsgval = $this->append_param($signmsgval, "ext1", $ext1);
        $signmsgval = $this->append_param($signmsgval, "ext2", $ext2);
        $signmsgval = $this->append_param($signmsgval, "payType", $pay_type);
        $signmsgval = $this->append_param($signmsgval, "bankId", $bank_id);
        $signmsgval = $this->append_param($signmsgval, "redoFlag", $redo_flag);
        $signmsgval = $this->append_param($signmsgval, "pid", $pid);
        $signmsgval = $this->append_param($signmsgval, "key", $key);
        $signmsg    = strtoupper(md5($signmsgval));    //签名字符串 不可空

		$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$payment_log_id' where id = ".$payment_log_id);
        //https://www.99bill.com/gateway/recvMerchantInfoAction.htm
        //https://sandbox.99bill.com/gateway/recvMerchantInfoAction.htm
        $def_url  = '<div style="text-align:center"><form name="kqPay" style="text-align:center;" method="post" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" target="_blank">';
        $def_url .= "<input type='hidden' name='inputCharset' value='" . $input_charset . "' />";
        $def_url .= "<input type='hidden' name='bgUrl' value='" . $bg_url . "' />";
        $def_url .= "<input type='hidden' name='pageUrl' value='" . $page_url . "' />";
        $def_url .= "<input type='hidden' name='version' value='" . $version . "' />";
        $def_url .= "<input type='hidden' name='language' value='" . $language . "' />";
        $def_url .= "<input type='hidden' name='signType' value='" . $sign_type . "' />";
        $def_url .= "<input type='hidden' name='signMsg' value='" . $signmsg . "' />";
        $def_url .= "<input type='hidden' name='merchantAcctId' value='" . $merchant_acctid . "' />";
        $def_url .= "<input type='hidden' name='payerName' value='" . $payer_name . "' />";
        $def_url .= "<input type='hidden' name='payerContactType' value='" . $payer_contact_type . "' />";
        $def_url .= "<input type='hidden' name='payerContact' value='" . $payer_contact . "' />";
        $def_url .= "<input type='hidden' name='orderId' value='" . $order_id . "' />";
        $def_url .= "<input type='hidden' name='orderAmount' value='" . $order_amount . "' />";
        $def_url .= "<input type='hidden' name='orderTime' value='" . $order_time . "' />";
        $def_url .= "<input type='hidden' name='productName' value='" . $product_name . "' />";
        $def_url .= "<input type='hidden' name='payType' value='" . $pay_type . "' />";
        $def_url .= "<input type='hidden' name='productNum' value='" . $product_num . "' />";
        $def_url .= "<input type='hidden' name='productId' value='" . $product_id . "' />";
        $def_url .= "<input type='hidden' name='productDesc' value='" . $product_desc . "' />";
        $def_url .= "<input type='hidden' name='ext1' value='" . $ext1 . "' />";
        $def_url .= "<input type='hidden' name='ext2' value='" . $ext2 . "' />";
        $def_url .= "<input type='hidden' name='bankId' value='" . $bank_id . "' />";
        $def_url .= "<input type='hidden' name='redoFlag' value='" . $redo_flag ."' />";
        $def_url .= "<input type='hidden' name='pid' value='" . $pid . "' />";
        $def_url .= "<input type='submit' name='submit' class='paybutton' value='前往快钱在线支付' />";
        $def_url .= "</form></div></br>";
		$def_url .="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";
        return $def_url;	
	}
	
	public function dealResult($get,$post,$request)
	{
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
    	
    	$ext1  = trim($_REQUEST['ext1']);
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($ext1));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
		//$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where class_name='Kuaiqian'");  
    	//$payment['config'] = unserialize($payment['config']);
    	    	
        $merchant_acctid     = $payment['config']['kq_account'];                 //人民币账号 不可空
        $key                 = $payment['config']['kq_key'];
        $get_merchant_acctid = trim($_REQUEST['merchantAcctId']);
        $pay_result          = trim($_REQUEST['payResult']);
        $version             = trim($_REQUEST['version']);
        $language            = trim($_REQUEST['language']);
        $sign_type           = trim($_REQUEST['signType']);
        $pay_type            = trim($_REQUEST['payType']);
        $bank_id             = trim($_REQUEST['bankId']);
        $order_id            = trim($_REQUEST['orderId']);
        $order_time          = trim($_REQUEST['orderTime']);
        $order_amount        = trim($_REQUEST['orderAmount']);
        $deal_id             = trim($_REQUEST['dealId']);
        $bank_deal_id        = trim($_REQUEST['bankDealId']);
        $deal_time           = trim($_REQUEST['dealTime']);
        $pay_amount          = trim($_REQUEST['payAmount']);
        $fee                 = trim($_REQUEST['fee']);
        
        $ext2                = trim($_REQUEST['ext2']);
        $err_code            = trim($_REQUEST['errCode']);
        $sign_msg            = trim($_REQUEST['signMsg']);

        
        //开始初始化参数
        $payment_log_id = intval($ext1);
    	$money = $pay_amount/100;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency'];         
        
        //生成加密串。必须保持如下顺序。
        $merchant_signmsgval = '';
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"merchantAcctId",$merchant_acctid);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"version",$version);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"language",$language);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"signType",$sign_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payType",$pay_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"bankId",$bank_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderId",$order_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderTime",$order_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderAmount",$order_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"dealId",$deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"bankDealId",$bank_deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"dealTime",$deal_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payAmount",$pay_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"fee",$fee);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"ext1",$ext1);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"ext2",$ext2);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payResult",$pay_result);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"errCode",$err_code);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"key",$key);
        $merchant_signmsg    = md5($merchant_signmsgval);

        //首先对获得的商户号进行比对
        if ($get_merchant_acctid != $merchant_acctid)
        {
            //商户号错误
            return $return_res['info'] = a_L('KUAIQIAN_ERROR_01');
        }

        if (strtoupper($sign_msg) == strtoupper($merchant_signmsg))
        {
            if ($pay_result == 10 || $pay_result == 00)
            {
                //order_paid($ext1);
				return s_order_paid($payment_log_id,$money,$payment_id,$currency_id); 
            }
            else
            {
                //'支付结果失败';
                return $return_res['info'] = a_L('KUAIQIAN_ERROR_02');
            }
        }
        else
        {
            //'密钥校对错误';
            return $return_res['info'] = a_L('KUAIQIAN_ERROR_03');
        }         
         
         
	}
	
    /**
    * 将变量值不为空的参数组成字符串
    * @param   string   $strs  参数字符串
    * @param   string   $key   参数键名
    * @param   string   $val   参数键对应值
    */
    function append_param($strs,$key,$val)
    {
        if($strs != "")
        {
            if($key != '' && $val != '')
            {
                $strs .= '&' . $key . '=' . $val;
            }
        }
        else
        {
            if($val != '')
            {
                $strs = $key . '=' . $val;
            }
        }
            return $strs;
    }	
}
?>