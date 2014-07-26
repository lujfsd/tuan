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
    $modules[$i]['code']    = 'AlipayBank';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '支付宝纯网关接口';

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


//http://pre.biz.sfubao.com/MerLogin.aspx 测试商户号:827438 测试登陆密码:38953532


require_once(VENDOR_PATH.'payment3/Payment.class.php');
class AlipayBankPayment implements Payment  {
	public $config = array(
	    'alipay_bank_partner'=>'',  //合作者身份ID
		'alipay_bank_account'=>'',  //支付宝帐号
		'alipay_bank_key'	=>'',  //校验码
	
		'tencentpay_gateway'	=>	array(			
			'ICBCBTB'=>'',    //工商银行(B2B)
			'ABCBTB'=>'',    //农业银行(B2B)
			'CCBBTB'=>'',    //建设银行(B2B)
			'SPDBBTB'=>'',    //上海浦东发展银行(B2B)
			'BOCB2C'=>'',    //中国银行
			'ICBCB2C'=>'',    //工商银行
			'CMB'=>'',    //招商银行
			'CCB'=>'',    //建设银行
			'ABC'=>'',    //农业银行
			'SPDB'=>'',    //上海浦东发展银行
			'CIB'=>'',    //兴业银行
			'GDB'=>'',    //广东发展银行
			'SDB'=>'',    //深圳发展银行
			'CMBC'=>'',    //民生银行
			'COMM'=>'',    //交通银行
			'CITIC'=>'',    //中信银行				
			'HZCBB2C'=>'',    //杭州银行		
			'CEBBANK'=>'',    //光大银行
			'SHBANK'=>'',    //上海银行
			'NBBANK'=>'',    //宁波银行
			'SPABANK'=>'',    //平安银行
			'BJRCB'=>'',    //北京农村商业银行
			'FDB'=>'',    //富滇银行
			'POSTGC'=>'',    //中国邮政储蓄银行
			'abc1003'=>'',    //visa
			'abc1004'=>'',    //master
		)	
	);	
	
