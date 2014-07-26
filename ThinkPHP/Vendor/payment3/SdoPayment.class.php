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
    $modules[$i]['code']    = 'Sdo';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '盛付通';

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
class SdoPayment implements Payment  {
	public $config = array(
	    'sdo_merchantno'=>'',  //商户号码
        'sdo_md5key'=>'',  //MD5值
		'sdo_paychannel'=>'04',  //支付通道
		'sdo_defaultchannel'=>'04',  //默认支付通道
		//'sdo_bankcode'=>'SDTBNK',  //银行编号
		'tencentpay_gateway'	=>	array(
			'SDO1'=>'',    //盛付通
			'SDTBNK'=>'',    //测试银行
			'ICBC'=>'',    //工商银行
			'CCB'=>'',    //建设银行
			'ABC'=>'',    //农业银行
			'CMB'=>'',    //招商银行
			'COMM'=>'',    //交通银行
			'CMBC'=>'',    //民生银行
			'CIB'=>'',    //兴业银行
			'HCCB'=>'',    //杭州银行
			'CEB'=>'',    //光大银行
			'CITIC'=>'',    //中信银行
			'GZCB'=>'',    //广州银行
			'HXB'=>'',    //华夏银行
			'HKBEA'=>'',    //东亚银行
			'BOC'=>'',    //中国银行
			'WZCB'=>'',    //温州银行
			'BCCB'=>'',    //北京银行
			'SXJS'=>'',    //晋商银行
			'NBCB'=>'',    //宁波银行
			'SZPAB'=>'',    //平安银行
			'BOS'=>'',    //上海银行
			'NJCB'=>'',    //南京银行
			'SPDB'=>'',    //浦东发展银行
			'GNXS'=>'',    //广州市农村信用合作社
			'GDB'=>'',    //广东发展银行
			'SHRCB'=>'',    //上海市农村商业银行
			'CBHB'=>'',    //渤海银行
			'HKBCHINA'=>'',    //汉口银行
			'ZHNX'=>'',    //珠海市农村信用合作联社
			'SDE'=>'',    //顺德农信社
			'YDXH'=>'',    //尧都信用合作联社
			'CZCB'=>'',    //浙江稠州商业银行
			'BJRCB'=>'',    //北京农商行
			'PSBC'=>'',    //中国邮政储蓄银行
			'SDB'=>'',    //深圳发展银行
		)	
	);	
	
	public $bank_types = array(
			'SDO1',//盛付通
			'SDTBNK',    //测试银行
			'ICBC',    //工商银行
			'CCB',    //建设银行
			'ABC',    //农业银行
			'CMB',    //招商银行
			'COMM',    //交通银行
			'CMBC',    //民生银行
			'CIB',    //兴业银行
			'HCCB',    //杭州银行
			'CEB',    //光大银行
			'CITIC',    //中信银行
			'GZCB',    //广州银行
			'HXB',    //华夏银行
			'HKBEA',    //东亚银行
			'BOC',    //中国银行
			'WZCB',    //温州银行
			'BCCB',    //北京银行
			'SXJS',    //晋商银行
			'NBCB',    //宁波银行
			'SZPAB',    //平安银行
			'BOS',    //上海银行
			'NJCB',    //南京银行
			'SPDB',    //浦东发展银行
			'GNXS',    //广州市农村信用合作社
			'GDB',    //广东发展银行
			'SHRCB',    //上海市农村商业银行
			'CBHB',    //渤海银行
			'HKBCHINA',    //汉口银行
			'ZHNX',    //珠海市农村信用合作联社
			'SDE',    //顺德农信社
			'YDXH',    //尧都信用合作联社
			'CZCB',    //浙江稠州商业银行
			'BJRCB',    //北京农商行
			'PSBC',    //中国邮政储蓄银行
			'SDB',    //深圳发展银行
	);	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
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
		
		$payChannel = $payment_info['config']['sdo_paychannel'];
		$defaultChannel = $payment_info['config']['sdo_defaultchannel'];		
		if ($bank_id=='0' || $payChannel != '04'|| trim($bank_id) == 'SDO1'){
			$bank_id = '';
		}
		
		//请根据您目前的开发环境同时请参考“盛付通银行直连即时账接口文档V1.5.pdf”文档设置
		//测试地址: http://pre.netpay.sdo.com/paygate/ibankpay.aspx
		//正式地址: http://netpay.sdo.com/paygate/ibankpay.aspx
		//http://pre.netpay.sdo.com/paygate/ibankpay.aspx	
		
