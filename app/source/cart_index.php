<?php

	//初始化购物车
	$max_time = a_fanweC('CART_MAX_TIME');  //购物车保留的最长时效
	$now = a_gmtTime();	
	$session_id = session_id();
	
	if($_REQUEST['act'] == 'del_cart')
	{
			
			$id = intval($_REQUEST['id']);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."cart where id = ".$id);
		    $GLOBALS['tpl']->assign("ROOT_PATH",__ROOT__);
		    
		    //$cart_list 数据
		    $cart_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."cart where session_id='".$session_id."'");
		    if(!$cart_list)
		    {
		    	if(a_fanweC("URL_ROUTE")==0)
	    		{
	    			$cart_login_url = __ROOT__."/index.php";
	    		}
	    		else
	    		{
	    			$cart_login_url = __ROOT__."/";
	    		}
	    		$res['status'] = 2;
	    		$res['info'] = $cart_login_url;
	    		header("Content-Type:text/html; charset=utf-8");
	    		echo json_encode($res);exit;
		    }
		    $total_price = 0;
		    foreach($cart_list as $kk=>$vv)
		    {
		    	$goods_id = $vv['rec_id'];
				$cart_goods_info =getGoodsItem($goods_id);
				$cart_list[$kk]['goods_info'] = $cart_goods_info;
				$cart_list[$kk]['attr_ids'] = explode(",",$vv['attr_ids']);
				$total_price += $vv['data_total_price'];								
		    }
		 
		    $GLOBALS['tpl']->assign("cart_list",$cart_list);
		    $GLOBALS['tpl']->assign("total_price",$total_price);
		    $GLOBALS['tpl']->assign("is_cart_ajax",1);
		    
		    $result = $GLOBALS['tpl']->fetch('Inc/cart/goods_cart_list.moban');
		    $res['status'] = 1;
	    	$res['html'] = dotran($result);
	    	header("Content-Type:text/html; charset=utf-8");
	    	echo json_encode($res);exit;

	}
	elseif($_REQUEST['act'] == 'ajax_count')
	{
	    	$cart_id = intval($_REQUEST['id']);
	    	$cart_item = $GLOBALS['db']->getRow("select id,rec_id from ".DB_PREFIX."cart where session_id='".$session_id."' and id=".$cart_id);
	    	
			 if(!$cart_item)
		    {
		    	if(a_fanweC("URL_ROUTE")==0)
	    		{
	    			$cart_login_url = __ROOT__."/index.php";
	    		}
	    		else
	    		{
	    			$cart_login_url = __ROOT__."/";
	    		}
	    		$res['status'] = 2;
	    		$res['info'] = $cart_login_url;
	    		header("Content-Type:text/html; charset=utf-8");
	    		echo json_encode($res);exit;
		    }	    	
	    	$res['status'] = 1; //0失败 1成功 2跳转
	    	$res['info'] = '';
	    	$goods_id = intval($cart_item['rec_id']);
	    	if(intval($_SESSION['user_id'])==0)
	    	{
	    		if(a_fanweC("URL_ROUTE")==0)
	    		{
	    			$cart_login_url = __ROOT__."/index.php?m=Cart&a=cartLogin&id=".$goods_id;
	    		}
	    		else
	    		{
	    			$cart_login_url = __ROOT__."/Cart-cartLogin-id-".$goods_id.".html";
	    		}
	    		$res['status'] = 2;
	    		$res['info'] = $cart_login_url;
	    		header("Content-Type:text/html; charset=utf-8");
	    		echo json_encode($res);exit;
	    	}
	    	


		    //开始处理添加到购物车的动作    	
			//以下对购物车进行检测
		    	
		   		$goods_info = getGoodsItem($goods_id);
	
		   		if(!$goods_info || ($goods_info['promote_begin_time'] > $now && $goods_info['type_id'] != 2))
		   		{   			
		   			$res['status'] = 2;
		    		$res['info'] = a_fanweC("SHOP_URL");
		    		header("Content-Type:text/html; charset=utf-8");
		    		echo json_encode($res);exit;
		   		}
		   		
		   		
				$number = intval($_REQUEST['quantity'])==0?1:intval($_REQUEST['quantity']);  //购买数量
				//开始取首个属性为默认添加到购物车的属性
				$goods_attr = $_REQUEST['goods_attr'];
	
				if($goods_attr)
				{
					
				}
				elseif($goods_info['attrlist'])
				{
					$attr_list = $goods_info['attrlist'];
					$goods_attr = array();
					foreach($attr_list as $k=>$v)
					{
						$goods_attr[] = $v['attr_value'][0]['id'];
					}
				}
				else
				{
					$goods_attr = array();
				}
				
				$attr_ids = implode(",",$goods_attr);
				//$goods_attr = $_REQUEST['goods_attr'];
				//$goods_attr = '';
				
				$attrStr = "";
				/*
	    		if(intval($_SESSION['user_id']) > 0)
			   {
					$sql = "select sum(og.number) as num from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."order as o on og.order_id = o.id where og.rec_id = ".intval($goods_id)." and o.user_id=".intval($_SESSION['user_id']);
					$num = $GLOBALS['db']->getOne($sql);
					$goods_info['userBuyCount'] = intval($num);
				}*/
				
		    	if($goods_info['promote_begin_time'] > $now && $goods_info['type_id'] != 2) 
		   		{
		   			$res['status'] = 0;
		   			$res['info'] = $GLOBALS['Ln']['HC_GROUPON_NOT_BEGIN'];
		   			header("Content-Type:text/html; charset=utf-8");
		   			echo json_encode($res);exit;
		   		}elseif($number < 1) 
		   		{	
		   			$res['status'] = 0;
		   			$res['info'] = $GLOBALS['Ln']['HC_BUYCOUNT_LESS_ONE'];
		   			header("Content-Type:text/html; charset=utf-8");
		   			echo json_encode($res);exit;
		   		}
		   		
		    	$bln = false;
				$err = "";
				//在购买车中的数据  add by chenfq 2011-03-01
				$cart_num = $GLOBALS['db']->getOne("select sum(number) from ".DB_PREFIX."cart where session_id='".$session_id."' and rec_id =".$goods_id." and id <>".$cart_id);
				
				$userBuyCount = intval($goods_info['userBuyCount']) + intval($cart_num); //用户已经购买数量				
				$maxBought    = intval($goods_info['max_bought']); //用户最大购买数量
				$surplusCount = intval($goods_info['surplusCount']);//剩余库存数
				$goodsStock   = intval($goods_info['stock']); //库存数量
					
				if($number + $userBuyCount > $maxBought && $maxBought > 0)
				{
					$number = $maxBought - $userBuyCount;
					$bln = true;
				}
					
				if($number + intval($cart_num)> $surplusCount && $goodsStock > 0)//$number + intval($cart_num) add by chenfq 2011-06-07 购物车中的商品，也要添加上去
				{
					$number = $surplusCount;
					$bln = true;
				}
			
				if($bln)
				{
					if($maxBought > 0)
						$err.=sprintf($GLOBALS['Ln']['HC_USER_MAX_BUYCOUNT'],$maxBought);				
						
					if($goodsStock > 0)
					
						$err.=sprintf($GLOBALS['Ln']['HC_ONLY_LESS_COUNT'],$surplusCount).(($err == "") ? $GLOBALS['Ln']['HC_GOODS'] : "")."，";
		
					$err.= sprintf($GLOBALS['Ln']['HC_HASBUYCOUNT_LESSCOUNT'],$userBuyCount,$number);
					
					$res['status'] = 0;
		   			$res['info'] = $err;
		   			header("Content-Type:text/html; charset=utf-8");
					echo json_encode($res);exit;
				}
				
				
			
				if($goods_info['type_id'] == 2)
					$unit_price = floatval($goods_info['earnest_money']);
				else
		   			$unit_price = floatval($goods_info['shop_price']);
				
				if(is_array($goods_attr))
				{
					foreach($goods_attr as $attr)
					{
						$sql ="select ga.attr_value_1 as attr_value,ga.price,gta.name_1 as name from ".DB_PREFIX."goods_attr as ga left join ".DB_PREFIX."goods_type_attr as gta on gta.id = ga.attr_id where ga.id = ".intval($attr)." and ga.goods_id = ".$goods_id;
						
						$attrItem = $GLOBALS['db']->getRow($sql);
										
						$unit_price += floatval($attrItem['price']);
						
						if(empty($attrStr))
							$attrStr.=$attrItem['name']."：".$attrItem['attr_value'];
						else
							$attrStr.= "\n".$attrItem['name']."：".$attrItem['attr_value'];
					}
				}
				
				//add by chenfq 2011-06-12 添加判断商品属性有没有被选择
				if ($goods_info['attrlist'] && (empty($attr_ids)||empty($attrStr))){
					$res['status'] = 0;
		   			$res['info'] = a_L('PLS_SELECT_ATTR');// '请选择商品属性后,再试一下.';
		   			header("Content-Type:text/html; charset=utf-8");
					echo json_encode($res);exit;					
				}

				$goodsname = $goods_info['name_1'];
				if (!empty($goods_info['goods_short_name'])){
					$goodsname = $goods_info['goods_short_name'];
				}
						
				$sql_upd = "update ".DB_PREFIX."cart set pid =0,rec_id=".$goods_id.",rec_module='PromoteGoods'".
						   ",session_id='".$session_id."'".
						   ",user_id='".intval($_SESSION['user_id'])."'".
						   ",number='".$number."'".
						   ",data_unit_price='".floatval($unit_price)."'".
						   ",data_score='".$goods_info['score']."'".
						   ",data_total_score='".(intval($goods_info['score'])*$number)."'".
				 		   ",data_total_referral_money='".(intval($goods_info['referral_money'])*$number)."'". //add by chenfq 2011-03-05
						   ",data_total_price='".($unit_price*$number)."'".
						   ",create_time='".$now."'".
						   ",update_time='".$now."'".
						   ",data_name='".addslashes($goodsname)."'".
						   ",data_sn='".$goods_info['sn']."'".
						   ",data_weight='".$goods_info['weight']."'".
						   ",data_total_weight='".(floatval($goods_info['weight'])*$number)."'".
						   ",is_inquiry='".$goods_info['is_inquiry']."'".
						   ",goods_type='".$goods_info['type_id']."'".
						   ",attr='".$attrStr."'".
						   ",attr_ids='".$attr_ids.
						   "' where id = ".$cart_id; 
				$GLOBALS['db']->query($sql_upd);

		    //加入购物车动作结束
		    
		    
		    //$cart_list 数据
		    $cart_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."cart where session_id='".$session_id."'");
		    $total_price = 0;
		    foreach($cart_list as $kk=>$vv)
		    {
		    	$goods_id = $vv['rec_id'];
				$cart_goods_info = getGoodsItem($goods_id);
				$cart_list[$kk]['goods_info'] = $cart_goods_info;
				$cart_list[$kk]['attr_ids'] = explode(",",$vv['attr_ids']);
				$total_price += $vv['data_total_price'];								
		    }
		 
		    $GLOBALS['tpl']->assign("cart_list",$cart_list);
		    $GLOBALS['tpl']->assign("total_price",$total_price);
		    $GLOBALS['tpl']->assign("is_cart_ajax",1);
		     
		    $result = $GLOBALS['tpl']->fetch('Inc/cart/goods_cart_list.moban');
		    $res['status'] = 1;
		   	$res['info'] = '';
		   	$res['html'] = dotran($result);
		   	header("Content-Type:text/html; charset=utf-8");
			echo json_encode($res);exit;
	}elseif(intval($_REQUEST['id']) > 0)
	{
		//添加一个团购商品到购买车
		$close_cart = intval(a_fanweC('CLOSE_CART'));  //关闭购买车功能(关闭后，一次只能购买一个商品)
		if ($close_cart == 1){
			$GLOBALS['db']->query("delete from ".DB_PREFIX."cart where session_id='".$session_id."'");
		}else{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."cart where ".$now."-update_time>".$max_time);
		}		
	    	$goods_id = intval($_REQUEST['id']);
	    	//echo $goods_id; exit;
	    	if(intval($_SESSION['user_id'])==0)
	    	{
	    		if(a_fanweC("URL_ROUTE")==0)
	    		{
					if(!empty($_REQUEST['act']))
	    				$cart_login_url = __ROOT__."/index.php?m=Cart&a=cartLogin&id=".$goods_id;
					else
						$cart_login_url = __ROOT__."/index.php?m=Cart&a=cartLogin&id=".$goods_id."&act=".$_REQUEST['act'];
	    		}
	    		else
	    		{
					if(!empty($_REQUEST['act']))
						$cart_login_url = a_getDomain().__ROOT__."/Cart-cartLogin-id-".$goods_id."-act-".$_REQUEST['act'].".html";
					else
	    				$cart_login_url = a_getDomain().__ROOT__."/Cart-cartLogin-id-".$goods_id.".html";
	    		}
	    		redirect2($cart_login_url);
	    	}
	    	
	    	
			$err = base64_decode(base64_decode($_REQUEST['err']));
		    	
		    if($err=='')
		    {
		    //开始处理添加到购物车的动作    	
			//以下对购物车进行检测
				
				//没有商品时
		    	if($goods_id == 0)
		    	{
		    		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."cart where session_id = '".session_id()."'")==0)
		    		{
			    		$GLOBALS['tpl']->assign("other_goods_list",getTodayGoodsList(0,0));
		    			$GLOBALS['tpl']->assign("cart_empty",a_L("CART_EMPTY"));
		    			$GLOBALS['tpl']->display("Page/cart_index.moban");
		   				exit;
		    		}
		    	}
		    	
				$goods_info = getGoodsItem($goods_id);
		   		if(!$goods_info || ($goods_info['promote_begin_time'] > $now && $goods_info['promote_end_time'] < $now && $goods_info['type_id'] != 2))
		   		{   			
		   			redirect2(a_fanweC("SHOP_URL"));
		   			exit;
		   		}
		   		
				$rec_id = $goods_id;  //购买的ID
		   		$rec_module = "PromoteGoods";  //购买的模块
				$number = intval($_REQUEST['quantity'])==0?1:intval($_REQUEST['quantity']);  //购买数量
				//开始取首个属性为默认添加到购物车的属性
				$goods_attr = $_REQUEST['goods_attr'];
	
				if($goods_attr)
				{
					
				}
				elseif($goods_info['attrlist'])
				{
					$attr_list = $goods_info['attrlist'];
					$goods_attr = array();
					foreach($attr_list as $k=>$v)
					{
						$goods_attr[] = $v['attr_value'][0]['id'];
					}
				}
				else
				{
					$goods_attr = '';
				}
				if($goods_attr!='')
				$attr_ids = implode(",",$goods_attr);
				//$goods_attr = $_REQUEST['goods_attr'];
				//$goods_attr = '';
				
				$attrStr = "";
				$_REQUEST['id'] = $rec_id;
				
		    	if($goods_info['promote_begin_time'] > $now && $goods_info['type_id'] != 2) 
		   		{
		   			$_SESSION['error'] = $GLOBALS['Ln']['HC_GROUPON_NOT_BEGIN']; 
		   			redirect2(__ROOT__."/index.php?m=Cart&a=index&id=".$goods_id."&err=". base64_encode(base64_encode($GLOBALS['Ln']['HC_GROUPON_NOT_BEGIN'])));
					exit;
		   		}
		   		
		    	if(($goods_info['type_id'] == 2 && $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."cart where session_id='".$session_id."' and goods_type <> 2") > 0) 
		    	||($goods_info['type_id'] != 2 && $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."cart where session_id='".$session_id."' and goods_type = 2") > 0))
		   		{		   		
		   			$_SESSION['error'] = a_L("GROUPON_TYPE_ID_ERROR");
		   			redirect2(__ROOT__."/index.php?m=Cart&a=index&err=". base64_encode(base64_encode(a_L("GROUPON_TYPE_ID_ERROR"))));
					exit;
		   		}
		   		
		   		if($goods_info['score_goods'] ==1 && $GLOBALS['db']->getOne("select score from ".DB_PREFIX."user where id =".intval($_SESSION['user_id']))< abs($goods_info['score'])) 
		   		{	
		   			$_SESSION['error'] = a_L("NOT_ENOUGH_SCORE");
					redirect2(__ROOT__."/index.php?m=Cart&a=index&err=".base64_encode(base64_encode($GLOBALS['Ln']['NOT_ENOUGH_SCORE'])));
					exit;
		   		}
		   		
		    	//modify by chenfq 2010-12-21 0:普通商品;1:积分商品;2:抽奖商品;
		    	if($goods_info['score_goods'] ==2 && $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."cart where session_id='".$session_id."'") > 0) 
		   		{	
		   			$_SESSION['error'] = a_L("CHECKING_AND_BUY_LOTTERY");
					redirect2(__ROOT__."/index.php?m=Cart&a=index&err=".base64_encode(base64_encode(a_L('CHECKING_AND_BUY_LOTTERY'))));
					exit;
		   		}		

		   		if($goods_info['score_goods'] ==2){
		   			redirect2(__ROOT__."/index.php?m=Lottery&a=step1&id=".$goods_info['id']);
		   		}		   		
		   		
		    	if($number < 1)
		   		{	
		   			$_SESSION['error'] = a_L("HC_BUYCOUNT_LESS_ONE");
					redirect2(__ROOT__."/index.php?m=Cart&a=index&err=".base64_encode(base64_encode($GLOBALS['Ln']['HC_BUYCOUNT_LESS_ONE'])));
					exit;
		   		}
		   		
		    	$bln = false;
				$err = "";
				
				//在购买车中的数据  add by chenfq 2011-03-01
				$cart_num = $GLOBALS['db']->getOne("select sum(number) from ".DB_PREFIX."cart where session_id='".$session_id."' and rec_id =".$goods_id);
				$cart_num = intval($cart_num);
				$userBuyCount = intval($goods_info['userBuyCount']) + $cart_num;
				$maxBought    = intval($goods_info['max_bought']);
				$surplusCount = intval($goods_info['surplusCount']);
				$goodsStock   = intval($goods_info['stock']);
					
				if($number + $userBuyCount > $maxBought && $maxBought > 0)
				{
					$number = $maxBought - ($userBuyCount - $cart_num);//用户还能购买数量=用户最多购买数量 - 已经购买的数量
					$bln = true;
				}
					
				if($number + $cart_num> $surplusCount && $goodsStock > 0)
				{
					$number = $surplusCount;
					$bln = true;
				}
			
		    	if($bln)
				{
					if($goods_info['score_goods'] ==2){
						redirect2(__ROOT__."/index.php?m=Lottery&a=view&id=".$goods_info['id']);
						exit;
					}					
					if($maxBought > 0)
						$err.=sprintf($GLOBALS['Ln']['HC_USER_MAX_BUYCOUNT'],$maxBought);				
						
					if($goodsStock > 0)
						$err.=sprintf($GLOBALS['Ln']['HC_ONLY_LESS_COUNT'],$surplusCount).(($err == "") ? $GLOBALS['Ln']['HC_GOODS'] : "")."，";
		
					$err.= sprintf($GLOBALS['Ln']['HC_HASBUYCOUNT_LESSCOUNT'],$userBuyCount - $cart_num,$number);
					$_SESSION['error'] = $err;
					redirect2(__ROOT__."/index.php?m=Cart&a=index");
					exit;
				}
				
				
		    	if($goods_info['score_goods'] ==2){
		   			redirect2(__ROOT__."/index.php?m=Lottery&a=step1&id=".$goods_info['id']);
		   		}
				
				
				if($goods_info['type_id'] == 2)
					$unit_price = floatval($goods_info['earnest_money']);
				else
		   			$unit_price = floatval($goods_info['shop_price']);
				
				if(is_array($goods_attr))
				{
					foreach($goods_attr as $attr)
					{
						$sql ="select ga.attr_value_1 as attr_value,ga.price,gta.name_1 as name from ".DB_PREFIX."goods_attr as ga left join ".DB_PREFIX."goods_type_attr as gta on gta.id = ga.attr_id where ga.id = ".intval($attr)." and ga.goods_id = ".$goods_info['id'];
						
						$attrItem = $GLOBALS['db']->getRow($sql);
										
						$unit_price += floatval($attrItem['price']);
						
						if(empty($attrStr))
							$attrStr.=$attrItem['name']."：".$attrItem['attr_value'];
						else
							$attrStr.= "\n".$attrItem['name']."：".$attrItem['attr_value'];
					}
				}
				
		    	//add by chenfq 2011-06-12 添加判断商品属性有没有被选择
				if ($goods_info['attrlist'] && (empty($attr_ids)||empty($attrStr))){
					$_SESSION['error'] = a_L('PLS_SELECT_ATTR');// '请选择商品属性后,再试一下.';
					redirect2(__ROOT__."/index.php?m=Cart&a=index");
					exit;				
				}				
				
				$cart_item = $GLOBALS['db']->getRow("select id from ".DB_PREFIX."cart where session_id='".$session_id."' and rec_id=".$rec_id);
		
				$goodsname = $goods_info['name_1'];
				if (!empty($goods_info['goods_short_name'])){
					$goodsname = $goods_info['goods_short_name'];
				}
				//var_dump($attrStr);
				//var_dump($cart_item['id']);
				if($cart_item['id'] > 0 && empty($attrStr)) //&&$_REQUEST['act']=='ajax_count'
				{
					$sql_upd = "update ".DB_PREFIX."cart set pid =0,rec_id=".$rec_id.",rec_module='".$rec_module."'".
						   ",session_id='".$session_id."'".
						   ",user_id='".intval($_SESSION['user_id'])."'".
						   ",number='".$number."'".
						   ",data_unit_price='".floatval($unit_price)."'".
						   ",data_score='".$goods_info['score']."'".
						   ",data_total_referral_money='".(intval($goods_info['referral_money'])*$number)."'". //add by chenfq 2011-03-05
						   ",data_total_score='".(intval($goods_info['score'])*$number)."'".
						   ",data_total_price='".($unit_price*$number)."'".
						   ",create_time='".$now."'".
						   ",update_time='".$now."'".
						   ",data_name='".addslashes($goodsname)."'".
						   ",data_sn='".$goods_info['sn']."'".
						   ",data_weight='".$goods_info['weight']."'".
						   ",data_total_weight='".(floatval($goods_info['weight'])*$number)."'".
						   ",is_inquiry='".$goods_info['is_inquiry']."'".
						   ",goods_type='".$goods_info['type_id']."'".
						   ",attr='".$attrStr."'".
						   ",attr_ids='".$attr_ids.
						   "' where id = ".$cart_item['id']; 
					$GLOBALS['db']->query($sql_upd);
				}
				elseif($rec_id>0) //intval($cart_item['id']) == 0&&
				{
					$sql_ins = "insert into ".DB_PREFIX."cart (`id`,`pid`,`rec_id`,`rec_module`,`session_id`,`user_id`,`number`,`data_unit_price`,`data_score`,`data_promote_score`,`data_total_score`,`data_total_referral_money`,`data_total_price`,`create_time`,`update_time`,`data_name`,`data_sn`,`data_weight`,`data_total_weight`,`is_inquiry`,`goods_type`,`attr`,`attr_ids`)".
						   " values (0,0,'".$rec_id."','".$rec_module."','".$session_id."','".intval($_SESSION['user_id'])."','".$number."','".floatval($unit_price)."','".$goods_info['score']."',0,'".(intval($goods_info['score'])*$number)."','".(intval($goods_info['referral_money'])*$number)."','".($unit_price*$number)."','".$now."','".$now."','".addslashes($goodsname)."','".$goods_info['sn']."','".$goods_info['weight']."','".(floatval($goods_info['weight'])*$number)."','".$goods_info['is_inquiry']."','".$goods_info['type_id']."','".$attrStr."','".$attr_ids."')";
					//var_dump($sql_ins);
					$GLOBALS['db']->query($sql_ins);
				}
		    }
		    else
		    {
			    $cart_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."cart where session_id='".session_id()."'");
			    if(!$cart_list)
			    {
			    	a_error($err,$err,__ROOT__."/index.php?m=Cart&a=index");
			    }
			    $GLOBALS['tpl']->assign('error',$err);
		    }
		    //加入购物车动作结束
	    	//正常添加的提交处理
	    }

   
		    	$data = array(
		    		'navs' => array(
		    			array('name'=>a_L("CART_LIST"),'url'=>"")
		    		),
		    		'keyword'=>	'',
		    		'content'=>	'',
		    	);
			   
		    	assignSeo($data);
		    	//输出主菜单
				$GLOBALS['tpl']->assign("main_navs",assignNav(2));
				//输出城市
				$GLOBALS['tpl']->assign("city_list",getGroupCityList());
				//输出帮助
				
				$GLOBALS['tpl']->assign("help_center",assignHelp());	    
   			//载入购物车中的列表
	    
			
		    //$cart_list 数据
		    $cart_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."cart where session_id='".$session_id."'");
	    	/*if(!$cart_list)
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
		    }*/
		    $total_price = 0;
		    $goodsidlist = "0,";
		    foreach($cart_list as $kk=>$vv)
		    {
		    	$goods_id = $vv['rec_id'];
				$cart_goods_info = getGoodsItem($goods_id);
				$cart_list[$kk]['goods_info'] = $cart_goods_info;
				$cart_list[$kk]['attr_ids'] = explode(",",$vv['attr_ids']);
				$total_price += $vv['data_total_price'];
				if (empty($vv['attr_ids']))
					$goodsidlist .= $vv['rec_id'].",";			
		    }
		    $GLOBALS['tpl']->assign("other_goods_list",getTodayGoodsList(substr($goodsidlist,0,strlen($goodsidlist)-1),0));
		 
		    $GLOBALS['tpl']->assign("cart_list",$cart_list);
		    $GLOBALS['tpl']->assign("total_price",$total_price);
		    //$result = $GLOBALS['tpl']->fetch('Inc/smarty/cart_list.moban');
		    
		    if($_REQUEST['id']!='')
		    {
		    	if(empty($_REQUEST['act']))
		    		header("Location:".a_getDomain().a_u("Cart/index"));
		    	else
		    		header("Location:".a_getDomain().a_u("Cart/index","act-".$_REQUEST['act']));
		    	exit();
		    }
			
		    $GLOBALS['tpl']->display('Page/cart_index.moban');
		    
?>