<?php
//静态化时，有些公共静态值要返回，比如：城市ID
 function getCurrentCityID(){
 	
 	if(!empty($_REQUEST['cityname']))
	{
		$cityName = trim($_REQUEST['cityname']);
		$currentCity = $GLOBALS['db']->getRowCached("SELECT id,py FROM ".DB_PREFIX."group_city where py = '".$cityName."' and verify=1");
		if($currentCity){
			$_SESSION['user_info']['city_id'] = $currentCity['id'];
			$_SESSION['user_info']['cityName'] = $currentCity['py'];
			
			return $currentCity['id'];				
		}
	}
 	if(isset($_SESSION['user_info']['city_id']))
 		$cityID = intval($_SESSION['user_info']['city_id']);
 	else
 	{
		$user_info = unserialize(base64_decode($_REQUEST['s']));
		$cityID =  intval($$user_info['city_id']);
 	}
	
	if($cityID > 0){
		return $cityID;
	}
	
	$currentCity = $GLOBALS['db']->getRowCached("SELECT id,py FROM ".DB_PREFIX."group_city where is_defalut=1 and verify=1");
	setcookie('cityID',base64_encode(serialize($currentCity['id'])));	
	$_SESSION['cityID'] = $currentCity['id'];
	$_SESSION['cityName'] = $currentCity['py'];
		
	return $currentCity['id'];		
}

function initSession()
{
	$session_id = isset($_REQUEST['s'])? $_REQUEST['s'] : session_id();
	$GLOBALS['tpl']->assign("s",$session_id);
	
	if(!defined("SESSION_ID"))
		define("SESSION_ID",$session_id);
	
	$user_info = array();
	if(!is_file(ROOT_PATH.'Runtime/sessionid/'.$session_id.'.php'))
	{
		error_reporting(0);
		$user_info = file_get_contents(ROOT_PATH.'mobile/Runtime/sessionid/'.$session_id.'.php');
		$user_info = unserialize($user_info);
		error_reporting(1);
	}
    return  $user_info;
}
?>