		//请根据您目前的开发环境同时请参考“盛付通即时到账接口文档V1.5.pdf”文档设置
		//测试地址: http://pre.netpay.sdo.com/paygate/default.aspx
		//正式地址: http://netpay.sdo.com/paygate/default.aspx
		if (empty($bank_id)){
			$paymentGateWayURL = 'http://netpay.sdo.com/paygate/default.aspx';
		}else{
			$paymentGateWayURL = 'http://netpay.sdo.com/paygate/ibankpay.aspx';	
		}

		
		$_orderNo = $payment_log_id;
		$_amount = round($money,2) ;
		$_merchantNo = $payment_info['config']['sdo_merchantno'];
		$_merchantUserId = "";
		$_orderTime = a_toDate($payment_log['create_time'],'YmdHis');//date('YmjHis');
		$_productNo = '';
		$_productDesc = '';//$data_sn_1;
		$_remark1 = "";
		$_remark2 = "";
		$_bankCode = $bank_id;//$payment_info['config']['sdo_bankcode'];
		$_productURL = "";
		
		$Version = '3.0';
		
		$postBackURL = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Sdo';//付款完成后的跳转页面
		$notifyURL = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=Sdo';//通知发货页面
		$backURL = '';
		$currencyType = "RMB";
		$notifyUrlType='http'; //发货通知方式  http,https,tcp等,默认是http（如果不填写也为http）
		$signType = 2; //MD5

		
        $signString = $Version .$_amount .$_orderNo .$_merchantNo
					        .$_merchantUserId .$payChannel
					        .$postBackURL .$notifyURL
					        .$backURL .$_orderTime.$currencyType 
					        .$notifyUrlType .$signType
					        .$_productNo .$_productDesc .$_remark1 .$_remark2 .$_bankCode
					        .$defaultChannel.$_productURL;

        //echo $signString."<br>";
        //echo "md5key:".$payment_info['config']['sdo_md5key']."<br>";
		$_mac = md5($signString.$payment_info['config']['sdo_md5key']);
		//echo "mac:".$_mac."<br>";				
		$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$_orderNo' where id = ".$payment_log_id);
        $def_url  = '<form style="text-align:center;" method=post action="'.$paymentGateWayURL.'" target="_blank">';
        $def_url .= "<input type=HIDDEN name='Amount' value='".$_amount."'>";
        $def_url .= "<input type=HIDDEN name='MerchantUserId' value='".$_merchantUserId."'>";
        $def_url .= "<input type=HIDDEN name='OrderNo' value='".$_orderNo."'>";
        $def_url .= "<input type=HIDDEN name='OrderTime'  value='".$_orderTime."'>";
        $def_url .= "<input type=HIDDEN name='ProductNo'  value='".$_productNo."'>";
        $def_url .= "<input type=HIDDEN name='ProductDesc' value='".$_productDesc."'>";
        $def_url .= "<input type=HIDDEN name='Remark1' value='".$_remark1."'>";
        $def_url .= "<input type=HIDDEN name='Remark2' value='".$_remark2."'>";
        $def_url .= "<input type=HIDDEN name='ProductURL' value='".$_productURL."'>";
        
        $def_url .= "<input type=HIDDEN name='BankCode' value='".$_bankCode."'>";
        
