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
    $modules[$i]['code']    = 'TenpayBank';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '财付通集成网关支付';

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
// 财付通模型

require_once(VENDOR_PATH.'payment3/Payment.class.php');
class TenpayBankPayment implements Payment  {
	public $config = array(
	    'tencentpay_id'=>'',  //财付通商户号
        'tencentpay_key'=>'',  //财付通商户密钥
		'tencentpay_sign'=>'',  //自定义签名
		'tencentpay_gateway'	=>	array(
			'0'=>'',    //纯网关支付
			'1002'=>'', //中国工商银行
			'1001'=>'', //招商银行
			'1003'=>'', //中国建设银行
			'1005'=>'', //中国农业银行
			'1004'=>'', //上海浦东发展银行
			'1008'=>'', //深圳发展银行
			'1009'=>'', //兴业银行
			'1032'=>'', //北京银行
			'1022'=>'', //中国光大银行
			'1006'=>'', //中国民生银行
			'1021'=>'', //中信银行
			'1027'=>'', //广东发展银行
			'1010'=>'', //平安银行
			'1052'=>'', //中国银行
			'1020'=>'', //交通银行
			'1030'=>'', //中国工商银行(企业)
			'1042'=>'', //招商银行(企业)
			'1028'=>'', //中国邮政储蓄银行(银联)
		)

	);	
	
