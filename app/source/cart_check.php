<?php
		$user_id = intval($_SESSION['user_id']);
		if($user_id==0)
    	{
    		if(a_fanweC("URL_ROUTE")==0)
    		{
    			$cart_login_url = __ROOT__."/index.php?m=Cart&a=cartLogin";
    		}
    		else
    		{
    			$cart_login_url = __ROOT__."/Cart-cartLogin.html";
    		}    		
    		redirect2($cart_login_url);
    	}
    	else 
    	{
    		//购物车提交页的静态页处理
    		//以下对购物车进行检测	    	
	   		$session_id = session_id();		
	   		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id =".$user_id);	
			$cart_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."cart where session_id='".$session_id."'");
    		if(!$cart_list)
		    {
		    	if(a_fanweC("URL_ROUTE")==0)
	    		{
	    			$cart_login_url = __ROOT__."/index.php";
	    		}
	    		else
	    		{
	    			$cart_login_url = __ROOT__;
	    		}
	    		redirect2($cart_login_url);exit;
		    }
			$cart_total_price = $GLOBALS['db']->getOne("select sum(data_total_price) from ".DB_PREFIX."cart where session_id='".$session_id."'");
	   		//end 购物车检测
	   		foreach($cart_list as $k=>$v)
	   		{
	   			if($v['goods_type'] == 1 || $v['goods_type'] == 3)
	   			{
	   				$goods_type = 1;
	   				break;
	   			}
	   		}
	   		
	   		$is_combine = 0;
    	 	foreach($cart_list as $kk=>$vv)
		    {
		    	$goods_id = $vv['rec_id'];
				$cart_goods_info = getGoodsItem($goods_id);
				$cart_list[$kk]['goods_info'] = $cart_goods_info;
				$cart_list[$kk]['attr_ids'] = explode(",",$vv['attr_ids']);
				//$total_price += $vv['data_total_price'];
				if ($cart_goods_info['allow_combine_delivery'] == 1){
					$is_combine = 1;//开始输出可以拼运单的订单
				}
				
		    	//add by chenfq 2011-06-12 添加判断商品属性有没有被选择
				if ($cart_goods_info['attrlist'] && (empty($vv['attr_ids'])||empty($vv['attr']))){
					a_error(a_L('PLS_SELECT_ATTR'),a_L('PLS_SELECT_ATTR'),__ROOT__."/index.php?m=Cart&a=index");				
				}				
		    }

		    $GLOBALS['tpl']->assign('cart_list',$cart_list);
		    $GLOBALS['tpl']->assign('cart_total_price',$cart_total_price);
		    $GLOBALS['tpl']->assign('goods_type',$goods_type);
		    	
		    //拼单解析
			if($is_combine == 1)
			{
				$sql = "select distinct o.id,o.sn,o.region_lv1,o.region_lv2,o.region_lv3,o.region_lv4, o.address, o.consignee, o.delivery,g.allow_combine_delivery as acd from ".DB_PREFIX."order as o left join ".DB_PREFIX."order_goods as og on o.id = og.order_id left join ".DB_PREFIX."goods as g on og.rec_id = g.id ".
						" where o.delivery > 0 and o.money_status = 2 and o.goods_status = 0 and o.user_id = ".$user_id." and g.allow_combine_delivery = 1";
				$order_deliverys = $GLOBALS['db']->getALl($sql);
				foreach($order_deliverys as $k=>$v)
				{
					$order_deliverys[$k]['region_lv1_name'] = $GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."region_conf where id=".intval($v['region_lv1']));
					$order_deliverys[$k]['region_lv2_name'] = $GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."region_conf where id=".intval($v['region_lv2']));
					$order_deliverys[$k]['region_lv3_name'] = $GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."region_conf where id=".intval($v['region_lv3']));
					$order_deliverys[$k]['region_lv4_name'] = $GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."region_conf where id=".intval($v['region_lv4']));
					$order_deliverys[$k]['delivery_name'] = $GLOBALS['db']->getOneCached("select name_1 from ".DB_PREFIX."delivery where id=".intval($v['delivery']));
				}
						//}			
				$GLOBALS['tpl']->assign("order_deliverys",$order_deliverys);												
			}
		   //拼单解析
		    	
			//配送地址解析
			$consignee_id = intval($_REQUEST['consignee_id']);
			//add by chenfq 2010-04-21 默认取最后一次添加的地址
			if ($consignee_id <= 0 && intval($_SESSION['user_id']) > 0){
				$sql = "select max(id) as maxid from ".DB_PREFIX."user_consignee where user_id = ".$user_id;
				$tmp = $GLOBALS['db']->getOne($sql);
				$consignee_id = intval($tmp);
			}
				
		    $consignee_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where id =".$consignee_id);
			$GLOBALS['tpl']->assign("alipay_info",a_fanweC('ALIPAY_INFO'));
			if($_REQUEST['alipay_address']=='alipay_address'&&a_fanweC('ALIPAY_INFO')==1)
			{
						
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
					$city=preg_replace('/市/','',$city);
				}
				$prov = '';
				if( ! empty($doc->getElementsByTagName( "prov" )->item(0)->nodeValue) ) {
					$prov= $doc->getElementsByTagName( "prov" )->item(0)->nodeValue;
					$prov=preg_replace('/省/','',$prov);
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
			
			
						$consignee_info['qq'] = $user_info['qq'];
						$consignee_info['msn'] = $user_info['msn'];
						$consignee_info['alim'] = $user_info['alim'];
						$consignee_info['email'] = $user_info['email'];
						
						$region_lv2= $GLOBALS['db']->getRow("select pid from ".DB_PREFIX."region_conf where name like '%".$prov."%'");
						$consignee_info['region_lv1']=$region_lv2[pid];
						$region_lv3= $GLOBALS['db']->getRow("select pid from ".DB_PREFIX."region_conf where name like '%".$city."%'");
						$consignee_info['region_lv2']=$region_lv3[pid];
						$region_lv4= $GLOBALS['db']->getRow("select pid from ".DB_PREFIX."region_conf where name like '%".$area."%'");
						$consignee_info['region_lv3']=$region_lv4[pid];
						$region_lv5= $GLOBALS['db']->getRow("select id from ".DB_PREFIX."region_conf where name like '%".$area."%'");
						$consignee_info['region_lv4']=$region_lv5[id];
						
						$consignee_info['address']=$address;
						$consignee_info['consignee']=$fullname;
						$consignee_info['zip']=$address_code;
						$consignee_info['mobile_phone']=$mobile_phone;
						$consignee_info['fix_phone']=$phone;
						//exit;
							//输出一级地区
						
				}else
				{
					a_error('验证失败','','http://'.$_SERVER['HTTP_HOST'].__ROOT__);
					exit;
				}
			}
			if($consignee_info)
			{    		
				$consignee_info['qq'] = $user_info['qq'];
				$consignee_info['msn'] = $user_info['msn'];
				$consignee_info['alim'] = $user_info['alim'];
				$consignee_info['email'] = $user_info['email'];
				$GLOBALS['tpl']->assign("consignee_info",$consignee_info);
				//print_r($consignee_info);
				//输出一级地区
				$region_lv1_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."region_conf where pid = 0 order by name asc");
				$GLOBALS['tpl']->assign("region_lv1_list",$region_lv1_list);
					
				//输出二级地区	
				$region_lv2_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."region_conf where pid = ".$consignee_info['region_lv1']." order by name asc");
				$GLOBALS['tpl']->assign("region_lv2_list",$region_lv2_list);
					
				//输出三级地区
				$region_lv3_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."region_conf where pid = ".$consignee_info['region_lv2']." order by name asc");
				$GLOBALS['tpl']->assign("region_lv3_list",$region_lv3_list);
					
				//输出四级地区
				$region_lv4_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."region_conf where pid = ".$consignee_info['region_lv3']." order by name asc");
				$GLOBALS['tpl']->assign("region_lv4_list",$region_lv4_list);
			}else 
			{    		
				$user_info['consignee'] = $user_info['nickname'];
				$GLOBALS['tpl']->assign("consignee_info",$user_info);
							
				//输出一级地区
				$region_lv1_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."region_conf where pid = 0 order by name asc");
				$GLOBALS['tpl']->assign("region_lv1_list",$region_lv1_list);
			}
		    
			//配送方式解析
			if($consignee_info)
			{
				if($consignee_info['region_lv4']>0)
				{
					$end_region_id = $consignee_info['region_lv4'];
				}elseif($consignee_info['region_lv3']>0)
				{
					$end_region_id = $consignee_info['region_lv3'];
				}elseif($consignee_info['region_lv2']>0)
				{
					$end_region_id = $consignee_info['region_lv2'];
				}elseif($consignee_info['region_lv1']>0)
				{
					$end_region_id = $consignee_info['region_lv1'];
				}
			}else{
				$end_region_id = 0;
			}
						
			//获取支持的配送地区列表
			$delivery_ids = array();
			$delivery_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."delivery where status = 1 order by sort");
				   		
			foreach($delivery_list as $v)
			{
				if($v['allow_default'] == 1&&$GLOBALS['db']->getOneCached("select count(*) from ".DB_PREFIX."delivery_region where delivery_id = ".$v['id'])==0)
				{
					 //允许默认   				
					 array_push($delivery_ids,$v['id']);
				}else{				   				
					 $delivery_region = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."delivery_region where delivery_id = ".$v['id']);
					 $tag = true; //是否未查询到
					 foreach($delivery_region as $vv)
					 {
					   	$region_ids = explode(",",$vv['region_ids']);   					
					   	$tmp_id = intval($end_region_id);				   					
					   	while(intval($GLOBALS['db']->getOneCached("select region_level from ".DB_PREFIX."region_conf where id = ".$tmp_id))>0)
					   	{
					   		if(in_array($tmp_id,$region_ids))
					   		{
					   			array_push($delivery_ids,$v['id']);
						   		$tag = false;
						   		break;
					   		}else{				   							
					   			$tmp_id = intval($GLOBALS['db']->getOneCached("select pid from ".DB_PREFIX."region_conf where id = ".$tmp_id));
					   		}
					   	}
					   					
					} 
					if($tag)
					{
					   	if($v['allow_default'] == 1)
					   	{
					   		//允许默认		   				
							array_push($delivery_ids,$v['id']);
					   	}
					 }  				
				}   			
					
			}
			//end 获取结束

			$delivery_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."delivery where status = 1 order by sort");
					
			foreach($delivery_list as $k=>$v)
			{
				if(!in_array($v['id'],$delivery_ids))
				{
					unset($delivery_list[$k]);
				}else
				{
					$delivery_list[$k]['protect_radio'] = floatval($v['protect_radio'])."%";
					$delivery_list[$k]['protect_price'] = a_formatPrice($v['protect_price']);
				}
			}
			$GLOBALS['tpl']->assign('delivery_list',$delivery_list);
			    

		   //支付方式
			if ($cart_total_price < 0){
					$isAccountpay = 1;
					  		
					$payment = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."payment where class_name = 'Accountpay'");
					$currency_item = array('unit'=>a_fanweC("BASE_CURRENCY_UNIT"),'radio'=>1);
					
			    	if($payment['fee_type']==0)
			    		$payment['fee_format'] = a_formatPrice($payment['fee']);
			    	else 
			    		$payment['fee_format'] = floatval($payment['fee'])."%";
			    			
			    	$GLOBALS['tpl']->assign("accountpay",$payment);
					$GLOBALS['tpl']->assign("isAccountpay",$isAccountpay);			
				}else{
					$isAccountpay = 0;
					$Bank_list = '';
			        if(a_fanweC('ALIPAY_INFO')){
                                    $payment_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."payment where status = 1 and class_name='Alipay' order by sort");
                                }
                                else{
                                    $payment_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."payment where status = 1 order by sort");
                                }
                                foreach($payment_list as $kk=>$vv)
			    	{
						if($vv['class_name'] == "Accountpay")
							$isAccountpay = 1;
			    		$currency_item = array('id'=>1,'unit'=>a_fanweC("BASE_CURRENCY_UNIT"),'radio'=>1);
			    		if($vv['fee_type']==0)
			    			$payment_list[$kk]['fee_format'] = a_formatPrice($vv['fee']);
			    		else 
			    			$payment_list[$kk]['fee_format'] = floatval($vv['fee'])."%";
			    			
			    		
			    		//if($vv['class_name'] == "TenpayBank" ||$vv['class_name'] == "Sdo"){//财付通银行直接接口 add by chenfq 2010-12-30
						    $payment_name = $vv['class_name']."Payment";
							require_once (VENDOR_PATH.'payment3/'.$payment_name.'.class.php');
							$payment_model = new $payment_name;
							
							if (method_exists($payment_model,'getBackList')){
				    			$res = $payment_model->getBackList($vv['id']);
				    			$Bank_list = $Bank_list.$res;
						    	//$GLOBALS['tpl']->assign("Bank_list",$res);
						    	unset($payment_list[$kk]);	//不在前台显示,财付通,只显示各银行 add by chenfq 2011-02-22
							};
							
							if (method_exists($payment_model,'selection')){
						    	$payment_list[$kk]['selection'] = $payment_model->selection($vv['id']);
							};							    			
						//}
			    	}  	
			    	$GLOBALS['tpl']->assign("Bank_list",$Bank_list);
			    	$GLOBALS['tpl']->assign("payment_list",$payment_list);
					$GLOBALS['tpl']->assign("isAccountpay",$isAccountpay);			
				}
				$GLOBALS['tpl']->assign('TAX_RADIO',a_fanweC("TAX_RADIO"));
				$GLOBALS['tpl']->assign('user_info_money',$user_info['money']);
				$GLOBALS['tpl']->assign("PAY_SHOW_TYPE",a_fanweC("PAY_SHOW_TYPE"));
				
				if (($goods_type==1&&$cart_total_price >= 0)||($goods_type!=1&&$cart_total_price > 0))
					$GLOBALS['tpl']->assign("SHOW_PAYMENT_LIST",1);
				else
					$GLOBALS['tpl']->assign("SHOW_PAYMENT_LIST",0);
				
				$GLOBALS['tpl']->assign("user_info",$user_info);
		    	//end 支付方式
				
			    //支付方式解析
				//手机号码框解析
			    foreach($cart_list as $k=>$v)
		   		{
		   			if($v['goods_type'] == 0 ||$v['goods_type'] == 3)
		   			{
		   				$sms_send = 1;
		   				break;
		   			}
		   		}
		   		$GLOBALS['tpl']->assign("sms_send",$sms_send);
		   		
		    	$GLOBALS['tpl']->assign("user_info",$user_info);

				$data = array(
		    		'navs' => array(
		    			array('name'=>a_L("JJ_YOUR_ORDER"),'url'=>"")
		    		),
		    		'keyword'=>	'',
		    		'content'=>	'',
	    		);
		   
	    		assignSeo($data);
				
		    	//代金券
		    	$GLOBALS['tpl']->assign("OPEN_ECV",a_fanweC("OPEN_ECV"));
		    	//输出主菜单
				$GLOBALS['tpl']->assign("main_navs",assignNav(2));
				//输出城市
				$GLOBALS['tpl']->assign("city_list",getGroupCityList());
				//输出帮助
				$GLOBALS['tpl']->assign("help_center",assignHelp());
		    	$GLOBALS['tpl']->display('Page/cart_check.moban');
		    	//代金券
			    //$goods_cart_check = $result = $GLOBALS['tpl']->fetch('Inc/smarty/cart_check_list.moban');;
		    	//$GLOBALS['tpl']->assign("goods_cart_check", $goods_cart_check);
	 }    
	   		
?>