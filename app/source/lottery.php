<?php

	if(intval($_SESSION['user_id'])==0)
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
    };
    	
	$ma= $_REQUEST ['m']."_".$_REQUEST ['a'];
	$ma();
	
	function Lottery_step1()
	{
		$goods_id = intval($_REQUEST['id']);
		
		//$cache_id = C_CITY_ID."_lottery_step1#".$goods_id;
		//if(!$GLOBALS['tpl']->is_cached("Inc/lottery/step1.moban",$cache_id)){
/**/
	    	//判断用户是否已经购买
			$sql = "select sum(og.number) as num from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."order as o on og.order_id = o.id where og.rec_id = ".$goods_id." and o.user_id=".intval($_SESSION['user_id']);
			$num = $GLOBALS['db']->getOne($sql);
	    	if ($num > 0){
	    		redirect2(__ROOT__."/index.php?m=Lottery&a=view&id=".$goods_id);
	    		exit;
	    	}
	    	
	    	$sql = "select id, name_1 from ".DB_PREFIX."goods where id='".$goods_id."'"; 
			$goods = $GLOBALS['db']->getRowCached($sql);
	    	$GLOBALS['tpl']->assign("goods",$goods);
			

			//输出当前页seo内容
		    $data = array(
		    	'navs' => array(
		    		array(
						'name'=>a_L("HC_LOTTERY"),
						'url' =>''
					)
		    	),
		    );
			assignSeo ( $data );
			
			$sql = "select mobile_phone from ".DB_PREFIX."sms_subscribe where status = 1 and goods_id > 0 and user_id=".intval($_SESSION['user_id']);
			$mobile_phone = $GLOBALS['db']->getOne($sql);			
			$GLOBALS ['tpl']->assign ( "mobile_phone", $mobile_phone);
			
			//输出主菜单
			$GLOBALS ['tpl']->assign ( "main_navs", assignNav (2));
			//输出城市
			$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList());
			//输出帮助
			$GLOBALS ['tpl']->assign ( "help_center", assignHelp());
		//}
		$GLOBALS['tpl']->display ("Inc/lottery/step1.moban");
	}
	function Lottery_step2(){
    	$goods_id = intval($_REQUEST['id']);
    	$sql = "select id, name_1 from ".DB_PREFIX."goods where id='".$goods_id."'"; 
		$goods = $GLOBALS['db']->getRowCached($sql);
    	$GLOBALS['tpl']->assign("goods",$goods);
    			
		//输出当前页seo内容
		 $data = array(
		    	'navs' => array(
		    		array(
						'name'=>a_L("HC_LOTTERY"),
						'url' =>''
					)
		    	),
		);
		assignSeo ( $data );
		
		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav (2));
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList());
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp());
					
		$GLOBALS['tpl']->display ("Inc/lottery/step2.moban");
	}
	
	function Lottery_step3()
	{
			
    	$user_id = intval($_SESSION['user_id']);
    	$goods_id = intval($_REQUEST['id']);
    	//$sql = "select id, name_1,small_img from ".DB_PREFIX."goods where id='".$goods_id."'"; 
		$goods = getGoodsData($goods_id);//$GLOBALS['db']->getRowCached($sql);
    	$GLOBALS['tpl']->assign("goods",$goods);
    	if($goods_id)
		{
			if(a_fanweC ( "URL_ROUTE" )==1)
				$referrals_text = a_fanweC("SHOP_URL")."/tg-".$goods_id."-ru-".$user_id.".html";
			else
				$referrals_text = a_fanweC("SHOP_URL")."/index.php?m=goods&a=show&id=".$goods_id."&ru=".$user_id;
		}
		else
    		$referrals_text = a_fanweC("SHOP_URL")."/index.php?ru=".$user_id;
    		
    	$GLOBALS['tpl']->assign("referrals_text",$referrals_text);
		
		//输出当前页seo内容
		 $data = array(
		    	'navs' => array(
		    		array(
						'name'=>a_L("HC_LOTTERY"),
						'url' =>''
					)
		    	),
		);
		assignSeo ( $data );
		$GLOBALS ['tpl']->assign ( 'is_referrals_page', 1 );
		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav (2));
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList());
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp());
					
		$GLOBALS['tpl']->display ("Inc/lottery/step3.moban");
	}
	
	function Lottery_view()
	{
    	$user_id = intval($_SESSION['user_id']);
    	$goods_id = intval($_REQUEST['id']);
    	$sql = "select id, name_1,expand2 from ".DB_PREFIX."goods where id='".$goods_id."'"; 
		$goods = $GLOBALS['db']->getRow($sql);
    	$GLOBALS['tpl']->assign("goods",$goods);
    	    	
    	
    	$sql = "select a.*, b.user_name from ".DB_PREFIX."lottery_no as a left outer join ".DB_PREFIX."user b on b.id = a.invite_user_id where a.goods_id='".$goods_id."' and a.user_id ='".$user_id."'"; 
		$lottery_no_list = $GLOBALS['db']->getAll($sql);
		
		//成功邀请会员数
		$invite_num = count($lottery_no_list) -1;
		if ($invite_num < 0){
			$invite_num = 0;
		}
		
		
		//头部内容
		$GLOBALS['tpl']->assign("title", $goods['expand2']);
		
		$GLOBALS['tpl']->assign("invite_num", $invite_num);
		
		//抽奖号数量
		$GLOBALS['tpl']->assign("lottery_num", count($lottery_no_list));

		//抽奖号列表
		$GLOBALS['tpl']->assign("lottery_no_list", $lottery_no_list);
		
		
			//输出当前页seo内容
		 $data = array(
		    	'navs' => array(
		    		array(
						'name'=>a_L("HC_LOTTERY"),
						'url' =>''
					)
		    	),
		);
		assignSeo ( $data );
		
		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav (2));
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList());
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp());
					
		$GLOBALS['tpl']->display ("Inc/lottery/lview.moban");	
	}
?>