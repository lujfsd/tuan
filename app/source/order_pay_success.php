<?php
		$order_id = intval($_REQUEST['id']);
		$user_id = intval($_SESSION['user_id']);
		$order = $GLOBALS['db']->getRow("select id, sn, offline, user_id, money_status,is_360_post,is_2345_post,is_baidu_post,total_price,create_time from ".DB_PREFIX."order where id = ".$order_id);
		if(!$order || $user_id != $order['user_id'])
		{ 
			redirect2(a_u("Index/index"));
			exit;
		}
		if($order['money_status'] == 2)
		{		
			
			$referrals = 0;
			$urlweb = '';
			$sql = "select b.id,b.name_1 as goods_name, b.goods_short_name, b.allow_sms,b.buy_count, b.complete_time, b.is_group_fail, b.referrals from ".DB_PREFIX."order_goods a ".
					"left outer join  ".DB_PREFIX."goods b on b.id = a.rec_id where a.order_id = ".$order_id;
			$goods_list = $GLOBALS['db']->getAll($sql);

			foreach($goods_list as $key=>$goods){
				$goods_list[$key]['urlweb'] = a_getDomain().__ROOT__."/index.php?m=Goods&a=show&id=".$goods['id']."&ru=".$user_id;
				$goods_list[$key]['url'] = __ROOT__."/index.php?m=Goods&a=show&id=".intval($goods['id']);
				
				if($goods['goods_short_name']!='') 
					$goods_list[$key]['goods_name'] = $goods['goods_short_name'];
				
				if ($goods['allow_sms'] == 1){
					$allow_sms = 1;
				}
				
				if ($goods['referrals'] >= $referrals || empty($urlweb)){
					$urlweb = $goods_list[$key]['urlweb'];
					$referrals = $goods['referrals'];
				}
			}
			
			//print_r($goods_list);

			
			$ugb_urlweb = __ROOT__."/index.php?m=UcGroupBond&a=index";
			
			//2010/6/7 awfigq 查询订单成功时，是否有未发送的团购券
			$group_bond_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."group_bond where order_id = '$order[sn]' and is_valid = 1 ");
			$group_bond_count_no_send = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."group_bond where order_id = '$order[sn]' and is_valid = 1 and is_send_msg = 0");
			
			
			$mobile_phone = $GLOBALS['db']->getOne("select mobile_phone from ".DB_PREFIX."user where id = ".$user_id);

			
			$referralsMoney = a_fanweC("REFERRALS_MONEY");
			if(a_fanweC("REFERRAL_TYPE") == 0)
			{
				$referralsMoney = a_formatPrice($referralsMoney);
			}
			else
			{
				$referralsMoney = $referralsMoney."".a_L("SCORE_UNIT");
			}
			
			if(!empty($_SESSION['qid'])&&a_fanweC('360_ORDER_INFO')&&($order['is_360_post']==0))
			{
				include_once(VENDOR_PATH."user_login/360/Tuan360Client.php");
				define("APP_KEY",a_fanweC('360_KEY'));       // input your key
	            define("APP_SECRET",a_fanweC('360_SECRET')); //input you secret
				$goods_list = $GLOBALS['db']->getAll("select g.id as order_goods_id,og.data_price,og.number,og.data_name,g.brief_1,s.address from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."goods as g on og.rec_id=g.id left join ".DB_PREFIX."suppliers as s on s.id=g.suppliers_id where  og.order_id = ".$order_id);
				
				if($goods_list){
					$order_time=a_toDate($order['create_time'],"YmdHi");
					$spend_close_time=a_toDate($order['create_time']+(30*24*3600),"YmdHis");
					
		            $client = new Tuan360Client(APP_KEY,APP_SECRET);
		            foreach($goods_list as $k => $goods){
						$goods['address']=!empty($goods['address'])?$goods['address']:'团购网';
			            $res = $client->send(intval($_SESSION['qid']),intval($order_id),$order_time,$goods['order_goods_id'],round($goods['data_price'],2),intval($goods['number']),round($order['total_price'],2),a_getDomain().'/index.php?m=Goods&a=show&id='.$goods['order_goods_id'],a_msubstr($goods['data_name'],0,20),$goods['data_name'],$spend_close_time,$goods['address']);
			        	/*echo "APP_KEY:|".a_fanweC('360_KEY')."|<br>";
						echo "APP_SECRET:|".a_fanweC('360_SECRET')."|<br>";
						echo "qid:".intval($_SESSION['qid'])."<br>";
						echo "order_id:".intval($order_id)."<br>";
						echo "order_time:".$order_time."<br>";
						echo "pid:".$goods['order_goods_id']."<br>";
						echo "price:".round($goods['data_price'],2)."<br>";
						echo "number:".intval($goods['number'])."<br>";
						echo "total_price:".round($order['total_price'],2)."<br>";
						echo "goods_url:".a_getDomain().'/index.php?m=Goods&a=show&id='.$goods['order_goods_id']."<br>";
						echo "title:".a_msubstr($goods['data_name'],0,20)."<br>";
						echo "desc:".a_msubstr($goods['data_name'],0,20)."<br>";
						echo "title:".$goods['data_name']."<br>";
						echo "spend_close_time:".$spend_close_time."<br>";
						echo "merchant_addr:".$goods['address']."<br>";
						echo "返回值：";
						print_r($res);
						echo '<br>========================================================';
						die();*/
			        }
		            $sql = "update ".DB_PREFIX."order set is_360_post = 1 where id =".$order_id;
					$GLOBALS['db']->query($sql);
				}
			}
			
			if(!empty($_SESSION['t2345id'])&&a_fanweC('2345_ORDER_INFO')&&($order['is_2345_post']==0))
			{
				include_once(VENDOR_PATH."user_login/360/Tuan360Client.php");
				define("APP_KEY",a_fanweC('2345_KEY'));       // input your key
	            define("APP_SECRET",a_fanweC('2345_SECRET')); //input you secret
				$goods_list = $GLOBALS['db']->getAll("select g.id as order_goods_id,og.data_price,og.number,og.data_name,g.brief_1,g.market_price,g.shop_price,g.small_img,s.address from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."goods as g on og.rec_id=g.id left join ".DB_PREFIX."suppliers as s on s.id=g.suppliers_id where og.order_id = ".$order_id);
				if($goods_list)
				{
					$order_time=$order['create_time']+8*3600;
					$spend_close_time=$order['create_time']+8*3600+(30*24*3600);
					
					$client = new Tuan2345Client(APP_KEY,APP_SECRET);
					foreach($goods_list as $k=>$goods){
			            $res = $client->send(intval($_SESSION['t2345id']),intval($order_id),$order_time,$goods['order_goods_id'],round($goods['market_price'],2),round($goods['shop_price'],2),round(($goods['shop_price'] / $goods['market_price']) * 10,2),CND_URL.$goods['small_img'],a_fanweC("SHOP_NAME"),intval($goods['number']),round($order['total_price'],2),a_getDomain().'/index.php?m=Goods&a=show&id='.$goods['order_goods_id'],substr($goods['data_name'],0,20),$spend_close_time);
					}
		            $sql = "update ".DB_PREFIX."order set is_2345_post = 1 where id =".$order_id;
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
				$goods_list = $GLOBALS ['db']->getAll ( "select g.id as order_goods_id,g.small_img,g.group_bond_end_time,og.data_price,og.number,og.data_name,g.brief_1,s.address from " . DB_PREFIX . "order_goods as og left join " . DB_PREFIX . "goods as g on og.rec_id=g.id left join " . DB_PREFIX . "suppliers as s on s.id=g.suppliers_id where  og.order_id = " . $order_id );
				
				if ($goods_list) {
					foreach ( $goods_list as $k => $goods ) {
						if(intval($goods['group_bond_end_time'])>0)
						{
							$order_time = a_toDate($goods['group_bond_end_time'],"YmdHis");
							$spend_close_time = a_strtotime($order_time);
						}
						else
						{
							$spend_close_time = 0;
						}
						$order_arr = array ('access_token' => $token, 'order_id' => $order_id, 'title' => $goods ['data_name'], 'logo' => CND_URL . $goods ['small_img'], 'url' => a_fanweC("SHOP_URL") . a_U ( "Goods/show", "id-" . $goods ['order_goods_id'] ), 'price' => round ( $goods ['data_price']*100, 2 ), 'goods_num' => intval ( $goods ['number'] ), 'sum_price' => round ( $order ['total_price']*100, 2 ), 'summary' => $goods ['data_name'], 'expire' => $spend_close_time, 'bonus' => 0, 'uid' => $_SESSION ['baidu_id'], 'tn' => $_SESSION ['tn'] );
						$url = $hao123_open_api_uri . "saveOrder";
						$ret = curl_http_request ( $url, $order_arr, 'POST' );
					}
					$sql = "update " . DB_PREFIX . "order set is_baidu_post = 1 where id =" . $order_id;
					$GLOBALS ['db']->query ( $sql );
				}
			}
		}

		
			$GROUPBOTH = a_fanweC('GROUPBOTH');
			
			$GLOBALS['tpl']->assign("order_sn",$order['sn']);
			$GLOBALS['tpl']->assign("goods_list",$goods_list);
			$GLOBALS['tpl']->assign("allow_sms",$allow_sms);
			$GLOBALS['tpl']->assign("group_bond_count",$group_bond_count);
			$GLOBALS['tpl']->assign("group_bond_count_no_send",$group_bond_count_no_send);
			$GLOBALS['tpl']->assign("mobile_phone",$mobile_phone);
			$GLOBALS['tpl']->assign("urlweb", $urlweb);
			$GLOBALS['tpl']->assign("referralsMoney",$referralsMoney);
			$GLOBALS['tpl']->assign("GROUPBOTH",$GROUPBOTH);
			$GLOBALS['tpl']->assign("ugb_urlweb",$ugb_urlweb);
			//输出主菜单
			$GLOBALS['tpl']->assign("main_navs",assignNav(2));
			//输出城市
			$GLOBALS['tpl']->assign("city_list",getGroupCityList());
			//输出帮助
			$GLOBALS['tpl']->assign("help_center",assignHelp());
			
			$navs = array('name'=>a_L("PAY_SUCCESS"),'url'=>a_u("Order/pay_success"));
			$data = array(
				'navs' => array(
					$navs,
				),
				'keyword'=>	'',
				'content'=>	'',
			);  
			assignSeo($data); 
			
			$GLOBALS['tpl']->display('Page/pay_success.moban');
		   	//$pay_success_info = $GLOBALS['tpl']->fetch('Inc/smarty/pay_success_info.moban');
		   	//$GLOBALS['tpl']->assign("pay_success_info",$pay_success_info);
		   	//$content = preg_replace("/<loader_pay_success_info([^>]*)>/i",$result,$content);
		}else{
			redirect2(__ROOT__);
			exit;			
		}	
		?>