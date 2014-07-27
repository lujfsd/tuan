<?php
	$do = isset ( $_REQUEST ['do'] ) ? $_REQUEST ['do'] : "";
	
	if($do==='coupon_check')
	{
		header("Content-Type:text/html; charset=utf-8");
		$time = a_gmtTime();
		
		$sn = trim($_REQUEST['sn']);
		
		$end_time = $GLOBALS['db']->getOne("select end_time from ".DB_PREFIX."group_bond where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and  sn = '".addslashes($sn)."'");
		
		if($end_time >0)
		{
			echo a_toDate($end_time);
		}
		else
			echo 0;

		exit();
	}
	
	if($do==='coupon_bus')
	{
		header("Content-Type:text/html; charset=utf-8");
		
		$time = a_gmtTime();
		$sn = trim($_REQUEST['sn']);
		$pwd = trim($_REQUEST['pwd']);
		
		//$sql = "update ".DB_PREFIX."group_bond set use_time = ".$time ." where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
		$sql = "update ".DB_PREFIX."group_bond set use_time = ".$time ." where sn = '".addslashes($sn)."' and is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." ";

		$GLOBALS['db']->query($sql);
		$is_updated = $GLOBALS['db']->affected_rows();
		
		if($is_updated >0)
		{
			echo 1;
		}
		else
			echo 0;
		
		exit();
	}
	
	if($do==='coupon_check2')
	{
		header("Content-Type:text/html; charset=utf-8");
		$result = array("type"=>0,"msg"=>"");
		$time = a_gmtTime();
		
		$sn = trim($_REQUEST['sn']);
		
		$group_bond = $GLOBALS['db']->getAll("select goods_name, end_time from ".DB_PREFIX."group_bond where is_valid = 1 and status = 1 and use_time = 0 and sn = '".addslashes($sn)."'");
		//var_dump($group_bond[0]['end_time']);exit;
		if($group_bond)
		{
			$result['msg'] = '';
                        if( $group_bond[0][end_time] < $time )
                        { 
                       foreach($group_bond as $kk=>$vv)
		       {	
				$result['msg'] = $result['msg']."\n\n".a_L('JS_GOODS_T').":".$vv['goods_name']."\n".a_L('JS_GROUP_BOND_007').':'.a_toDate($vv['end_time']);							
		       }
                                $result['type'] = 2;
                        }
                        else{
			foreach($group_bond as $kk=>$vv)
		       {	
				$result['msg'] = $result['msg']."\n\n".a_L('JS_GOODS_T').":".$vv['goods_name']."\n".a_L('JS_GROUP_BOND_007').':'.a_toDate($vv['end_time']);							
		       }			
			$result['type'] = 1;
                        }
                        }
                  else{
			$result['type'] = 0;
                      }
		echo json_encode($result);
		exit();
	}	
	
	if($do==='coupon_bus2')
	{
	//判断商户是否登陆
		header("Content-Type:text/html; charset=utf-8");
		
		$result = array("type"=>0,"msg"=>"");
		$time = a_gmtTime();
		$sn = trim($_REQUEST['sn']);
		$pwd = trim($_REQUEST['pwd']);
		
		//$sql = "update ".DB_PREFIX."group_bond set use_time = ".$time ." ,is_balance=1 where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
		//$sql = "select goods_name from ".DB_PREFIX."group_bond where is_valid = 1 and status = 1 and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
		$sql = "update ".DB_PREFIX."group_bond set use_time = ".$time ." ,is_balance=1 where sn = '".addslashes($sn)."' and is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." ";
		$GLOBALS['db']->query($sql);
		$is_updated = $GLOBALS['db']->affected_rows();
		
		if($is_updated >0)
		{
			//$bond_id= $GLOBALS['db']->getOne("select id from ".DB_PREFIX."group_bond where password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'");
			$bond_id= $GLOBALS['db']->getOne("select id from ".DB_PREFIX."group_bond where sn = '".addslashes($sn)."'");

			require ROOT_PATH.'app/source/func/com_send_sms_func.php';
			s_send_groupbond_use_sms($bond_id,true);
			$result['msg'] = $GLOBALS['db']->getOne($sql);
			$result['type'] = 1;
		}
		else{
			$result['type'] = 0;
		}
			
		echo json_encode($result);
		exit();
	}	
	if(!$tpl->is_cached("Page/coupon.moban",md5("coupon_index"))){
		$title = sprintf($GLOBALS ['lang'] ['GROUP_BOND_CHECK_BUG'],a_fanweC('GROUPBOTH'));
		//输出当前页seo内容
		$data = array ('navs' => array (array ('name' => $title , 'url' => a_u ( "Coupon/index" ) ) ), 'keyword' => '', 'content' => '' );
		assignSeo ( $data );
		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	}
	$tpl->display ( "Page/coupon.moban",md5("coupon_index"));
?>
