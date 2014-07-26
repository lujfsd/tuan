<?php
	$url=preg_replace('/localhost/','127.0.0.1','http://'.$_SERVER['HTTP_HOST'].__ROOT__."/alipay_login_address.php");
	$aliapy_config = array(
		        //合作身份者id，以2088开头的16位纯数字
		        "partner"	=> trim(a_fanweC('ALIAPY_PARTNER')),
				//安全检验码，以数字和字母组成的32位字符
				"key"	=> trim(a_fanweC('ALIAPY_KEY')),
				//安全检验码，以数字和字母组成的32位字符
				//页面跳转同步通知路径 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
				//return_url的域名不能写成http://localhost/alipay.auth.authorize_php_utf8/return_url.php ，否则会导致return_url执行无效
				"return_url"	=> $url,
				//签名方式 不需修改
				"sign_type"	=> 'MD5',
				//字符编码格式 目前支持 gbk 或 utf-8
				"input_charset"	=> 'utf-8',
				//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
				"transport"	=> 'http',				
	);
			
	if ($_REQUEST['oauth_alipay']=='1'){
		$token  = '';
		$token=$_SESSION['token'];
		//构造要请求的参数数组，无需改动
		$parameter = array(
		"service"			=> "user.logistics.address.query",
		"partner"			=> trim($aliapy_config['partner']),
		"_input_charset"	=> trim(strtolower($aliapy_config['input_charset'])),
        "return_url"		=> trim($aliapy_config['return_url']),

        "token"				=> $token
);
		require_once (VENDOR_PATH . 'user_login/alipay_address/alipay_service.class.php');
				
		//构造快捷登录接口
		$alipayService = new AlipayService($aliapy_config);
		$html_text = $alipayService->user_logistics_address_query($parameter);

		//print_r($html_text);
		echo($html_text);
	}else{
		//计算得出通知验证结果
		require_once (VENDOR_PATH.'user_login/alipay_address/alipay_notify.class.php');
		$alipayNotify = new AlipayNotify($aliapy_config);
        $verify_result = $alipayNotify->verifyNotify();
		if($verify_result) {//验证成功
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	$user_id = $_POST['user_id'];
	//用户选择的收货地址

    $receive_address = (get_magic_quotes_gpc()) ? stripslashes(htmlspecialchars_decode($_POST['receive_address'])) : htmlspecialchars_decode($_POST['receive_address']);
	//对receive_address做XML解析，获得各节点信息
	$doc = new DOMDocument();
	$doc->loadXML($receive_address);
	//获取地址
	$address = '';
	if( ! empty($doc->getElementsByTagName( "address" )->item(0)->nodeValue) ) {
		$address= $doc->getElementsByTagName( "address" )->item(0)->nodeValue;
	}
	echo $address;
	//获取收货人名称
	$fullname = '';
	if( ! empty($doc->getElementsByTagName( "fullname" )->item(0)->nodeValue) ) {
		$fullname= $doc->getElementsByTagName( "fullname" )->item(0)->nodeValue;
	}
		//获取收货人名称
	$address_code = '';
	if( ! empty($doc->getElementsByTagName( "address_code" )->item(0)->nodeValue) ) {
		$address_code= $doc->getElementsByTagName( "address_code" )->item(0)->nodeValue;
	}
	
	$area = '';
	if( ! empty($doc->getElementsByTagName( "area" )->item(0)->nodeValue) ) {
		$area= $doc->getElementsByTagName( "area" )->item(0)->nodeValue;
	}
	
	$city = '';
	if( ! empty($doc->getElementsByTagName( "city" )->item(0)->nodeValue) ) {
		$city= $doc->getElementsByTagName( "city" )->item(0)->nodeValue;
	}
	$prov = '';
	if( ! empty($doc->getElementsByTagName( "prov" )->item(0)->nodeValue) ) {
		$prov= $doc->getElementsByTagName( "prov" )->item(0)->nodeValue;
	}
	$mobile_phone = '';
	if( ! empty($doc->getElementsByTagName( "mobile_phone" )->item(0)->nodeValue) ) {
		$mobile_phone= $doc->getElementsByTagName( "mobile_phone" )->item(0)->nodeValue;
	}
	$phone = '';
	if( ! empty($doc->getElementsByTagName( "phone" )->item(0)->nodeValue) ) {
		$phone= $doc->getElementsByTagName( "phone" )->item(0)->nodeValue;
	}
	$post = '';
	if( ! empty($doc->getElementsByTagName( "post" )->item(0)->nodeValue) ) {
		$post= $doc->getElementsByTagName( "post" )->item(0)->nodeValue;
	}
	//执行商户的业务程序
	$address_info=array(
			"address"=>$address,
			"fullname"=>$fullname,
			"address_code"=>$address_code,
			"area"=>$area,
			"city"=>$city,
			"prov"=>$prov,
			"mobile_phone"=>$mobile_phone,
			"phone"=>$phone,
			"post"=>$post,
	);
	
	print_r($address_info);
				$GLOBALS ['tpl']->assign ( "address_info", $address_info );
				
				
				$data = array ('navs' => array (array ('name' => a_L ( "HC_PLEASE_REG_OR_REGISTER" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
				
				assignSeo ( $data );
				$GLOBALS['tpl']->display('Inc/user/login_sync.moban');			
		}else{
			a_error('验证失败','','http://'.$_SERVER['HTTP_HOST'].__ROOT__);
			exit;
		}	
	}
	function &init_users3_1() {
		$set_modules = false;
		static $cls = null;
		if ($cls != null) {
			return $cls;
		}
		$code = a_fanweC ( 'INTEGRATE_CODE' );
		if (empty ( $code ))
			$code = 'fanwe';
		$code = $code . '3';
		include_once (VENDOR_PATH . 'integrates3/' . $code . '.php');
		$cfg = unserialize ( a_fanweC ( 'INTEGRATE_CONFIG' ) );
		$cls = new $code ( $cfg );

		return $cls;
	}
?>