        $def_url .= "<input type=HIDDEN name='Version' value='".$Version."'>";
        $def_url .= "<input type=HIDDEN name='MerchantNo' value='".$_merchantNo."'>";
        $def_url .= "<input type=HIDDEN name='PayChannel' value='".$payChannel."'>";
        $def_url .= "<input type=HIDDEN name='PostBackURL' value='".$postBackURL."'>";
        $def_url .= "<input type=HIDDEN name='NotifyURL' value='".$notifyURL."'>";
        $def_url .= "<input type=HIDDEN name='BackURL' value='".$backURL."'>";
        $def_url .= "<input type=HIDDEN name='CurrencyType' value='".$currencyType."'>";
        $def_url .= "<input type=HIDDEN name='NotifyURLType' value='".$notifyUrlType."'>";
        $def_url .= "<input type=HIDDEN name='SignType' value='".$signType."'>";
        $def_url .= "<input type=HIDDEN name='DefaultChannel' value='".$defaultChannel."'>";
        $def_url .= "<input type=HIDDEN name='MAC' value='".$_mac."'>";
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
        $def_url .= "<input type='submit' class='paybutton' value=".a_L('TENCENT_'.$Order['bank_id'])."支付></form>";
        $def_url .= "</form>";
        $def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($_amount)."</span>";
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
		
		
		$_orderNo = $get["OrderNo"];//商户订单号
		$payment_log_id = intval($_orderNo);
		
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
		$payment_info = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);
    	$payment_info['config'] = unserialize($payment_info['config']);
    	
		//获取参数
		$_amount = $get["Amount"];//订单金额
		$_payAmount = $get["PayAmount"];//实际支付金额
		//$_orderNo = $get["OrderNo"];//商户订单号
		$_serialNo = $get["serialno"];//支付序列号
		$_status = $get["Status"];//支付状态 "01"表示成功
		$_merchantNo = $get["MerchantNo"];//商户号
		$_payChannel = $get["PayChannel"];//实际支付渠道
		$_discount = $get["Discount"];//实际折扣率
		$_signType = $get["SignType"];//签名方式。1-RSA 2-Md5
		$_payTime = $get["PayTime"];//支付时间
		$_currencyType = $get["CurrencyType"];//货币类型
		$_productNo = $get["ProductNo"];//产品编号
		$_productDesc = $get["ProductDesc"];//产品描述
		$_remark1 = $get["Remark1"];//产品备注1
		$_remark2 = $get["Remark2"];//产品备注2
		$_exInfo = $get["ExInfo"];//额外的返回信息
		$_mac = $get["MAC"];//签名字符串
		
		$verifyResult= $this->verifySign($_amount,$_payAmount,$_orderNo,$_serialNo,$_status
				,$_merchantNo,$_payChannel,$_discount,$_signType,$_payTime,$_currencyType
				,$_productNo,$_productDesc,$_remark1,$_remark2,$_exInfo,$payment_info['config']['sdo_md5key']);
       
		if (strtoupper($verifyResult) != strtoupper($_mac))
        {
            $return_res['info'] = a_L("VALID_ERROR");
            return $return_res; 
        }
        
    	$money = $_payAmount;
    	$payment_id = $payment_info['id'];
    	$currency_id = $payment_info['currency']; 
		$pay_back_code = $_serialNo;//支付序列号 2011-05-30
		
		if ($_status == '01'){
		   return s_order_paid($payment_log_id,$money,$payment_id,$currency_id,$pay_back_code);
		}else{
		   return false;
		}    	
	}
	
	
	public function getBackList($payment_id){
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo,description_1 from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);	
 
        $def_url = "<style type='text/css'>.bank_sdo_types{float:left; display:block; background:url(./global/banklist_sdo.jpg); font-size:0px; width:160px; height:10px; text-align:left; padding:15px 0px; _padding:11px 0px;}";
        
        $def_url .=".bk_typeSDO1{background-position:15px -1420px; }";    //盛付通
		$def_url .=".bk_typeSDTBNK{background-position:15px -5px; }";    //测试银行
		$def_url .=".bk_typeICBC{background-position:15px -44px; }";    //工商银行
		$def_url .=".bk_typeCCB{background-position:15px -84px; }";    //建设银行
		$def_url .=".bk_typeABC{background-position:15px -124px; }";    //农业银行
		$def_url .=".bk_typeCMB{background-position:15px -164px; }";    //招商银行
		$def_url .=".bk_typeCOMM{background-position:15px -204px; }";    //交通银行
		$def_url .=".bk_typeCMBC{background-position:15px -244px; }";    //民生银行
		$def_url .=".bk_typeCIB{background-position:15px -284px; }";    //兴业银行
		$def_url .=".bk_typeHCCB{background-position:15px -324px; }";    //杭州银行
		$def_url .=".bk_typeCEB{background-position:15px -364px; }";    //光大银行
		$def_url .=".bk_typeCITIC{background-position:15px -404px; }";    //中信银行
		$def_url .=".bk_typeGZCB{background-position:15px -444px; }";    //广州银行
		$def_url .=".bk_typeHXB{background-position:15px -484px; }";    //华夏银行
		$def_url .=".bk_typeHKBEA{background-position:15px -524px; }";    //东亚银行
		$def_url .=".bk_typeBOC{background-position:15px -568px; }";    //中国银行
		$def_url .=".bk_typeWZCB{background-position:15px -610px; }";    //温州银行
		$def_url .=".bk_typeBCCB{background-position:15px -655px; }";    //北京银行
		$def_url .=".bk_typeSXJS{background-position:15px -700px; }";    //晋商银行
		$def_url .=".bk_typeNBCB{background-position:15px -745px; }";    //宁波银行
		$def_url .=".bk_typeSZPAB{background-position:15px -785px; }";    //平安银行
		$def_url .=".bk_typeBOS{background-position:15px -825px; }";    //上海银行
		$def_url .=".bk_typeNJCB{background-position:15px -860px; }";    //南京银行
		$def_url .=".bk_typeSPDB{background-position:15px -900px; }";    //浦东发展银行
		$def_url .=".bk_typeGNXS{background-position:15px -940px; }";    //广州市农村信用合作社
		$def_url .=".bk_typeGDB{background-position:15px -980px; }";    //广东发展银行
		$def_url .=".bk_typeSHRCB{background-position:15px -1020px; }";    //上海市农村商业银行
		$def_url .=".bk_typeCBHB{background-position:15px -1060px; }";    //渤海银行
		$def_url .=".bk_typeHKBCHINA{background-position:15px -1100px; }";    //汉口银行
		$def_url .=".bk_typeZHNX{background-position:15px -1140px; }";    //珠海市农村信用合作联社
		$def_url .=".bk_typeSDE{background-position:15px -1180px; }";    //顺德农信社
		$def_url .=".bk_typeYDXH{background-position:15px -1220px; }";    //尧都信用合作联社
		$def_url .=".bk_typeCZCB{background-position:15px -1260px; }";    //浙江稠州商业银行
		$def_url .=".bk_typeBJRCB{background-position:15px -1300px; }";    //北京农商行
		$def_url .=".bk_typePSBC{background-position:15px -1340px; }";    //中国邮政储蓄银行
		$def_url .=".bk_typeSDB{background-position:15px -1380px; }";    //深圳发展银行        
                $def_url .="</style>";
                $def_url .="<script type=\"text/javascript\">";
                $def_url .="function set_bank(bank_id){";
                $def_url .="$(\"input[name='bank_id']\").val(bank_id)";
                $def_url .="}</script>";
        //$def_url  .= '<form style="text-align:center;" action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" target="_blank" style="margin:0px;padding:0px" >';
		
        $ks = 0;
        //echo $payment_info['config']['sdo_paychannel'];
        if ($payment_info['config']['sdo_paychannel'] == '04'){
        	foreach($this->bank_types as $key=>$bank_type)
			{
				//echo "./global/sdo/BanK".$bank_type.".gif'<br>";
				if(intval($payment_info['config']['tencentpay_gateway'][$bank_type])==1)
				{
					//<img for="check-{$payment_item.id}" src="{$CND_URL}{$payment_item.logo}" width="150" alt="{$payment_item.name_1}" title="{$payment_item.name_1}"/>
					$def_url .="<label class='bank_sdo_types bk_type".$bank_type."'><input id= check-".$bank_type." type='radio' name='payment' onclick=\"set_bank('".$bank_type."')\" value='".$bank_type.'-'.$payment_id."'";
					if($ks == 0)
					{
						//$def_url .= " checked='checked'";
					}
					$def_url .= " /></label>".$payment_info['description_1'];
					$ks++;
				}
			} 	      	
        }else{
			$def_url .="<label class='bank_sdo_types bk_typeSDO1'><input id=check-SDO1 type='radio' name='payment' value='SDO1".'-'.$payment_id."'";
			$def_url .= " /></label>".$payment_info['description_1'];        	
        }
		$def_url .= "<input type='hidden' name='bank_id' value='' />";
		$def_url .= "<br clear='both' />";	
		$def_url .= "<br clear='both' />";	
		return $def_url;
	}
		
	public function verifySign($amount , $payAmount ,$orderNo
    		,$serialNo,$status,$merchantNo , $payChannel
    		,$discount,$signType ,$payTime,$currencyType
    		,$productNo,$productDesc,$remark1,$remark2,$exInfo,$md5key){
    			
			$toSignString = $amount."|".$payAmount."|".$orderNo."|".
										$serialNo."|".$status."|".$merchantNo."|".
										$payChannel."|".$discount."|".$signType."|".
										$payTime."|".$currencyType."|".$productNo."|".
										$productDesc."|".$remark1."|".$remark2."|".$exInfo;

			return  md5($toSignString. "|" . $md5key);			
		}	
		
}
?>