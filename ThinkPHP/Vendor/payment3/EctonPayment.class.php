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
    $modules[$i]['code']    = 'Ecton';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '山东城市一卡通';

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
// 支付宝模型
require_once(VENDOR_PATH.'payment3/Payment.class.php');
class EctonPayment implements Payment  {
	public $config = array(
		'ecton_mchid'=>'',  //帐号
		'ecton_pwd'	=>'',  //密码
		'ecton_backpaypwd'	=>'', //商户对账密码
		
	);	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$money = round($money,2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Ecton';
		$noticeUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&md=autoNotice&payment_name=Ecton';

		$mchid = $payment_info['config']['ecton_mchid'];
		$paypwd = $payment_info['config']['ecton_pwd'];
	
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
		if($payment_log['rec_module']=='Order'){
			$create_time = $GLOBALS['db']->getOne("select create_time from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$create_time = $GLOBALS['db']->getOne("select create_time from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
		}	
		
		$ordetime = a_toDate($create_time,'Y-m-d H:i:s');
		//$ordetime = date( "Y-m-d H:i:s" );
		$orderid = $payment_log_id;
		$total_fee = $money;

		$paypwd = md5($mchid.$orderid.$paypwd.$ordetime.$total_fee);
		
        $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$data_orderid' where id = ".$payment_log_id);
        $def_url  = '<form style="text-align:center;" method=post action="https://58.56.23.89:9443/paygate/paygate.action" target="_blank">';
        
		$def_url .= "<input type='hidden' name='mchid '  value='".$mchid."'>"; 
		$def_url .= "<input type='hidden' name='orderid'  value='".$orderid."'>";
		$def_url .= "<input type='hidden' name='ordetime'  value='".$ordetime."'>";
		$def_url .= "<input type='hidden' name='total_fee'  value='".$total_fee."'>";
		$def_url .= "<input type='hidden' name='page_url'  value='".$returnUrl."'>";
		$def_url .= "<input type='hidden' name='server_url'  value='".$noticeUrl."'>";
		$def_url .= "<input type='hidden' name='paypwd'  value='".$paypwd."'>";
  
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
        $def_url .= "<input type='submit' class='paybutton' value='前往山东城市一卡通支付'>";
        $def_url .= "</form>";
        $def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";
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
        
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		

		$payment_log_id = intval($get['orderid']);
		
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
        $payment_id = intval($payment_id);
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
    	
    	$mchid = $payment['config']['ecton_mchid'];
    	$backpaypwd = $payment['config']['ecton_backpaypwd'];
		$total_fee = $get['total_fee'];
		$status = $get['status'];
		$md5str = $get['md5str'];
		$total_fee = $get['total_fee'];
		


        $sign = md5($payment_log_id.$status.$backpaypwd.$total_fee.$mchid);
       
		if ($sign != $md5str)
        {
            $return_res['info'] = a_L("VALID_ERROR");
            return $return_res; 
        }
        
    	$money = $total_fee;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency']; 
		
		if ($status == '20'){
		   return s_order_paid($payment_log_id,$money,$payment_id,$currency_id);
		}else{
		   return false;
		}    	
	}
	
	//自动对账
	public function autoNotice(){
	    $res = $this->dealResult($_GET,$_POST,$_REQUEST);
	    if($res['status'])
		{
		    echo 'ok';
		}
		else 
		{
		    echo 'error';
		}		
	}
	
	//自动退款(现在只有收款退款,没有冲值退款)
	public function autoRefund($payment_log_id,$mydb){
		if (!isset($mydb) && isset($GLOBALS['db'])){
			$mydb = $GLOBALS['db'];
		}
		
		$payment_log = $mydb->getRow("select rec_id,rec_module,money,payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
		
		if($payment_log['rec_module']=='Order'){
			$order_uncharge = $mydb->getRow("select payment_log_id,status from ".DB_PREFIX."order_incharge where payment_log_id=".intval($payment_log_id));
			if (empty($order_uncharge)){
				return "";
			}			
			if ($order_uncharge['status'] == 2){
				return "已退款";
			}			
			
			$create_time = $mydb->getOne("select create_time from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$create_time = $mydb->getOne("select create_time from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
		}
				
		$money = round($payment_log['money'],2);
		$payment_id = $payment_log['payment_id'];		
		 		
		$payment_info = $mydb->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$noticeUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/admin.php?m=Pay&md=autoRefundNotice&payment_name=Ecton';
		$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/admin.php?m=Pay&md=autoRefundResult&payment_name=Ecton';
		

		$mchid = $payment_info['config']['ecton_mchid'];
		$paypwd = $payment_info['config']['ecton_pwd'];
	
		if (function_exists('a_toDate')) {
			$ordetime = a_toDate($create_time,'Y-m-d H:i:s');
		}else{
			$ordetime = toDate($create_time,'Y-m-d H:i:s');
		}
		$backtime = date( "Y-m-d H:i:s" );
		$orderid = $payment_log_id;
		$total_fee = $money;

		$backpwd = md5($mchid.$orderid.$paypwd.$backtime.$total_fee);
		
        $def_url  = '<form style="text-align:center;" method=post action="https://58.56.23.89:9443/paygate/backgate.action" target="_blank">';
		$def_url .= "<input type='hidden' name='mchid '  value='".$mchid."'>"; 
		$def_url .= "<input type='hidden' name='orderid'  value='".$orderid."'>";
		$def_url .= "<input type='hidden' name='total_fee'  value='".$total_fee."'>";
		$def_url .= "<input type='hidden' name='page_url'  value='".$returnUrl."'>";
		$def_url .= "<input type='hidden' name='server_url'  value='".$noticeUrl."'>";		
		$def_url .= "<input type='hidden' name='backtime'  value='".$backtime."'>";
		$def_url .= "<input type='hidden' name='backpwd'  value='".$backpwd."'>";
  		$def_url .= "<input type='hidden' name='ordetime'  value='".$ordetime."'>";
        $def_url .= "<input type='submit' class='submit' value='自动退款到一卡通'>";
        $def_url .= "</form>";
        return $def_url;  			
	}
	
	//在后台处理的函数
	public function autoRefundNotice($mydb){
		if (!isset($mydb) && isset($GLOBALS['db'])){
			$mydb = $GLOBALS['db'];
		}		
		$payment_log_id = intval($_REQUEST['orderid']);
		
		$payment_log = $mydb->getRow("select rec_id,rec_module,money,payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
        $payment_id = intval($payment_log['payment_id']);
        
		$payment = $mydb->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
    	
    	$mchid = $payment['config']['ecton_mchid'];
    	$backpaypwd = $payment['config']['ecton_backpaypwd'];
		$total_fee = $_REQUEST['total_fee'];
		$status = $_REQUEST['status'];
		$md5str = $_REQUEST['md5str'];
		$total_fee = $_REQUEST['total_fee'];


        $sign = md5($payment_log_id.$status.$backpaypwd.$total_fee.$mchid);
       
		if ($sign != $md5str)
        {
            return false;
             
        }else{
        	
/*
 * <form method="post" action="http://localhost:8888/fanwetg3/admin.php?m=Pay&md=autoRefundResult&payment_name=Ecton" id="test_form_page_id">
				<input type="hidden" name="orderid"  value="406">          
				<input type="hidden" name="status" value="20">
				<input type="hidden" name="total_fee" value="10.9">
				<input type="hidden" name="md5str" value="22e4244a6074f0f9d6be15e88bf279b6">
		</form>	

 */        	
        	if ($payment_log['rec_module'] == 'Order'){
	        	$sql = "update ".DB_PREFIX."order_incharge set status =2 where payment_log_id=".intval($payment_log_id)." limit 1";
	        	$mydb->query($sql);
	        	$is_updated = $mydb->affected_rows();
	        	if ($is_updated > 0){
	        		
	        		
	        		
	        		
	        		include_once(getcwd()."/admin/Lib/Action/OrderAction.class.php");
	        		$order = new OrderAction;
	        		
	        		
					$model = D("OrderUncharge");
				    if(false === $vo = $model->create()) {
		        		$this->error($model->getError());
				    }
				    
				    $total_fee = $_REQUEST['total_fee'];
			         //收款金额
			        $vo['order_id'] = $payment_log['rec_id'];
			        $vo['cost_payment_fee'] = 0;
			        $vo['currency_id'] = $payment['currency'];
			        $vo['currency_radio'] = 1;
			        $vo['money'] = $total_fee;
			        $vo['memo'] = '自动退款:'.date( "Y-m-d H:i:s" );
					$vo['payment_id'] = $payment_log['payment_id'];
					$vo['create_time'] = gmtTime();
	
					$id = $model->add($vo);
					if($id) { //保存成功
						$order->inc_order_uncharge($id);	//减少已收金额
					}  
	        	}      		
        	}
        	return true; 
        }		
	}
	
	//在后台处理的函数
	public function autoRefundResult($mydb){
		if (!isset($mydb) && isset($GLOBALS['db'])){
			$mydb = $GLOBALS['db'];
		}		
		if ($this->autoRefundNotice($mydb)){
			$payment_log_id = intval($_REQUEST['orderid']);
			$payment_log = $mydb->getRow("select rec_id,rec_module,money,payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
			if ($payment_log['rec_module'] == 'Order'){
				$order_id = $mydb->getOne("select order_id from ".DB_PREFIX."order_incharge where payment_log_id =".intval($payment_log_id));
				redirect(__ROOT__."/admin.php?m=Order&a=show&id=".intval($order_id));
			}	
		}
	}
	
}
?>