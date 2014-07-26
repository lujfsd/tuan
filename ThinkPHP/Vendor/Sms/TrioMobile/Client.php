<?php
/**
 TrioMobile
 */
class Client{
	
	var $serviceUrl;
	var $userName;
	var $userPass;
	var $originatingNo;
	
	var $status = array(
		"-21" => "country_code is invalid. Eg. Non-existence country_code",
		"-22" => "trx_id is invalid",
		"-23" => "passname is invalid",
		"-24" => "password is invalid",
		"-25" => "originating_no is null",
		"-26" => "destination_no is invalid",
		"-27" => "cp_ref_id is invalid",
		"-28" => "bill_type is invalid",
		"-29" => "bill_price is invalid",
		"-30" => "content_type is invalid",
		"-31" => "content provider is barred",
		"-32" => "Invalid msg length",
		"-36" => "Invalid character. ONLY a-z, A-Z, 0-9, !@#$%&*()-_+=;:\"\'<>,.?/ characters are supported",
		"-50" => "Invalid trx_id provided as a reference trx_id in MO",
		"-60" => "Insufficient prepaid credit"
	);
	
	function Client($serviceUrl,$userName,$userPass,$originatingNo)
	{
		$this->serviceUrl = $serviceUrl;
		$this->userName = $userName;
		$this->userPass = $userPass;
		$this->originatingNo = $originatingNo;
	}

	/**
	 * 发送短信
	 * @return int 操作结果状态码
	*/
	function sendSMS($mobiles,$msg)
	{
		$mobiles = implode(",",$mobiles);
		
		$result = array("status"=>false,"msg"=>"send fail","success"=>0);
		
		$string = "0a";
		$hstring = pack('H*', $string);
		$url_info=parse_url($this->serviceUrl);
		
		$headers = array();
		$port = isset($url_info['port']) ? $url_info['port'] : 80;
		$fp=fsockopen($url_info['host'], $port, $errno, $errstr, 30);
		if($fp)
		{
        	$head = "HEAD ".$url_info['path']."?".$url_info['query'];
        	$head .= " HTTP/1.0\r\nHost: ".@$url_info['host']."\r\n";
			$head .= " \r\npassname: ".urlencode($this->userName)."\r\n";
			$head .= " \r\npassword: ".urlencode($this->userPass)."\r\n";
			$head .= " \r\ntrx_id: ".urlencode('0')."\r\n"; 
			$head .= " \r\nshort_code: ".urlencode('36828')."\r\n";
			$head .= " \r\noriginating_no: ".urlencode($this->originatingNo)."\r\n";
			$head .= " \r\ndestination_no: ".urlencode($mobiles)."\r\n";
			$head .= " \r\ncp_ref_id: ".urlencode('0')."\r\n";
			$head .= " \r\nbill_type: ".urlencode('0')."\r\n";
			$head .= " \r\nbill_price: ".urlencode('0')."\r\n";
			$head .= " \r\ncontent_type: ".urlencode('1')."\r\n";
			$head .= " \r\nmsg: ".urlencode($msg)."\r\n";
			$head .= " \r\nbulk_fg: ".urlencode('1')."\r\n\r\n";
	   		fputs($fp, $head);
           	while(!feof($fp))
			{
               if($header=trim(fgets($fp, 1024)))
			   {
				   //$log .= $header;
				   $h2 = explode(':',$header);
				   if($h2[0] == $header)
				   {
					   $headers['status'] = $header;
				   }
				   else {
					   $headers[strtolower($h2[0])] = trim($h2[1]);
				   }
               }
           }
       }
	   
	   //file_put_contents("D:/51ECSHOP/FanWeSVN/GroupNew/groupon/log.txt",$log);
	   
	   if(isset($headers['result']))
	   {
		   $code = $headers['result'];
		   
		    if(isset($this->status[$code]))
			{
				$result['status'] = false;
				$result['msg'] = $this->status[$code];
			}
			elseif(intval($code) > 0)
			{
				$result['status'] = true;
				$result['msg'] = "send success";
				$result['success'] = count(explode(",",$code));
			}
	   }
	
		return $result;
	}
}
?>
