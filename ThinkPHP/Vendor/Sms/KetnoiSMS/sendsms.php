<?php
include_once "nusoap-0.9.5/lib/nusoap.php";

	function KetoniStatusStr($code){
	
		$statusStr = array(
				"1"   => "Gửi thành công",
				"0"  => "Gửi tin nhắn bị lỗi",
				"-1"  => "Username hoặc Password không chính xác",
				"-2"  => "Nội dung tin nhắn không hợp lệ",
				"-4"  => "Bị look tin nhắn (Cùng số điện thoại, nội dung và RequestID)",
				"-5"  => "Loại khác",
				"-6"  => "Gửi quá số lượng tin",
				"-15"  => "Bị chặn IP"  
			);		
			/*
			-	返回价值和意义: 
			1 : 发送成功
			0 : 发送信息错误
			-1 : Username 或 Password 不正确(该信息请联系VASC公司以获取)
			-2 : 信息内容不符合要求
			-3 : 电话号码错误(手机号不符合越南的标准)
			-4 : 信息循环 (由于在5分钟内发送内容一样的信息到同一号码)
			-5 : 其他类型 (由于系统，由于网络环境等)
			-6 : 超过一天的发送数量(每个合作方都会有一个每天最多可以发送的信息数量。如果遇到这样的报错请联系我们以增加信息数量。)
			-15 : 发送信息的服务器IP不正确(合作方需提供给VASC 发送信息的服务器IP地址以便VASC 进行阻止IP)
			*/
		return 	$statusStr[$code];
	};
	
	function doSendMTSpam($UserID, $Message, $Username, $Password)
	{
		$result = array();
		
		
		$result['status'] = false;
		$result['msg'] = "send fail";
		$result['success'] = 0;

		$proxyhost 		= '';
		$proxyport 		= '';
		$proxyusername 	= '';
		$proxypassword 	= '';
		$useCURL 		= '0';		
		
		//$Message = iconv("UTF-8","GBK",$Message);
			
		if($UserID && $Message)
		{
			$client = new nusoap_client("http://service.123sms.vn/MTSenderPartner/MTSenderPartner.asmx?WSDL", true, $proxyhost, $proxyport, $proxyusername, $proxypassword);
			$err = $client->getError();
			if ($err) {
				$result['msg'] = $err; // htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
				return $result;
			}
			$client->setUseCurl($useCURL);
			// This is an archaic parameter list
			$params = array(
				'UserID' 	  	=> ''.$UserID.'', //Số di động gửi đến (Theo chuẩn international, bắt đầu bằng 84)
				'Message'      	=> ''.$Message.'', //Nội dung tin nhắn
				'Username'  	=> ''.$Username.'', //Tên user gửi tin
				'Password'      => ''.$Password.'' //Mật khẩu xác nhận
			);
			
			$result_sms = $client->call('doSendMTSPAM', $params);
			if ($client->fault) {
				$result['msg'] = 'Fault (Expect - The request contains an invalid SOAP body);'.$result['doSendMTSPAMResult'];
				return $result;
			} else {
				$err = $client->getError();
				if ($err) {
					$result['msg'] = $err;
				} else {
					$code = intval($result_sms['doSendMTSPAMResult']);
					
					$result['msg'] = KetoniStatusStr($code);
					if ( $code== 1) // connect sms gateway service OK, return result
					{
						$result['success'] = 1;
						$result['status'] = true;
					}
				}
			}
			return $result;
		}
		else
		{
			$result['msg'] = 'error-khong du param';
			return $result;
		}
	}
?>