<?php
if(intval($_SESSION['user_id']) > 0)
{
	
	if(is_file(ROOT_PATH."Public/upload/avatar/avatar_big/".$_SESSION['user_id'].".jpg"))
	{
		$GLOBALS['tpl']->assign("post_user_face","Public/upload/avatar/avatar_big/".$_SESSION['user_id'].".jpg");
	}
}

$ma = $_REQUEST ['m'] . "_" . strtolower ( $_REQUEST ['a'] );
$ma ();

function forum_index()
{
	//初始化分页
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$PAGE_LISTROWS = 40;
	$result = getForumList("Forum",0,$page, C_CITY_ID,$_REQUEST['filter'],$_REQUEST['orderby'],$PAGE_LISTROWS);
	$viewBar = 0;
	foreach( $result ['list'] as $idx=>$v)
	{
		if((a_gmtTime()-intval($v['create_time'])) <=24*3600 )
			$result ['list'][$idx]['is_new'] = 1;
		
		if(preg_match("/<img.*>/",$v['content']))
			$result ['list'][$idx]['have_img'] = 1;
			
		if(intval($v['is_top'])==1)
		{
			$viewBar = $v['id'];
		}
	}
	
	$navs = array ('name' => a_L("FORUM_BOARD"), 'url' => a_u ( "Forum/index" ) );
	//输出当前页seo内容
	$data = array ('navs' => array ($navs ), 'keyword' => "", 'content' => "" );
	assignSeo ( $data );
	//分页
	$page = new Pager ( $result ['total'], $PAGE_LISTROWS ); //初始化分页对象	
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	
	$GLOBALS ['tpl']->assign ( "list", $result ['list'] );
	$GLOBALS ['tpl']->assign ( 'currentCityID', C_CITY_ID );
	$GLOBALS ['tpl']->assign ( 'viewBar', $viewBar );

	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS ['tpl']->display ( 'Page/forum_index.moban' );
}

function forum_add()
{
	$navs = array ('name' => a_L("FORUM_ADD"), 'url' => a_u ( "Forum/index" ) );
	//输出当前页seo内容
	$data = array ('navs' => array ($navs ), 'keyword' => "", 'content' => "" );
	assignSeo ( $data );
	
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS ['tpl']->display ( 'Page/forum_add.moban' );
}

