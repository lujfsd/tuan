<?php	
	if(a_fanweC("SHOP_REG_CLOSED")==1)
	{
		a_error(a_L("SHOP_REG_CLOSED"));
	}
    	
		$extend_fields = $GLOBALS['db']->getAllCached("SELECT * FROM ".DB_PREFIX."user_field where is_show = 1 order by sort desc");
		foreach ($extend_fields as $k=>$v)
		{
			$extend_fields[$k]['val_scope'] = explode(",",$v['val_scope']);
		}
		$GLOBALS['tpl']->assign("extend_fields",$extend_fields);
						
		//输出当前页seo内容
    	$data = array(
    		'navs' => array(
    			array('name'=>a_L('USER_REGISTER'),'url'=>a_u("User/register"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
		assignSeo($data);
		
		$sql = "select id from ".DB_PREFIX."article where type=1 and status=1";
		$id = $GLOBALS['db']->getOneCached($sql);
		$agreement_url = a_u("Article/show", 'id-'.$id);
		
		$city_list = getGroupCityList(false,0,null);
		$GLOBALS['tpl']->assign("city_list",$city_list);
		$GLOBALS['tpl']->assign("agreement_url",$agreement_url);
	   	$GLOBALS['tpl']->assign('redirect',$_SERVER['HTTP_REFERER']); 
	   	$GLOBALS['tpl']->assign('goods_id',$_REQUEST['id']);
	   	//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS['tpl']->assign("city_list1",getGroupCityList(true));
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		$GLOBALS['tpl']->display('Inc/user/register.moban');	    
?>