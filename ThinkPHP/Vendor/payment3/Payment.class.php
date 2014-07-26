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


interface Payment{
	
	
	/**
	 * 获取支付代码或提示信息
	 * @param integer $payment_log_id  支付日志ID
	 * @param float $money  实际支付给接口的金额，如$600就直接传入600，而不是原始的 ￥1000 
	 * @param integer $payment_id   支付方式ID
	 * @param integer $currency_id  支付货币ID
	 */
	function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id);
	
	//响应在线支付
	function dealResult($get,$post,$request);
}
?>