function forum_view()
{
	//初始化分页
	$page = intval ( $_REQUEST ['p'] );
	if ($page == 0)
		$page = 1;
	
	$PAGE_LISTROWS = 20;
	$GLOBALS ['tpl']->assign ( 'PAGE_LISTROWS', $PAGE_LISTROWS );
	$limit = ($page-1)*$PAGE_LISTROWS.",".$PAGE_LISTROWS;
	
	$id = intval($_REQUEST['id']);
	if (check_ip_operation ( $_SESSION['CLIENT_IP'], "Forum", 5, $id )) //防刷五秒
		$GLOBALS['db']->query("update ".DB_PREFIX."message set click_count=click_count+1 where id='{$id}' and status = 1 ");
		
	$where = " (pid = {$id} or id ={$id}) and status = 1 and rec_module = 'Forum'";
	$order =" order by id asc,is_top asc,create_time desc";
	
	$sql = "select count(*) from ".DB_PREFIX."message where ".$where;
	$result['total'] = $GLOBALS['db']->getOne($sql);
	$sql = "select * from ".DB_PREFIX."message where ".$where." {$order} limit ".$limit;
	
	$result['list'] = $GLOBALS['db']->getAll($sql);
	foreach($result['list'] as $idx =>$v)
	{
		if(is_file(ROOT_PATH."Public/upload/avatar/avatar_big/".$v['user_id'].".jpg"))
			$result['list'][$idx]['user_face'] = "Public/upload/avatar/avatar_big/".$v['user_id'].".jpg";
		else
			$result['list'][$idx]['user_face'] = "";
	}
	if($result['list'])
		$navs = array ('name' => strip_tags($result['list'][0]['title']), 'url' => a_u ( "Forum/index" ) );
	else
		$navs = array ('name' => a_L("FORUM_BOARD"), 'url' => a_u ( "Forum/index" ) );
		
	$reply_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."message where pid='{$id}' and rec_module='Forum' and status = 1");
	
	$foruminfo = $GLOBALS['db']->getRowCached(" select * from ".DB_PREFIX."message where id='{$id}'");
	
	if(is_file(ROOT_PATH."Public/upload/avatar/avatar_big/".$foruminfo['user_id'].".jpg"))
		$foruminfo['user_face'] = "Public/upload/avatar/avatar_small/".$foruminfo['user_id'].".jpg";
	
	//输出当前页seo内容
	$data = array ('navs' => array ($navs ), 'keyword' => "", 'content' => "" );
	assignSeo ( $data );
	//分页
	$page = new Pager ( $result ['total'], $PAGE_LISTROWS ); //初始化分页对象	
	$p = $page->show ();
	$GLOBALS ['tpl']->assign ( 'pages', $p );
	$GLOBALS ['tpl']->assign ( 'foruminfo', $foruminfo );
	$GLOBALS ['tpl']->assign ( 'foruminfo', $foruminfo );
	$GLOBALS ['tpl']->assign ( "list", $result ['list'] );
	$GLOBALS ['tpl']->assign ( 'currentCityID', C_CITY_ID );
	$GLOBALS ['tpl']->assign('reply_count',$reply_count);
	//输出主菜单
	$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
	//输出城市
	$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
	//输出帮助
	$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
	$GLOBALS ['tpl']->display ( 'Page/forum_view.moban' );
}

function forum_insert()
{
	if(!check_referer())
	{
		a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
	}
	$user_id = intval($_SESSION['user_id']);
	if($user_id == 0)
	{
		a_error ( a_L ( "PLEASE_LOGIN" ), '', a_u ( "User/login" ) );
	}
	$pid = intval($_REQUEST['pid']);
	$user_name=$_SESSION['user_name'];
	$tg_title = htmlspecialchars ( $_REQUEST ['tg_title'], ENT_QUOTES );
	$tg_content = addslashes($_REQUEST['tg_content']);
	
	if($pid!=0)
	{
		$tg_title = $GLOBALS['db']->getOneCached("select `title` from ".DB_PREFIX."message where id={$pid} ");
		$tg_title = "[".a_l('REPLY')."]".strip_tags($tg_title);
	}
	
	if($tg_title=="")
	{
		a_error(a_L("HC_PLEASE_ENTER_TITLE"),"","back");
	}
	if($tg_content=="")
	{
		a_error(a_L("CONTENT_EMPTY"),"","back");
	}
	$msgData['title'] = $tg_title;
	$msgData['content'] = $tg_content;
	$msgData['user_name'] = $user_name;
	$msgData['user_id'] = $user_id;
	$msgData['pid'] = $pid;
	$msgData ['reply_type'] = $pid == 0 ? 0 : 1;
	$msgData ['create_time'] = a_gmtTime ();
	$msgData ['city_id'] = C_CITY_ID;
	$msgData ['status'] = a_fanweC ( "MESSAGE_AUTO_CHECK" );
	$msgData ['user_email'] = $_SESSION ['user_email'];
	$msgData ['rec_module'] = "Forum";
	if (check_ip_operation ( $GLOBALS ['client_ip'], "Forum", 5 )) {
		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "message",  $msgData , 'INSERT' );
		if($pid!=0)
			success ( a_l ( "HC_PUBLIC_SUCCESS" ) );
		else
			success ( a_l ( "HC_PUBLIC_SUCCESS" ),"",a_u("Forum/index") );
			
	} else {
		a_error ( a_l ( "HC_SUBMIT_TOO_FAST" ) );
	}
}

?>