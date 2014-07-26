<?php
    if ($_REQUEST ['m'] == 'Index' && $_REQUEST ['a'] == 'unSubScribe') {
    	//退订
		$email = trim(urldecode($_REQUEST['email']));
		$sql = "delete from " . DB_PREFIX . "mail_address_list where mail_address = '{$email}'";
		if ($GLOBALS ['db']->query ( $sql )) {
			success(a_L ( "SUBSCRIBEBACK_SUCCESS" ),'',a_u("Index/index"));
		} else {
			a_error(a_L ( "SUBSCRIBEBACK_FAILED" ),'',a_u("Index/index"));
		}
		exit;	
	}

	$do = isset($_REQUEST['do']) ? $_REQUEST['do'] : "" ;
	if($do==="subScribe")
	{
		$noajax = intval($_REQUEST['noajax']);
		if(!check_referer())
		{
			if($noajax)
			{
				a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
			}
			else
			{
				echo a_L('_OPERATION_FAIL_');
				die();
			}
		}
		
		if(intval(a_gmtTime())-intval($_SESSION['set_maill_address']) < 15 ){
			if($noajax)
			{
				a_error($lang['HC_SUBMIT_TOO_FAST']);
			}
			else {
				echo $lang['HC_SUBMIT_TOO_FAST'];
			}
			exit();
		}
		$_SESSION['set_maill_address'] = a_gmtTime();
		
		$email = trim(urldecode($_REQUEST['uemail']));
		
		$city_id = intval($_REQUEST['cityid']);
		if(!empty($_REQUEST['othercity']))
		{
			$city_name = trim(urldecode(htmlspecialchars($_REQUEST['othercity'])));
			
			if($GLOBALS['db']->getOne("select count(*) as countx from ".DB_PREFIX."group_city where `name` = '{$city_name}'")>0)
			{
				$city_id = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."group_city where `name` = '".$city_name."'");
			}
			else
			{
				require ROOT_PATH.'app/source/class/Pinyin.class.php';
				$py = new Pinyin();
				$new_city_data['name'] = $city_name;
				$new_city_data['sort'] = $GLOBALS['db']->getOne("select max(id) from ".DB_PREFIX."group_city")+1;
				$py_name = $py->complie($city_name);
				if($GLOBALS['db']->getOneCached("select count(*) as countx from ".DB_PREFIX."group_city where py='".$py_name."'")>0)
				{
					$py_name.="_".$GLOBALS['db']->getOne("select count(*) as countx from ".DB_PREFIX."group_city where py='".$py_name."'")+1;
				}
				$new_city_data['py'] = $py_name;
				$GLOBALS['db']->autoExecute(DB_PREFIX."group_city",$new_city_data);
				$city_id = $GLOBALS['db']->insert_id();
			}
		}
		
		$city_status = intval($GLOBALS['db']->getOne("select `status` from ".DB_PREFIX."group_city where id = '".intval($city_id)."'"));
		
		$err = '';
		if($email=='')
		{
			$err = $lang["HC_PLEASE_ENTER_EMAIL"];
		}
		elseif(!a_checkEmail($email))
	   	{
	   		$err = $lang["HC_ENTER_WRONG_EMAIL"];
	   	}

		if($err!='')
		{
			if($noajax)
			{
				a_error($err);
			}
			else
			{
				echo $err;
			}
			exit;
		}
		
		$data['mail_address'] = $email;
		$data['city_id'] = $city_id;
		$data['status'] = 1;
		
		$mailAddress = $GLOBALS['db']->getOne("select count(*) as counx from ".DB_PREFIX."mail_address_list where mail_address = '{$email}'");
		if($mailAddress)
		{
			if($noajax)
				{
					$tpl->assign("title",a_L("HC_SUBSCRIBE_SUCCESS"));
					$success_str = sprintf(a_L("HC_YOU_WILL_RECEIVE_EMAIL"),$email,$GLOBALS['db']->getOneCached("select `name` from ".DB_PREFIX."group_city where id = '".intval($city_id)."'"));
					success($success_str);
				}
				else
				{
					echo $lang["SUBSCRIBE_SUCCESS"];
				}
				exit;
		}
		else
		{
			if ($_SESSION['user_id']<=0)
			{
				if($GLOBALS['db']->getOne("select count(*) as countx from ".DB_PREFIX."user where email='".$email."'")>0)
				{
					if($noajax)
					{
						success(a_L("HC_EMAIL_HAS_BEEN_REG"));
					}
					else
					{
						echo a_L("HC_EMAIL_HAS_BEEN_REG"); exit;
					}
				}
			}
			
			if($GLOBALS['db']->autoExecute(DB_PREFIX."mail_address_list",$data))
			{
				if($noajax)
				{
						//$tpl->assign("title",);
						$success_str = sprintf(a_L("HC_YOU_WILL_RECEIVE_EMAIL"),$email,$GLOBALS['db']->getOneCached("select `name` from ".DB_PREFIX."group_city where id = {$city_id}"));
						success($success_str,a_L("HC_SUBSCRIBE_SUCCESS"));
				}
				else
				{
					echo a_L("SUBSCRIBE_SUCCESS");
				}
				exit;
			}
		}
		
		
	}
	elseif($do=="unSubScribe")
	{
		if(!check_referer())
		{
			if($noajax)
			{
				a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
			}
			else
			{
				echo a_L('_OPERATION_FAIL_');
				die();
			}
		}
		
		if(intval(a_gmtTime())-intval($_SESSION['un_maill_address']) < 15 ){
			if($noajax)
			{
				a_error(a_L('HC_SUBMIT_TOO_FAST'));
			}
			else {
				echo a_L('HC_SUBMIT_TOO_FAST');
			}
			exit();
		}
		$_SESSION['un_maill_address'] = a_gmtTime();
		
		$email = trim(urldecode($_REQUEST['email']));
		
		if($email=='')
		{
			$err = $lang["HC_PLEASE_ENTER_EMAIL"];
		}
		elseif(!a_checkEmail($email))
	   	{
	   		$err = $lang["HC_ENTER_WRONG_EMAIL"];
	   	}
		
		//退订
		$sql = "delete from " . DB_PREFIX . "mail_address_list where mail_address = '{$email}'";
		if ($GLOBALS ['db']->query ( $sql )) {
			echo a_L ( "SUBSCRIBEBACK_SUCCESS" );
		} else {
			echo a_L ( "SUBSCRIBEBACK_FAILED" );
		}
		exit();
	}
		//输出当前页seo内容
    	$data = array(
    		'navs' => array(
    			array('name'=>a_L('HOME'),'url'=>a_u("Index/malllist"))
    		),
    		'keyword'=>	'',
    		'content'=>	'',
    	);
    	assignSeo($data);
    	
		if($currentCity['status'] == 0)
			$tpl->assign("ismalllist",1);
		
		if(!$_REQUEST['cityname'])
		{
			$tpl->assign("nonecity",1);
		}
		
		$condition =" status =1 ";
		$now = a_gmtTime();
		$condition .= " and promote_end_time < {$now}";
		$condition .= " and city_id =".C_CITY_ID;
	
		$goods_list = getGoodsList($condition,2);
		$tpl->assign("goods_list",$goods_list);
		
		//输出主菜单
		$GLOBALS['tpl']->assign("main_navs",assignNav(2));
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		$GLOBALS['tpl']->assign("city_list1",getGroupCityList(true));
		
		//输出帮助
		$GLOBALS['tpl']->assign("help_center",assignHelp());
		
	$tpl->display("Page/maillist_index.moban",$cache_id);
?>