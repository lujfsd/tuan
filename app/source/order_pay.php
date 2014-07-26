<?php
		$order_id = intval($_REQUEST['id']);
		$accountpay_str = $_REQUEST['accountpay_str'];
		$ecvpay_str = $_REQUEST['ecvpay_str'];
		
		$order_info = $GLOBALS['db']->getRow("select id, sn, offline, user_id,money_status, (order_total_price - order_incharge) as total_price_less,payment,create_time,total_price,is_360_post,is_2345_post,is_baidu_post from ".DB_PREFIX."order where id = ".$order_id);
		
		if(!$order_info || intval($_SESSION['user_id']) != $order_info['user_id'])
		{ 
			redirect2(a_u("Index/index"));
			exit;
		}
		
		$user_id = intval($_SESSION['user_id']);
		
		if($order_info['payment']==0)
		{
			a_error(a_L("HC_CHOICE_PAYMENT"));
		}
				
		$money = floatval($_REQUEST['money']);
		if($money == 0)
		{
			$money = round($order_info['total_price_less'],2);
		}
		
		if($order_info['money_status'] == 2)
		{
			redirect2(__ROOT__."/index.php?m=Order&a=pay_success&id=".$order_id);
			exit;
		}
		elseif($money<=0)
		{
			a_error(a_L("AMOUNT_ERROR"), '', __ROOT__."/index.php?");
		}
				
		
		$sql = "select b.id,b.stock, b.buy_count, b.promote_end_time, b.promote_end_time, b.is_group_fail, a.number from ".DB_PREFIX."order_goods a ".
				"left outer join  ".DB_PREFIX."goods b on b.id = a.rec_id where a.order_id = ".$order_id;
		$goods_list = $GLOBALS['db']->getAll($sql);
		foreach($goods_list as $key=>$goods){
			//add by chenfq 2010-07-3 判断时间是否结束
			if ($goods['promote_end_time'] < a_gmtTime() || $goods['is_group_fail'] == 1 || ($goods['stock'] > 0 && $goods['buy_count'] > $goods['stock'])){ 		
				a_error(a_L("STOP_BUY"), '', __ROOT__."/index.php?");//'团购结束，终止支付'
			}
						
			//已经购买的商品个数 add by chenfq 2010-05-17 $order_info['money_status'] <> 2
			if ($goods['stock'] > 0){
				if ($goods['buy_count'] >= $goods['stock']) {
					a_error(a_L("HC_GROUPON_OVER"), '', __ROOT__."/index.php?");
				}elseif ($goods['buy_count'] + $goods['number']  > $goods['stock']) {
					a_error(a_L("HC_BUYCOUNT_TOO_MUCH"), '', __ROOT__."/index.php?");
				}
			}
		}
		
		/* 如果全部使用余额支付，检查余额是否足够 */
		$payment = $GLOBALS['db']->getRow("select class_name from ".DB_PREFIX."payment where id = ".intval($order_info['payment'])." limit 1");
    	if ($payment['class_name'] == 'Accountpay'){//会员使用预存款支付
    		$user = $GLOBALS['db']->getRow("select money from ".DB_PREFIX."user where id = ".intval($user_id)." limit 1");	
    	    if ($user){
		  	 	if (($user['money'] < 0) || ($money - $user['money'] > 0.01 )){
		  	 		//a_error(a_L('USER_MONEY_DEFICIT'), '', __ROOT__."/index.php?");
		  	 		a_error(a_L('USER_MONEY_DEFICIT'), '', __ROOT__."/index.php?m=Order&a=check&id=".$order_id);
		  	 	}
    	  	}else{
    	  		//a_error(a_L('INVALID_USER_ID'), '', __ROOT__."/index.php?");
    	  		a_error(a_L('INVALID_USER_ID'), '', "back");
    	  	}
		}
		if(!empty($_SESSION['qid'])&&a_fanweC('360_ORDER_INFO')&&($order_info['is_360_post']==0))
		{
			include_once(VENDOR_PATH."user_login/360/Tuan360Client.php");
			define("APP_KEY",a_fanweC('360_KEY'));       // input your key
            define("APP_SECRET",a_fanweC('360_SECRET')); //input you secret
			$goods_list = $GLOBALS['db']->getAll("select g.id as order_goods_id,og.data_price,og.number,og.data_name,g.brief_1,s.address from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."goods as g on og.rec_id=g.id left join ".DB_PREFIX."suppliers as s on s.id=g.suppliers_id where  og.order_id = ".$order_info['id']);
			
			if($goods_list){
				$order_time=a_toDate($order_info['create_time'],"YmdHi");
				$spend_close_time=a_toDate($order_info['create_time']+(30*24*3600),"YmdHis");
				
				$client = new Tuan360Client(APP_KEY,APP_SECRET);
				foreach($goods_list as $k=>$goods){
					$goods['address']=!empty($goods['address'])?$goods['address']:'团购网';
		            $res = $client->send(intval($_SESSION['qid']),intval($order_info['id']),$order_time,$goods['order_goods_id'],round($goods['data_price'],2),intval($goods['number']),round($order_info['total_price'],2),a_getDomain().'/index.php?m=Goods&a=show&id='.$goods['order_goods_id'],a_msubstr($goods['data_name'],0,20),$goods['data_name'],$spend_close_time,$goods['address']);
				}
	            $sql = "update ".DB_PREFIX."order set is_360_post = 1 where id =".$order_info['id'];
				$GLOBALS['db']->query($sql);
			}
		}
		
		if(!empty($_SESSION['t2345id'])&&a_fanweC('2345_ORDER_INFO')&&($order_info['is_2345_post']==0))
		{
			include_once(VENDOR_PATH."user_login/2345/Tuan2345Client.php");
			define("APP_KEY",a_fanweC('2345_KEY'));       // input your key
            define("APP_SECRET",a_fanweC('2345_SECRET')); //input you secret
            $goods_list = $GLOBALS['db']->getAll("select g.id as order_goods_id,og.data_price,og.number,og.data_name,g.brief_1,g.market_price,g.shop_price,g.small_img,s.address from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."goods as g on og.rec_id=g.id left join ".DB_PREFIX."suppliers as s on s.id=g.suppliers_id where og.order_id = ".$order_info['id']);
			if($goods_list)
			{
				$order_time=$order_info['create_time']+8*3600;
				$spend_close_time=$order_info['create_time']+8*3600+(30*24*3600);
				
				$client = new Tuan2345Client(APP_KEY,APP_SECRET);
				foreach($goods_list as $k=>$goods){
			            $res = $client->send(intval($_SESSION['t2345id']),intval($order_info['id']),$order_time,$goods['order_goods_id'],round($goods['market_price'],2),round($goods['shop_price'],2),round(($goods['shop_price'] / $goods['market_price']) * 10,2),CND_URL.$goods['small_img'],a_fanweC("SHOP_NAME"),intval($goods['number']),round($order['total_price'],2),a_getDomain().'/index.php?m=Goods&a=show&id='.$goods['order_goods_id'],substr($goods['data_name'],0,20),$spend_close_time);
				}
				
				$sql = "update ".DB_PREFIX."order set is_2345_post = 1 where id =".$order_info['id'];
				$GLOBALS['db']->query($sql);
			}
		}
		
		if (! empty ( $_SESSION ['baidu_id'] ) && a_fanweC ( 'BAIDU_ORDER_INFO' ) && ($order_info ['is_baidu_post'] == 0)) {
			include_once (VENDOR_PATH . "user_login/baidu/TuanBaiduClient.php");
			$access_token_uri = "https://openapi.baidu.com/oauth/2.0/token";
			$hao123_open_api_uri = "https://openapi.baidu.com/rest/2.0/hao123/";
			$api_key = a_fanweC ( "APP_KEY_baidu" ); //todo
			$api_secret = a_fanweC ( "APP_SECRET_baidu" ); //todo
			
		
			$url = $access_token_uri . "?grant_type=client_credentials&client_id=" . $api_key . "&client_secret=" . $api_secret;
			
			$content = curl_http_request ( $url, array (), 'GET' );
			$content_arr = json_decode ( $content, true );
			if (isset ( $content_arr ["access_token"] )) {
				$token = $content_arr ["access_token"];
			}
			if (empty ( $token )) {
				echo "error to get token:$content\n";
				
			// log->warn("get token from baidu by client_credentials failed:".$content['error_description']);
			} else {
				$goods_list = $GLOBALS ['db']->getAll ( "select g.id as order_goods_id,g.group_bond_end_time,og.data_price,og.number,og.data_name,g.brief_1,s.address from " . DB_PREFIX . "order_goods as og left join " . DB_PREFIX . "goods as g on og.rec_id=g.id left join " . DB_PREFIX . "suppliers as s on s.id=g.suppliers_id where  og.order_id = " . $order_info ['id'] );
				
				if ($goods_list) {
					if(intval($goods['group_bond_end_time'])>0)
					{
						$order_time = a_toDate($goods['group_bond_end_time'],"YmdHis");
						$spend_close_time = a_strtotime($order_time);
					}
					else
					{
						$spend_close_time = 0;
					}
						
					foreach ( $goods_list as $k => $goods ) {
						$goods ['address'] = ! empty ( $goods ['address'] ) ? $goods ['address'] : '团购网';
						$order_arr = array ('access_token' => $token, 'order_id' => $order_info ['id'], 'title' => $goods ['data_name'], 'logo' => CND_URL . $goods ['small_img'], 'url' => CND_URL . a_U ( "Goods/show", "id-" . $goods ['order_goods_id'] ), 'price' => round ( $goods ['data_price'], 2 ), 'goods_num' => intval ( $goods ['number'] ), 'sum_price' => round ( $order_info ['total_price'], 2 ), 'summary' => $goods ['data_name'], 'expire' => $spend_close_time, 'bonus' => 0, 'uid' => $_SESSION ['baidu_id'], 'tn' => $_SESSION ['tn'] );
						$url = $hao123_open_api_uri . "saveOrder";
						$ret = curl_http_request ( $url, $order_arr, 'POST' );
					}
					// log $ret
					$sql = "update " . DB_PREFIX . "order set is_baidu_post = 1 where id =" . $order_info ['id'];
					$GLOBALS ['db']->query ( $sql );
				}
			}
		}
		
		//生成支付按钮
		$payment_str = getPayment($order_id,0,$money);
		if(empty($_REQUEST['pay']))
			$GLOBALS['tpl']->assign("error",a_L("HC_ORDER_REPAY"));
	
		$GLOBALS['tpl']->assign("payment_str",$payment_str);
		$GLOBALS['tpl']->assign("accountpay_str",$accountpay_str);
		$GLOBALS['tpl']->assign("ecvpay_str",$ecvpay_str);
		
		$GLOBALS['tpl']->assign("order_info",a_L("PAY_NOW")." [&nbsp;&nbsp;".a_L("ORDER_SN")."：<span class='red'>".$order_info['sn']."</span>&nbsp;&nbsp;]");
		if($order_info['offline'] == 1)
			$GLOBALS['tpl']->assign("goods_type",2);
			
		$GLOBALS['tpl']->assign("order_id",$order_id);
		
	   	//$order_pay_info = $GLOBALS['tpl']->fetch('Inc/smarty/order_pay_info.moban');
	   	//$GLOBALS['tpl']->assign("order_pay_info",$order_pay_info);
		$url_check = __ROOT__."/index.php?m=Order&a=check&id=".$order_id;
		$GLOBALS['tpl']->assign("order_check_url",$url_check);
		
		$url_pay = __ROOT__."/index.php?m=Order&a=pay&id=".$order_id;
		$GLOBALS['tpl']->assign("order_pay_url",$url_pay); 
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		
		$navs = array('name'=>a_L("PAY_NOW"),'url'=>a_u("Order/pay"));
		//输出当前页seo内容
		$data = array(
			'navs' => array(
				$navs,
			),
			'keyword'=>	'',
			'content'=>	'',
		);
		assignSeo($data); 
		
		$GLOBALS['tpl']->display('Page/order_pay.moban');
?>