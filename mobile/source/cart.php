<?php
if (intval ( $GLOBALS ['user_info'] ['id'] ) == 0) {
	redirect2 ( "m.php?m=User&a=login" );
} else {
	if ($GLOBALS ['db']->getOne ( "select count(*) from " . DB_PREFIX . "user where id='" . $GLOBALS ['user_info'] ['id'] . "' and user_name='" . $GLOBALS ['user_info'] ['user_name'] . "' and email='" . $GLOBALS ['user_info'] ['email'] . "' " ) == 0) {
		redirect2 ( "m.php?m=User&a=login" );
	}
}

$ma = strtolower ( $_REQUEST ['m'] . '_' . $_REQUEST ['a'] );
if ($ma == "cart_done") {
	m_cart_done ();
} else {
	$ma ();
}

function cart_index() {
	checkCart ();
	$goods_id = intval ( $_REQUEST ['id'] );
	$goods = getGoodsItem ( $goods_id );
	$goods ['userCount'] = $goods ['userBuyCount'];
	$GLOBALS ['tpl']->assign ( "goods", $goods );
	$GLOBALS ['tpl']->display ( "Page/cart_index.html" );
}

function cart_check() {
	//
	$session_id = isset ( $_REQUEST ['s'] ) ? $_REQUEST ['s'] : SESSION_ID;
	$rec_id = intval ( $_POST ['goods_id'] ); //购买的ID
	$rec_module = "PromoteGoods"; //购买的模块
	$number = intval ( $_POST ['count'] ); //购买数量
	//$goods_attr = $_REQUEST['goods_attr'];
	$attrStr = "";
	$goods = getGoodsItem ( $rec_id );
	$user_id = intval ( $GLOBALS ['user_info'] ['id'] );
	$user_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user where id='" . $user_id . "'" );
	if ($goods) {
		$user_buy_count = $GLOBALS ['db']->query ( "select sum(og.number) as user_buy_count from " . DB_PREFIX . "order_goods as og left join " . DB_PREFIX . "order as o on o.id = og.order_id where og.rec_id = " . $goods ['id'] . " and og.user_id=" . $user_id );
		$user_buy_count = intval ( $user_buy_count [0] ['user_buy_count'] );
		
		//有团购商品时
		//开始验证是否可购买
		if ($number <= 0) {
			$err = a_L ( "BOUGHT_NUMBER_ERROR" );
		} elseif ($goods ['stock'] != 0 && $goods ['stock'] - $goods ['buy_count'] < $number) {
			$err = sprintf ( a_L ( "STOCK_LEFT_GOODS" ), ($goods ['stock'] - $goods ['buy_count']) );
		} elseif ($goods ['max_bought'] != 0 && ($user_buy_count + $number) > $goods ['max_bought']) {
			$err = sprintf ( a_L ( "MAX_BOUGHT_LIMIT" ), $goods ['max_bought'] );
		} elseif ($goods ['type_id'] != 2 && $user_info ['money'] < $goods ['shop_price'] * $number) {
			$err = sprintf ( a_L ( "CREDIT_LEFT" ), a_formatPrice ( $user_info ['money'] ) , HTTP_URL );
		} elseif ($goods ['type_id'] == 2 && $user_info ['money'] < $goods ['earnest_money'] * $number) {
			$err = sprintf ( a_L ( "CREDIT_LEFT" ), a_formatPrice ( $user_info ['money'] )  , HTTP_URL);
		} else {
			$err = '';
		}
		
		if ($goods ['type_id'] == 1 || $goods ['type_id'] == 3) //实体商品
		{			
			$consignee_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_consignee where user_id =" . $user_id );
			
			if ($consignee_info) {
				//输出已有的配送信息
				$consignee_info ['qq'] = $user_info ['qq'];
				$consignee_info ['msn'] = $user_info ['msn'];
				$consignee_info ['alim'] = $user_info ['alim'];
				$consignee_info ['email'] = $user_info ['email'];
				
				$GLOBALS ['tpl']->assign ( "consignee_info", $consignee_info );
				
				if ($consignee_info ['region_lv4'] > 0) {
					$end_region_id = $consignee_info ['region_lv4'];
				} elseif ($consignee_info ['region_lv3'] > 0) {
					$end_region_id = $consignee_info ['region_lv3'];
				} elseif ($consignee_info ['region_lv2'] > 0) {
					$end_region_id = $consignee_info ['region_lv2'];
				} elseif ($consignee_info ['region_lv1'] > 0) {
					$end_region_id = $consignee_info ['region_lv1'];
				}
				//获取支持的配送地区列表
				$delivery_ids = loadDelivery($end_region_id);
				$delivery_list = $GLOBALS ['db']->getAllCached ( "select * from " . DB_PREFIX . "delivery where status = 1" );
				
				foreach ( $delivery_list as $k => $v ) {
					if (! in_array ( $v ['id'], $delivery_ids )) {
						unset ( $delivery_list [$k] );
					} else
						$delivery_list [$k] ['protect_radio'] = $v ['protect_radio'] . "%";
				}
				$GLOBALS ['tpl']->assign ( 'delivery_list', $delivery_list );
			
			} else {
				$err = sprintf(a_L ( "FIRST_BUY_GOODS" ),HTTP_URL);
			}
		}
		
		$GLOBALS ['tpl']->assign ( "goods", $goods );
		$GLOBALS ['tpl']->assign ( "number", $number );
		$GLOBALS ['tpl']->assign ( "err", $err );
		if ($err != '') {
			$GLOBALS ['tpl']->display ( "Page/cart_index.html" );
		} else {
			//加入到购物车
			

			$cart_item = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "cart where  session_id = '{$session_id}'" );
			
			if ($goods ['type_id'] == 2)
				$unit_price = $goods ['earnest_money'];
			else
				$unit_price = $goods ['shop_price'];
			
			$now = a_gmtTime ();
			
			$cart_item ['pid'] = 0;
			$cart_item ['rec_id'] = $rec_id;
			$cart_item ['rec_module'] = $rec_module;
			$cart_item ['session_id'] = $session_id;
			$cart_item ['user_id'] = $user_id;
			$cart_item ['number'] = $number;
			$cart_item ['data_unit_price'] = floatval ( $unit_price );
			$cart_item ['data_score'] = $goods ['score'];
			$cart_item ['data_promote_score'] = 0;
			$cart_item ['data_total_score'] = intval ( $goods ['score'] ) * $number;
			$cart_item ['data_total_price'] = $unit_price * $number;
			$cart_item ['create_time'] = $now;
			$cart_item ['update_time'] = $now;
			$cart_item ['data_name'] = $goods ['name_1'];
			$cart_item ['data_sn'] = $goods ['sn'];
			$cart_item ['data_weight'] = $goods ['weight'];
			$cart_item ['data_total_weight'] = floatval ( $goods ['weight'] ) * $number;
			$cart_item ['is_inquiry'] = $goods ['is_inquiry'];
			$cart_item ['goods_type'] = $goods ['type_id'];
			$cart_item ['attr'] = $attrStr;
			
			if ($cart_item ['id'] > 0) {
				$id = $cart_item ['id'];
				unset ( $cart_item ['id'] );
				$GLOBALS ['db']->autoExecute ( DB_PREFIX . "cart", $cart_item, "UPDATE", "id={$id}" );
			} else {
				unset ( $cart_item ['id'] );
				$GLOBALS ['db']->autoExecute ( DB_PREFIX . "cart", $cart_item );
			}
			
			$GLOBALS ['tpl']->display ( "Page/cart_check.html" );
		}
	
	} else {
		redirect2 ( "m.php?m=Index&a=index&s=" . $_REQUEST ['s'] );
	}
}

