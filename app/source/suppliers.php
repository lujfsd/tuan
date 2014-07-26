<?php
	$ma= $_REQUEST ['m']."_".strtolower($_REQUEST ['a']);
	if(strtolower($ma)=="suppliers_groupbond" || strtolower($ma)=="suppliers_index")
		Suppliers_groupbond();
	else
		$ma();
	
	function Suppliers_groupbond(){
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
		$sn = trim($_REQUEST['sn']);
		$id = intval($_REQUEST['id']);
		$goods_id = intval($_REQUEST['goods_id']);
		
		$status =  $_REQUEST['status'];
		
		if($status == "")
			$status = 1;
					
		//初始化分页
    	$page = intval($_REQUEST["p"]);
    	if($page==0 || !empty($sn))
    		$page = 1;
    	
    	$GLOBALS['tpl']->assign('sn',$sn);	
    	if($id>0)
    		$GLOBALS['tpl']->assign('id',$id);
    	
		$GLOBALS['tpl']->assign('status',$status);
		
		if($goods_id > 0)
			$GLOBALS['tpl']->assign('goods_id',$goods_id);
		
		$result = getSuppliersGroupBondList(intval($_SESSION['suppliers_id']), $sn, $status,$page,$id,$goods_id);
		
		
		$GLOBALS['tpl']->assign("groupbond_list",$result['list']);
		
		
		//分页
		$page = new Pager($result['total'],a_fanweC("PAGE_LISTROWS"));   //初始化分页对象 		
		$p  =  $page->show();
		
		$GLOBALS['tpl']->assign('pages',$p);
        $GLOBALS['tpl']->assign('total',$result['total']);
		//商品列表
		$sql = "select a.id,a.name_1,a.goods_short_name from ".DB_PREFIX."goods as a left join ".DB_PREFIX."group_city as b on b.id = a.city_id left join ".DB_PREFIX."group_bond gb on gb.goods_id=a.id   where a.suppliers_id = ".intval($_SESSION['suppliers_id'])."  group by a.id order by a.id desc";
    	$goods_list = $GLOBALS['db']->getAll($sql);
		$GLOBALS['tpl']->assign("goods_list",$goods_list);
		
		$data = array(
    		'navs' => array(
    			array('name'=>a_L("HC_BOND_SEARCH"),'url'=>a_u("Suppliers/groupbond"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
    	$GLOBALS['tpl']->assign("content_page",'groupbond');
		$GLOBALS['tpl']->display("Page/suppliers.moban");		
	}
	
	function Suppliers_login($err = '')
	{				
		if (intval($_SESSION['suppliers_id']) > 0){
			Suppliers_groupbond();
			exit;			
		}		
		
    	$data = array(
    		'navs' => array(
    			array('name'=>a_L('SUPPLIERS_LOGIN'),'url'=>a_u("Suppliers/login"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	if($err)
    		$GLOBALS['tpl']->assign("error",$err);
    		
    	//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
    	$GLOBALS['tpl']->assign("content_page",'login');
		$GLOBALS['tpl']->display("Page/suppliers.moban");
		
	}
	
	function Suppliers_dologin()
	{
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
    	$depart_name = trim($_POST['suppliers_name']);
    	$pwd = trim($_POST['pwd']);
		
		if($depart_name=='')
		{
			$err = a_L("HC_PLEASE_ENTER_SUPPLIER_NAME");
		}
		elseif(strlen($pwd) < 4)
		{
			$err = a_L("HC_PASSWORD_ERROR");
		}
		
		if($err != '')
		{
			login($err);
			exit;
		}
		
		$pwd = md5($pwd);
		
		$suppliersinfo =$GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers_depart where pwd='".$pwd."' and depart_name='".$depart_name."'");
		if($suppliersinfo)
		{
				$suppliers_baseinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers where id={$suppliersinfo['supplier_id']}");
				
				$suppliersinfo['last_ip'] = $_SESSION['CLIENT_IP'];
				$_SESSION["suppliers_name"]=$suppliersinfo['depart_name'];
				$_SESSION["suppliers_id"]=$suppliers_baseinfo['id'];
				$_SESSION["depart_id"]=$suppliersinfo['id'];
				$suppliersinfo['id'] = $suppliersinfo['supplier_id'];
				$GLOBALS['db']->autoExecute(DB_PREFIX."suppliers",$suppliersinfo,"update","id={$suppliersinfo['supplier_id']}");
				//success(a_L("LOGIN_SUCCESS","",a_u("Suppliers/groupbond")));
				redirect2(a_u("Suppliers/groupbond"),0,a_L("LOGIN_SUCCESS"));
		}
		else 
		{
			Suppliers_login(a_L("LOGIN_FAILED"));
			exit;
		}
	}
	
	function Suppliers_reset($err='')
    {
    	if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
		
    	$data = array(
    		'navs' => array(
    			array('name'=>a_L("HC_CHANGE_SUPPLIER_PWD"),'url'=>a_u("Suppliers/reset"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	if($err)
    		$GLOBALS['tpl']->assign("error",$err);

    	//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->assign("content_page",'reset');
		$GLOBALS['tpl']->display("Page/suppliers.moban");
    }
    
    function Suppliers_doreset()
    {
    	if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0 || intval($_SESSION['depart_id']) == 0){
			Suppliers_login();
			exit;			
		}
    	$cfm_password = trim($_POST['user_pwd_confirm']);
    	$user_pwd = trim($_POST['user_pwd']);

		
		$err = "";
		
		if(strlen($user_pwd) < 4)
		{
			$err = a_L("HC_PASSWORD_TOO_SHORT");
		}
		elseif($user_pwd !=$cfm_password)
		{
			$err = a_L("HC_PASSWORD_CONFIRMED_ERROR");
		}
		
		if($err != '')
		{
			Suppliers_reset($err);
			exit;
		}
		
		$data = array(
				'navs' => array(
						array('name'=>a_L("HC_CHANGE_SUPPLIER_PWD"),'url'=>'')
					),
					'keyword'=>	'',
					'content'=>	'',
				);
		
		assignSeo($data);
		
		$suppliersinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers_depart where id={$_SESSION['depart_id']}");
		if($suppliersinfo)
		{
			$suppliersinfo['pwd'] = md5($user_pwd);
			$GLOBALS['db']->autoExecute(DB_PREFIX."suppliers_depart",$suppliersinfo,"update","id={$suppliersinfo['id']}");
		}
			
		success(a_L("HC_PASSWORD_RESET_SUCCESS"),"",a_u("Suppliers/reset"));		
    }
    
    function Suppliers_usetime(){
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;			
		}
		
    	$suppliersinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers where id = ".intval($_SESSION['suppliers_id']));
    	$GLOBALS['tpl']->assign("suppliers",$suppliersinfo);
    			
		$time = a_gmtTime();
    	$groupbond = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."group_bond where id = ".intval($_REQUEST['id']));
		$groupbond['create_time_format'] = a_toDate($groupbond['create_time'],'Y-m-d');
		$groupbond['buy_time_format'] = a_toDate($groupbond['buy_time'],'Y-m-d');
		$groupbond['use_time_format'] = a_toDate($groupbond['use_time'],'Y-m-d');
		$groupbond['end_time_format'] = a_toDate($groupbond['end_time'],'Y-m-d');
		
		if(($groupbond['end_time'] > $time || $groupbond['end_time'] == 0) && $groupbond['use_time'] == 0)
		{
			$groupbond['is_edit'] = 1;
		}
		
    	//当商品按单发短信时，一张团购券的消费数(订单商品的购买数量)
		$is_order_sms = $GLOBALS['db']->getOne("select is_order_sms from ".DB_PREFIX."goods where id=".$groupbond['goods_id']."");
		if($is_order_sms)
		{
			$order_goods_number=$GLOBALS['db']->getOne("select number from ".DB_PREFIX."order_goods where order_id=(select id from ".DB_PREFIX."order where sn=".$groupbond['order_id'].") and rec_id=".$groupbond['goods_id']."");
			$groupbond['order_goods_number']=$order_goods_number;
		}		
    	$GLOBALS['tpl']->assign("groupbond",$groupbond);
    	$data = array(
    		'navs' => array(
    			array('name'=>a_L("HC_USE_DATE"),'url'=>a_u("Suppliers/usetime"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->assign("content_page",'groupbondedit');
		$GLOBALS['tpl']->display("Page/suppliers.moban");		
	}
	
	function Suppliers_dousetime(){
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;			
		}
		
    	$groupbond = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."group_bond where is_valid=1 and id = ".intval($_POST['id']));
    	$suppliersinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers where id = ".intval($_SESSION['suppliers_id']));
    	
    	if ($suppliersinfo['h_pwd_groupbond'] == 1){
    		$groupbond_pwd = trim($_POST['groupbond_pwd']);
    		if ($groupbond['password'] != $groupbond_pwd){
    			a_error (a_L("HC_BONDPASSWORD_ERROR"));
    		}
    		
    	}
    	
		$groupbond['use_time'] = ! empty ( $_POST ['use_time'] ) ? localStrToTime( $_POST ['use_time'] ) : a_gmtTime();
		$groupbond['depart_id'] = intval($_SESSION["depart_id"]);
                $groupbond['is_balance']= 1;
		$rs = $GLOBALS['db']->autoExecute(DB_PREFIX."group_bond",$groupbond,"update","id={$groupbond['id']}");
		if ($rs){
			require_once ROOT_PATH.'app/source/func/com_send_sms_func.php';
			s_send_groupbond_use_sms($groupbond['id'],true);
		}
			

		success(a_L("HC_EDIT_BOND_SUCCESS"),"",a_u("Suppliers/groupbond"));		
	}
	
    function Suppliers_logout()
	{
		unset($_SESSION['suppliers_name']);
		unset($_SESSION['suppliers_id']);
		unset($_SESSION['depart_id']);
		success(a_L("LOGOUT_SUCCESS"),"",a_u("Index/index"));
	}
    
	function getSuppliersGroupBondList($suppliers_id, $sn, $status=1,$page=1,$id =0,$goods_id=0)
	{
		$time = a_gmtTime();
		if(intval($goods_id) > 0)
			$where = " and goods_id = $goods_id and is_valid = 1";
		else
			$where = " and goods_id in (select id from ".DB_PREFIX."goods where suppliers_id =".$suppliers_id.") and is_valid = 1";
		if($status == 1)
			$where .= " and (use_time = 0 or use_time is null)";
		elseif($status == 2)
			$where .= " and use_time > 0";
		elseif($status == 3)
			$where .= " and end_time < $time and end_time > 0";
		
		if (!empty($sn)){
			$where .= " and sn like '%".$sn."%'";
		}	
		
		if (!empty($id)){
			$where .= " and id =".$id;
		}	
			
		$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".a_fanweC("PAGE_LISTROWS");
		
		$list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."group_bond where 1=1 {$where} order by create_time desc limit {$limit}");
		
		foreach($list as $k=>$v)
		{
			$list[$k]['create_time_format'] = a_toDate($v['create_time'],'Y-m-d');
			$list[$k]['buy_time_format'] = a_toDate($v['buy_time'],'Y-m-d');
			$list[$k]['use_time_format'] = a_toDate($v['use_time'],'Y-m-d');
			$list[$k]['end_time_format'] = a_toDate($v['end_time'],'Y-m-d');
			if(($v['end_time'] > $time || $v['end_time'] == 0) && $v['use_time'] == 0)
				$list[$k]['is_edit'] = 1;
			if(($v['end_time'] >= $v['use_time'] && $v['use_time'] >= $v['buy_time']) && $v['depart_id']>0)
			{
				$list[$k]['suppliers_depart_name'] = $GLOBALS['db']->getOne("select depart_name from ".DB_PREFIX."suppliers_depart where id=".$v['depart_id']."");
			}
			//当商品按单发短信时，一张团购券的消费数(订单商品的购买数量)
			$is_order_sms = $GLOBALS['db']->getOne("select is_order_sms from ".DB_PREFIX."goods where id=".$v['goods_id']."");
			if($is_order_sms)
			{
				$order_goods_number=$GLOBALS['db']->getOne("select number from ".DB_PREFIX."order_goods where order_id=(select id from ".DB_PREFIX."order where sn=".$v["order_id"].") and rec_id=".$v['goods_id']."");
				$list[$k]['order_goods_number']=$order_goods_number;
			}
		}
		$result['list'] = $list;
		$result['total'] = $GLOBALS['db']->getOne("select count(*) as countx from ".DB_PREFIX."group_bond where 1=1 {$where} ");
		return $result;
	}
	
	
	//关于商家优惠券的处理
	function Suppliers_coupon()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}

					
		//初始化分页
    	$page = intval($_REQUEST["p"]);
    	if($page==0)
    		$page = 1;
    	
		$result = get_sp_coupon_list(intval($_SESSION['suppliers_id']),$page);
    		
		//分页
		$page = new Pager($result['count'],a_fanweC("PAGE_LISTROWS"));   //初始化分页对象 		
		$p  =  $page->show();
		
		$GLOBALS['tpl']->assign('pages',$p);
		
		$GLOBALS['tpl']->assign('coupon_list',$result['list']);
        		
		$data = array(
    		'navs' => array(
    			array('name'=>a_L("YOUHUI_LIST"),'url'=>a_u("Suppliers/coupon"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_coupon.moban");				
	}
	
		function Suppliers_addcoupon()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}	
        		
		$data = array(
    		'navs' => array(
    			array('name'=>a_L("ADD_YOUHUI"),'url'=>a_u("Suppliers/coupon"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
    	
    	
	
    	$supplier_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id = ".intval($_SESSION['suppliers_id']));
    	$depart_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".intval($_SESSION['suppliers_id']));
    	    	
    	$city_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."group_city where status = 1 and youhui= 1 order by sort desc");
    	$GLOBALS['tpl']->assign("supplier_info",$supplier_info);
    	$GLOBALS['tpl']->assign("depart_list",$depart_list);
    	$GLOBALS['tpl']->assign("city_list",$city_list);
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_addcoupon.moban");				
	}
	
	function Suppliers_editcoupon()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}	
        		
		$coupon = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."coupon where id = ".intval($_REQUEST['id']));
		if(!$coupon)
		{
			a_error(a_L("NO_YOUHUI"));
		}
		$coupon['depart'] = explode(",",$coupon['depart']);
		
		$data = array(
    		'navs' => array(
    			array('name'=>$coupon['name'],'url'=>a_u("Suppliers/editcoupon","id-".$coupon['id']))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
    	$supplier_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id = ".intval($_SESSION['suppliers_id']));
    	$depart_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".intval($_SESSION['suppliers_id']));
    	foreach($depart_list as $k=>$v)
    	{
    		if(in_array($v['id'],$coupon['depart']))
    		{
    			$depart_list[$k]['checked'] = 1;
    		}
    	}    	
    	
    	$city_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."group_city where status = 1 and youhui = 1 order by sort desc");
    	$GLOBALS['tpl']->assign("supplier_info",$supplier_info);
    	$GLOBALS['tpl']->assign("depart_list",$depart_list);
    	$GLOBALS['tpl']->assign("city_list",$city_list);
    	$GLOBALS['tpl']->assign("coupon",$coupon);
    	
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_editcoupon.moban");				
	}
	
	function Suppliers_load_region()
	{
		$city_id = intval($_REQUEST['city_id']);
		$coupon_id = intval($_REQUEST['coupon_id']);
		
		$region_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."coupon_region where city_id = ".$city_id." order by sort desc");
		$coupon_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."coupon where id = ".$coupon_id);
		foreach($region_list as $k=>$v)
		{
			if($v['id']==$coupon_info['region_id'])
			{
				$region_list[$k]['selected'] = 1;
			}
		}
		
		$GLOBALS['tpl']->assign("region_list",$region_list);
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_region_list.moban");	
	}
	
	function Suppliers_doaddcoupon()
	{
		if(trim($_REQUEST['name'])=='')
		{
			a_error(a_L("YOUHUI_NAME_EMPTY"));
		}
		if(intval($_REQUEST['region_id'])==0)
		{
			a_error(a_L("SELECT_REGION_YOUHUI"));
		}
		
		$data = array();
		$data['name'] = addslashes(htmlspecialchars(trim($_REQUEST['name'])));
		$data['city_id'] = intval($_REQUEST['city_id']);
		$data['region_id'] = intval($_REQUEST['region_id']);
		$depart  = $_REQUEST['depart'];
		if($depart&&is_array($depart))
		{
			$depart = implode(",",$depart);
		}
		$data['depart'] = $depart;
		$data['supplier_id'] = intval($_REQUEST['supplier_id']);
		$data['is_sms'] = intval($_REQUEST['is_sms']);
		$data['txt'] = addslashes(htmlspecialchars(trim($_REQUEST['txt'])));
		$data['content'] = addslashes(htmlspecialchars(trim($_REQUEST['content'])));
		$data['sn'] = addslashes(htmlspecialchars(trim($_REQUEST['sn'])));
		$data['end_time'] = empty($_REQUEST['end_time']) ? 0 : (localStrToTime($_REQUEST['end_time'])+(24*3600-1));
		$data['create_time'] = a_gmtTime();
		
		require_once ROOT_PATH.'app/source/class/file.php';
		$result = uploadFile();
		foreach($result as $k=>$v)
		{
			if($v['key'] == 'icon')
			{
				$data['icon'] = $v['recpath'].$v['savename'];
			}
			if($v['key'] == 'img')
			{
				$data['img'] = $v['recpath'].$v['savename'];
			} 
		}
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."coupon",$data);
		success(a_L("ADD_YOUHUI_SUCCESS"));
	}
	
	
	function Suppliers_doeditcoupon()
	{
		if(trim($_REQUEST['name'])=='')
		{
			a_error(a_L("YOUHUI_NAME_EMPTY"));
		}
		if(intval($_REQUEST['region_id'])==0)
		{
			a_error(a_L("SELECT_REGION_YOUHUI"));
		}
		
		$data = array();
		
		$data['name'] = addslashes(htmlspecialchars(trim($_REQUEST['name'])));
		$data['city_id'] = intval($_REQUEST['city_id']);
		$data['region_id'] = intval($_REQUEST['region_id']);
		$depart  = $_REQUEST['depart'];
		if($depart&&is_array($depart))
		{
			$depart = implode(",",$depart);
		}
		$data['depart'] = $depart;
		$data['supplier_id'] = intval($_REQUEST['supplier_id']);
		$data['is_sms'] = intval($_REQUEST['is_sms']);
		$data['txt'] = addslashes(htmlspecialchars(trim($_REQUEST['txt'])));
		$data['content'] = addslashes(htmlspecialchars(trim($_REQUEST['content'])));
		$data['sn'] = addslashes(htmlspecialchars(trim($_REQUEST['sn'])));
		$data['end_time'] = empty($_REQUEST['end_time']) ? 0 : (localStrToTime($_REQUEST['end_time'])+(24*3600-1));
		$data['create_time'] = a_gmtTime();
		
		require_once ROOT_PATH.'app/source/class/file.php';
		$result = uploadFile();
		foreach($result as $k=>$v)
		{
			if($v['key'] == 'icon')
			{
				@unlink(getcwd().$coupon['icon']);
				$data['icon'] = $v['recpath'].$v['savename'];
			}
			if($v['key'] == 'img')
			{
				@unlink(getcwd().$coupon['img']);
				$data['img'] = $v['recpath'].$v['savename'];
			} 
		}
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."coupon",$data,"UPDATE","id=".intval($_REQUEST['id']));
		success(a_L("SAVE_YOUHUI_SUCCESS"));
	}
	
	//关于自助团购的处理
	
	function Suppliers_grouplist()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
		
        		
		
		//初始化分页
    	$page = intval($_REQUEST["p"]);
    	if($page==0)
    		$page = 1;
    	$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".($page*a_fanweC("PAGE_LISTROWS"));
    	
		
		
		$list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."supplier_goods where supplier_id = ".intval($_SESSION['suppliers_id'])." order by create_time desc limit ".$limit);
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."supplier_goods where supplier_id = ".intval($_SESSION['suppliers_id']));
		 
    		
		//分页
		$page = new Pager($count,a_fanweC("PAGE_LISTROWS"));   //初始化分页对象 		
		$p  =  $page->show();
		
		$GLOBALS['tpl']->assign('pages',$p);
		
		$GLOBALS['tpl']->assign('group_list',$list);
		
		
		
		$data = array(
    		'navs' => array(
    			array('name'=>a_L("SUPPLIERS_GROUP_LIST"),'url'=>a_u("Suppliers/grouplist"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
    	
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
    	$GLOBALS['tpl']->assign("content_page",'groupbond');
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_grouplist.moban");	
	}
	
		function Suppliers_addgroup()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
		
        		
		$data = array(
    		'navs' => array(
    			array('name'=>a_L("ADD_SUPPLIERS_GROUPON"),'url'=>a_u("Suppliers/addgroup"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
    	
    	$supplier_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id = ".intval($_SESSION['suppliers_id']));
    	$GLOBALS['tpl']->assign("supplier_info",$supplier_info);
    	////add by hjt 2013-3-29
    	$supplier_goods_id=0;
    	//输出商品类型
    	$type_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_type");
    	
    	$lang_envs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."lang_conf");
		$lang_ids = array();
		$lang_names = array();
		foreach($lang_envs as $lang_item)
		{
			$lang_ids[]=$lang_item['id'];
			$lang_names[] = $lang_item['lang_name'];
		}
		$lang_ids = implode(",",$lang_ids);	
		$lang_names = implode(",",$lang_names);
		
    	$default_lang_id=$GLOBALS['db']->getRow("select id from ".DB_PREFIX."lang_conf where lang_name='".a_fanweC('DEFAULT_LANG')."'");
    	$default_lang_id = $default_lang_id['id'];  //默认语言的ID
		$select_dispname = "name_".$default_lang_id;
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
    	$GLOBALS['tpl']->assign("content_page",'groupbond');
    	$GLOBALS['tpl']->assign("type_list",$type_list);
    	$GLOBALS['tpl']->assign("select_dispname",$select_dispname);
    	$GLOBALS['tpl']->assign("default_lang_id",$default_lang_id);
		$GLOBALS['tpl']->assign("lang_ids",$lang_ids);
		$GLOBALS['tpl']->assign("lang_names",$lang_names);
		$GLOBALS['tpl']->assign("supplier_goods_id",$supplier_goods_id);
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_addgroup.moban");	
	}
	
	function Suppliers_editgroup()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
		$id = intval($_REQUEST['id']);
        		
		$data = array(
    		'navs' => array(
    			array('name'=>a_L("MODIFY_SUPPLIERS_GROUP"),'url'=>a_u("Suppliers/editgroup"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
    	
    	$supplier_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id = ".intval($_SESSION['suppliers_id']));
    	$GLOBALS['tpl']->assign("supplier_info",$supplier_info);
    	
    	$group_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."supplier_goods where id = ".$id." and is_public = 0 ");
    	if(!$group_info)
    	{
    		a_error(a_L("INVALID_VISIT"));
    	}
    	else
    	{
    		$img_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_gallery where supplier_goods_id = ".$group_info['id']);
    		$GLOBALS['tpl']->assign("img_list",$img_list);
    		$GLOBALS['tpl']->assign("group_info",$group_info);
    	}
    	
    	////add by hjt 2013-3-29
    	//输出商品类型
    	$type_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_type");
    	
    	$lang_envs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."lang_conf");
		$lang_ids = array();
		$lang_names = array();
		foreach($lang_envs as $lang_item)
		{
			$lang_ids[]=$lang_item['id'];
			$lang_names[] = $lang_item['lang_name'];
		}
		$lang_ids = implode(",",$lang_ids);	
		$lang_names = implode(",",$lang_names);
		
    	$default_lang_id=$GLOBALS['db']->getRow("select id from ".DB_PREFIX."lang_conf where lang_name='".a_fanweC('DEFAULT_LANG')."'");
    	$default_lang_id = $default_lang_id['id'];  //默认语言的ID
		$select_dispname = "name_".$default_lang_id;
		
    	$GLOBALS['tpl']->assign("type_list",$type_list);
    	$GLOBALS['tpl']->assign("select_dispname",$select_dispname);
    	$GLOBALS['tpl']->assign("default_lang_id",$default_lang_id);
		$GLOBALS['tpl']->assign("lang_ids",$lang_ids);
		$GLOBALS['tpl']->assign("lang_names",$lang_names);
		
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
    	$GLOBALS['tpl']->assign("content_page",'groupbond');
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_editgroup.moban");	
	}
	
	function Suppliers_uploadimg()
	{
		require_once ROOT_PATH.'app/source/class/file.php';
		$result = uploadFile();
		if($result)
		{
			//上传成功
			header("Content-Type:text/html; charset=utf-8");
			echo $result[0]['recpath'].$result[0]['savename'];
			exit;
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			echo '0';
			exit;
		}
		
	}
	
	function Suppliers_uploadeditor()
	{
		
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			header("Content-Type:text/html; charset=utf-8");
			echo json_encode(array('error' => 1, 'message' => a_L("PLEASE_LOGIN_FIRST")));
			exit;	
		}	
		
		require_once ROOT_PATH.'app/source/class/file.php';
		$result = uploadFile();
		if($result)
		{
			//上传成功
			
			header("Content-Type:text/html; charset=utf-8");
			//$cnd = CND_URL;
			$file_url = ".".$result[0]['recpath'].$result[0]['savename'];
			echo json_encode(array('error' => 0, 'url' => $file_url));
			exit;
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			echo json_encode(array('error' => 1, 'message' => a_L("UPLOAD_FAILED")));
			exit;
		}
		
	}
	
	function Suppliers_doaddgroup()
	{
		$data = array();
		$data['name'] = addslashes(htmlspecialchars(trim($_REQUEST['name'])));
		$data['min_count'] = intval($_REQUEST['min_count']);
		$data['max_count'] = intval($_REQUEST['max_count']);
		$data['user_max_count'] = intval($_REQUEST['user_max_count']);
		$data['supplier_id'] = intval($_REQUEST['supplier_id']);
		$data['origin_price'] = doubleval($_REQUEST['origin_price']);
		$data['shop_price'] = doubleval($_REQUEST['shop_price']);
		$data['promote_begin_time'] = empty($_REQUEST['promote_begin_time']) ? 0 : localStrToTime($_REQUEST['promote_begin_time']);
		$data['promote_end_time'] = empty($_REQUEST['promote_begin_time']) ? 0 : localStrToTime($_REQUEST['promote_end_time']);
		$data['create_time'] = a_gmtTime();
		$data['brief'] = htmlspecialchars_decode(stripslashes($_REQUEST['brief']));
		$data['contents'] = htmlspecialchars_decode(stripslashes($_REQUEST['contents']));
		$data['other_desc'] = htmlspecialchars_decode(stripslashes($_REQUEST['other_desc']));
		$data['goods_type'] = intval($_REQUEST['goods_type']);
		
		if($data['name']=='')
		{
			a_error(a_L("GROUP_NAME_MUST"));
		}
		
		$GLOBALS['db']->autoExecute(DB_PREFIX."supplier_goods",$data);
		$id = $GLOBALS['db']->insert_id();
		
		//add by hjt 2013-3-30 在属性表添加记录
		if($id)
		{
			$mail_msg = '';
			
			//属性
			$GLOBALS['db']->query("delete from ".DB_PREFIX."goods_attr where supplier_goods_id = ".$id);
			$attr_value = $_REQUEST['attr_value'];
			$attr_price = $_REQUEST['attr_price'];
			$attr_stock = $_REQUEST['attr_stock'];
		    if($attr_value)
			{
				
				foreach($attr_value as $attr_id=>$attr_list)
				{
					$attr_item = array();
					foreach($attr_list as $lang_id=>$val_list)
					{
						foreach($val_list as $row_idx=>$val)
						{
							$attr_item[$row_idx]['attr_id'] = $attr_id;
							$attr_item[$row_idx]['supplier_goods_id'] = $id;
							$attr_item[$row_idx]['attr_value_'.$lang_id] = $val;
							$attr_item[$row_idx]['price'] = floatval($attr_price[$attr_id][$row_idx]);
							$attr_item[$row_idx]['stock'] = intval($attr_stock[$attr_id][$row_idx]);
						}
					}		
					foreach ($attr_item as $val_item)
					{
						$GLOBALS['db']->autoExecute(DB_PREFIX."goods_attr",$val_item);
					}
				}
			}
		}
		
		//开始处理图片
		$img_list = $_REQUEST['img'];
		foreach($img_list as $k=>$v)
		{
			$img_data['small_img'] = $v;
			$img_data['big_img'] = $v;
			$img_data['origin_img'] = $v;
			$img_data['supplier_goods_id'] = $id;
			$GLOBALS['db']->autoExecute(DB_PREFIX."goods_gallery",$img_data);
		}
		success(a_L("SUBMIT_SUCCESS"));
		
		
	}
	
	function Suppliers_doeditgroup()
	{
		$id = intval($_REQUEST['id']);
		$group_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."supplier_goods where id = ".$id." and is_public = 0");
		if(!$group_info)
    	{
    		a_error(a_L("INVALID_VISIT"));
    	}
		$data = array();
		$data['name'] = addslashes(htmlspecialchars(trim($_REQUEST['name'])));
		$data['min_count'] = intval($_REQUEST['min_count']);
		$data['max_count'] = intval($_REQUEST['max_count']);
		$data['user_max_count'] = intval($_REQUEST['user_max_count']);
		$data['supplier_id'] = intval($_REQUEST['supplier_id']);
		$data['origin_price'] = doubleval($_REQUEST['origin_price']);
		$data['shop_price'] = doubleval($_REQUEST['shop_price']);
		$data['promote_begin_time'] = empty($_REQUEST['promote_begin_time']) ? 0 : localStrToTime($_REQUEST['promote_begin_time']);
		$data['promote_end_time'] = empty($_REQUEST['promote_begin_time']) ? 0 : localStrToTime($_REQUEST['promote_end_time']);
		$data['create_time'] = a_gmtTime();
		$data['brief'] = htmlspecialchars_decode(stripslashes($_REQUEST['brief']));
		$data['contents'] = htmlspecialchars_decode(stripslashes($_REQUEST['contents']));
		$data['other_desc'] = htmlspecialchars_decode(stripslashes($_REQUEST['other_desc']));
		
		if($data['name']=='')
		{
			a_error(a_L("GROUP_NAME_MUST"));
		}
		
		
		
		$b=$GLOBALS['db']->autoExecute(DB_PREFIX."supplier_goods",$data,"UPDATE","id=".intval($_REQUEST['id']));
		
		//add by hjt 2013-3-30 更新属性  
		if($b)
		{
			//属性
			$GLOBALS['db']->query("delete from ".DB_PREFIX."goods_attr where supplier_goods_id = ".$id);
			$attr_value = $_REQUEST['attr_value'];
			$attr_price = $_REQUEST['attr_price'];
			$attr_stock = $_REQUEST['attr_stock'];
		    if($attr_value)
			{
				
				foreach($attr_value as $attr_id=>$attr_list)
				{
					$attr_item = array();
					foreach($attr_list as $lang_id=>$val_list)
					{
						foreach($val_list as $row_idx=>$val)
						{
							$attr_item[$row_idx]['attr_id'] = $attr_id;
							$attr_item[$row_idx]['supplier_goods_id'] = $id;
							$attr_item[$row_idx]['attr_value_'.$lang_id] = $val;
							$attr_item[$row_idx]['price'] = floatval($attr_price[$attr_id][$row_idx]);
							$attr_item[$row_idx]['stock'] = intval($attr_stock[$attr_id][$row_idx]);
						}
					}		
					foreach ($attr_item as $val_item)
					{
						$GLOBALS['db']->autoExecute(DB_PREFIX."goods_attr",$val_item);
					}
				}
			}
		
		}
		//开始处理图片
		$GLOBALS['db']->query("delete from ".DB_PREFIX."goods_gallery where supplier_goods_id = ".$id);
		$img_list = $_REQUEST['img'];
		foreach($img_list as $k=>$v)
		{
			$img_data['small_img'] = $v;
			$img_data['big_img'] = $v;
			$img_data['origin_img'] = $v;
			$img_data['supplier_goods_id'] = $id;
			$GLOBALS['db']->autoExecute(DB_PREFIX."goods_gallery",$img_data);
		}
		success(a_L("SUBMIT_SUCCESS"));
		
		
	}	
	
	
	function Suppliers_delgroup()
	{
		$id = intval($_REQUEST['id']);
		$GLOBALS['db']->query("delete from ".DB_PREFIX."supplier_goods where id = ".$id);
		$GLOBALS['db']->query("delete from ".DB_PREFIX."goods_gallery where supplier_goods_id = ".$id." and goods_id = 0");
		success(a_L("DEL_SUCCESS"));		
	}
	
	function Suppliers_orderlist()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
		
        		
		$data = array(
    		'navs' => array(
    			array('name'=>a_L("SUPPLIER_ORDER_LIST"),'url'=>a_u("Suppliers/editgroup"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);

    	
    	//初始化分页
    	$page = intval($_REQUEST["p"]);
    	if($page==0)
    		$page = 1;
    	$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".(a_fanweC("PAGE_LISTROWS"));
    	
		
		$sql = "select og.data_name as name,group_concat(og.attr) as attr,og.number" .
				",o.id,o.sn,o.delivery,o.create_time,o.goods_status, o.money_status,g.id as goods_id" .
				",g.type_id, g.score_goods, og.order_id from ".DB_PREFIX."order_goods as og " .
				"left join ".DB_PREFIX."order as o on o.id = og.order_id " .
				"left join ".DB_PREFIX."goods as g on og.rec_id = g.id " .
				"where g.suppliers_id = ".intval($_SESSION['suppliers_id'])." " .
				"and (o.money_status = 2  or o.delivery in(select id from ".DB_PREFIX."delivery where allow_cod=1 ) ) " .
				"group by og.id order by o.create_time desc limit ".$limit;
    	$list = $GLOBALS['db']->getAll($sql);
    	foreach($list as $k=>$v)
		{	
			$delivery= null;
			if(intval($v['delivery'])> 0)
			{
				$delivery= $GLOBALS['db']->getRow("select allow_cod,name_1 from ".DB_PREFIX."delivery where id=".intval($v['delivery'])."");
				$list[$k]['sn'] = $v['sn'].'&nbsp;['.$delivery['name_1'].']';
			}
			else{
				$list[$k]['sn'] =$v['sn'];
			}
			
			$goods = $list[$k]['goods'] = $GLOBALS['db']->getRow("select id,name_1,goods_short_name,is_group_fail,promote_end_time,small_img,type_id ,shop_price,market_price,score_goods from ".DB_PREFIX."goods where id='{$v['goods_id']}'");
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
			
			
			$sql = "select a.*,b.name as express_name, b.code as express_code, c.express_id as d_express_id from ".DB_PREFIX."order_consignment a left outer join  ".DB_PREFIX."express b on b.id = a.express_id left outer join  ".DB_PREFIX."delivery c on c.id = a.delivery_id where a.order_id=".$v['order_id'];
			$orderConsignment = $GLOBALS['db']->getAll($sql);
			foreach($orderConsignment as $dk=>$dv){
				if (intval($dv['express_id']) == 0){
					$dv['express_name'] = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."express where id =".intval($dv['d_express_id']));
					$dv['express_code'] = $GLOBALS['db']->getOne("select code from ".DB_PREFIX."express where id =".intval($dv['d_express_id']));
				}
				$AppKey = a_fanweC('KUAIDI_APP_KEY');
				if (!empty($AppKey)){
					$url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$dv['express_code'].'&nu='.$dv['delivery_code'].'&show=2&muti=1&order=asc';
				}
				$url2 = "http://www.kuaidi100.com/chaxun?com=".$dv['express_code']."&nu=".$dv['delivery_code'];
				$url = $url2;
				$orderConsignment[$dk]['express_url'] = $url;
				$orderConsignment[$dk]['express_url2'] = $url2;				
			}
			$list[$k]['orderConsignment'] = $orderConsignment;			
		}
    	
    	
    	$sql_count = "select count(DISTINCT o.id)  from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."order as o on o.id = og.order_id left join ".DB_PREFIX."goods as g on og.rec_id = g.id where g.suppliers_id = ".intval($_SESSION['suppliers_id'])." and o.money_status = 2";
    	$count = $GLOBALS['db']->getOne($sql_count);
    	
		//分页
		$page = new Pager($count,a_fanweC("PAGE_LISTROWS"));   //初始化分页对象 		
		$p  =  $page->show();
		
		$GLOBALS['tpl']->assign('pages',$p);
		
		$GLOBALS['tpl']->assign('order_list',$list);
		
		
    	
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
    	$GLOBALS['tpl']->assign("content_page",'groupbond');
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_orderlist.moban");	
	}
	
	
	function Suppliers_delcoupon()
	{
		$id = intval($_REQUEST['id']);
		$coupon = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."coupon where id = ".intval($_REQUEST['id']));
		@unlink(getcwd().$coupon['icon']);
		@unlink(getcwd().$coupon['img']);
		$GLOBALS['db']->query("delete from ".DB_PREFIX."coupon where id = ".$id);
		success(a_L("DEL_YOUHUI_SUCCESS"));
	}
	
	function Suppliers_dealorder()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
		$id = intval($_REQUEST['id']);
        		
		
		$sql = "select o.delivery,g.type_id,g.goods_short_name,group_concat(CONCAT(og.data_name,og.attr,'(".a_L("NUMBER_SHOW").":',og.number,')' )SEPARATOR '<br>') as attr,o.* from ".DB_PREFIX."order_goods as og left join ".DB_PREFIX."order as o on o.id = og.order_id left join ".DB_PREFIX."goods as g on og.rec_id = g.id where g.suppliers_id = ".intval($_SESSION['suppliers_id'])." and (o.money_status = 2 or  o.delivery in (select id from ".DB_PREFIX."delivery where allow_cod=1)) and o.id = ".$id." group by o.id";
    	
		$order = $GLOBALS['db']->getRow($sql);
		$userid = $order['user_id'];
    	/*if($order['goods_short_name'])
		{
			$order['name'] = $order['goods_short_name'];
		}*/
		if(intval($order['delivery'])> 0)
		{
			$delivery= $GLOBALS['db']->getRow("select allow_cod,name_1 from ".DB_PREFIX."delivery where id=".intval($order['delivery'])."");
			$order['sn'] = $order['sn'].'&nbsp;['.$delivery['name_1'].']';
		}


		$data = array(
    		'navs' => array(
    			array('name'=>a_L("DEAL_ORDER"),'url'=>a_u("Suppliers/editgroup"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
    	if ($order['type_id'] == 3){
    		$order['type_id'] = 1;
    	}
    	$supplier_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id = ".intval($_SESSION['suppliers_id']));
    	$GLOBALS['tpl']->assign("supplier_info",$supplier_info);
    	$GLOBALS['tpl']->assign("order_info",$order);
    	
    	$express_list = $GLOBALS['db']->getAllCached("select * from ".DB_PREFIX."express order by sort desc");
    	$GLOBALS['tpl']->assign("express_list",$express_list);
    	
    	
    	//输出订单留言
		//初始化分页
		$page = intval ( $_REQUEST ['p'] );
		if ($page == 0)
			$page = 1;
		$result = getMessageList2 ( '', $id, $page,0,0 ," and (rec_module = 'Order' or rec_module='OrderReConsignment'  or rec_module='OrderUncharge' ) and user_id=".$userid );
		$GLOBALS ['tpl']->assign ( "message_list", $result ['list'] );
		//分页
		$page = new Pager ( $result ['total'], a_fanweC ( "ARTICLE_PAGE_LISTROWS" ) ); //初始化分页对象 		
		$p = $page->show ();
		$GLOBALS ['tpl']->assign ( 'pages', $p );
		//end 分页  
    	
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
    	$GLOBALS['tpl']->assign("content_page",'groupbond');
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_dealorder.moban");
	}
	
	function Suppliers_dodealorder()
	{
		$id = intval($_REQUEST['id']); //订单号
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."order where id = ".$id);
		/*if($order_info['goods_status'] != 0)
		{
			a_error(a_L("ORDER_DELIVERYED"));
		}*/
		$allow_cod =0;
		if(intval($order_info['delivery']) > 0){
			$allow_cod = $GLOBALS['db']->getOne("select allow_cod from ".DB_PREFIX."delivery where id=".intval($order_info['delivery']));
		}
		
		if($allow_cod ==0){
			if($order_info['money_status'] != 2)
			{
				a_error(a_L("ORDER_NOT_PAID"));
			}
		}
		$delivery_code =  addslashes(htmlspecialchars(trim($_REQUEST['delivery_code'])));
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."order_consignment where order_id='".intval($id)."'")==0)
		{
			//开始生成发货单
			$delivery_data = array();
			$delivery_data['order_id'] = $id;
			$delivery_data['delivery_id'] = $order_info['delivery'];
			$delivery_data['delivery_code'] = $delivery_code;
			$delivery_data['delivery_fee'] = $order_info['delivery_fee'];
			$delivery_data['protect_fee'] = $order_info['protect_fee'];
			$delivery_data['protect'] = $order_info['protect'];
			$delivery_data['cost_calc'] = 1;
			$delivery_data['region_lv1'] = $order_info['region_lv1'];
			$delivery_data['region_lv2'] = $order_info['region_lv2'];
			$delivery_data['region_lv3'] = $order_info['region_lv3'];
			$delivery_data['region_lv4'] = $order_info['region_lv4'];
			$delivery_data['address'] = $order_info['address'];
			$delivery_data['mobile_phone'] = $order_info['mobile_phone'];
			$delivery_data['fix_phone'] = $order_info['fix_phone'];
			$delivery_data['consignee'] = $order_info['consignee'];
			$delivery_data['zip'] = $order_info['zip'];
			$delivery_data['email'] = $order_info['email'];
			$delivery_data['create_time'] = a_gmtTime();
			$delivery_data['express_id'] = intval($_REQUEST['express_id']);
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."order_consignment",$delivery_data);
			$consignment_id = $GLOBALS['db']->insert_id();
			
			if(intval($consignment_id)>0)
			{
				$order_goods = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."order_goods where order_id = ".$id);
				foreach($order_goods as $k=>$v)
				{
					$c_goods = array();
					$c_goods['order_goods_id'] = $v['id'];
					$c_goods['order_consignment_id'] = $consignment_id;
					$c_goods['number'] = $v['number'];
					$GLOBALS['db']->autoExecute(DB_PREFIX."order_consignment_goods",$c_goods);
				}
			}
			
			$GLOBALS['db']->query("update ".DB_PREFIX."order set goods_status = 2 where id = ".$order_info['id']);
                        $GLOBALS['db']->query("update ".DB_PREFIX."order_goods set is_balance = 1 where order_id = ".$id);
		}
		else
		{
			$consignment_id = $GLOBALS['db']->getOne("select max(id) from ".DB_PREFIX."order_consignment where order_id='".intval($id)."'");
			$GLOBALS['db']->query("update ".DB_PREFIX."order_consignment set express_id ='".intval($_REQUEST['express_id'])."',delivery_code='".$delivery_code."' where order_id='".intval($id)."' ");
		}
		
		if(intval($_REQUEST['sendSms'])==1){
			a_send_delivery_sms($consignment_id);
			
	   		require_once ROOT_PATH.'app/source/func/com_func.php';
	    	require_once ROOT_PATH.'app/source/func/com_send_sms_func.php';
			require_once ROOT_PATH.'services/Sms/SmsPlf.class.php';
			require_once ROOT_PATH.'services/Mail/Mail.class.php';
			
			send_list(intval($order_info['user_id']));			
		}	
		success(a_L("DELIVERY_SUCCESS"));
	}
	
	function get_sp_coupon_list($supplier_id,$page)
	{
		$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".($page*a_fanweC("PAGE_LISTROWS"));
		$cond = " supplier_id = ".$supplier_id;
		
		$sql = "select * from ".DB_PREFIX."coupon where ".$cond." order by create_time desc limit ".$limit;
		$sql_count = "select count(*) from ".DB_PREFIX."coupon where ".$cond;
		
		$list = $GLOBALS['db']->getAll($sql);
		$count = $GLOBALS['db']->getOne($sql_count);

		return array("list"=>$list,"count"=>$count);
	}
	
	function localStrToTime($str)
	{
	    $timezone = intval(a_fanweC('TIME_ZONE'));
		$time = strtotime($str) - $timezone * 3600;
	    return $time;
	}
	
	
	
	function a_send_delivery_sms($delivery_id,$send = false)
	{
		if(a_fanweC("IS_SMS")==1&&a_fanweC("DELIVERY_SMS")==1)
    	{
    		//$delivery_vo = M("OrderConsignment")->getById($delivery_id);==SQL优化==
    		$delivery_vo = $GLOBALS['db']->getRow("select id,order_id,delivery_code,express_id from ".DB_PREFIX."order_consignment where id = ".$delivery_id);;     		
    		
    		//获取定单号
    		//$delivery_notify['order_sn'] = M("Order")->where("id=".$delivery_vo['order_id'])->getField("sn");==SQL优化==
    		$order = $GLOBALS['db']->getRow("select sn,user_id,mobile_phone_sms from ".DB_PREFIX."order where id = ".$delivery_vo['order_id']);
    		$order_sn = $order['sn'];
    		$user_id = intval($order['user_id']);
    		$mobile_phone_sms = $order['mobile_phone_sms'];
    		$delivery_notify['order_sn'] = $order_sn;
    		
    		//$user_id =  M("Order")->where("id=".$delivery_vo['order_id'])->getField("user_id");==SQL优化==
    		//$user = D("User")->getById($user_id);==SQL优化==
    		if ($mobile_phone_sms == '')
				$mobile_phone_sms = $GLOBALS['db']->getOne("select mobile_phone from ".DB_PREFIX."user where id = ".$user_id);
    
    		
			$delivery_notify['delivery_code'] = $delivery_vo['delivery_code'];
			$delivery_notify['delivery_name'] = $GLOBALS['db']->getOneCached("select `name` from ".DB_PREFIX."express where id='".$delivery_vo['express_id']."'");
			
			//模板解析
			//$payment_sms_tmpl = M("MailTemplate")->where("name='delivery_sms'")->getField("mail_content");==SQL优化==
			$payment_sms_tmpl = $GLOBALS['db']->getOne("select mail_content from ".DB_PREFIX."mail_template where name='delivery_sms'");
			$GLOBALS['tpl']->assign("delivery_notify",$delivery_notify);
			$content = $GLOBALS['tpl']->fetch("str:".$payment_sms_tmpl);

			$sendData = array();
			$sendData['dest'] = $mobile_phone_sms;
			$sendData['title'] = '';
			$sendData['content'] = $content;
			$sendData['create_time'] = a_gmtTime();
			$sendData['send_type'] = 1;  //短信
			$sendData['user_id'] = $user_id;
			$sendData['order_id'] = $delivery_vo['order_id'];//订单id 2012-6-1(chh)
	
			$GLOBALS['db']->autoExecute(DB_PREFIX."send_list",$sendData);					
    	}
		if(a_fanweC("MAIL_ON")==1&&a_fanweC("SEND_DELIVERY_MAIL")==1)
    	{
    		//$delivery_vo = M("OrderConsignment")->getById($delivery_id);==SQL优化==
    		$delivery_vo = $GLOBALS['db']->getRow("select order_id,delivery_code,express_id from ".DB_PREFIX."order_consignment where id =".$delivery_id);
    	
    		
    		//获取定单号
    		//$delivery_notify['order_sn'] = M("Order")->where("id=".$delivery_vo['order_id'])->getField("sn");==SQL优化==
    		$order = $GLOBALS['db']->getRow("select sn,user_id from ".DB_PREFIX."order where id =".$delivery_vo['order_id']);
    		$order_sn = $order['sn'];
    		$user_id = $order['user_id'];
    		    		
    		$delivery_notify['order_sn'] = $order_sn;
    		
    		//$user_id =  M("Order")->where("id=".$delivery_vo['order_id'])->getField("user_id");==SQL优化==
    		//$user = D("User")->getById($user_id);==SQL优化==
			$user = $GLOBALS['db']->getRow("select u.email,u.user_name from ".DB_PREFIX."order as o left join ".DB_PREFIX."user as u on o.user_id = u.id where o.id = ".$delivery_vo['order_id']);
    		
    		
			$delivery_notify['delivery_code'] = $delivery_vo['delivery_code'];
			$delivery_notify['delivery_name'] = $GLOBALS['db']->getOneCached("select `name` from ".DB_PREFIX."express where id='".$delivery_vo['express_id']."'");
			
			//模板解析
			//$payment_tmpl = M("MailTemplate")->where("name='delivery_mail'")->find();==SQL优化==
			$payment_tmpl = $GLOBALS['db']->getRow("select mail_title,mail_content from ".DB_PREFIX."mail_template where name ='delivery_mail'");
			$GLOBALS['tpl']->assign("delivery_notify",$delivery_notify);
			$content = $GLOBALS['tpl']->fetch("str:".$payment_tmpl['mail_content']);;

			$sendData = array();
			$sendData['dest'] = $user['email'];
			$sendData['title'] = $payment_tmpl['mail_title'];
			$sendData['content'] = $content;
			$sendData['create_time'] = a_gmtTime();
			$sendData['send_type'] = 0;  
			$sendData['user_id'] = $user_id;
			$sendData['order_id'] = $delivery_vo['order_id'];//订单id 2012-6-1(chh)
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."send_list",$sendData);					
    	}
	}
	
	function Suppliers_goodslist()
	{
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
		
        		
		$data = array(
    		'navs' => array(
    			array('name'=>a_L("SUPPLIER_ORDER_LIST"),'url'=>a_u("Suppliers/editgroup"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);

    	
    	//初始化分页
    	$page = intval($_REQUEST["p"]);
    	if($page==0)
    		$page = 1;
    	$limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".($page*a_fanweC("PAGE_LISTROWS"));
    	
    	$time = a_gmtTime();
		
		$sql = "select a.id,a.name_1, a.type_id,a.is_group_fail, a.goods_short_name, a.market_price,a.shop_price, b.name as city_name, a.promote_begin_time, a.promote_end_time,a.buy_count,a.group_user, a.virtual_count, ".
		
				"(select count(*) from ".DB_PREFIX."group_bond where goods_id = a.id and use_time > 0) as ysf_count, a.buy_count - a.virtual_count as b_count from ".DB_PREFIX."goods as a left join ".DB_PREFIX."group_city as b on b.id = a.city_id where a.suppliers_id = ".intval($_SESSION['suppliers_id'])." order by a.id desc limit ".$limit;
    	$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$goods)
		{

			if (intval($goods['promote_begin_time']) > $time){//团购时间未开始
				$str = "团购未开始";
			}else{
				$goods['buy_count'] = $goods['buy_count'] - intval($data['virtual_count']); 		
				//团购时间到期 或 团购时间到期前，已经成功团购
				if((intval($goods['promote_end_time']) < $time) || (($goods['is_group_fail'] == 2) && ($goods['buy_count'] >= $goods['group_user'])))
				{
					if (($goods['type_id'] == 0 || $goods['type_id'] == 2 || $goods['type_id'] == 3) && ($goods['buy_count'] >= $goods['group_user']) && ($goods['buy_count'] > 0))
					{
						//团购时间到期
						if(intval($goods['promote_end_time']) < $time)
							$str = "团购结束(成功)<br/>";
						else
							$str = "团购成功(未结束)<br/>";	
					}
					else
					{
						//团购时间结束，且购买人数大于最低购买人数，则团购结束（成功）
						if (intval($goods['promote_end_time']) < $time && ($goods['buy_count'] == 0))//团购结束，未有人购买
							$str = "团购结束(失败)"; 				
						else if(intval($goods['promote_end_time']) < $time && ($goods['buy_count'] >= $goods['group_user']))
							$str = "团购结束(成功)";
						else if (intval($goods['promote_end_time']) < $time && ($goods['buy_count'] < $goods['group_user']))//团购时间期
						{
							$str = "团购结束(失败)"; 
						}	
						else if($goods['is_group_fail'] == 0)
							$str = "团购进行中";
						else
							$str =  "团购进行中，已成功";
					}
				}else if (intval($goods['promote_end_time']) >= $time){//团购时间未到期
					$str = "团购进行中";
				}				
			}

			$list[$k]['goods_state'] = $str;
		}
    	
    	$sql = "select count(*) from ".DB_PREFIX."goods as a left join ".DB_PREFIX."group_city as b on b.id = a.city_id where a.suppliers_id = ".intval($_SESSION['suppliers_id'])." order by a.id desc ";
    	$count = $GLOBALS['db']->getOne($sql);
    	
		//分页
		$page = new Pager($count,a_fanweC("PAGE_LISTROWS"));   //初始化分页对象 		
		$p  =  $page->show();
		
		$GLOBALS['tpl']->assign('pages',$p);
		
		$GLOBALS['tpl']->assign('goods_list',$list);
		
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
    	$GLOBALS['tpl']->assign("content_page",'groupbond');
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_goodslist.moban");	
	}	
	
	//导出会员列表
	function Suppliers_exportcsv(){
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0){
			Suppliers_login();
			exit;	
		}
				
		ini_set("memory_limit","150M"); 
		set_time_limit(0);
		
		$goods_id = intval($_REQUEST["goods_id"]);
		
		$sql = "select id from ".DB_PREFIX."goods where suppliers_id = ".intval($_SESSION['suppliers_id'])." and id =".$goods_id;
		$goods_id = intval($GLOBALS['db']->getOne($sql));
		
		$sql = "select * from  ".DB_PREFIX."group_bond where goods_id =".$goods_id;
		$list = $GLOBALS['db']->getAll($sql);
		$content = a_utf8ToGB("编号,序列号,团购名称,会员名称,过期时间,状态" . "\n");
		if($list)
		{
			//register_shutdown_function(array(&$this, 'exportcsv'), $page+1,$condition);
			//dump($sql);
	    	/* csv文件数组 */
	    	$groupBond_value = array('id'=>'""', 'sn'=>'""', 'goods_name'=>'""', 'user_name'=>'""','end_time'=>'""', 'use_time'=>'""');
			//if($page == 1)
			foreach($list as $k=>$v)
			{			
				$groupBond_value['id'] = a_utf8ToGB('"' . $v['id'] . '"');
				$groupBond_value['sn'] = a_utf8ToGB('"' . $v['sn'] . '"');
				//$groupBond_value['password'] = utf8ToGB('"' . $v['password'] . '"');
				//$groupBond_value['goods_id'] = utf8ToGB('"' . $v['goods_id'] . '"');
				$groupBond_value['goods_name'] = a_utf8ToGB('"' . $v['goods_name'] . '"');
				//$groupBond_value['order_id'] = utf8ToGB('"' . $v['order_id'] . '"');
				$groupBond_value['user_name'] = a_utf8ToGB('"' . $v['user_name'] . '"');
				//$groupBond_value['mobile_phone'] = utf8ToGB('"' . $v['mobile_phone'] . '"');
				$groupBond_value['end_time'] = a_utf8ToGB('"' . a_toDate($v['end_time'],'Y-m-d') . '"');
				if (intval($v['use_time']) == 0){
					$groupBond_value['use_time'] = a_utf8ToGB('"' . "未使用" . '"');
				}else{
					$groupBond_value['use_time'] = a_utf8ToGB('"' . "已使用" . '"');
				}
				$content .= implode(",", $groupBond_value) . "\n";
			}	
		}
		header("Content-Disposition: attachment; filename=group_bond_".a_toDate(a_gmtTime(),'Y-m-d').".csv");
		echo $content; 
	}

	function suppliers_couponbus()
	{	
		if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0)
			{
				Suppliers_login();
				exit;	
			}
		header("Content-Type:text/html; charset=utf-8");		
		$result = array("type"=>0,"msg"=>"");
		$time = a_gmtTime();
		$sn = trim($_REQUEST['sn']);
		$pwd = trim($_REQUEST['pwd']);
		
		$sql2="select goods_id from ".DB_PREFIX."group_bond where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
		$bond_id2=$GLOBALS['db']->getOne($sql2);
		if($bond_id2>0)
		{
			$sql3="select suppliers_id from ".DB_PREFIX."goods where suppliers_id=".$_SESSION['suppliers_id']." and id=".$bond_id2."";
			$good_suppliers_id=$GLOBALS['db']->getOne($sql3);
			if(!$good_suppliers_id)
			{
				$result['type'] = 2;
				echo json_encode($result);
				exit();
			}
		}
		$sql = "update ".DB_PREFIX."group_bond set use_time = ".$time .",depart_id = ".$_SESSION["depart_id"]." ,is_balance = 1 where is_valid = 1 and status = 1 and use_time = 0 and end_time >".$time ." and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
		
		$GLOBALS['db']->query($sql);
		$is_updated = $GLOBALS['db']->affected_rows();
		
		if($is_updated >0)
		{
			$bond_id= $GLOBALS['db']->getOne("select id from ".DB_PREFIX."group_bond where password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'");
			require ROOT_PATH.'app/source/func/com_send_sms_func.php';
			s_send_groupbond_use_sms($bond_id,true);
			$sql = "select goods_name from ".DB_PREFIX."group_bond where is_valid = 1 and status = 1 and password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'";
			$result['msg'] = $GLOBALS['db']->getOne($sql);
			$result['type'] = 1;
		}
		else{
			$result['msg']= $GLOBALS['db']->getOne("select use_time,goods_name,end_time from ".DB_PREFIX."group_bond where password = '".addslashes($pwd)."' and sn = '".addslashes($sn)."'");
			$result['type'] = 0;
		}
			
		echo json_encode($result);
		exit();
	}
        function Suppliers_balance()
	{		
                 if (empty($_SESSION['suppliers_id']) || intval($_SESSION['suppliers_id']) == 0)
			{
				Suppliers_login();
				exit;	
			}
		$account_id = intval($s_account_info['id']);		
		$GLOBALS['tpl']->assign("page_title",'结算报表');
		$goods_id = intval($_REQUEST['goods_id']);		
		$is_balance = intval($_REQUEST['is_balance']);
                $param = null; //page $parameter = array();
                if($goods_id){
                    $param = '&'.'goods_id='.$goods_id;
                    }
		
                
//		if($_REQUEST['is_redirect']==1)
//		{
//			$url_param=array("deal_id"=>$deal_id,"is_balance"=>$is_balance);
//			app_redirect(url("biz","balance",$url_param));
//		}
		$sql = "select * from ".DB_PREFIX."goods  where id = ".$goods_id." and suppliers_id=".intval($_SESSION['suppliers_id']);
		$deal_info = $GLOBALS['db']->getRow($sql);
                 //var_dump($deal_info);exit;
		//==========
		
		$GLOBALS['tpl']->assign("is_balance",$is_balance);
		
		if($deal_info)
		{			
			$page = intval($_REQUEST["p"]);
                        //var_dump($page);exit;
                        if($page==0)
                        $page = 1;
                        $limit = ($page-1)*a_fanweC("PAGE_LISTROWS").",".($page*a_fanweC("PAGE_LISTROWS"));

			$GLOBALS['tpl']->assign("deal_info",$deal_info);
                        //var_dump($deal_info);exit;
			if($deal_info['type_id']==0)
			{		
				if($is_balance==2)
				{
					$sort = " order by balance_time desc ";
				}	
                                elseif($is_balance==1)
                                {
                                    $sort = " order by use_time desc ";
                                }
				else
				{
					$sort = " order by id desc ";
				}	
                                //var_dump($deal_info['id']);exit;
				$condition = " goods_id = ".$deal_info['id']." and status = 1 and user_id > 0 and is_valid = 1 ";
				$dataList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."group_bond where ".$condition." and is_balance = ".$is_balance.$sort." limit ".$limit);
				$dataTotal = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."group_bond where ".$condition." and is_balance = ".$is_balance);
                                //$result = getSuppliersGroupBondList(intval($_SESSION['suppliers_id']), $sn, $status,$page,$id,$goods_id);
				foreach($dataList as $k=>$v)
				{       
                                        $dataList[$k]['use_time'] = a_toDate($v['use_time'],'Y-m-d  H:i:s');
                                        $dataList[$k]['balance_time'] = a_toDate($v['balance_time'],'Y-m-d  H:i:s');
                                        $dataList[$k]['profit'] = a_formatPrice($v['profit']);
					$dataList[$k]['name'] = $GLOBALS['db']->getOne("select data_name from ".DB_PREFIX."order_goods where id = ".$v['order_goods_id']);
					if(!$dataList[$k]['name'])
					$dataList[$k]['name'] = $deal_info['goods_name'];
                                       	$is_order_sms = $GLOBALS['db']->getOne("select is_order_sms from ".DB_PREFIX."goods where id=".$v['goods_id']."");
                                        if($is_order_sms)
                                        {
                                                $order_goods_number=$GLOBALS['db']->getOne("select number from ".DB_PREFIX."order_goods where order_id=(select id from ".DB_PREFIX."order where sn=".$v["order_id"].") and rec_id=".$v['goods_id']."");
                                                $dataList[$k]['order_goods_number']=$order_goods_number;
                                        }
                                         else {
                                                $dataList[$k]['order_goods_number'] = 1;
                                          }


				}				
				
				$totalBalance0 = $GLOBALS['db']->getOne("select sum(profit) from ".DB_PREFIX."group_bond where ".$condition." and is_balance = 0");
				$totalBalance1 = $GLOBALS['db']->getOne("select sum(profit) from ".DB_PREFIX."group_bond where ".$condition." and is_balance = 1");
				$totalBalance2 = $GLOBALS['db']->getOne("select sum(profit) from ".DB_PREFIX."group_bond where ".$condition." and is_balance = 2");
				
				$totalBalancesum = $totalBalance0 + $totalBalance1 ;//未结算
                                $totalBalance0 = a_formatPrice($totalBalancesum);
                                $totalBalance1 = a_formatPrice($totalBalance1); //待结算
                                $totalBalance2 = a_formatPrice($totalBalance2); //已结算
				$GLOBALS['tpl']->assign("totalBalance0",$totalBalance0);
				$GLOBALS['tpl']->assign("totalBalance1",$totalBalance1);
				$GLOBALS['tpl']->assign("totalBalance2",$totalBalance2);
				
				$GLOBALS['tpl']->assign ( 'dataList', $dataList );
				$page = new Pager($dataTotal,a_fanweC("PAGE_LISTROWS"),$param);   //初始化分页对象 		
				$p  =  $page->show();
				$GLOBALS['tpl']->assign('pages',$p);
				//团购券结算
				
				$html = $GLOBALS['tpl']->fetch("Inc/suppliers/suppliers_balance_coupon.moban");
				$GLOBALS['tpl']->assign("html",$html);
				
			}
			else
			{
				if($is_balance==2)
				{
					$sort = " order by balance_time desc ";
				}	
				else
				{
					$sort = " order by id desc ";
				}	
				$condition = " rec_id = ".$deal_info['id']." ";
				$dataList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."order_goods where ".$condition." and is_balance = ".$is_balance.$sort."  limit ".$limit);
                                
                                foreach($dataList as $k=>$v)
				{       
                                        $dataList[$k]['balance_time'] = a_toDate($v['balance_time'],'Y-m-d  H:i:s');
                                        $dataList[$k]['balance_total_price'] = a_formatPrice($v['balance_total_price']);
				}
                                
                                
				$dataTotal = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."order_goods where ".$condition." and is_balance = ".$is_balance);
                                
                                $totalBalance0 = $GLOBALS['db']->getOne("select sum(balance_total_price) from ".DB_PREFIX."order_goods where ".$condition." and is_balance = 0");
				$totalBalance1 = $GLOBALS['db']->getOne("select sum(balance_total_price) from ".DB_PREFIX."order_goods where ".$condition." and is_balance = 1");
				$totalBalance2 = $GLOBALS['db']->getOne("select sum(balance_total_price) from ".DB_PREFIX."order_goods where ".$condition." and is_balance = 2");
                                //未结算	：未结算的加上待结算
                                $totalBalancesum = $totalBalance + $totalBalance1 ;//未结算
                                $totalBalance0 = a_formatPrice($totalBalancesum);
                                $totalBalance1 = a_formatPrice($totalBalance1); //待结算
                                $totalBalance2 = a_formatPrice($totalBalance2); //已结算
				$GLOBALS['tpl']->assign("totalBalance0",$totalBalance0);
				$GLOBALS['tpl']->assign("totalBalance1",$totalBalance1);
				$GLOBALS['tpl']->assign("totalBalance2",$totalBalance2);
				
				$GLOBALS['tpl']->assign ( 'dataList', $dataList );
                                $page = new Pager($dataTotal,a_fanweC("PAGE_LISTROWS"),$param);   //初始化分页对象 		
				$p  =  $page->show();
				$GLOBALS['tpl']->assign('pages',$p);
				
				$html = $GLOBALS['tpl']->fetch("Inc/suppliers/suppliers_balance_order.moban");
				$GLOBALS['tpl']->assign("html",$html);
				
				
			}
		}
		
		//=============	
                //帮助
                $GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->display("Inc/suppliers/suppliers_balance.moban");
	}
?>