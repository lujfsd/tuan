<?php
/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = 'MCod';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '手机版货到付款';

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
//手机版货到付款
require_once(VENDOR_PATH.'payment3/Payment.class.php');
class MCodPayment implements Payment
{
	public $config = array(
	);
		
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{		
		$money = round($money,2);
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		
		$sql = "select a.data_name, a.attr, a.number ".
								  "from ".DB_PREFIX."order_goods a ".
								  "left outer join ".DB_PREFIX."goods b on b.id = a.rec_id ".
								 "where a.order_id =". intval($payment_log['rec_id']);
		$order_goods_list = $GLOBALS['db']->getAll($sql);
		foreach($order_goods_list as $goods){
			$index = intval($k) + 1;
			if (empty($goods['attr'])){
				$body .= $index ."、".$goods['data_name'].';数量:'.$goods['number'].'<br>';
			}else{
				$body .= $index ."、".$goods['data_name'].'('.$goods['attr'].') 数量:'.$goods['number'].'<br>';
			}
		}
		
		$subject = $body;
		
		$pay = array();
		$pay['subject'] = $subject;
		$pay['body'] = $body;
		$pay['total_fee'] = $money;
		$pay['total_fee_format'] = a_formatPrice($money);
		$pay['out_trade_no'] = $payment_log_id;
		
		$pay['pay_code'] = 'mcod';//,支付宝;mtenpay,财付通;mcod,货到付款
			
		return $pay;
	}
	public function dealResult($get,$post,$request)
	{
		return a_L("INVALID_OPERATION");
	}
}
?>