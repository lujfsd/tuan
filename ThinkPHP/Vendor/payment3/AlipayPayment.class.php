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
    $modules[$i]['code']    = 'Alipay';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '支付宝';

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
class AlipayPayment implements Payment  {
	public $config = array(
		'alipay_service'=>'',  //接口方式
		'alipay_account'=>'',  //支付宝帐号
	    'alipay_partner'=>'',  //合作者身份ID           		
		'alipay_key'	=>'',  //校验码
	);	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$money = round($money,2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		$agent = '';//C4335319945672464113';
		
		$data_return_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Alipay';
		$data_notify_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=Alipay';

		$real_method = $payment_info['config']['alipay_service'];

        switch ($real_method){
            case '0':
                $service = 'trade_create_by_buyer';
			
                break;
            case '1':
                $service = 'create_partner_trade_by_buyer';
				
                break;
            case '2':
                $service = 'create_direct_pay_by_user';
				
                break;
        }	
        
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		
		if($payment_log['rec_module']=='Order'){
			//$rec_id = $GLOBALS['db']->getOne("select rec_id from ".DB_PREFIX."order_goods where order_id=".intval($payment_log['rec_id'])." limit 1");
			//$goods_data = $GLOBALS['db']->getRow("select name_1,goods_short_name from ".DB_PREFIX."goods where id=".intval($rec_id)." limit 1");
			//$data_sn = $goods_data['goods_short_name']==''?$goods_data['name_1']:$goods_data['goods_short_name'];
			
			$order_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
			/*
			$sql = "select a.data_name, a.attr, a.number, c.name_1 ,b.goods_short_name ".
				  "from ".DB_PREFIX."order_goods a ".
				  "left outer join ".DB_PREFIX."goods b on b.id = a.rec_id ".
				  "left outer join ".DB_PREFIX."weight c on c.id = b.weight_unit ".
				 "where a.order_id =". intval($payment_log['rec_id']);
			$order_goods_list = $GLOBALS['db']->getAll($sql);
			foreach($order_goods_list as $goods){
				if($goods['goods_short_name'])
					$goods['data_name'] = $goods['goods_short_name'];
					
				if (empty($goods['attr'])){
					$data_sn .= $goods['data_name'].'('.$goods['number'].$goods['name_1'].')';
				}else{
					$data_sn .= $goods['data_name'].'('.$goods['attr'].')('.$goods['number'].$goods['name_1'].')';
				}
			}
			$data_sn = a_msubstr($data_sn,0,120);
			*/
			$data_sn = $order_sn;
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
			
			$order_sn = $data_sn;
		}
		
		$out_trade_no = 'fw-'.$order_sn.'-'.$payment_log_id;
		
        $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$out_trade_no' where id = ".$payment_log_id);
         if($_SESSION['token']){
               $parameter = array(
            'agent'             => $agent,
            'service'           => $service,
            'partner'           => $payment_info['config']['alipay_partner'],
            //'partner'           => ALIPAY_ID,
            '_input_charset'    => 'utf-8',
            'notify_url'        => $data_notify_url,
            'return_url'        => $data_return_url,
            /* 业务参数 */
            'subject'           => $data_sn,
            'out_trade_no'      => $out_trade_no,//'fw123456'.$payment_log_id, //modify by chenfq 2010-05-17 将$data_sn.$payment_log_id改为：'fw123456'.$payment_log_id
            'price'             => $money,
            'quantity'          => 1,
           	'extend_param'      =>'isv^fw11',
               
            'payment_type'      => 1,
            "token"		=> $_SESSION['token'],
            /* 物流参数 */
            //'logistics_type'    => 'EXPRESS',
            //'logistics_fee'     => 0,
            //'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            /* 买卖双方信息 */
        	//'paymethod' =>	'directPay',
           /* 'defaultbank' =>	'CMB',*/
            'seller_email'      => $payment_info['config']['alipay_account'],
            'extend_param'		=>'isv^fw11'
        );
         }
        else{
             $parameter = array(
            'agent'             => $agent,
            'service'           => $service,
            'partner'           => $payment_info['config']['alipay_partner'],
            //'partner'           => ALIPAY_ID,
            '_input_charset'    => 'utf-8',
            'notify_url'        => $data_notify_url,
            'return_url'        => $data_return_url,
            /* 业务参数 */
            'subject'           => $data_sn,
            'out_trade_no'      => $out_trade_no,//'fw123456'.$payment_log_id, //modify by chenfq 2010-05-17 将$data_sn.$payment_log_id改为：'fw123456'.$payment_log_id
            'price'             => $money,
            'quantity'          => 1,
            'extend_param'      =>'isv^fw11',
          
            'payment_type'      => 1,
            
            /* 物流参数 */
           // 'logistics_type'    => 'EXPRESS',
            //'logistics_fee'     => 0,
            //'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            /* 买卖双方信息 */
        	//'paymethod' =>	'directPay',
        	/* 'defaultbank' =>	'CMB',*/
            'seller_email'      => $payment_info['config']['alipay_account'],
            'extend_param'		=>'isv^fw11'
        );
        }
		switch ($service){
            case 'trade_create_by_buyer':
                $service = 'trade_create_by_buyer';
				$parameter['logistics_type']='EXPRESS';
				$parameter['logistics_fee']='0.00';
				$parameter['logistics_payment']='BUYER_PAY';
				
                break;
            case 'create_partner_trade_by_buyer':
                $service = 'create_partner_trade_by_buyer';
				$parameter['logistics_type']='EXPRESS';
				$parameter['logistics_fee']='0.00';
				$parameter['logistics_payment']='BUYER_PAY';
				$parameter['paymethod']='directPay';
				
                break;
            case 'create_partner_trade_by_buyer':
                $service = 'create_direct_pay_by_user';
				 $service = 'create_partner_trade_by_buyer';
				$parameter['logistics_type']='EXPRESS';
				$parameter['logistics_fee']=0;
				$parameter['logistics_payment']='BUYER_PAY_AFTER_RECEIVE';
				$parameter['paymethod']='directPay';
				 
                break;
        }	
		
        if(intval($service)<>2){
        	$result = $GLOBALS['db']->getRow("select consignee,address,zip,mobile_phone,user_id from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
            if($result["address"]){
	            $parameter['receive_name']= $result["consignee"];
	            $parameter['receive_address'] = $result["address"];
	            $parameter['receive_zip'] = $result["zip"];
	            $parameter['receive_phone'] = $result["mobile_phone"];
	            $parameter['receive_mobile'] = $result["mobile_phone"];
            }
            else{
            	//担保交易无地址时
            	$parameter['receive_name']= $GLOBALS['db']->getOne("SELECT user_name FROM ".DB_PREFIX."user WHERE id=".intval($result['user_id']));
	            $parameter['receive_address'] = "担保交易地址：到店消费！";
	            $parameter['receive_zip'] = "000000";
	            $parameter['receive_phone'] = "88888888";
	            $parameter['receive_mobile'] =  "88888888";;
            }
        }
        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
			if(!empty($val)){
        	$param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
			}
        }

        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment_info['config']['alipay_key'];
        $sign_md5 = md5($sign);

		
		$payLinks = '<a onclick="window.open(\'https://mapi.alipay.com/gateway.do?'.$param. '&sign='.$sign_md5.'&sign_type=MD5\')" href="javascript:;"><input type="submit" class="paybutton" name="buy" value="前往支付宝在线支付"/></a>';
		
    	if(!empty($payment_info['logo']))
		{
			$payLinks = '<a href="https://mapi.alipay.com/gateway.do?'.$param. '&sign='.$sign_md5.'&sign_type=MD5" target="_blank" class="payLink"><img src='.$payment_info['logo'].' style="border:solid 1px #ccc;" /></a><div class="blank"></div>'.$payLinks;
		}
		
        $def_url = '<div style="text-align:center">'.$payLinks.'</div>';
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
		
		$out_trade_no = str_replace('fw123456', '', $get['out_trade_no']);
		$i = intval(strrpos($out_trade_no,'-'));
		if ($i > 0){
			$out_trade_no = substr($out_trade_no,$i + 1);
		}
		$payment_log_id = intval($out_trade_no);
		
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        /* 检查数字签名是否正确 */
        ksort($get);
        reset($get);
	
        foreach ($get AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key!='payment_name' && $key!='a' && $key!='m' && $val!='')
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['config']['alipay_key'];
       
		if (md5($sign) != $get['sign'])
        {
            $return_res['info'] = a_L("VALID_ERROR");
            return $return_res; 
        }
		
        //初始化处理订单函数的参数.//modify by chenfq 2010-05-17 $get['subject']===>'fw123456'
        //$payment_log_id = intval(str_replace('fw123456', '', $get['out_trade_no']));
        
    	$money = $get['total_fee'];
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency']; 
		$pay_back_code = $get['trade_no'];//支付序列号 2011-05-30
		
		if ($get['trade_status'] == 'TRADE_SUCCESS' || $get['trade_status'] == 'TRADE_FINISHED' || $get['trade_status'] == 'WAIT_SELLER_SEND_GOODS'){
			//记录支付序列号 2011-05-30
		   return s_order_paid($payment_log_id,$money,$payment_id,$currency_id,$pay_back_code);
		}else{
		   return false;
		}    	
	}
}
?>