function m_cart_done() {
	$user_id = intval ( $GLOBALS ['user_info'] ['id'] );
	if ($user_id == 0) {
		redirect2 ( "m.php?m=User&a=login" );
	} else {
		$payment_id = $GLOBALS ['db']->getOne ( "select `id` from " . DB_PREFIX . "payment where class_name='Accountpay'" );
		$delivery_id = intval ( $_POST ['delivery_id'] );
		$is_protect = intval ( $_POST ['is_protect'] );
		$region_lv1 = intval ( $_POST ['region_lv1'] );
		$region_lv2 = intval ( $_POST ['region_lv2'] );
		$region_lv3 = intval ( $_POST ['region_lv3'] );
		$region_lv4 = intval ( $_POST ['region_lv4'] );
		$credit = $GLOBALS ['db']->getOne ( "select `money` from " . DB_PREFIX . "user where id=" . $user_id );
		$isCreditAll = 1;
		
		$cart_total = s_countCartTotal ( $payment_id, $delivery_id, $is_protect, array ('region_lv1' => $region_lv1, 'region_lv2' => $region_lv2, 'region_lv3' => $region_lv3, 'region_lv4' => $region_lv4 ), $tax, $credit, $isCreditAll, $ecvSn, $ecvPassword );
		
		//订单总价
		$order ['order_total_price'] = $cart_total ['all_fee'] - $cart_total ['discount_price'];
		
		//开始生成计单	   
		$now = a_gmtTime ();
		$order ['sn'] = a_toDate ( a_gmtTime (), 'ymdhis' );
		$order ['money_status'] = 0;
		$order ['goods_status'] = 0;
		$order ['status'] = 0;
		$order ['create_time'] = $now;
		$order ['update_time'] = $now;
		$order ['promote_money'] = 0;
		$order ['adm_memo'] = '';
		$order ['memo'] = $_POST ['memo'] ? htmlspecialchars ( $_POST ['memo'], ENT_QUOTES ) : '';
		
		if ($cart_total ['goods_type'] == 1 || $cart_total ['goods_type'] == 3) //修改by hc
		{
			$order ['zip'] = $_POST ['zip'] ? $_POST ['zip'] : '';
			
			//配送地区
			$order ['region_lv1'] = $region_lv1;
			$order ['region_lv2'] = $region_lv2;
			$order ['region_lv3'] = $region_lv3;
			$order ['region_lv4'] = $region_lv4;
			
			$order ['address'] = $_POST ['address'] ? htmlspecialchars ( $_POST ['address'], ENT_QUOTES ) : '';
			$order ['fix_phone'] = $_POST ['fix_phone'] ? htmlspecialchars ( $_POST ['fix_phone'], ENT_QUOTES ) : '';
			$order ['fax_phone'] = $_POST ['fax_phone'] ? htmlspecialchars ( $_POST ['fax_phone'], ENT_QUOTES ) : '';
			$order ['mobile_phone'] = $_POST ['mobile_phone'] ? htmlspecialchars ( $_POST ['mobile_phone'], ENT_QUOTES ) : '';
			$order ['qq'] = $_POST ['qq'] ? htmlspecialchars ( $_POST ['qq'], ENT_QUOTES ) : '';
			$order ['msn'] = $_POST ['msn'] ? htmlspecialchars ( $_POST ['msn'], ENT_QUOTES ) : '';
			$order ['alim'] = $_POST ['alim'] ? htmlspecialchars ( $_POST ['alim'], ENT_QUOTES ) : '';
			
			$order ['consignee'] = $_POST ['consignee'] ? htmlspecialchars ( $_POST ['consignee'], ENT_QUOTES ) : '';
			
			$order ['delivery'] = $delivery_id;
			$order ['protect'] = $is_protect;
			
			$order ['delivery_fee'] = $cart_total ['delivery_free'] == 1 ? 0 : $cart_total ['delivery_fee'];
			$order ['protect_fee'] = $cart_total ['protect_fee'];
			
			$order ['order_weight'] = $cart_total ['total_weight'];
			
			$order ['user_id'] = $user_id;
			//保存本次收货地址到会员地址列表 add by chenfq 2010-04-21
			//dump($order['user_id']);
			if ($order ['user_id'] > 0) {
				//MemberAddress
				//dump($order['user_id']);
				$condition = array ();
				$condition ['user_id'] = $order ['user_id'];
				$condition ['consignee'] = $order ['consignee'];
				$condition ['region_lv1'] = $order ['region_lv1'];
				$condition ['region_lv2'] = $order ['region_lv2'];
				$condition ['region_lv3'] = $order ['region_lv3'];
				$condition ['region_lv4'] = $order ['region_lv4'];
				$condition ['address'] = $order ['address'];
				$condition ['zip'] = $order ['zip'];
				$condition ['mobile_phone'] = $order ['mobile_phone'];
				$condition ['fix_phone'] = $order ['fix_phone'];
				$uc_where = "";
				foreach ( $condition as $k => $v ) {
					$uc_where .= " and `{$k}`='{$v}' ";
				}
				
				if ($GLOBALS ['db']->getOne ( "select count(*) as countx from " . DB_PREFIX . "user_consignee where 1=1 {$uc_where}" ) == 0) {
					$ma_vo ['user_id'] = $order ['user_id'];
					$ma_vo ['consignee'] = $order ['consignee'];
					$ma_vo ['region_lv1'] = $order ['region_lv1'];
					$ma_vo ['region_lv2'] = $order ['region_lv2'];
					$ma_vo ['region_lv3'] = $order ['region_lv3'];
					$ma_vo ['region_lv4'] = $order ['region_lv4'];
					$ma_vo ['address'] = $order ['address'];
					$ma_vo ['zip'] = $order ['zip'];
					$ma_vo ['mobile_phone'] = $order ['mobile_phone'];
					$ma_vo ['fix_phone'] = $order ['fix_phone'];
					$ma_vo ['id'] = null;
					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_consignee", $ma_vo );
				}
			}
		
		} else {
			$order ['zip'] = '';
			$order ['region_lv1'] = 0;
			$order ['region_lv2'] = 0;
			$order ['region_lv3'] = 0;
			$order ['region_lv4'] = 0;
			
			$order ['address'] = '';
			$order ['fix_phone'] = '';
			$order ['fax_phone'] = '';
			$order ['mobile_phone'] = '';
			$order ['qq'] = '';
			$order ['msn'] = '';
			$order ['alim'] = '';
			
			$order ['consignee'] = '';
			
			$order ['delivery'] = 0;
			$order ['protect'] = 0;
			
			$order ['delivery_fee'] = 0;
			$order ['protect_fee'] = 0;
			
			$order ['order_weight'] = 0;
		}
		
		$order ['email'] = $GLOBALS ['user_info'] ['email'];
		$order ['user_id'] = $user_id;
		$order ['payment'] = $payment_id;
		$order ['total_price'] = $cart_total ['goods_total_price']; //商品总价
		$order ['order_score'] = $cart_total ['total_add_score']; //计算订单最终产生的积分
		//$card_code = D("CartCard")->where("session_id='".$session_id."' and user_id=".$user_id)->getField("card_code");
		$order ['card_code'] = '';
		$order ['cost_total_price'] = 0;
		$order ['cost_delivery_fee'] = 0;
		$order ['cost_protect_fee'] = 0;
		$order ['cost_payment_fee'] = 0;
		$order ['cost_other_fee'] = 0;
		$order ['order_profit'] = 0;
		$order ['is_paid'] = 0;
		$order ['parent_id'] = 0;
		
		$order ['promote_money'] = 0;
		$order ['discount'] = $cart_total ['discount_price'];
		$order ['payment_fee'] = $cart_total ['payment_fee'];
		
		$order ['currency_id'] = intval ( $GLOBALS ['db']->getOne ( "select `currency` from " . DB_PREFIX . "payment where id=" . $payment_id ) );
		$order ['currency_radio'] = $GLOBALS ['db']->getOne ( "select `radio` from " . DB_PREFIX . "currency where id='{$order ['currency_id']}'" );
		
		$order ['order_incharge'] = 0;
		
		$order ['lang_conf_id'] = 1;
		
		if ($cart_total ['goods_type'] == 2) {
			$order ['offline'] = 1;
			$GLOBALS ['tpl']->assign ( "goods_type", $cart_total ['goods_type'] );
		}
		else
		{
			//modify by chenfq 2010-06-02 $cart_total['total_price'] 改成：$order['order_total_price']
			if ($order ['order_total_price'] == 0) {
				//会员直接使用：余额支付或代金券支付
				$order ['payment_fee'] = 0;
				$order ['currency_radio'] = 0;
				$order ['payment'] = 0;
			} else {
				$order ['payment'] = 0;
				$order ['payment_fee'] = 0;
				if ($cart_total ['credit'] != 0) //modify by chenfq 2010-05-12  $cart_total['credit'] > 0 ==> $cart_total['credit'] <> 0
				{
					$accountpay = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment where class_name = 'Accountpay'" );
					$order ['payment'] = $accountpay ['id'];
					$order ['currency_id'] = intval ( $GLOBALS ['db']->getOne ( "select `currency` from " . DB_PREFIX . "payment where id=" . $order ['payment'] ) );
					$order ['currency_radio'] = $GLOBALS ['db']->getOne ( "select `radio` from " . DB_PREFIX . "currency where id='{$order ['currency_id']}'" );
				}
				
				if (! $order ['currency_radio'])
					$order ['currency_radio'] = 0;
			}
		}
		
		//modify by chenfq 2010-06-02 $cart_total['goods_total_price'] 改成：$order['order_total_price']
		if ($cart_total ['goods_type'] == 1||$cart_total ['goods_type'] == 3 && $cart_total ['order_total_price'] > 0) // add by chenfq 2010-05-17  && $cart_total['goods_total_price'] > 0
		{
		
		} else {
			/**
			 * modify by chenfq 2010-06-03
			 * 修改订单都为：无需配送 bug 状态
			 * $order['goods_status'] = 5; 
			 */
			if ($cart_total ['goods_type'] != 1 && $cart_total ['goods_type'] != 3)
				$order ['goods_status'] = 5; //团购券商品改为5
		}
		
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "order", $order );
		$order_id = $GLOBALS ['db']->insert_id();
		if ($order_id > 0) //提交成功后提交订单商品
		{
			
			$session_id = SESSION_ID;
			
			$cart_item = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "cart where session_id='" . $session_id . "' and user_id=" . $user_id );
			
			$order_goods ['pid'] = 0;
			$order_goods ['user_id'] = $user_id;
			$order_goods ['order_id'] = $order_id;
			$order_goods ['rec_module'] = $cart_item ['rec_module'];
			$order_goods ['rec_id'] = $cart_item ['rec_id'];
			$order_goods ['data_name'] = $cart_item ['data_name'];
			$order_goods ['data_sn'] = $cart_item ['data_sn'];
			$order_goods ['data_score'] = $cart_item ['data_score'];
			$order_goods ['data_total_score'] = $cart_item ['data_total_score'];
			$order_goods ['data_price'] = $cart_item ['data_unit_price'];
			$order_goods ['data_total_price'] = $cart_item ['data_total_price'];
			$order_goods ['data_score'] = $cart_item ['data_promote_score'];
			$order_goods ['data_total_score'] = $cart_item ['data_total_score'];
			$order_goods ['data_score'] = $cart_item ['data_score'];
			$order_goods ['attr'] = $cart_item ['attr'];
			$order_goods ['number'] = $cart_item ['number'];
			$order_goods ['is_inquiry'] = $cart_item ['is_inquiry'];
			$order_goods ['create_time'] = $now;
			$order_goods ['status'] = 0;
			$order_goods ['data_weight'] = $cart_item ['data_weight'];
			
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "order_goods", $order_goods );
			
			//$og_id = $OrderGoods->add ( $order_goods );
			

			$order = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "order where id=" . $order_id );
			
			if ($order ['order_total_price'] <= 0) {
				s_order_incharge_handle ( $order );
				//upd_mobile_user_cache ();
			}
			
			//add by chenfq 2010-05-12 begin	
			if ($order ['order_total_price'] < 0 && $user_id > 0) {
				//记录会员预存款变化明细
				//$memo 格式为 #LANG_KEY#memos  ##之间所包含的是语言包的变量
				$memo = $order ['sn'] . a_L ( "ORDER_INCHARGE" );
				s_user_money_log ( $user_id, $order_id, 'UserIncharge', abs ( $order ['order_total_price'] ), $memo );
				$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "cart  where session_id='" . $session_id . "'" );
				$accountpay_str = a_L ( "PAY_SUCCESS" );
				redirect2 ( "m.php?m=Usercenter&a=order&s=" . $session_id . "&err=" . base64_encode ( $accountpay_str ) );
				exit ();
			}
			
			$goods = getGoodsItem ( $cart_item ['rec_id'] );
			if ($cart_total ['credit'] > 0 || ($cart_total ['credit'] == 0 && $goods ['shop_price'] >= 0 && $goods ['type_id'] != 2)) {
				$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "cart  where session_id='" . $session_id . "'" );
				$accountpay_str = getPayment ( $order_id, 0 , $cart_total ['credit'], 'Accountpay' );
				//upd_mobile_user_cache ();
				redirect2 ( "m.php?m=Usercenter&a=order&s=" . $session_id . "&err=" . urlencode($accountpay_str ) );
			} else {
				$user_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user where id='{$user_id}'" );
				$err = sprintf ( a_L ( "CREDIT_LEFT" ), a_formatPrice ( $user_info ['money'] ) , HTTP_URL );
				$GLOBALS ['tpl']->assign ( "err", $err );
				
				$goods ['userCount'] = $goods ['userBuyCount'];
				
				$GLOBALS ['tpl']->assign ( "goods", $goods );
				$GLOBALS ['tpl']->display ( "Page/cart_index.html" );
			}
		}
	
	}
}

function checkCart() {
	$goods_id = intval ( $_REQUEST ['id'] );
	$now = a_gmtTime ();
	
	$where = " and status = 1";
	$where .= " and promote_end_time > {$now}";
	$where .= " and id ='{$goods_id}'";
	$where .= " and is_group_fail <>1 ";
	
	$goods = $GLOBALS ['db']->getRow ( "select `id`,`promote_begin_time`,`type_id` from " . DB_PREFIX . "goods where 1=1 {$where}" );
	
	if (! $goods || ($goods ['promote_begin_time'] > a_gmtTime ())) {
		redirect2 ( "m.php?s=" . $_REQUEST ['id'] );
	}
}
?>