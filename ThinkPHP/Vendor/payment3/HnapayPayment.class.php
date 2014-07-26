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
    $modules[$i]['code']    = 'Hnapay';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '新生在线';

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
class HnapayPayment implements Payment {
	public $config = array(
	    'hnapay_partnerid'=>'',  //商户编号
        'hnapay_key'=>'',  	  //MD5密钥
		'tencentpay_gateway'	=>	array(
			'hnapay'=>'',    //新生支付
			'icbc'=>'',    // 工商银行
			'abc'=>'',    // 农业银行
			'ccb'=>'',    // 建设银行
			'boc'=>'',    // 中国银行
			'comm'=>'',    // 交通银行
			'cmb'=>'',    // 招商银行
			'cmbc'=>'',    // 民生银行
			'cib'=>'',    // 兴业银行
			'spdb'=>'',    // 浦发银行
			'hxb'=>'',    // 华夏银行
			'ecitic'=>'',    // 中信银行
			'ceb'=>'',    // 光大银行
			'gdb'=>'',    // 广发银行
			'post'=>'',    // 邮政储蓄
			'sdb'=>'',    // 深发展银行
			'bea'=>'',    // 东亚银行
			'nb'=>'',    // 宁波银行
			'bccb'=>'',    // 北京银行
		)	
	);	
	
	public $bank_types = array(
			'hnapay',	//新生支付
			'icbc',    // 工商银行
			'abc',    // 农业银行
			'ccb',    // 建设银行
			'boc',    // 中国银行
			'comm',    // 交通银行
			'cmb',    // 招商银行
			'cmbc',    // 民生银行
			'cib',    // 兴业银行
			'spdb',    // 浦发银行
			'hxb',    // 华夏银行
			'ecitic',    // 中信银行
			'ceb',    // 光大银行
			'gdb',    // 广发银行
			'post',    // 邮政储蓄
			'sdb',    // 深发展银行
			'bea',    // 东亚银行
			'nb',    // 宁波银行
			'bccb',    // 北京银行
	);
	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$partnerID           = trim($payment_info['config']['hnapay_partnerid']);
        $data_vpaykey       = trim($payment_info['config']['hnapay_key']);
        
