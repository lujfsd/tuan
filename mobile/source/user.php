<?php
$ma = strtolower($_REQUEST['m'].'_'.$_REQUEST['a']);
$ma();
function user_login()
{
	$GLOBALS['tpl']->display("Page/user_login.html");
}
function user_dologin()
{
	$s = session_id();
		$data['email'] = trim($_POST['email']);
    	if($data['email']==''||trim($_POST['password'])=='')
    	{
    		if($data['email']=='')
    		$err = a_L("PLEASE_ENTER_EMAIL");
    		
    		if(trim($_POST['password'])=='')
    		$err = a_L("PLEASE_ENTER_PASSWORD");
    	}
    	else
    	{
	    	$uinfo = $GLOBALS['db']->getRow("select id,user_name,email,`user_pwd`,`status` from ".DB_PREFIX."user where email = '{$data['email']}'");
	    	if($uinfo)
	    	{
    			$password = md5(trim($_POST['password']));
	    		if($uinfo['user_pwd']!=$password)
	    		{
	    			$err = a_L("PASSWORD_ERROR");
	    		}
	    		else
	    		{
	    			if($uinfo['status'])
	    			{
		    			$err = '';
		    			$user_info = $uinfo;
		    			unset($user_info['user_pwd']);
		    			$GLOBALS['user_info'] =	$user_info;
		    			$GLOBALS['user_info']['city_id'] = C_CITY_ID;
		    			$_SESSION['user_info']=$user_info;
		    			error_reporting(0);
		    			$contents = serialize($user_info);
		    			file_put_contents(ROOT_PATH.'mobile/Runtime/sessionid/'.$s.'.php',$contents);
		    			error_reporting(1);
	    			}
	    			else
	    			{
	    				$err = sprintf(a_L("NO_ACTIVE_USER"),HTTP_URL);
	    			}
	    			
	    		}
	    	}
	    	else
	    	{
	    		$err = a_L("NO_EXIST_USER");
	    	}
    	}
    	
    	$GLOBALS['tpl']->assign("err",$err);
    	$GLOBALS['tpl']->assign("email",$data['email']);
    	if(intval($GLOBALS['user_info']['id'])==0)
    	{
    		//未登录成功
    		$GLOBALS['tpl']->display("Page/user_login.html");
    	}
    	else 
    	{  
    		redirect2("m.php?s=".$s);
    	}  
}
function user_dologinout()
{
	$s=isset($_REQUEST['s']) ? $_REQUEST['s'] : session_id();
	unset($_SESSION);
	unset($GLOBALS);
	session_destroy();
	@unlink(ROOT_PATH.'mobile/Runtime/sessionid/'.$s.'.php');
	redirect2("m.php");
}
?>