	public $bank_types = array(			
			'ICBCBTB',    //工商银行(B2B)
			'ABCBTB',    //农业银行(B2B)
			'CCBBTB',    //建设银行(B2B)
			'SPDBBTB',    //上海浦东发展银行(B2B)
			'BOCB2C',    //中国银行
			'ICBCB2C',    //工商银行
			'CMB',    //招商银行
			'CCB',    //建设银行
			'ABC',    //农业银行
			'SPDB',    //上海浦东发展银行
			'CIB',    //兴业银行
			'GDB',    //广东发展银行
			'SDB',    //深圳发展银行
			'CMBC',    //民生银行
			'COMM',    //交通银行
			'CITIC',    //中信银行				
			'HZCBB2C',    //杭州银行		
			'CEBBANK',    //光大银行
			'SHBANK',    //上海银行
			'NBBANK',    //宁波银行
			'SPABANK',    //平安银行
			'BJRCB',    //北京农村商业银行
			'FDB',    //富滇银行
			'POSTGC',    //中国邮政储蓄银行
			'abc1003',    //visa
			'abc1004',    //master
	);	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$total_fee = round($money,2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
			
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module,create_time from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		
		if($payment_log['rec_module']=='Order'){
			$rec_id = $GLOBALS['db']->getOne("select rec_id from ".DB_PREFIX."order_goods where order_id=".intval($payment_log['rec_id'])." limit 1");
			$goods_data = $GLOBALS['db']->getRow("select sn,name_1,goods_short_name from ".DB_PREFIX."goods where id=".intval($rec_id)." limit 1");
			$data_sn_1 = $goods_data['goods_short_name']==''?$goods_data['name_1']:$goods_data['goods_short_name'];
			
			$Order = $GLOBALS['db']->getRow("select sn,bank_id from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
			$data_sn = $Order['sn'];
			$bank_id = $Order['bank_id'];			
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$Order = $GLOBALS['db']->getRow("select sn,bank_id from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
			$data_sn = $Order['sn'];
			$bank_id = $Order['bank_id'];			
			$data_sn_1 = '';
		}
		$out_trade_no = 'fw-'.$data_sn.'-'.$payment_log_id;
		$body=$data_sn_1;
		if ($bank_id=='0' || trim($bank_id) == 'SDO1' || $payChannel == '04'){
			$bank_id = '';
		}
		
		$paymentGateWayURL = 'https://mapi.alipay.com/gateway.do';			
		$_orderNo = $payment_log_id;
		$defaultbank = $bank_id;//$payment_info['config']['sdo_bankcode'];		
		$postBackURL = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=AlipayBank';//付款完成后的跳转页面
		$notifyURL = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&md=autoNotice&payment_name=AlipayBank';//通知页面
		$signType = 'MD5'; //MD5
		
		$parameter = array(
		"service"			=> "create_direct_pay_by_user",
		"payment_type"		=> "1",
		
		"partner"			=> $payment_info['config']['alipay_bank_partner'],
		"_input_charset"	=> 'utf-8',
        "seller_email"		=> $payment_info['config']['alipay_bank_account'],
        "return_url"		=> $postBackURL,
        "notify_url"		=> $notifyURL,
		/* 业务参数 */
		'out_trade_no'      => $out_trade_no,//'fw123456'.$payment_log_id, //modify by chenfq 2010-05-17 将$data_sn.$payment_log_id改为：'fw123456'.$payment_log_id
		"subject"			=> $data_sn,//订单名称，显示在支付宝收银台里的“商品名称”里，显示在支付宝的交易管理的“商品名称”的列表里。
		"total_fee"			=> $total_fee,//订单总金额，显示在支付宝收银台里的“应付总额”里
		
		"paymethod"			=> 'bankPay',//默认支付方式（纯网关是bankPay）
		"defaultbank"		=> $defaultbank,//默认网银（银行编码）
		/*防钓鱼*/
		"anti_phishing_key"	=> '',//防钓鱼时间戳
		"exter_invoke_ip"	=> '',//获取客户端的IP地址，建议：编写获取客户端IP地址的程序
		);
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
        $sign  = substr($sign, 0, -1). $payment_info['config']['alipay_bank_key'];
        $sign_md5 = md5($sign);
			
		$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$out_trade_no' where id = ".$payment_log_id);
		$payLinks = '<a onclick="window.open(\'https://mapi.alipay.com/gateway.do?'.$param. '&sign='.$sign_md5.'&sign_type=MD5\')" href="javascript:;"><input type="submit" class="paybutton" name="buy" value="'.a_L('TENCENT_'.$Order['bank_id']).'支付"/></a>';
	if(!empty($payment_info['logo']))
		{
			$payLinks = '<a href="https://mapi.alipay.com/gateway.do?'.$param. '&sign='.$sign_md5.'&sign_type=MD5" target="_blank" class="payLink"><img src='.$payment_info['logo'].' style="border:solid 1px #ccc;" /></a><div class="blank"></div>'.$payLinks;
		}
		$def_url = '<div style="text-align:center">'.$payLinks.'</div>';
		$def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($total_fee)."</span>";
        return $def_url; 
	}
	
	//自动对账
	public function autoNotice(){
		$res = $this->dealResult($_GET,$_POST,$_REQUEST);
		if($res['status'])
		{
			echo "success";
		}
		else
		{
			echo 'fail';
		}
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
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key!='payment_name' && $key!='a' && $key!='m' && $key!='md' && $key!='md')
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['config']['alipay_bank_key'];
       
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
		
		if ($get['trade_status'] == 'TRADE_FINISHED' ||$get['trade_status'] == 'TRADE_SUCCESS'){
			//记录支付序列号 2011-05-30
		   return s_order_paid($payment_log_id,$money,$payment_id,$currency_id,$pay_back_code);
		}else{
		   return false;
		}    	
	}
	
	
	public function getBackList($payment_id){
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo,description_1,name_1 from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);	
 				//print_r($payment_info['config']['tencentpay_gateway']);
        $def_url = "<style type='text/css'>.bank_alipay_types{float:left; display:block; font-size:0px; width:160px; height:10px; text-align:left; padding:15px 0px; _padding:11px 0px;}";
        
        $def_url .=".bk_alipay_typeICBCBTB{background:url(./global/alipaybank_img/B2BENV_ICBC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";    //工商银行(B2B)
		$def_url .=".bk_alipay_typeABCBTB{background:url(./global/alipaybank_img/B2B_ENV_ABC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";    //农业银行(B2B)
		$def_url .=".bk_alipay_typeCCBBTB{background:url(./global/alipaybank_img/B2B_ENV_CCB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //建设银行(B2B)	
		$def_url .=".bk_alipay_typeSPDBBTB{background:url(./global/alipaybank_img/B2B_ENV_SPDB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //上海浦东发展银行(B2B)				
		$def_url .=".bk_alipay_typeBOCB2C{background:url(./global/alipaybank_img/BOC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //中国银行
		$def_url .=".bk_alipay_typeICBCB2C{background:url(./global/alipaybank_img/ICBC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //工商银行		
		$def_url .=".bk_alipay_typeCMB{background:url(./global/alipaybank_img/CMB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //招商银行		
		$def_url .=".bk_alipay_typeCCB{background:url(./global/alipaybank_img/CCB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //建设银行	
		$def_url .=".bk_alipay_typeABC{background:url(./global/alipaybank_img/ABC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //农业银行	
		$def_url .=".bk_alipay_typeSPDB{background:url(./global/alipaybank_img/SPDB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //上海浦东发展银行			
		$def_url .=".bk_alipay_typeCIB{background:url(./global/alipaybank_img/CIB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //兴业银行		
		$def_url .=".bk_alipay_typeGDB{background:url(./global/alipaybank_img/GDB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //广东发展银行
		$def_url .=".bk_alipay_typeSDB{background:url(./global/alipaybank_img/SDB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //深圳发展银行		
		$def_url .=".bk_alipay_typeCMBC{background:url(./global/alipaybank_img/CMBC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //民生银行	
		$def_url .=".bk_alipay_typeCOMM{background:url(./global/alipaybank_img/COMM_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //交通银行		
		$def_url .=".bk_alipay_typeCITIC{background:url(./global/alipaybank_img/CITIC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //中信银行	
		$def_url .=".bk_alipay_typeHZCBB2C{background:url(./global/alipaybank_img/HZCB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //杭州银行		
		$def_url .=".bk_alipay_typeCEBBANK{background:url(./global/alipaybank_img/CEB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //光大银行		
		$def_url .=".bk_alipay_typeSHBANK{background:url(./global/alipaybank_img/SHBANK_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //上海银行
		$def_url .=".bk_alipay_typeNBBANK{background:url(./global/alipaybank_img/NBBANK_OUT.gif) no-repeat 15px 10px;*background-position:15px 14px;_background-position:15px 10px;}";   //宁波银行	
		$def_url .=".bk_alipay_typeSPABANK{background:url(./global/alipaybank_img/SPABANK_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //平安银行		
		$def_url .=".bk_alipay_typeBJRCB{background:url(./global/alipaybank_img/BJRCB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //北京农村商业银行
		$def_url .=".bk_alipay_typeFDB{background:url(./global/alipaybank_img/FDB_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //富滇银行		
		$def_url .=".bk_alipay_typePOSTGC{background:url(./global/alipaybank_img/PSBC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //中国邮政储蓄银行
		$def_url .=".bk_alipay_typeabc1003{background:url(./global/alipaybank_img/visa_ENV_ABC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //建设银行(B2B)
		$def_url .=".bk_alipay_typeabc1004{background:url(./global/alipaybank_img/master_ENV_ABC_OUT.gif) no-repeat 25px 10px;*background-position:25px 14px;_background-position:25px 10px;}";   //建设银行(B2B)
        $def_url .="</style>";
        //$def_url  .= '<form style="text-align:center;" action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" target="_blank" style="margin:0px;padding:0px" >';
		
        $ks = 0;
        //echo $payment_info['config']['sdo_paychannel'];
        $def_url .="<p style='background:url(./global/alipaybank_img/alipay_i_safe.gif) no-repeat 0px 0px; padding-left:22px;'><strong>".$payment_info['name_1']."</strong>&nbsp;银行：</p>";
        	foreach($this->bank_types as $key=>$bank_type)
			{
				//echo "./global/sdo/BanK".$bank_type.".gif'<br>";
				//echo $bank_type.":".$payment_info['config']['tencentpay_gateway'][$bank_type]."<br>";
				if(intval($payment_info['config']['tencentpay_gateway'][$bank_type])==1)
				{
					//<img for="check-{$payment_item.id}" src="{$CND_URL}{$payment_item.logo}" width="150" alt="{$payment_item.name_1}" title="{$payment_item.name_1}"/>
					$def_url .="<label class='bank_alipay_types bk_alipay_type".$bank_type."'><input id= check-".$bank_type." type='radio' name='payment' value='".$bank_type.'-'.$payment_id."'";
					if($ks == 0)
					{
						//$def_url .= " checked='checked'";
					}
					$def_url .= " /></label>".$payment_info['description_1'];
					$ks++;
				}
			 }        	

		//exit;
		$def_url .= "<br clear='both' />";	
		$def_url .= "<br clear='both' />";	
		return $def_url;
	}
}
?>