        //index.php?m=Order&a=pay&id=
		//$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Hnapay';
		//returnUrl 不返回参数，所以需要自己传参数定位
		$noticeUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&md=autoNotice&payment_name=Hnapay';
		
        
        $totalAmount = $money * 100;
        $user_id = 0;
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		$orderDetails = '';
		if($payment_log['rec_module']=='Order'){
			//$rec_id = $GLOBALS['db']->getOne("select rec_id from ".DB_PREFIX."order_goods where order_id=".intval($payment_log['rec_id'])." limit 1");
			//$goods_data = $GLOBALS['db']->getRow("select name_1,goods_short_name from ".DB_PREFIX."goods where id=".intval($rec_id)." limit 1");
			//$data_sn = $goods_data['goods_short_name']==''?$goods_data['name_1']:$goods_data['goods_short_name'];
			
			$Order = $GLOBALS['db']->getRow("select sn,bank_id,user_id from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
			$data_sn = $Order['sn'];
			$bank_id = $Order['bank_id'];
			$user_id = $Order['user_id'];
						
			$sql = "select a.data_name, a.attr, a.number, c.name_1 ".
				  "from ".DB_PREFIX."order_goods a ".
				  "left outer join ".DB_PREFIX."goods b on b.id = a.rec_id ".
				  "left outer join ".DB_PREFIX."weight c on c.id = b.weight_unit ".
				 "where a.order_id =". intval($payment_log['rec_id']);
			$order_goods_list = $GLOBALS['db']->getAll($sql);
			foreach($order_goods_list as $goods){
				if (empty($goods['attr'])){
					$data_sn .= $goods['data_name'];//.'('.$goods['number'].$goods['name_1'].')';
				}else{
					$data_sn .= $goods['data_name'];//.'('.$goods['attr'].')('.$goods['number'].$goods['name_1'].')';
				}
			}
			
			$data_sn = str_replace(',', '', $data_sn);
			$orderDetails = $payment_log_id.",".$totalAmount.",".a_fanweC('shop_name').",".$data_sn.",1";
			
			$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Order&a=pay&id='.intval($payment_log['rec_id']);
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$Order = $GLOBALS['db']->getRow("select sn,bank_id,user_id from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
			
			$data_sn = $Order['sn'];
			$bank_id = $Order['bank_id'];
			$user_id = $Order['user_id'];
			
			$order_sn = $data_sn;
			
			$orderDetails = $payment_log_id.",".$totalAmount.",".a_fanweC('shop_name').",会员冲值,1";
			
			$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&md=check&payment_name=Hnapay&id='.intval($payment_log_id);
		}
		   
		        
        $version = '2.6';
        $submitTime =  date( "YmdHis" );
		$type = '1001';//商品购买（即时到帐）
		$charset = '1';
		$signType = '2';
		//$failureTime = date( "YmdHis" ) + 3600 * 24 * 7;		
		$failureTime = date( "YmdHis", time() + 90*86400);
		$customerIP = '';
		$buyerMarked = '0898-31915068';
		
		if (strtoupper($bank_id) == 'HNAPAY'){
			$payType = 'ALL';
			$directFlag = '0';//是否使用银企直连0：非直连 （默认）1：直连
			$orgCode = '';//目标资金机构代码。按附件银行代码列表中选择一家。仅在选择直联的状态下有效。需商务开通例如：icbc						
		}else{
			$payType = 'BANK_B2C';
			$directFlag = '1';//是否使用银企直连0：非直连 （默认）1：直连
			$orgCode = $bank_id;//目标资金机构代码。按附件银行代码列表中选择一家。仅在选择直联的状态下有效。需商务开通例如：icbc					
		}

		$currencyCode = '1';
		$couponFlag = '0';
		$borrowingMarked = '0';		
		$platformID = '';
		
		$user_info = $GLOBALS['db']->getRow("select mobile_phone,email from ".DB_PREFIX."user where id=".intval($user_id));
		if (!empty($user_info) && (!empty($user_info['mobile_phone']) || !empty($user_info['email']))){
			
			if (!empty($user_info['email']) && $user_info['email'] != ''){
				$buyerMarked = $user_info['email'];
			}
						
			if (!empty($user_info['mobile_phone']) && $user_info['mobile_phone'] != ''){
				$buyerMarked = $user_info['mobile_phone'];
			}
		}
		

		$signMsg = "version=".$version
			 ."&serialID=".$payment_log_id
			 ."&submitTime=".$submitTime
			 ."&failureTime=".$failureTime
			 ."&customerIP=".$customerIP
			 ."&orderDetails=".$orderDetails
			 ."&totalAmount=".$totalAmount
			 ."&type=".$type
			 ."&buyerMarked=".$buyerMarked
			 ."&payType=".$payType
			 ."&orgCode=".$orgCode
			 ."&currencyCode=".$currencyCode
			 ."&directFlag=".$directFlag
			 ."&borrowingMarked=".$borrowingMarked
			 ."&couponFlag=".$couponFlag
			 ."&platformID=".$platformID
			 ."&returnUrl=".$returnUrl
			 ."&noticeUrl=".$noticeUrl
			 ."&partnerID=".$partnerID
			 ."&remark=".$payment_log_id
			 ."&charset=".$charset
			 ."&signType=".$signType;
		//print_r($signMsg)."<br>";
		//echo $data_vpaykey;
		$signMsg = $signMsg."&pkey=".$data_vpaykey;
		$signMsg =  md5($signMsg);

		//https://qaapp.hnapay.com/website/pay.htm; https://www.hnapay.com/website/pay.htm
        $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$data_orderid' where id = ".$payment_log_id);
        $def_url  = '<form style="text-align:center;" method=post action="https://www.hnapay.com/website/pay.htm" target="_blank">';
        
		$def_url .= "<input type='hidden' name='version'  value='".$version."'>"; 
		$def_url .= "<input type='hidden' name='serialID'  value='".$payment_log_id."'>";
		$def_url .= "<input type='hidden' name='submitTime'  value='".$submitTime."'>";
		$def_url .= "<input type='hidden' name='failureTime'  value='".$failureTime."'>";
		$def_url .= "<input type='hidden' name='customerIP'  value='".$customerIP."'>";
		$def_url .= "<input type='hidden' name='orderDetails'  value='".$orderDetails."'>";
		$def_url .= "<input type='hidden' name='totalAmount'  value='".$totalAmount."'>";
		$def_url .= "<input type='hidden' name='type'  value='".$type."'>";
		$def_url .= "<input type='hidden' name='buyerMarked'  value='".$buyerMarked."'>";
		$def_url .= "<input type='hidden' name='payType'  value='".$payType."'>";
		$def_url .= "<input type='hidden' name='orgCode'  value='".$orgCode."'>";
		$def_url .= "<input type='hidden' name='currencyCode'  value='".$currencyCode."'>";
		$def_url .= "<input type='hidden' name='directFlag'  value='".$directFlag."'>";
		$def_url .= "<input type='hidden' name='borrowingMarked'  value='".$borrowingMarked."'>";
		$def_url .= "<input type='hidden' name='couponFlag'  value='".$couponFlag."'>";
		$def_url .= "<input type='hidden' name='platformID'  value='".$payment_log_id."'>";
		$def_url .= "<input type='hidden' name='returnUrl'  value='".$returnUrl."'>";
		$def_url .= "<input type='hidden' name='noticeUrl'  value='".$noticeUrl."'>";
		$def_url .= "<input type='hidden' name='partnerID'  value='".$partnerID."'>";
		$def_url .= "<input type='hidden' name='remark'  value='".$payment_log_id."'>";
		$def_url .= "<input type='hidden' name='charset'  value='".$charset."'>";
		$def_url .= "<input type='hidden' name='signType'  value='".$signType."'>";//md5
		$def_url .= "<input type='hidden' name='signMsg'  value='".$signMsg."'>";
  
		if(!empty($payment_info['logo']))
			$def_url .= "<input type='image' src='".__ROOT__.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
        
        $def_url .= "<input type='submit' class='paybutton' value=".a_L(strtoupper('HNAPAY_'.$orgCode))."支付></form>";
        $def_url .= "</form>";
        $def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money)."</span>";
        return $def_url;       
	}
	
