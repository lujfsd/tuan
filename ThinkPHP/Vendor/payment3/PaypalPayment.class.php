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
    $modules[$i]['code']    = 'Paypal';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'Paypal';

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
class PaypalPayment implements Payment {
	public $config = array(
	    'paypal_account'=>'',
        'paypal_currency'=>'USD',
	);	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$data_return_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Paypal';
		$data_notify_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Paypal';
		$cancel_return = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Paypal';
		$cancel_return = '';

		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		if($payment_log['rec_module']=='Order'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
		}
		
		$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$payment_log_id' where id = ".$payment_log_id);
		
		$def_url  = '<form style="text-align:center;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">' .   // 不能省略
            "<input type='hidden' name='cmd' value='_xclick'>" .                             // 不能省略
            "<input type='hidden' name='business' value='".$payment_info['config']['paypal_account']."'>" .                 // 贝宝帐号
            "<input type='hidden' name='item_name' value='".$data_sn."'>" .                 // payment for
            "<input type='hidden' name='amount' value='".$money."'>" .                        // 订单金额
            "<input type='hidden' name='currency_code' value='".$payment_info['config']['paypal_currency']."'>" .            // 货币
            "<input type='hidden' name='return' value='$data_return_url'>" .                    // 付款后页面
            "<input type='hidden' name='invoice' value='".$payment_log_id."'>" .                      // 订单号
            "<input type='hidden' name='charset' value='utf-8'>" .                              // 字符集
            "<input type='hidden' name='no_shipping' value='1'>" .                              // 不要求客户提供收货地址
            "<input type='hidden' name='no_note' value=''>" .                                  // 付款说明
            "<input type='hidden' name='notify_url' value='$data_notify_url'>" .
            "<input type='hidden' name='rm' value='2'>" .
            "<input type='hidden' name='cancel_return' value='$cancel_return'>";
		
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
        $def_url .= "<input type='submit' class='paybutton' value='Directing to Payment'>";                      // 按钮
        $def_url  .= "</form>";
		$def_url .="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";
		return $def_url;
	}
	
	public function dealResult($get,$post,$request)
	{
		
		  //验证功能存在bug，善未实现
//        $req = 'cmd=_notify-validate';
//        foreach ($post as $key => $value)
//        {
//            $value = urlencode(stripslashes($value));
//            $req .= "&$key=$value";
//        }
//        echo $req."<br /><br />"; 
//		 // post back to PayPal system to validate
//        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
//        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
//        $header .= "Content-Length: " . strlen($req) ."\r\n\r\n";
//        $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
//        if (!$fp)
//        {
//            fclose($fp);
//            return false;
//        }
//        else
//        {
//            fputs($fp, $header . $req);
//            while (!feof($fp))
//            {
//                $res = fgets($fp, 1024);
//                dump($res);               
//            }
//             fclose($fp);
//        }
//        exit;
        
		
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		
		 $data_id = $post['invoice'];
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($data_id));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);		
		//$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where class_name='Paypal'");  
    	//$payment['config'] = unserialize($payment['config']);
        $merchant_id    = $payment['config']['paypal_account'];               ///获取商户编号
        
        // assign posted variables to local variables
        $item_name = $post['item_name'];
        $item_number = $post['item_number'];
        $payment_status = $post['payment_status'];
        $payment_amount = floatval($post['mc_gross']);
        $payment_currency = $post['mc_currency'];
        $txn_id = $post['txn_id'];
        $receiver_email = $post['receiver_email'];
        $payer_email = $post['payer_email'];
        //$data_id = $post['invoice'];

        
        //开始初始化参数
        $payment_log_id = $data_id;
    	$money = $payment_amount;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency']; 

		if ($payment_status != 'Completed' && $payment_status != 'Pending')
	         {
				$return_res['info'] = a_L("PAYMENT_NO_SUCCESS");	
	         }
	    elseif ($receiver_email != $merchant_id)
	         {
	             $return_res['info'] = a_L("PAYMENT_ACCOUNT_NOT_MATCH");	
	         }         
/*	    elseif (number_format($total_price,2)!=number_format($payment_amount,2))
	         {         	
	             $return_res['info'] = a_L("PAYMENT_AMOUNT_ERROR");	
	         }
*/	    elseif ($payment['config']['paypal_currency'] != $payment_currency)
	         {
	             $return_res['info'] = a_L("PAYMENT_AMOUNT_ERROR");
	         }
	    else
	    {
	    	return s_order_paid($payment_log_id,$money,$payment_id,$currency_id);   
	    }
        
         return $return_res;           
	}
}
?>