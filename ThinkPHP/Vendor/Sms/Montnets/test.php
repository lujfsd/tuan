<?php

// 梦网短信平
include_once("Client.php");

	$smsInfo['server_url'] = 'http://ws.montnets.com:9002/MWGate/wmgw.asmx?wsdl';
	$smsInfo['user_name'] = 'JC1022';
	$smsInfo['password'] = '720621';
	$smsInfo['pszSubPort'] = '*';
	
	$content = '测试短信a';
	$mobiles = array('15989439712');
	/*
	print_r(implode(",",$mobiles));
	echo "<br>";
	print_r(count($mobiles));
	exit;
	*/
	$sms = new Client($smsInfo['server_url'],$smsInfo['user_name'],$smsInfo['password']);
	$sms->pszSubPort = $smsInfo['pszSubPort'];
	$sms->setOutgoingEncoding("UTF-8");
	$result = $sms->sendSMS($mobiles,$content);
	
	header("Content-Type:text/plain;charset=utf-8");
	echo $result['msg'];
?>