	public $bank_types = array(
		0,    //纯网关支付
		1002, //中国工商银行
		1001, //招商银行
		1003, //中国建设银行
		1005, //中国农业银行
		1004, //上海浦东发展银行
		1008, //深圳发展银行
		1009, //兴业银行
		1032, //北京银行
		1022, //中国光大银行
		1006, //中国民生银行
		1021, //中信银行
		1027, //广东发展银行
		1010, //平安银行
		1052, //中国银行
		1020, //交通银行
		1030, //中国工商银行(企业)
		1042, //招商银行(企业)
		1028, //中国邮政储蓄银行(银联)
	);
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{   
        $money = round($money,2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		/* 订单描述，用订单号替代 */
        $payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module,create_time from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		$data_return_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=TenpayBank';

        
        $cmd_no = '1';

        /* 获得订单的流水号，补零到10位 */
        $sp_billno = $payment_log_id;
	$spbill_create_ip =  $_SERVER['REMOTE_ADDR'];

        /* 交易日期 */
        $today = a_toDate($payment_log['create_time'],'Ymd');

        /* 将商户号+年月日+流水号 */
        $bill_no = str_pad($payment_log_id, 10, 0, STR_PAD_LEFT);
        $transaction_id = $payment_info['config']['tencentpay_id'].$today.$bill_no;

        /* 银行类型:支持纯网关和财付通 */ //修改by hc 将类型的选择放于界面让用户选择
        //$bank_type = '0';

        
//		$orderGoods = M('OrderGoods')->where("order_id=".$payment_log['rec_id'])->find();
//		if($orderGoods){
//			$goods_data = M("Goods")->getById($orderGoods['rec_id']);			
//			$data_sn = $goods_data['goods_short_name']==''?$goods_data['name_1']:$goods_data['goods_short_name'];
//			//$quantity = intval(M('OrderGoods')->where("order_id=".$payment_log['rec_id'])->sum('number'));
//		}else{
//			//$quantity = 1;
//			$data_sn = D($payment_log['rec_module'])->where("id=".$payment_log['rec_id'])->getField("sn");
//		}
        $bank_id = 0;
		if($payment_log['rec_module']=='Order'){
			$Order = $GLOBALS['db']->getRow("select sn,bank_id from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
			$data_sn = $Order['sn'];
			$bank_id = $Order['bank_id'];
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$Order = $GLOBALS['db']->getRow("select sn,bank_id from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
			$data_sn = $Order['sn'];
			$bank_id = $Order['bank_id'];
		}
        $desc = $data_sn;
        $attach = $payment_info['config']['tencentpay_sign'];
        
        /* 编码标准 
        if (String::is_utf8($desc))
        {
            $desc = iconv('utf-8', 'gbk', $desc);
        }
*/
        $desc = a_utf8ToGB($desc);
        /* 返回的路径 */
        $return_url = $data_return_url;

        /* 总金额 */
        $total_fee = $money*100;

        /* 货币类型 */
        $fee_type = '1';

        /* 重写自定义签名 */
        //$payment['magic_string'] = abs(crc32($payment['magic_string']));

        /* 数字签名 */
        $sign_text = "cmdno=" . $cmd_no . "&date=" . $today . "&bargainor_id=" . $payment_info['config']['tencentpay_id'] .
          "&transaction_id=" . $transaction_id . "&sp_billno=" . $sp_billno .
          "&total_fee=" . $total_fee . "&fee_type=" . $fee_type . "&return_url=" . $return_url .
          "&attach=" . $attach . "&";
       if($spbill_create_ip != "") {
	$sign_text .= "spbill_create_ip=" . $spbill_create_ip . "&";
		}
        $sign_text .= "key=" . $payment_info['config']['tencentpay_key'] ;
        $sign = strtolower(md5($sign_text));

        /* 交易参数 */
        $parameter = array(
            'cmdno'             => $cmd_no,                     // 业务代码, 财付通支付支付接口填  1
            'date'              => $today,                      // 商户日期：如20051212
            //'bank_type'         => $bank_type,                  // 银行类型:支持纯网关和财付通
            'desc'              => $desc,                       // 交易的商品名称
            //'purchaser_id'      => '',                          // 用户(买方)的财付通帐户,可以为空
            'bargainor_id'      => $payment_info['config']['tencentpay_id'],  // 商家的财付通商户号
            'transaction_id'    => $transaction_id,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'sp_billno'         => $sp_billno,                  // 商户系统内部的定单号,最多10位
            'total_fee'         => $total_fee,                  // 订单金额
            'fee_type'          => $fee_type,                   // 现金支付币种
            'return_url'        => $return_url,                 // 接收财付通返回结果的URL
            'attach'            => $attach,                     // 用户自定义签名
            'spbill_create_ip'  => $spbill_create_ip,           // 安全防范参数
            'sign'              => $sign,                       // MD5签名
            //'sys_id'            => '542554970',                 //ecshop C账号 不参与签名
            //'sp_suggestuser'    => '1202822001'                 //财付通分配的商户号

        );
        /*
        foreach($parameter as $k => $v) {
			$parameter[$k]=urlencode($v);
		}
         * 
         */
        
		//$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$sp_billno' where id = ".$payment_log_id);
		
        $def_url = "<style type='text/css'>.bank_types{float:left; display:block; background:url(./global/banklist_cz.gif); font-size:0px; width:160px; height:10px; text-align:left; padding:15px 0px;}";
        $def_url .=".bk_type0{background-position:10px -10px; }"; //默认
        $def_url .=".bk_type1001{background-position:15px -444px; }";  //招行
        $def_url .=".bk_type1002{background-position:15px -404px; }";  //工行
        $def_url .=".bk_type1003{background-position:15px -84px; }"; //建行
        $def_url .=".bk_type1005{background-position:15px -44px; }"; //农行
        $def_url .=".bk_type1004{background-position:15px -364px; }"; //上海浦东发展银行
        $def_url .=".bk_type1008{background-position:15px -324px; }"; //深圳发展银行
        $def_url .=".bk_type1009{background-position:15px -484px; }"; //兴业银行
        $def_url .=".bk_type1032{background-position:15px -610px; }"; //北京银行
        $def_url .=".bk_type1022{background-position:15px -124px; }"; //光大银行
        $def_url .=".bk_type1006{background-position:15px -164px; }"; //民生银行
        $def_url .=".bk_type1021{background-position:15px -284px; }"; //中信银行
        $def_url .=".bk_type1027{background-position:15px -244px; }"; //广东发展银行
        $def_url .=".bk_type1010{background-position:15px -903px; }"; //平安银行
        $def_url .=".bk_type1052{background-position:15px -939px; }"; //中国银行
        $def_url .=".bk_type1020{background-position:15px -204px; }"; //交通银行
        $def_url .=".bk_type1030{background-position:15px -782px; }"; //工行企业
        $def_url .=".bk_type1042{background-position:15px -864px; }"; //招行企业
        $def_url .=".bk_type1028{background-position:15px -524px; }"; //中国邮政储蓄银行(银联)
        $def_url .="</style>";
        $def_url  .= '<br /><form style="text-align:center;" action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" target="_blank" style="margin:0px;padding:0px" >';

        foreach ($parameter AS $key=>$val)
        {
            $def_url  .= "<input type='hidden' name='$key' value='$val' />";
        }
		
        /*
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
        $ks = 0;
		foreach($this->bank_types as $key=>$bank_type)
		{
			if(intval($payment_info['config']['tencentpay_gateway'][$bank_type])==1)
			{
				$def_url .="<label class='bank_types bk_type".$bank_type."'>：<input type='radio' name='bank_type' value='".$bank_type."'";
				if($ks == 0)
				{
					$def_url .= " checked='checked'";
				}
				$def_url .= " /></label>";
				$ks++;
			}
		}
		$def_url .= "<br clear='both' />";	
		$def_url .= "<br clear='both' />";	
		
		$def_url .="<label class='bank_types bk_type".$bank_id."'>";
			
		$def_url .= "<br clear='both' />";	
		$def_url .= "<br clear='both' />";
			*/
		$def_url .="<input type='hidden' name='bank_type' value='".$bank_id."'/>";		
		$def_url .= "<input type='submit' class='paybutton' value=".a_L('TENCENT_'.$bank_id)."支付></form>";
		$def_url .= "<br clear='both' />";	
		$def_url .= "<br clear='both' />";
				
		$def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";
        return $def_url;
        
        
	}
	
	public function getBackList($payment_id){
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo,description_1 from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);	

        $def_url = "<style type='text/css'>.bank_types{float:left; display:block; background:url(./global/banklist_cz.gif); font-size:0px; width:160px; height:10px;text-align:left; padding:15px 0px; _padding:12px 0px;}";
        $def_url .=".bk_type0{background-position:10px -10px; }"; //默认
        $def_url .=".bk_type1001{background-position:15px -444px; }";  //招行
        $def_url .=".bk_type1002{background-position:15px -404px; }";  //工行
        $def_url .=".bk_type1003{background-position:15px -84px; }"; //建行
        $def_url .=".bk_type1005{background-position:15px -44px; }"; //农行
        $def_url .=".bk_type1004{background-position:15px -364px; }"; //上海浦东发展银行
        $def_url .=".bk_type1008{background-position:15px -324px; }"; //深圳发展银行
        $def_url .=".bk_type1009{background-position:15px -484px; }"; //兴业银行
        $def_url .=".bk_type1032{background-position:15px -610px; }"; //北京银行
        $def_url .=".bk_type1022{background-position:15px -124px; }"; //光大银行
        $def_url .=".bk_type1006{background-position:15px -164px; }"; //民生银行
        $def_url .=".bk_type1021{background-position:15px -284px; }"; //中信银行
        $def_url .=".bk_type1027{background-position:15px -244px; }"; //广东发展银行
        $def_url .=".bk_type1010{background-position:15px -903px; }"; //平安银行
        $def_url .=".bk_type1052{background-position:15px -939px; }"; //中国银行
        $def_url .=".bk_type1020{background-position:15px -204px; }"; //交通银行
        $def_url .=".bk_type1030{background-position:15px -782px; }"; //工行企业
        $def_url .=".bk_type1042{background-position:15px -864px; }"; //招行企业
        $def_url .=".bk_type1028{background-position:15px -524px; }"; //中国邮政储蓄银行(银联)
        $def_url .="</style>";
        $def_url  .= '<form style="text-align:center;" action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" target="_blank" style="margin:0px;padding:0px" >';

		
        $ks = 0;
		foreach($this->bank_types as $key=>$bank_type)
		{
			if(intval($payment_info['config']['tencentpay_gateway'][$bank_type])==1)
			{
				$def_url .="<label class='bank_types bk_type".$bank_type."'><input id= check-".$bank_type." type='radio' name='payment' value='".$bank_type.'-'.$payment_id."'";
				if($ks == 0)
				{
					//$def_url .= " checked='checked'";
				}
				$def_url .= " /></label>".$payment_info['description_1'];
				$ks++;
			}
		}
		$def_url .= "<br clear='both' />";	
		$def_url .= "<br clear='both' />";	
		return $def_url;
	}
	
	public function dealResult($get,$post,$request)
	{			
		
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		


	 	/*取返回参数*/
        $cmd_no         = $request['cmdno'];
        $pay_result     = $request['pay_result'];
        $pay_info       = $request['pay_info'];
        $bill_date      = $request['date'];
        $bargainor_id   = $request['bargainor_id'];
        $transaction_id = $request['transaction_id'];
        $sp_billno      = $request['sp_billno'];
        $total_fee      = $request['total_fee'];
        $fee_type       = $request['fee_type'];
        $attach         = $request['attach'];
        $sign           = $request['sign'];

        //$payment    = D("Payment")->where("class_name='Tencentpay'")->find(); 
        //$order_sn   = $bill_date . str_pad(intval($sp_billno), 5, '0', STR_PAD_LEFT);
        //$log_id = preg_replace('/0*([0-9]*)/', '\1', $sp_billno); //取得支付的log_id
        $log_id = intval($sp_billno);
        
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($log_id));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
    	        
     	//$payment_id = $payment['id'];
    	$currency_id = $payment['currency']; 

        /* 如果pay_result大于0则表示支付失败 */
        if ($pay_result > 0)
        {
            $return_res['info'] = "支付失败";
            return $return_res;
        }
		
		$total_price = $total_fee / 100;


        /* 检查数字签名是否正确 */
        $sign_text  = "cmdno=" . $cmd_no . "&pay_result=" . $pay_result .
                          "&date=" . $bill_date . "&transaction_id=" . $transaction_id .
                            "&sp_billno=" . $sp_billno . "&total_fee=" . $total_fee .
                            "&fee_type=" . $fee_type . "&attach=" . $attach .
                            "&key=" . $payment['config']['tencentpay_key'];
        $sign_md5 = strtoupper(md5($sign_text));

        if ($sign_md5 != $sign)
        {
            $return_res['info'] = "验证失败";
            return $return_res;
        }
        else
        {
        	return s_order_paid($log_id, $total_price, $payment_id,$currency_id);
            /* 改变订单状态 */
        }
        
	}
}
?>