	public function getBackList($payment_id){
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo,description_1 from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
	
		$def_url = "<style type='text/css'>.bank_hnapay_types{float:left; display:block; background:url(./global/banklist_hnapay.jpg); font-size:0px; width:160px; height:10px; text-align:left; padding:15px 0px; _padding:10px 0px;}";
	
		$def_url .=".bk_typehnapay{background-position:15px -745px; }";    //新生支付
		$def_url .=".bk_typeicbc{background-position:15px -5px; }";    //工商银行
		$def_url .=".bk_typeabc{background-position:15px -44px; }";    //农业银行
		$def_url .=".bk_typeccb{background-position:15px -84px; }";    //建设银行
		$def_url .=".bk_typeboc{background-position:15px -124px; }";    //中国银行
		$def_url .=".bk_typecomm{background-position:15px -164px; }";    //交通银行
		$def_url .=".bk_typecmb{background-position:15px -204px; }";    //招商银行
		$def_url .=".bk_typecmbc{background-position:15px -244px; }";    //民生银行
		$def_url .=".bk_typecib{background-position:15px -284px; }";    //兴业银行
		$def_url .=".bk_typespdb{background-position:15px -324px; }";    //浦发银行
		$def_url .=".bk_typehxb{background-position:15px -364px; }";    //华夏银行
		$def_url .=".bk_typeecitic{background-position:15px -404px; }";    //中信银行
		$def_url .=".bk_typeceb{background-position:15px -444px; }";    //光大银行
		$def_url .=".bk_typegdb{background-position:15px -484px; }";    //广发银行
		$def_url .=".bk_typepost{background-position:15px -524px; }";    //邮政储蓄
		$def_url .=".bk_typesdb{background-position:15px -568px; }";    //深发展银行
		$def_url .=".bk_typebea{background-position:15px -610px; }";    //东亚银行
		$def_url .=".bk_typenb{background-position:15px -655px; }";    //宁波银行
		$def_url .=".bk_typebccb{background-position:15px -700px; }";    //北京银行
		$def_url .="</style>";
		//$def_url  .= '<form style="text-align:center;" action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" target="_blank" style="margin:0px;padding:0px" >';
	
		$ks = 0;
		//echo $payment_info['config']['sdo_paychannel'];
		foreach($this->bank_types as $key=>$bank_type)
		{
			if(intval($payment_info['config']['tencentpay_gateway'][$bank_type])==1)
			{
				$def_url .="<label class='bank_hnapay_types bk_type".$bank_type."'><input id= check-".$bank_type." type='radio' name='payment' value='".$bank_type.'-'.$payment_id."'";
				if($ks == 0)
				{
					$def_url .= " checked='checked'";
				}
				$def_url .= " /></label>".$payment_info['description_1'];
				$ks++;
			}
		}
	
		$def_url .= "<br clear='both' />";
		return $def_url;
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
		
	
	//冲值检查
	public function check(){
		$payment_log_id          = trim(trim($_REQUEST['id']));

		$payment_log_vo = $GLOBALS['db']->getRow("select id,is_paid,rec_module,rec_id,payment_id from ".DB_PREFIX."payment_log where id=".intval($payment_log_id));
		
		if ($payment_log_vo == false || $payment_log_vo['is_paid'] == 0){
			a_error("冲值失败",'',__ROOT__."/index.php");
		}

		if ($payment_log_vo['is_paid'] == 1){
			success("冲值成功",'',__ROOT__."/index.php");
		}		
		
	}
	
	public function dealResult($get,$post,$request)
	{			
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		

		
		$v_oid          = trim(trim($post['remark']));
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($v_oid));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".intval($payment_id));  
    	$payment['config'] = unserialize($payment['config']);
		$hnapay_key    = $payment['config']['hnapay_key'];
		
				
		$orderID          = trim($post['orderID']);
        $resultCode        = trim($post['resultCode']);
        $stateCode      = trim($post['stateCode']);
        $orderAmount      = trim($post['orderAmount']);
        $payAmount       = trim($post['payAmount']);
        $acquiringTime    = trim($post['acquiringTime']);
        $completeTime        = trim($post['completeTime' ]);
        $orderNo        = trim($post['orderNo' ]);
        $partnerID       = trim($post['partnerID' ]);	
        $remark       = trim($post['remark' ]);
        $charset       = trim($post['charset' ]);
        $signType       = trim($post['signType' ]);
        $signMsg       = trim($post['signMsg']);

		$pkey = "orderID=".$orderID
			 ."&resultCode=".$resultCode
			 ."&stateCode=".$stateCode
			 ."&orderAmount=".$orderAmount
			 ."&payAmount=".$payAmount
			 ."&acquiringTime=".$acquiringTime
			 ."&completeTime=".$completeTime
			 ."&orderNo=".$orderNo
			 ."&partnerID=".$partnerID
			 ."&remark=".$remark
			 ."&charset=".$charset
			 ."&signType=".$signType
			 ."&pkey=".$hnapay_key;

		//$str = $pkey."======signMsg:".$signMsg;
		$pkey =  md5($pkey);
		
		//$str = $str.";md5(pkey):".$pkey;	
		
		//@file_put_contents(getcwd()."/ThinkPHP/Vendor/payment3/HnapayPayment".$remark.".txt",$str);
        //开始初始化参数
        $payment_log_id = $v_oid;
    	$money = $payAmount / 100;
    	$payment_id = $payment['id'];
    	$currency_id = $payment['currency'];    
        
		/* 检查秘钥是否正确 */
	        if ($signMsg == $pkey)
	        {
	        	//@file_put_contents(getcwd()."/ThinkPHP/Vendor/payment3/HnapayPayment".$remark."_".$stateCode.".txt",$orderNo);
	            if ($stateCode == '2')
	            {	            	
	            	//@file_put_contents(getcwd()."/ThinkPHP/Vendor/payment3/HnapayPayment".$remark."_ok.txt",$orderNo);
	                //return s_order_paid($payment_log_id,$money,$payment_id,$currency_id,$orderNo);  
	            	return s_order_paid($payment_log_id,$money,$payment_id,$currency_id,$orderNo);
	
	            }
	        }
	        else
	        {
	        	//@file_put_contents(getcwd()."/ThinkPHP/Vendor/payment3/HnapayPayment".$remark."_err.txt",$payment_log_id);
	            $return_res['info'] = a_L("VALID_ERROR");
	            return $return_res; 
	        }
	}
}
?>