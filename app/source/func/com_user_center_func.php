<?php 
	function user_enter_init(){
		$user_id = intval($_SESSION['user_id']);
		if($user_id==0)
	    {
	    		if(a_fanweC("URL_ROUTE")==0)
	    		{
	    			$cart_login_url = __ROOT__."/index.php?m=User&a=login";
	    		}
	    		else
	    		{
	    			$cart_login_url = __ROOT__."/User-login.html";
	    		}    		
	    		redirect2($cart_login_url);
	    		exit;
	    }
		
		$module_name = strtolower($_REQUEST['m']);
		$user_menu = $GLOBALS['user_menu'];
		foreach($user_menu as $k=>$v)
		{
			$current_module = $v['module'];
			if(strtolower($current_module)==$module_name)
			{
				$user_menu[$k]['act'] = 1;
			}
		}
		
		//输出会员提现与充值
		$user_money_menu = array(
			array('name'=>a_L("UCINCHARGE_INDEX"), 'url'=>a_u("UcIncharge/index"),'module'=>'UcIncharge'),			
		);
		
		$user_uncharge_menu = array(
			array('name'=>a_L("UCUNCHARGE_INDEX"), 'url'=>a_u("UcUncharge/index"),'module'=>'UcUncharge'),
		);
		
		foreach($user_money_menu as $k=>$v)
		{
			$current_module = $v['module'];
			if(strtolower($current_module)==$module_name)
			{
				$user_money_menu[$k]['act'] = 1;
			}
		}
		foreach($user_uncharge_menu as $k=>$v)
		{
			$current_module = $v['module'];
			if(strtolower($current_module)==$module_name)
			{
				$user_uncharge_menu[$k]['act'] = 1;
			}
		}
		if(a_fanweC("CLOSE_USERMONEY")==0)
		{
			foreach($user_menu as $k=>$v)
			{
				$current_module = $v['module'];
				if(strtolower($current_module)=='uclog'&&$module_name=='ucincharge')
				{
					$user_menu[$k]['act'] = 1;
				}
			}
			$GLOBALS['tpl']->assign("user_money_menu",$user_money_menu);
		}
		
		if(a_fanweC("CLOSE_USERUNCHARGE") == 0)
		{
			foreach($user_menu as $k=>$v)
			{
				$current_module = $v['module'];
				if(strtolower($current_module)=='uclog'&&$module_name=='ucuncharge')
				{
					$user_menu[$k]['act'] = 1;
				}
			}
			$GLOBALS['tpl']->assign("user_uncharge_menu",$user_uncharge_menu);
		}
		
		
        $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($_SESSION['user_id']));
        $user_group = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."user_group where id =".intval($user_info['group_id']));
        
		$user_info['create_time_format'] = a_toDate($user_info['create_time'],'Y-m-d H:i');
		$user_info['update_time_format'] = a_toDate($user_info['update_time'],'Y-m-d H:i');
		if (substr($user_info['email'], 0, 3) == 'sy_') {
                    $user_info['email'] = null;	 
                  }
        $GLOBALS['tpl']->assign('user_info',$user_info);
        $GLOBALS['tpl']->assign('user_group',$user_group);
        $GLOBALS['tpl']->assign("user_group_name",$user_group['name_1']);
        $GLOBALS['tpl']->assign("user_score",$user_info['score']);
        
        $GLOBALS['tpl']->assign('user_money_format',a_formatprice($user_info['money']));
        $GLOBALS['tpl']->assign('user_score_format',$user_info['score']." ".$GLOBALS['Ln']["SCORE_UNIT"]);
        
        $GLOBALS['tpl']->assign('referrals_user_count',$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where parent_id =".intval($_SESSION['user_id'])));

		$GLOBALS['tpl']->assign("mail_exist",$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mail_address_list where mail_address='".$user_info['email']."'"));
	
		$GLOBALS['tpl']->assign('XY_RECHARGE_TO_USER',sprintf($GLOBALS['Ln']['XY_RECHARGE_TO_USER'],SHOP_NAME));
        //$result = $GLOBALS['tpl']->fetch('Inc/goods/user_info.moban');		

		//会员菜单
		$GLOBALS['tpl']->assign("user_menu",$user_menu);
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());		
		//=======================================================================================
	}
	
	//获取团购券
	function getGroupBondList($status=1,$page=1,$user_id=0)
	{
		if($user_id == 0)
		{
			$user_id = intval($_SESSION['user_id']);
		}
		$time = a_gmtTime();
		$where = " status = 1 and is_valid = 1 and user_id = ".$user_id;
		if($status == 1)
			$where .= " and (use_time = 0 or use_time is null)";
		elseif($status == 2)
			$where .= " and use_time > 0";
		elseif($status == 3)
			$where .= " and end_time < $time and end_time > 0";
		
		$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".a_fanweC("PAGE_LISTROWS");
		
		$list = $GLOBALS['db']->getAll("select `id`,`goods_id`,`goods_name`,`order_id`,`sn`,`password`,`create_time`,`status`,`end_time`,`use_time`,`buy_time`,`depart_id` from ".DB_PREFIX."group_bond where ".$where." order by buy_time desc limit ".$limit);
		$result['total'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."group_bond where ".$where);
		
		foreach($list as $k=>$v)
		{
			$list[$k]['goods'] = $GLOBALS['db']->getRow("select id,name_1,goods_short_name,small_img,shop_price,market_price,allow_sms from ".DB_PREFIX."goods where id='{$v['goods_id']}'");
			$list[$k]['goods']['url'] = a_u("Goods/show","id-".$list[$k]['goods']['id']);
			$list[$k]['create_time_format'] = a_toDate($v['create_time'],'Y-m-d');
			$list[$k]['buy_time_format'] = a_toDate($v['buy_time'],'Y-m-d');
			$list[$k]['use_time_format'] = a_toDate($v['use_time'],'Y-m-d');
			$list[$k]['end_time_format'] = a_toDate($v['end_time'],'Y-m-d');
			if(($v['end_time'] > $time || $v['end_time'] == 0) && $v['use_time'] == 0)
				$list[$k]['is_edit'] = 1;
				
			if(a_fanweC("IS_SMS") == 1)
				$list[$k]['is_sms'] = 1;
		}
		$result['list'] = $list;
		
		return $result;
	}
	
	
	function getOrderList($user_id,$page=1,$offline=0)
	{
		$limit = (($page-1)*a_fanweC("PAGE_LISTROWS")).",".(a_fanweC("PAGE_LISTROWS"));
		
		$sql = "select o.id,o.sn,o.create_time,o.order_total_price,o.money_status,o.goods_status,o.status,o.offline from ".DB_PREFIX."order as o ".
				//"left join ".DB_PREFIX."order_goods as og on og.order_id = o.id ".
				"where o.status!=2 and o.user_id = '".intval($user_id)."' and o.offline = $offline ";
		
		
		$result['total'] = $GLOBALS['db']->getOne("select count(*) from (".$sql.") a");
		$sql .= " order by o.create_time desc,o.update_time desc LIMIT $limit";
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{	
			//$time = a_gmtTime();
			
			$sql = "select og.attr,og.data_name as goods_name,og.number, og.is_inquiry,og.rec_id as goods_id, g.id,g.name_1,g.goods_short_name,g.is_group_fail,g.promote_end_time,g.small_img,g.type_id,g.shop_price,g.market_price,g.score_goods from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."goods as g on g.id = og.rec_id where og.order_id = '{$v['id']}'";  
			//$goods = $list[$k]['goods'] = $GLOBALS['db']->getRow("select id,name_1,goods_short_name,is_group_fail,promote_end_time,small_img,type_id ,shop_price,market_price,score_goods from ".DB_PREFIX."goods where id='{$v['goods_id']}'");
			$goods = $list[$k]['goods'] = $GLOBALS['db']->getRow($sql);
			$goods['url'] = a_u("Goods/show","id-".$goods['id']);
			 
			
			//$list[$k]['order_status_format'] = $GLOBALS['Ln']['ORDER_STATUS_1'];
			$list[$k]['goods_status_format'] = $GLOBALS['Ln']["ORDER_GOODS_STATUS_".$v['goods_status']];
			if(intval($list[$k]['offline']) == 1)
				$list[$k]['money_status_format'] = $GLOBALS['Ln']["EARNEST_MONEY_STATUS_".$v['money_status']];
			else
				$list[$k]['money_status_format'] = $GLOBALS['Ln']["ORDER_MONEY_STATUS_".$v['money_status']];

			//$list[$k]['order_status_format'] = $list[$k]['money_status_format'];	
			if($goods)
			{				
				//,og.attr,og.data_name as goods_name,og.number, //og.is_inquiry,og.rec_id as goods_id
				$list[$k]['attr'] = $goods['attr'];
				$list[$k]['goods_name'] = $goods['goods_name'];
				$list[$k]['number'] = $goods['number'];
				$list[$k]['goods_id'] = $goods['goods_id'];
				$list[$k]['is_inquiry'] = $goods['is_inquiry'];
				
				
				$list[$k]['stock_is_over'] = 0;	//1：已经卖光了；0：未卖光
				if ($goods['stock'] > 0){
					if ($goods['buy_count'] >= $goods['stock']) {
						$list[$k]['stock_is_over'] = 1; //团购结束，团购商品已经卖光了
					}elseif ($goods['buy_count'] + $v['number']  > $goods['stock']) {
						$list[$k]['stock_is_over'] = 1; //购买数量大于商品数量
					}
				}

				if(intval($goods['type_id']) == 0)
					$list[$k]['goods_status_format'] = $GLOBALS['Ln']["ORDER_GOODS_STATUS_5"];
									
					//0:普通商品;1:积分商品;2:抽奖商品; add by chenfq 2011-01-05
					$list[$k]['is_lottery'] = 0;
					if ($goods['score_goods'] == 2){
						$list[$k]['is_lottery'] = 1;
						$list[$k]['goods_id'] = $goods['id'];
						$list[$k]['money_status_format'] = '';
						$list[$k]['goods_status_format'] = '';
					}
					if($goods['promote_end_time']< a_gmtTime())
					{
						$list[$k]['stock_is_over'] = 1;
					}				
				}
				else
					$list[$k]['is_clear'] = 1;
			
			$list[$k]['goods'] = $goods;
			

			
			if(intval($list[$k]['money_status']) == 0)
				$list[$k]['is_clear'] = 1;
			
			$list[$k]['total_price_format'] = a_formatPrice($v['order_total_price']);
			
//			$list[$k]['orderConsignment'] =  D("OrderConsignment")->where("order_id = ".$v['id'])->findAll();
			$sql = "select a.*,b.name as express_name, b.code as express_code, c.express_id as d_express_id from ".DB_PREFIX."order_consignment a left outer join  ".DB_PREFIX."express b on b.id = a.express_id left outer join  ".DB_PREFIX."delivery c on c.id = a.delivery_id where order_id=".$v['id'];
			
			$orderConsignment = $GLOBALS['db']->getAll($sql);
			
			foreach($orderConsignment as $dk=>$dv){
				if (intval($dv['express_id']) == 0){
					$orderConsignment[$dk]['express_name'] = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."express where id =".intval($dv['d_express_id']));
					$orderConsignment[$dk]['express_code'] = $GLOBALS['db']->getOne("select code from ".DB_PREFIX."express where id =".intval($dv['d_express_id']));
				}
				/*
				$AppKey = a_fanweC('KUAIDI_APP_KEY');
				if (!empty($AppKey)){
					$url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$orderConsignment[$dk]['express_code'].'&nu='.$orderConsignment[$dk]['delivery_code'].'&show=2&muti=1&order=asc';
				}*/
				$url2 = "http://www.kuaidi100.com/chaxun?com=".$orderConsignment[$dk]['express_code']."&nu=".$orderConsignment[$dk]['delivery_code'];
				$url = $url2;
				$orderConsignment[$dk]['express_url'] = $url;
				$orderConsignment[$dk]['express_url2'] = $url2;				
			}
			
			$list[$k]['orderConsignment'] = $orderConsignment;
		}
	
		$result['list'] = $list;
		
		
		return $result;
	}
	
	
	function getMoneyLogList($user_id,$page)
	{
		$limit = (($page-1)*a_fanweC("PAGE_LISTROWS")).",".(a_fanweC("PAGE_LISTROWS"));
		$sql = "select *,'money' as log_type from ".DB_PREFIX."user_money_log where user_id= ".$user_id.
				" order by create_time desc limit ".$limit;
		
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{
			if($v['log_type'] == 'money')
			{
				$list[$k]['value'] = a_formatPrice(abs($v['money']));
			}
			if($v['log_type'] == 'score')
			{
				$list[$k]['value'] = $v['score'].$GLOBALS['Ln']['SCORE_UNIT'];
			}
			if($v['money']>=0)
			{
				$list[$k]['op_type'] = $GLOBALS['Ln']['OP_TYPE_0'];
			}
			else
			{
				$list[$k]['op_type'] = $GLOBALS['Ln']['OP_TYPE_1'];
			}
			$list[$k]['create_time_format'] = a_toDate($v['create_time']);

		}

		$result['list'] = $list;
		$sql_total_money = "select count(*) as total from ".DB_PREFIX."user_money_log where user_id=".$user_id;
		
	
		$total = $GLOBALS['db']->getOne($sql_total_money);
		
		$result['total'] = intval($total);
		return $result;
	}
	
	
	function getScoreLogList($user_id,$page)
	{
		$limit = (($page-1)*a_fanweC("PAGE_LISTROWS")).",".(a_fanweC("PAGE_LISTROWS"));
		$sql = "select *,'score' as log_type from ".DB_PREFIX."user_score_log where user_id= ".$user_id.
				" order by create_time desc limit ".$limit;
		
//		$list = D()->query($sql);
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{
			if($v['log_type'] == 'money')
			{
				$list[$k]['value'] = a_formatPrice(abs($v['money']));
			}
			if($v['log_type'] == 'score')
			{
				$list[$k]['value'] = $v['score']." ".$GLOBALS['Ln']['SCORE_UNIT'];
			}
			if($v['money']>=0)
			{
				$list[$k]['op_type'] = $GLOBALS['Ln']['OP_TYPE_0'];
			}
			else
			{
				$list[$k]['op_type'] = $GLOBALS['Ln']['OP_TYPE_1'];
			}
			$list[$k]['create_time_format'] = a_toDate($v['create_time']);
		}

		$result['list'] = $list;
		$sql_total_score = "select count(*) as total from ".DB_PREFIX."user_score_log where user_id=".$user_id;
		
		$total = $GLOBALS['db']->getOne($sql_total_score);
		
		$result['total'] = intval($total);
		return $result;
	}
	
	function getInchargeList($user_id=0,$page=1)
	{

		$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".a_fanweC("PAGE_LISTROWS");
		$list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_incharge where user_id=".$user_id." order by create_time desc limit ".$limit);
		foreach($list as $k=>$v)
		{
			$list[$k]['money_format'] = a_formatPrice($v['money']);
			$list[$k]['payment_fee_format'] = a_formatPrice($v['payment_fee']);
			$list[$k]['payment_money_format'] = a_formatPrice($v['payment_money']);
			$list[$k]['create_time_format'] = a_toDate($v['create_time']);
			$list[$k]['update_time_format'] = a_toDate($v['update_time']);
			$list[$k]['status_format'] = $GLOBALS['Ln']['USER_MONEY_'.$v['status']];
		}
		
		$result['list'] = $list;
		$result['total'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_incharge where user_id=".$user_id);
		return $result;
	}
	
	function getUnchargeList($user_id=0,$page=1)
	{
		$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".a_fanweC("PAGE_LISTROWS");
		$list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_uncharge where user_id=".$user_id." order by create_time desc limit ".$limit);
		foreach($list as $k=>$v)
		{
			$list[$k]['memo_format'] = nl2br($v['memo']);
			$list[$k]['money_format'] = a_formatPrice($v['money']);			
			$list[$k]['create_time_format'] = a_toDate($v['create_time']);
			$list[$k]['update_time_format'] = a_toDate($v['update_time']);
		}
		
		$result['list'] = $list;
		$result['total'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_uncharge where user_id=".$user_id);
		return $result;
	}
	
	
	function getUserEcvList($is_use = -1,$page=1)
	{
		$where = " e.user_id = ".$_SESSION['user_id'];
		if($is_use == 1)
			$where .= " and e.use_date_time = 0";
		elseif($is_use == 2)
			$where .= " and e.use_date_time > 0";
			
		$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".a_fanweC("PAGE_LISTROWS");
		
		$sql = "select e.use_count,e.status as estatus,e.type,e.id,e.sn,e.password,e.use_date_time,uu.user_name as use_user_name,et.name,et.use_start_date,et.use_end_date,g.name_1 as goods_name,et.money,et.status from ".DB_PREFIX."ecv as e left join ".DB_PREFIX."ecv_type  as et on et.id = e.ecv_type left join ".DB_PREFIX."user as uu on uu.id = e.use_user_id left join ".DB_PREFIX."goods as g on g.id = e.goods_id where".$where." group by e.id order by e.id desc LIMIT $limit";

		$list =$GLOBALS['db']->getAll($sql);
//		$list = M()->query($sql);
		
		foreach($list as $k=>$v)
		{
			
			$list[$k]['money_format'] = a_formatPrice(floatval($v['money']));
			$list[$k]['use_date_time_format'] = a_toDate($v['use_date_time'],'Y-m-d H:i');
			$list[$k]['use_end_date_format'] = a_toDate($v['use_end_date'],'Y-m-d H:i');
			$list[$k]['use_start_date_format'] = a_toDate($v['use_start_date'],'Y-m-d H:i');
		}
		$result['list'] = $list;
		
		$sql = "select count(*) as c from ".DB_PREFIX."ecv as e where".$where;
		
		$count = $GLOBALS['db']->getOne($sql);
		
		$result['total'] = intval($count);
		
		return $result;
	}	
?>