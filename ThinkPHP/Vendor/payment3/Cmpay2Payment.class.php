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
    $modules[$i]['code']    = 'Cmpay2';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = '移动手机支付2';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '1.0.1';

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
class Cmpay2Payment implements Payment  {
	public $config = array(
		'cmpay2_merchantid'=>'888099974309992',//商户编号
	    'cmpay2_signkey'=>'123456',  //商户密钥
	);	
	
	public function getPaymentCode($payment_log_id, $money, $payment_id, $currency_id)
	{
		$money = round($money * 100,2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_id));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$data_return_url = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/index.php?m=Payment&a=response&payment_name=Cmpay2';
		$data_notify_url = $data_return_url;


        
		$payment_log = $GLOBALS['db']->getRow("select rec_id,rec_module from ".DB_PREFIX."payment_log where id=".intval($payment_log_id)." limit 1");
		
		if($payment_log['rec_module']=='Order'){
			//$rec_id = $GLOBALS['db']->getOne("select rec_id from ".DB_PREFIX."order_goods where order_id=".intval($payment_log['rec_id'])." limit 1");
			//$goods_data = $GLOBALS['db']->getRow("select name_1,goods_short_name from ".DB_PREFIX."goods where id=".intval($rec_id)." limit 1");
			//$data_sn = $goods_data['goods_short_name']==''?$goods_data['name_1']:$goods_data['goods_short_name'];
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."order where id=".intval($payment_log['rec_id']));
		}elseif ($payment_log['rec_module']=='UserIncharge'){
			$data_sn = $GLOBALS['db']->getOne("select sn from ".DB_PREFIX."user_incharge where id=".intval($payment_log['rec_id']));
		}
		
		//报头数据
		$callbackUrl = $data_return_url;//$GLOBALS['callbackUrl'];
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$merchantId = $payment_info['config']['cmpay2_merchantid'];
		$notifyUrl = $data_return_url;//"http://220.168.94.201:83/iposmphpdemo_uat/notifyUrl.php";//$GLOBALS['notifyUrl'];
		$notifyEmail = 'fanwe@hotmail.com';//$payment_info['config']['cmpay_notifyemail'];
		$notifyMobile = '13800000000';//$payment_info['config']['cmpay_notifymobile'];
		$requestId = date( "YmdHis" );
		$signType = "MD5";
		$type = "DODIRECTPAYMENT"; //直接支付		
		$version = "1.0.1";
		
		//报文体数据
		$allowNote = "0";
		$amount = $money;
		$authorizeMode = "WEB";
		$banks = "";
		$currency = "CNY";//$payment_info['config']['cmpay_currency'];
		$deliverFlag = "0";	
		$invoiceFlag = "0";		
		$orderDate = date( "Ymd" );//a_toDate($payment_log['create_time'],'Ymd');
		$orderId = $payment_log_id;
		$pageStyle = "";
		$period = "2";		
		$periodUnit = "2";
		$productDesc = $data_sn;
		$productId = $payment_log_id;
		$productName = $data_sn;
		$reserved = "reserved";
		$userToken = "";

		$signKey = $payment_info['config']['cmpay2_signkey'];
		$source = $callbackUrl . $ipAddress . $merchantId . $notifyUrl . $notifyEmail
			. $notifyMobile . $requestId . $signType . $type . $version . $allowNote
			. $amount . $authorizeMode . $banks . $currency . $deliverFlag . $invoiceFlag 
			. $orderDate . $orderId . $pageStyle . $period . $periodUnit . $productDesc . $productId . $productName . $reserved . $userToken;
		
  		$hash = $this->hmac("",$source);
		$hmac = $this->hmac($signKey,$hash);
		
		$requestData = array();
		$requestData["callbackUrl"] = $callbackUrl;
		$requestData["hmac"] = $hmac;
		$requestData["ipAddress"] = $ipAddress;
		$requestData["merchantId"] = $merchantId;
		$requestData["notifyUrl"] = $notifyUrl;
		$requestData["notifyEmail"] = $notifyEmail;
		$requestData["notifyMobile"] = $notifyMobile;
		$requestData["requestId"] = $requestId;
		$requestData["signType"] = $signType;
		$requestData["type"] = $type;
		$requestData["version"] = $version;
		$requestData["allowNote"] = $allowNote;
		$requestData["amount"] = $amount;
		$requestData["authorizeMode"] = $authorizeMode;
		$requestData["banks"] = $banks;
		$requestData["currency"] = $currency;
		$requestData["deliverFlag"] = $deliverFlag;
		$requestData["invoiceFlag"] = $invoiceFlag;
		$requestData["orderDate"] = $orderDate;
		$requestData["orderId"] = $orderId;
		$requestData["pageStyle"] = $pageStyle;
		$requestData["period"] = $period;
		$requestData["periodUnit"] = $periodUnit;
		$requestData["productDesc"] = $productDesc;
		$requestData["productId"] = $productId;
		$requestData["productName"] = $productName;
		$requestData["reserved"] = $reserved;
		$requestData["userToken"] = $userToken;		
				
		$encoded = "";
		while (list($k,$v) = each($requestData))
		{
			$encoded .= ($encoded ? "&" : "");
			$encoded .= rawurlencode($k)."=".rawurlencode($v);
		}	
		 
		//$url = "https://211.138.236.210:26111/ips/APITrans2";//测试
		$url = "https://ipos.10086.cn/ips/APITrans2";//"https://ipos.10086.cn/ips/APITrans2"; //正式
		//$url = "https://211.138.236.209:32033/ips/APITrans2";//测试
		
		$sTotalString = $this->POSTDATA($url,$requestData);
		$recv = $sTotalString["MSG"];
		$recvArray = $this->parseRecv($recv);
		
		//校验签名
		$r_hmac = $recvArray["hmac"];
		$r_merchantId = $recvArray["merchantId"];
		$r_payNo = $recvArray["payNo"];
		$r_requestId = $recvArray["requestId"];
		$r_returnCode = $recvArray["returnCode"];
		$r_message = a_gbToUTF8($recvArray["message"]);
		
		$r_signType = $recvArray["signType"];
		$r_type = $recvArray["type"];
		$r_version = $recvArray["version"];
		$sessionId = $recvArray["SESSIONID"];
		$r_source = $r_merchantId.$r_payNo.$r_requestId.$r_returnCode.$r_message.$r_signType.$r_type.$r_version.$sessionId;
		$r_hash = $this->hmac("",$r_source);
		$r_newhmac = $this->hmac($signKey,$r_hash);
				
		if($r_hmac != $r_newhmac )
		{
			$msg = "version=$r_version && r_source=$r_source && r_hmac=$r_hmac && r_newhmac=$r_newhmac::"."</br>";
			return $msg."验证签名失败！".var_dump($sTotalString);
		}
		else
		{					
			$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set pay_code = '$payment_log_id' where id = ".$payment_log_id);
				
			//https://211.138.236.210:26111/ips/FormTrans3
			//https://ipos.10086.cn/ips/FormTrans3
			//测试号码：13607491409;  支付密码 ：111111
			$payLinks = '<a onclick="window.open(\'https://ipos.10086.cn/ips/FormTrans3?SESSIONID='.$sessionId. '\')" href="javascript:;"><input type="submit" class="paybutton" name="buy" value="前往移动手机在线支付"/></a>';
			
	    	if(!empty($payment_info['logo']))
			{
				$payLinks = '<a href="https://ipos.10086.cn/ips/FormTrans3?SESSIONID='.$sessionId. '" target="_blank" class="payLink"><img src='.__ROOT__.$payment_info['logo'].' style="border:solid 1px #ccc;" /></a><div class="blank"></div>'.$payLinks;
			}
			
	        $def_url = '<div style="text-align:center">'.$payLinks.'</div>';
			$def_url.="<br /><span class='red'>".a_L("PAY_TOTAL_PRICE").":".a_formatPrice($money / 100)."</span>";
	        return $def_url;			
			//$newUrl = $GLOBALS["tokenRedirectUrl"];
		}			
	}
	
	function POSTDATA($url, $data)
	{
		$url = parse_url($url);
		if (!$url)
		{
			return "couldn't parse url";
		}
		if (!isset($url['port'])) { $url['port'] = ""; }
	
		if (!isset($url['query'])) { $url['query'] = ""; }
	
		$encoded = "";
	
		while (list($k,$v) = each($data))
		{
			$encoded .= ($encoded ? "&" : "");
			$encoded .= rawurlencode($k)."=".rawurlencode($v);
		}
		//$fp = fsockopen($url['host'], $url['port'] ? $url['port'] : 80);
		$urlHead = null;
		$urlPort = $url['port'];
		if($url[scheme] == "https")
		{
			$urlHead = "ssl://".$url['host'];
			if($url['port'] == null || $url['port'] == 0)
			{
				$urlPort = 443;
			}
		}
		else
		{
			$urlHead = $url['host'];
			if($url['port'] == null || $url['port'] == 0)
			{
				$urlPort = 80;
			}
		}
		
		$fp = @fsockopen($urlHead, $urlPort);
		if (!$fp){
			return "Failed to open socket to $urlHead:$urlPort";
			/*
			require_once(VENDOR_PATH.'transport.php');
			$t = new transport;
		    $results = $t->request($url_org, $encoded);
		    echo $url_org.'?'.$encoded.'<br>';
		    var_dump(file_get_contents($url_org.'?'.$encoded));
		    //var_dump($results);
		     exit;
		    return array("FLAG"=>1,"MSG"=>$results);
		    //$ucconfig = $ucconfig['body'];
			//print_r($ucconfig);
			*/
		} 
			
		$tmp = "";
		$tmp .= sprintf("POST %s%s%s HTTP/1.0\r\n", $url['path'], $url['query'] ? "?" : "", $url['query']);
		$tmp .= "Host: $url[host]\r\n";
		$tmp .= "Content-type: application/x-www-form-urlencoded\r\n";
		$tmp .= "Content-Length: " . strlen($encoded) . "\r\n";
		$tmp .= "Connection: close\r\n\r\n";
		$tmp .= "$encoded\r\n";
		fputs($fp,$tmp);
	
		$line = fgets($fp,1024);
		if (!eregi("^HTTP/1\.. 200", $line))
		{
			return array("FLAG"=>0,"MSG"=>$line);
		}
	
		$results = ""; $inheader = 1;
		while(!feof($fp))
		{
			$line = fgets($fp,1024);
			if ($inheader && ($line == "\n" || $line == "\r\n"))
			{
				$inheader = 0;
			}
			elseif (!$inheader)
			{
				$results .= $line;
			}
		}
		fclose($fp);
		return array("FLAG"=>1,"MSG"=>$results);
	} 	
	
	function parseRecv($source)
	{
		$ret = array();
		$temp = explode("&",$source);
		
		foreach ($temp as $value)
		{
			$tempKey = explode("=",$value);
			$ret[$tempKey[0]] = $tempKey[1];
		}
	
		return $ret;
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
		
		$orderId = $request["orderId"]; 
		
        $payment_id = $GLOBALS['db']->getOne("select payment_id from ".DB_PREFIX."payment_log where id=".intval($orderId));
		$payment = $GLOBALS['db']->getRow("select id,config,currency from ".DB_PREFIX."payment where id=".$payment_id);  
    	$payment['config'] = unserialize($payment['config']);
    	$signKey = $payment['config']['cmpay2_signkey'];	
    	
		$hmac = $request["hmac"];
	    $merchantId = $request["merchantId"];
	    $payNo = $request["payNo"];
	    $requestId = $request["requestId"];
	    $returnCode = $request["returnCode"];
	    $message = $request["message"];
	    $message = $this->decodeUtf8($message);
	    
	    $sigTyp = $request["signType"];
	    $type = $request["type"];
	    $version = $request["version"];
	    
	    //报文体
	    $amount = $request["amount"];        
      	$banks = $request["banks"];        
      	$contractName = $request["contractName"];  
      	$contractName = $this->decodeUtf8($contractName);            
      	$invoiceTitle = $request["invoiceTitle"];   
      	$invoiceTitle = $this->decodeUtf8($invoiceTitle);     
      	$mobile = $request["mobile"];
      	       
      	$payDate = $request["payDate"];        
      	$reserved = $request["reserved"];   
      	$reserved = $this->decodeUtf8($reserved);     
      	$status = $request["status"];     
      	$amtItem = $request["amtItem"];
         
      	$signData = $merchantId.$payNo.$requestId.$returnCode.$message.$sigTyp.$type.$version
      				.$amount.$banks.$contractName.$invoiceTitle.$mobile.$orderId.$payDate.$reserved.$status;
      	//echo $signData."<br>";		
      	if($version == "1.0.1")
         	$signData = $merchantId.$payNo.$requestId.$returnCode.$message.$sigTyp.$type.$version
      					.$amount.$banks.$contractName.$invoiceTitle.$mobile.$orderId.$payDate.$reserved.$status.$amtItem;
      					
      	$hash = $this->hmac("",$signData);
		$newhmac = $this->hmac($signKey,$hash);
      
		//echo $hash."<br>";
		//echo $newhmac."<br>";
		//exit;
	    if($newhmac == $hmac && $version == "1.0.1" && $status == 'SUCCESS')
	    {
	        $payment_log_id = $orderId;
	    	$money = $amount / 100;
	    	$payment_id = $payment['id'];
	    	$currency_id = $payment['currency']; 
	    	$pay_back_code = '';//支付序列号 2011-05-30
	
			 return s_order_paid($payment_log_id,$money,$payment_id,$currency_id);
	      }else{
	         $return_res['info'] = a_L("VALID_ERROR");
	         return $return_res; 
	     }
	}
	
	function decodeUtf8($source)
	{
		$temp = urldecode($source);
		//$ret = iconv("UTF-8","GB2312//IGNORE",$temp);
		return $temp;
	}
	
	function hmac($key, $data)
	{
		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
			$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;
		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	} 	
	
	
}
?>