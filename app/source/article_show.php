<?php    	
    if ($_REQUEST ['m'] == 'Article' && $_REQUEST ['a'] == 'showByUname') {
	    $uname = $_REQUEST['uname'];
	    if($uname!='')
	    {
	    	$uname = rawurldecode($uname);
			$sql ="select id from ".DB_PREFIX."article where u_name='".$uname."'";
			$id =intval($GLOBALS['db']->getOneCached($sql));    	
	    }	
	}else{
		$id= intval($_REQUEST['id']);
	}    	
	
	if($id > 0)
	{
		$db->query("update ".DB_PREFIX."article set click_count=click_count+1 where id='{$id}' and status= 1");
		$cache_id = C_CITY_ID."_".$_REQUEST['m']."_".$_REQUEST['a']."#".md5("id-".$id);
		if(!$tpl->is_cached("Page/article_show.moban",$cache_id)){
			$row = $db->getRowCached("select * from ".DB_PREFIX."article where id='{$id}' and status= 1");
			if($row)
			{
				if($row['ref_link']!='')
				{
					redirect2($row['ref_link']);
					exit;
				}
				//输出主菜单
				$GLOBALS['tpl']->assign("main_navs",assignNav(2));
				//输出城市
				$GLOBALS['tpl']->assign("city_list",getGroupCityList());
				//输出帮助
				$GLOBALS['tpl']->assign("help_center",assignHelp());
				$GLOBALS['tpl']->assign("page_title",$row['name_1']);
				
				$cate_info = $db->getRowCached("select * from ".DB_PREFIX."article_cate where id='{$row['cate_id']}'");
				
				$navs = array('name'=>$row['name_1'],'url'=>a_U("Article/show","id-".$row['url']));
				$navs_cate = array('name'=>$cate_info['name_1'],'url'=>a_U("Article/index","id-".$cate_info['id']));
				
				$data = array(
	    			'navs' => array(
	    				$navs_cate,
	    				$navs,
	    			),
	    			'keyword'=>	$row['seokeyword_1']!=''?$row['seokeyword_1']:$cate_info['name_1'].",".$row['name_1'],
	    			'content'=>	$row['seocontent_1']!=''?$row['seocontent_1']:$cate_info['name_1'].",".$row['name_1'],
	    		);
	    		assignSeo($data);    
				
				$tpl->assign("article_info",$row);
			}
			else {
				a_error(a_L("NO_ARTICLE"));exit();
			}
		}
		$tpl->display("Page/article_show.moban",$cache_id);
	}
	else{
		a_error(a_L("NO_ARTICLE"));exit();
	}
?>