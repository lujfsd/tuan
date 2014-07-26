<?php
	if(strtolower($_REQUEST['a'])=='set_sort')
	{
		$sort = trim($_REQUEST['sort']);
		$_COOKIE['fw_coupon_order_field'] = $sort;
		setcookie('fw_coupon_order_field',$sort);	
	}
	if(strtolower($_REQUEST['a'])=='index')
	{
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());		
				
		$navs = array('name'=>a_L("BEST_YOUHUI"),'url'=>a_U("Youhui/index"));
				
		$data = array(
	    			'navs' => array(
		    				$navs,
	    			)	    	
	    );
	    assignSeo($data);    

	    $region_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."coupon_region where city_id = ".intval($currentCity['id'])." and pid=0 order by sort desc");
		
	    $c_region_id = intval($_REQUEST['region_id']);
	    $c_cate_id = intval($_REQUEST['cate_id']);
	    
	    if($c_cate_id==0&&$c_region_id==0)
	    {
	    	$GLOBALS['tpl']->assign("current_cate",a_L("YOUHUI_ALL_TIP"));
	    }
	    else
	    {
	    	$cate_name = $c_cate_id==0?a_L("YOUHUI_ALL_TIP"):$GLOBALS['db']->getOne("select name from ".DB_PREFIX."suppliers_cate where id = ".$c_cate_id);
	    	$region_name = $c_region_id==0?a_L("YOUHUI_ALL_TIP"):$GLOBALS['db']->getOne("select name from ".DB_PREFIX."coupon_region where id = ".$c_region_id);
	    	
	    	$GLOBALS['tpl']->assign("current_cate",$region_name." ".$cate_name);
	    }
	    
	    $region_list_data = array(array("name"=>a_L("YOUHUI_ALL"),"url"=>a_U("Youhui/index","cate_id-".$c_cate_id."|region_id-0"),"act"=>$c_region_id==0?1:0,"count"=>"none"));
	    $cate_list_data = array(array("name"=>a_L("YOUHUI_ALL"),"url"=>a_U("Youhui/index","cate_id-0|region_id-".$c_region_id),"act"=>$c_cate_id==0?1:0,"count"=>"none"));
	    
	    
	    foreach($region_list as $k=>$v)
	    {	    
	    	$region_list_data[$k+1] = $v;	
	    	$region_list_data[$k+1]['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."coupon where city_id = ".intval($currentCity['id'])." and (end_time = 0 or end_time > ".a_gmtTime().") and region_id = ".intval($v['id']));
	    	$region_list_data[$k+1]['url'] = a_U("Youhui/index","cate_id-".$c_cate_id."|region_id-".$v['id']);

	    	if($c_region_id==$v['id'])
	    	{
	    		$region_list_data[$k+1]['act'] = 1;
	    	}
	    }			
	    $GLOBALS['tpl']->assign("region_list",$region_list_data);	    
	    $cate_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."suppliers_cate order by sort desc");	
		foreach($cate_list as $k=>$v)
	    {
	    	$cate_list_data[$k+1] = $v;
	    	$cate_list_data[$k+1]['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."coupon as c left join ".DB_PREFIX."suppliers as s on c.supplier_id = s.id where (c.end_time = 0 or c.end_time > ".a_gmtTime().") and c.city_id = ".intval($currentCity['id'])." and s.cate_id = ".intval($v['id']));
	    	$cate_list_data[$k+1]['url'] = a_U("Youhui/index","cate_id-".intval($v['id'])."|region_id-".$c_region_id);
    		    	
	    	if($c_cate_id==$v['id'])
	    	{
	    		$cate_list_data[$k+1]['act'] = 1;
	    	}
	    }	    
	    $GLOBALS['tpl']->assign("cate_list",$cate_list_data);
	    
	    $page = isset ( $_REQUEST ['p'] ) ? intval ( $_REQUEST ['p'] ) > 0 ? intval ( $_REQUEST ['p'] ) : 1 : 1;
	    	    
	    $result = get_coupon_list($page,$c_cate_id,$c_region_id);
	    	    
	    $page = new Pager ( $result ['count'], a_fanweC ( "PAGE_LISTROWS" )); //初始化分页对象 		
		$p = $page->show ();
		
		$GLOBALS['tpl']->assign("pages",$p);
		
		$GLOBALS['tpl']->assign("coupon_list",$result['list']);
		
		$GLOBALS['tpl']->assign("coupon_total",$result['count']);
		
		$GLOBALS['tpl']->assign("best_coupon_list",get_best_coupon_list(5));	
		$GLOBALS['tpl']->assign("hot_coupon_list",get_hot_coupon_list(3));		
		$city_id = intval($GLOBALS['currentCity']['id']);
		
		//获取品牌商户
		$brand_list =$GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."suppliers where is_brand = 1 order by sort desc");
		$brand_coupon = array();
	    foreach($brand_list as $k=>$v)
	    {
	    	$b_coupon = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."coupon where supplier_id = ".intval($v['id'])." and (end_time > ".a_gmtTime()." or end_time = 0) and city_id = ".$city_id);
	    	if($b_coupon)
	    	{
	    		$b_coupon['supplier_name'] = $v['name'];
	    		$brand_coupon[] = $b_coupon;
	    	}
	    }
		$GLOBALS['tpl']->assign("brand_coupon",$brand_coupon);
		
		
		
		$field = $_COOKIE['fw_coupon_order_field'];
		if(!$field)
		$field = "create_time";
		
		$GLOBALS['tpl']->assign("sort_field",$field);
		
		$GLOBALS['tpl']->display("Inc/youhui/youhui_index.moban");				
	}

	if(strtolower($_REQUEST['a'])=='show')
	{
		$city_id = intval($GLOBALS['currentCity']['id']);
		$coupon_id = intval($_REQUEST['id']);
		$condition = " (end_time > ".a_gmtTime()." or end_time = 0) and city_id = ".$city_id." and id = ".$coupon_id;		
		$coupon = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."coupon where ".$condition);
		if(!$coupon)
		{
			a_error(a_L("NO_CITY_YOUHUI"));
		}
		$coupon['supplier_name'] = $GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."suppliers where id = ".intval($coupon['supplier_id']));
		$coupon['url'] = a_U("Youhui/print","id-".$coupon['id']);
		$GLOBALS['tpl']->assign("coupon",$coupon);
		
		if($coupon['depart']!='')
		$depart_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".intval($coupon['supplier_id'])." and id in (".$coupon['depart'].")");
		else
		$depart_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".intval($coupon['supplier_id']));
		
		$GLOBALS['tpl']->assign("depart_list",$depart_list);
				
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());		
				
		$navs = array('name'=>$coupon['name'],'url'=>a_U("Youhui/show","id-".$coupon['id']));
				
		$data = array(
	    			'navs' => array(
		    				$navs,
	    			)	    	
	    );
	    assignSeo($data);    
	    
	    $GLOBALS['tpl']->assign("best_coupon_list",get_best_coupon_list(5));	
		$GLOBALS['tpl']->assign("hot_coupon_list",get_hot_coupon_list(3));	
		
		$GLOBALS['tpl']->display("Inc/youhui/youhui_show.moban");
	}
	
	if(strtolower($_REQUEST['a'])=='print')
	{
		$city_id = intval($GLOBALS['currentCity']['id']);
		$coupon_id = intval($_REQUEST['id']);
		$condition = " (end_time > ".a_gmtTime()." or end_time = 0) and city_id = ".$city_id." and id = ".$coupon_id;		
		$coupon = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."coupon where ".$condition);
		if(!$coupon)
		{
			a_error(a_L("NO_CITY_YOUHUI"));
		}
		$coupon['supplier_name'] = $GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."suppliers where id = ".intval($coupon['supplier_id']));
		$coupon['url'] = a_U("Youhui/print","id-".$coupon['id']);
		$GLOBALS['tpl']->assign("coupon",$coupon);
		
		$GLOBALS['db']->query("update ".DB_PREFIX."coupon set count = count + 1 where id = ".$coupon_id);
		
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());		
				
		$navs = array('name'=>$coupon['name'],'url'=>a_U("Youhui/show","id-".$coupon['id']));
				
		$data = array(
	    			'navs' => array(
		    				$navs,
	    			)	    	
	    );
	    assignSeo($data);    
	    
	    
		
		$GLOBALS['tpl']->display("Inc/youhui/youhui_print.moban");
	}
	
	if(strtolower($_REQUEST['a'])=='loadsms')
	{
		$city_id = intval($GLOBALS['currentCity']['id']);
		$coupon_id = intval($_REQUEST['id']);
		$condition = " (end_time > ".a_gmtTime()." or end_time = 0) and city_id = ".$city_id." and id = ".$coupon_id;		
		$coupon = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."coupon where ".$condition);
		$coupon['supplier_name'] = $GLOBALS['db']->getOneCached("select name from ".DB_PREFIX."suppliers where id = ".$coupon['supplier_id']);
		$GLOBALS['tpl']->assign("coupon",$coupon);
		$GLOBALS['tpl']->display("Inc/youhui/youhui_loadsms.moban");
	}
	
	if(strtolower($_REQUEST['a'])=='sendsms')
	{
		$coupon_id = intval($_REQUEST['id']);
		$mobile = $_REQUEST['mobile'];
		$content = $_REQUEST['content'];		
		$user_id = intval($_SESSION['user_id']);
		$result = array();
		if($user_id==0)
		{
			$result['status'] = 2;
			$result['msg'] = a_U("User/login");
			header("Content-Type:text/html; charset=utf-8");
        	echo(json_encode($result));
        	exit;
		}
		
		$timezone = intval(a_fanweC('TIME_ZONE'));
		
		$today_begin = strtotime(a_toDate(a_gmtTime(),"Y-m-d"))-$timezone*3600;
		$today_end = strtotime(a_toDate(a_gmtTime(),"Y-m-d"))-$timezone*3600+(24*3600-1);
		
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."coupon_send_log where uid = ".$user_id." and send_time >".$today_begin." and send_time <=".$today_end);
		
		if($count>=intval(a_fanweC("YOUHUI_LIMIT")))
		{
			$result['status'] = 0;
			$result['msg'] = sprintf(a_L("YOUHUI_SMS_LIMIT"),intval(a_fanweC("YOUHUI_LIMIT")));
			header("Content-Type:text/html; charset=utf-8");
        	echo(json_encode($result));
        	exit;
		}
		
		require ROOT_PATH.'services/Sms/SmsPlf.class.php';
		$number = array($mobile);
		$smsobj = new SmsPlf();
		$smsobj->sendSMS($number,$content);		
		
		$send_log = array();
		$send_log['uid'] = $user_id;
		$send_log['send_time'] = a_gmtTime();
		$send_log['mobile'] = $mobile;	
		$GLOBALS['db']->autoExecute(DB_PREFIX."coupon_send_log", $send_log);	 
		$GLOBALS['db']->query("update ".DB_PREFIX."coupon set count = count + 1 where id = ".$coupon_id);
		$result['status'] = 1;
		$result['msg'] = a_U("User/login");
		header("Content-Type:text/html; charset=utf-8");
        echo(json_encode($result));
        exit;
		
	}
	if(strtolower($_REQUEST['a'])=='ditu')
	{
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list",getGroupCityList());
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());		
				
		$navs = array('name'=>a_L("BEST_YOUHUI"),'url'=>a_U("Youhui/ditu"));
				
		$data = array(
	    			'navs' => array(
		    				$navs,
	    			)	    	
	    );
	    assignSeo($data);    

	    $region_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."coupon_region where city_id = ".intval($currentCity['id'])." and pid=0 order by sort desc");
		
	    $c_region_id = intval($_REQUEST['region_id']);
	    $c_cate_id = intval($_REQUEST['cate_id']);
	    
	    $region_list_data = array();
	     $cate_list_data = array(array("name"=>a_L("YOUHUI_ALL"),"url"=>a_U("Youhui/index","cate_id-0|region_id-".$c_region_id),"act"=>$c_cate_id==0?1:0,"count"=>"none"));
	    
	    
	    
	    foreach($region_list as $k=>$v)
	    {	    
	    	$region_list_data[$k+1] = $v;	
	    	$region_list_data[$k+1]['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."coupon where city_id = ".intval($currentCity['id'])." and region_id = ".intval($v['id']));
	    	$region_list_data[$k+1]['url'] = a_U("Youhui/index","cate_id-".$c_cate_id."|region_id-".$v['id']);

	    	if($c_region_id==$v['id'])
	    	{
	    		$region_list_data[$k+1]['act'] = 1;
	    	}
	    }			
	    $GLOBALS['tpl']->assign("region_list",$region_list_data);	    
	    $cate_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."suppliers_cate order by sort desc");	
		foreach($cate_list as $k=>$v)
	    {
	    	$cate_list_data[$k+1] = $v;
	    	$cate_list_data[$k+1]['count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."coupon as c left join ".DB_PREFIX."suppliers as s on c.supplier_id = s.id where c.city_id = ".intval($currentCity['id'])." and s.cate_id = ".intval($v['id']));
	    	$cate_list_data[$k+1]['url'] = a_U("Youhui/index","cate_id-".intval($v['id'])."|region_id-".$c_region_id);
    		    	
	    	if($c_cate_id==$v['id'])
	    	{
	    		$cate_list_data[$k+1]['act'] = 1;
	    	}
	    }	    
	    $GLOBALS['tpl']->assign("cate_list",$cate_list_data);
	    
	    $page = isset ( $_REQUEST ['p'] ) ? intval ( $_REQUEST ['p'] ) > 0 ? intval ( $_REQUEST ['p'] ) : 1 : 1;
	    
	    
	    $result = get_coupon_list($page,$c_cate_id,$c_region_id);
	    
	    
	    $page = new Pager ( $result ['count'], a_fanweC ( "PAGE_LISTROWS" )); //初始化分页对象 		
		$p = $page->show ();
		
		$GLOBALS['tpl']->assign("pages",$p);
		
		$GLOBALS['tpl']->assign("coupon_list",$result['list']);
		
		$GLOBALS['tpl']->assign("coupon_total",$result['count']);
		
		$GLOBALS['tpl']->assign("best_coupon_list",get_best_coupon_list(5));
		$GLOBALS['tpl']->assign("hot_coupon_list",get_hot_coupon_list(5));
		
		$GLOBALS['tpl']->assign("cate_id",intval($_COOKIE["fw_coupon_cate"]));
		$GLOBALS['tpl']->assign("region_id",intval($_COOKIE["fw_coupon_region"]));
	    
		$GLOBALS['tpl']->display("Inc/youhui/youhui_ditu.moban");
				
	}	
	
	if(strtolower($_REQUEST['a'])=='set_cate_cookie')
	{
		$cate_id = intval($_REQUEST['cate_id']);
		setcookie("fw_coupon_cate", $cate_id);
        $_COOKIE["fw_coupon_cate"]  =  $cate_id;
	}

	
	if(strtolower($_REQUEST['a'])=='set_region_cookie')
	{
		$region_id = intval($_REQUEST['region_id']);
		setcookie("fw_coupon_region", $region_id);
        $_COOKIE["fw_coupon_region"]  =  $region_id;
	}
	
	if(strtolower($_REQUEST['a'])=='get_current_region')
	{
 		$region_id = intval($_COOKIE["fw_coupon_region"]);
 		$city_id = intval($GLOBALS['currentCity']['id']);
		if($region_id==0)
		{
			
			$region = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."coupon_region where city_id = ".$city_id." order by sort desc");
			setcookie("fw_coupon_region", intval($region['id']));
        	$_COOKIE["fw_coupon_region"]  =   intval($region['id']);
		}
		else
		{
			$region = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."coupon_region where id = ".$region_id." and city_id = ".$city_id);
			setcookie("fw_coupon_region", intval($region['id']));
	        $_COOKIE["fw_coupon_region"]  =   intval($region['id']);
		}
		header("Content-Type:text/html; charset=utf-8");
        echo(json_encode($region));
        exit;
	}	
	
	//获取当前商户列表
	if(strtolower($_REQUEST['a'])=='get_supplier_list')
	{
 		$region_id = intval($_COOKIE["fw_coupon_region"]);
		$cate_id = intval($_COOKIE["fw_coupon_cate"]);
		$sql = "select s.* from ".DB_PREFIX."coupon as c left join ".DB_PREFIX."suppliers as s on c.supplier_id = s.id where (c.end_time = 0 or c.end_time >".a_gmtTime().") and c.region_id = ".$region_id;
		if($cate_id!=0)
		{
			$sql.=" and s.cate_id = ".$cate_id." group by s.id";
		}
		$list = $GLOBALS['db']->getAll($sql);
		$depart_list = array();
		foreach($list as $k=>$v)
		{
			$departs  =  $GLOBALS['db']->getAll("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".$v['id']);
			foreach($departs as $kk=>$vv)
			{
				$vv['icon'] = __ROOT__.$GLOBALS['db']->getOne("select sc.icon from ".DB_PREFIX."suppliers as s left join ".DB_PREFIX."suppliers_cate as sc on s.cate_id = sc.id where s.id = ".$vv['supplier_id']." limit 1");
				$depart_list[] = $vv;
			}
		}
		header("Content-Type:text/html; charset=utf-8");
        echo(json_encode($depart_list));
        exit;
	}	
	
	//获取当前分店的最新优惠券
	if(strtolower($_REQUEST['a'])=='get_coupon_info')
	{
 		$depart_id = intval($_REQUEST['id']);
 		$region_id = intval($_COOKIE["fw_coupon_region"]);
		$cate_id = intval($_COOKIE["fw_coupon_cate"]);
		$sql = "select c.* from ".DB_PREFIX."coupon as c left join ".DB_PREFIX."suppliers as s on c.supplier_id = s.id where (c.end_time = 0 or c.end_time >".a_gmtTime().") and c.region_id = ".$region_id;
		if($cate_id!=0)
		{
			$sql.=" and s.cate_id = ".$cate_id." group by s.id";
		}
		$list = $GLOBALS['db']->getAll($sql);
		
		foreach($list as $k=>$v)
		{
			if(in_array($depart_id,explode(",",$v['depart'])))
			{
				$coupon = $v;
				break;
			}
		}
		$coupon['url'] = a_U("Youhui/show","id-".$coupon['id']);
		$GLOBALS['tpl']->assign("coupon",$coupon);
		$GLOBALS['tpl']->display("Inc/youhui/youhui_get_coupon_info.moban");
	}	
	
	
	function get_coupon_list($page,$cate_id=0,$region_id=0)
	{
		$field = $_COOKIE['fw_coupon_order_field'];
		$filename=md5("get_coupon_list".C_CITY_ID.$page.$cate_id.$region_id.$field).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			
			if(!$field)
				$field = "create_time";	
			
			$order=" ".$field." desc ";
			$city_id = intval($GLOBALS['currentCity']['id']);
			$condition = " (c.end_time > ".a_gmtTime()." or c.end_time = 0) and c.city_id = ".$city_id;
			$page_size = $page;
			$page_count = a_fanweC("PAGE_LISTROWS");
			$limit = ($page_size-1)*$page_count.",".$page_count;	
			
			if($cate_id > 0)
			{
				$condition.=" and s.cate_id = ".$cate_id;
			}
			if($region_id > 0)
			{
				$condition.=" and c.region_id = ".$region_id;
			}
			
			$sql_list = "select s.name as sname,c.* from ".DB_PREFIX."coupon as c left join ".DB_PREFIX."suppliers as s on c.supplier_id = s.id where ".$condition." order by ".$order." limit ".$limit;
			$sql_count = "select count(*) from ".DB_PREFIX."coupon as c left join ".DB_PREFIX."suppliers as s on c.supplier_id = s.id where ".$condition;
			$list = $GLOBALS['db']->getAll($sql_list);
			foreach($list as $k=>$v)
			{
				$list[$k]['url'] = a_U("Youhui/show","id-".$v['id']);
			}
			$count = $GLOBALS['db']->getOne($sql_count);
			$return['list'] = $list;
			$return['count'] = $count;
			setCaches($filename,$return,substr($filename,0,1));
			return $return;
		}
		return getCaches($filename,substr($filename,0,1));	
	}
	
	
	function get_best_coupon_list($limit)
	{
		$filename=md5("get_best_coupon_list".C_CITY_ID.$limit).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			$city_id = intval($GLOBALS['currentCity']['id']);
			$condition = "c.is_best = 1 and (c.end_time > ".a_gmtTime()." or c.end_time = 0) and c.city_id = ".$city_id;
	
			
			$sql_list = "select s.name as sname,c.* from ".DB_PREFIX."coupon as c left join ".DB_PREFIX."suppliers as s on c.supplier_id = s.id where ".$condition." order by create_time desc limit ".$limit;
			$list = $GLOBALS['db']->getAll($sql_list);
			foreach($list as $k=>$v)
			{
				$list[$k]['url'] = a_U("Youhui/show","id-".$v['id']);
			}
			setCaches($filename,$list,substr($filename,0,1));
			return $list;
		}
		return getCaches($filename,substr($filename,0,1));
	
	}
	function get_hot_coupon_list($limit)
	{
		$filename=md5("get_hot_coupon_list".C_CITY_ID.$limit).".php";
		if(getCacheIsUpdate(ROOT_PATH."/app/Runtime/caches/".substr($filename,0,1)."/".$filename,120)){
			$city_id = intval($GLOBALS['currentCity']['id']);
			$condition = "(c.end_time > ".a_gmtTime()." or c.end_time = 0) and c.city_id = ".$city_id;
	
			
			$sql_list = "select s.name as sname,c.* from ".DB_PREFIX."coupon as c left join ".DB_PREFIX."suppliers as s on c.supplier_id = s.id where ".$condition." order by count desc limit ".$limit;
			$list = $GLOBALS['db']->getAll($sql_list);
			foreach($list as $k=>$v)
			{
				$list[$k]['url'] = a_U("Youhui/show","id-".$v['id']);
			}
			setCaches($filename,$list,substr($filename,0,1));
			return $list;
		}
		return getCaches($filename,substr($filename,0,1));
	
	}
?>