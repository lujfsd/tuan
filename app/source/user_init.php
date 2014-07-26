<?php
//首次访问跳转到城市选择
if(!isset($_REQUEST['act']) && intval(a_fanweC("FIRST_VISIT_CITY"))==1&&!isset($_COOKIE['had_select_city'])&&!isset($_REQUEST['cityname']) && strtolower($_REQUEST['m'])=="index"){
	redirect2(a_u("City/more"));
}

//获取当前城市跟客户端IP
if(!isset($_SESSION['CLIENT_IP']) || empty($_SESSION['C_CITY_ID']) ||(isset($_REQUEST['cityname']) && !empty($_REQUEST['cityname'])))
{
	require ROOT_PATH.'app/source/class/IpLocation.class.php';
	define("C_CITY_ID",getCurrentCityID());
	$_SESSION['C_CITY_ID'] = C_CITY_ID;
	
	$iplocation = new IpLocation();
	$client_ip = $iplocation->getIP();
	$_SESSION['CLIENT_IP'] = $client_ip;
	
	if(intval(a_fanweC("FIRST_VISIT_CITY"))==1&&isset($_REQUEST['cityname']) && !isset($_COOKIE['had_select_city'])){
		setcookie('had_select_city',true,time()+365*60*60*24);
	}
}

if((!defined('C_CITY_ID')) || intval(C_CITY_ID) == 0) {
	define("C_CITY_ID",$_SESSION['C_CITY_ID']);
}

//返利
if (isset($_GET['ru']) && !empty($_GET['ru']))
{
	$parent_id = intval($_GET['ru']);		
	if(a_fanweC("REFERRALS_IP_LIMIT")==0 ||$GLOBALS['db']->getOne("SELECT last_ip FROM ".DB_PREFIX."user WHERE id=".$parent_id) != $client_ip)
	{
		setcookie('referrals_uid',base64_encode(serialize($parent_id)));
		$_SESSION['referrals_uid'] = $parent_id;	
	}
}	 
//开始自动登录 by hc
if($_SESSION['user_id'] == 0 && isset($_COOKIE['email']) && isset($_COOKIE['password']))
{
	$cookie_user['email'] = trim(unserialize(base64_decode($_COOKIE['email'])));
	$cookie_user['user_pwd'] = trim(unserialize(base64_decode($_COOKIE['password'])));	
	$userinfo = $GLOBALS['db']->getRow("SELECT `id`,`user_name`,`user_pwd`,`status`,`group_id`,`city_id`,`parent_id` FROM ".DB_PREFIX."user WHERE email='".$cookie_user['email']."' and user_pwd='".$cookie_user['user_pwd']."'");
				
	if($userinfo && $userinfo['status'])
	{
		setcookie('email',base64_encode(serialize($userinfo['email'])),time()+365*60*60*24);
		setcookie('password',base64_encode(serialize($userinfo['user_pwd'])),time()+365*60*60*24);
		$_SESSION['user_name'] = $userinfo['user_name'];
		$_SESSION['user_id'] = $userinfo['id'];
		$_SESSION['group_id'] = $userinfo['group_id'];
		$_SESSION['user_email'] = $userinfo['email'];
		$_SESSION['score'] = $userinfo['score'];
						
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user set last_ip='".$client_ip."' where id=".$userinfo['id']);
	}
}

  	
  $code = a_fanweC("INTEGRATE_CODE");
  $fanwe_user_id = intval(unserialize(base64_decode($_COOKIE['fanwe_user_id'])));
  $user_id =  intval($_SESSION['user_id']);
  if (empty($code)) $code = 'fanwe';

  if (($user_id == 0 or $user_id <> $fanwe_user_id) && $code == 'ucenter' && $fanwe_user_id > 0){
    $userinfo = $GLOBALS['db']->getRow("SELECT `id`,`user_name`,`user_pwd`,`last_ip`,`group_id`,`city_id` FROM ".DB_PREFIX."user where id = '$fanwe_user_id'");
	$_SESSION['user_name'] = $userinfo['user_name'];
	$_SESSION['user_id'] = $userinfo['id'];
	$_SESSION['group_id'] = $userinfo['group_id'];
	$_SESSION['user_email'] = $userinfo['email'];
	$_SESSION['score'] = $userinfo['score']; 
  }
         			
  if ($user_id > 0 && $code == 'ucenter' && $fanwe_user_id == 0){
  	 unset($_SESSION['user_name']);
	 unset($_SESSION['user_id']);
	 unset($_SESSION['group_id']);
	 unset($_SESSION['user_email']);
	 unset($_SESSION['other_sys']);
	 setcookie("email",null);
	 setcookie("password",null);
	 setcookie("fanwe_user_id",null);
   } 
   //保存来路
if(!isset($_COOKIE['referer_url']))
{	
	if(!preg_match("/".urlencode(a_getDomain().ROOT_PATH)."/",urlencode($_SERVER["HTTP_REFERER"])))
	setcookie("referer_url",$_SERVER["HTTP_REFERER"]);
}
$referer = htmlspecialchars(trim(addslashes($_COOKIE['referer_url'])));
//静态化时，有些公共静态值要返回，比如：城市ID
 function getCurrentCityID(){
		
	if(!empty($_REQUEST['cityname']))
	{
		$cityName = trim($_REQUEST['cityname']);
		$currentCity = $GLOBALS['db']->getRowCached("SELECT id,py FROM ".DB_PREFIX."group_city where py = '".$cityName."' and verify=1 and status =1");
		if($currentCity){
			setcookie('cityID',base64_encode(serialize($currentCity['id'])));
			$_SESSION['cityID'] = $currentCity['id'];
			$_SESSION['cityName'] = $currentCity['py'];
			
			return $currentCity['id'];				
		}
	}
		
	$cityID = intval($_SESSION["cityID"]);
	if($cityID > 0){
		return $cityID;
	}
				
	if ($cityID==0){
		$cityID = intval(unserialize(base64_decode($_COOKIE['cityID'])));			
	}
	
 	//动态定位
 	if($cityID==0)
	{			
		//$ip =  get_ip();
		$iplocation = new IpLocation();
		$ip = $iplocation->getIP();
		$address=$iplocation->getaddress($ip);
			
		$city_list = $GLOBALS['db']->getAllCached("SELECT id,name FROM ".DB_PREFIX."group_city where status =1 and verify = 1 order by pid desc");
		foreach ($city_list as $city)
		{
			//if(@strpos($address['area1'],$city['name']))
			if(strstr($address['area1'],$city['name'])!=false||strstr($city['name'],$address['area1'])!=false)
			{
				$city_sub = $GLOBALS['db']->getRow("SELECT id,name,py FROM ".DB_PREFIX."group_city where status =1 and verify= 1 and pid =".$city['id']." order by sort");
				if ($city_sub){
					$cityID = $city_sub['id'];	
				}else{
					$cityID = $city['id'];	
				}				
				break;
			}
		}
	}
	
	if($cityID > 0)
		$currentCity = $GLOBALS['db']->getRowCached("SELECT id,py FROM ".DB_PREFIX."group_city where id = $cityID and verify=1 and status =1");
		
	if(empty($currentCity))
		$currentCity = $GLOBALS['db']->getRowCached("SELECT id,py FROM ".DB_PREFIX."group_city where is_defalut=1 and verify=1 and status =1");

	setcookie('cityID',base64_encode(serialize($currentCity['id'])));	
	$_SESSION['cityID'] = $currentCity['id'];
	$_SESSION['cityName'] = $currentCity['py'];
		
	return $currentCity['id'];		
}  
		
?>