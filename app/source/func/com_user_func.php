<?php
	/**
	 * 初始化会员数据整合类
	 *
	 * @access  public
	 * @return  object
	 */
	function &init_users3() {
		$set_modules = false;
		static $cls = null;
		if ($cls != null) {
			return $cls;
		}
		$code = a_fanweC ( 'INTEGRATE_CODE' );
		if (empty ( $code ))
			$code = 'fanwe';
		$code = $code . '3';
		include_once (VENDOR_PATH . 'integrates3/' . $code . '.php');
		$cfg = unserialize ( a_fanweC ( 'INTEGRATE_CONFIG' ) );
		$cls = new $code ( $cfg );

		return $cls;
	}

	function user_do_login($user_data) {
		$redirect = trim ( $_REQUEST ['redirect'] );
		if ($user_data){
			$data = $user_data;;
			$is_login = 1;
			$code = a_fanweC ( 'INTEGRATE_CODE' );
			if($code =='ucenter'){
			$users = &init_users3 ();
			$users->login( '', '', '',$data['ucenter_id'] );
			}
		
		}else{
			if(!check_referer())
			{
				a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
			}
			
			if (a_fanweC('REGISTER_VERIFY') == 1){
				if($_SESSION['smsSubscribe'] != md5($_POST['login_verify'])) {
					a_error(a_L('VERIFY_ERROR'),"","back");
				}
			}
			
			$data ['email'] = trim ( $_POST['email'] );
			$data ['user_pwd'] = trim ( $_REQUEST ['user_pwd'] );
			$password = trim ( $_REQUEST ['user_pwd'] );

			$auto_login = empty ( $_REQUEST ['auto_login'] ) ? 0 : 1;
			$is_cart = empty ( $_REQUEST ['is_cart'] ) ? 0 : 1;
			$goods_id = intval ( $_REQUEST ['goods_id'] );
			//$number = intval($_REQUEST['number']);


			if ($data ['email'] == '') {
				$err = a_L ( "HC_PLEASE_ENTER_EMAIL", "", "back" );
			} elseif (strlen ( $data ['user_pwd'] ) < 1) {
				$err = a_L ( "HC_PASSWORD_ERROR", "", "back" );
			}

			if ($err != '') {
				a_error ( $err, "", "back" );
			}

			$email = $data ['email'];
			if (strpos ( $email, '@' ) > 0 && strpos ( $email, '@' ) != strlen ( $email )) {
				if(!a_checkEmail($email))
				{
					a_error(a_L("HC_ENTER_WRONG_EMAIL"));
					exit();
				}
				$user_name = $GLOBALS ['db']->getOne ( "select user_name from " . DB_PREFIX . "user where email='" . $data ['email'] . "'" );
			} else {
				$user_name = $data ['email'];
				$data ['email'] = '';
				//$userinfo = $GLOBALS['db']->getRow("select id,user_name,email,user_pwd,status,group_id,score from ".DB_PREFIX."user where user_name='".$user_name."' limit 1");
			}

			$data ['user_pwd'] = md5 ( $data ['user_pwd'] );

			$users = &init_users3 ();

			$is_login = $users->login ( $user_name, trim ( $data['user_pwd'] ), $data ['email'] );

			$code = a_fanweC ( 'INTEGRATE_CODE' );
			if (empty ( $code ))
				$code = 'fanwe';

			if (! $is_login && $code = 'ucenter') { //整合登陆出错


				$sql = "SELECT id FROM " . DB_PREFIX . "user WHERE user_name ='" . $user_name . "' and user_pwd ='" . md5 ( $password ) . "'";
				$user_id = intval ( $GLOBALS ['db']->getOne ( $sql ) );

				if ($user_id == 0) { //判断是否是最土过来用户
					$SECRET_KEY = '@4!@#$%@';
					$sql = "SELECT id FROM " . DB_PREFIX . "user WHERE user_name ='" . $user_name . "' and user_pwd ='" . md5 ( $password . $SECRET_KEY ) . "'";
					$user_id = intval ( $GLOBALS ['db']->getOne ( $sql ) );
				}

				if ($user_id == 0) { //
					$sql = "SELECT id FROM " . DB_PREFIX . "user WHERE user_name ='" . $user_name . "' and user_pwd ='" . md5(md5($password)) . "'";
					$user_id = intval ( $GLOBALS ['db']->getOne ( $sql ) );
				}
				$is_login = $user_id > 0;
			}
		}

		if (empty($redirect) || (strpos ( $redirect, 'login' ) > 0) || (strpos ( $redirect, 'register' ) > 0) || (strpos ( $redirect, 'logout' ) > 0) || ($redirect == '')) {
		  $redirect = "http://".$_SERVER['HTTP_HOST'].__ROOT__;
		}
		if ($is_login) {
			//开始自动发放返利
			if (a_fanweC ( "AUTO_REFERRAL" ) == 1) {
				$sql = "select * from " . DB_PREFIX . "referrals where is_pay=0 and create_time<>0";
				$referrals = $GLOBALS ['db']->getAll ( $sql );
				foreach ( $referrals as $k => $v ) {
					if (a_gmtTime () - $v ['create_time'] >= a_fanweC ( "REFERRALS_LIMIT_TIME" ) * 3600) {
						s_payReferrals ( $v ['id'] );
					}
				}
			}
			
			if (empty ( $user_name )) {
				$userinfo = $GLOBALS ['db']->getRow ( "select id,user_name,email,user_pwd,status,group_id,score from " . DB_PREFIX . "user where email='" . $data ['email'] . "'" );
				$data ['user_name'] = $userinfo ['user_name'];
			} else {
				$userinfo = $GLOBALS ['db']->getRow ( "select id,user_name,email,user_pwd,status,group_id,score from " . DB_PREFIX . "user where user_name='" . $user_name . "'" );
				$data ['user_name'] = $userinfo ['user_name'];
			}

			//新浪微博登陆  add by chenfq 2011-03-16
			if (!empty($_REQUEST ['user_api_field_name']) && $_REQUEST ['user_api_field_value'] != '' ){
				//$sql_str = 'update ' . DB_PREFIX . 'user set '.$_REQUEST ['user_api_field_name'].'= ' . intval ($_REQUEST ['user_api_field_value']) . ' where id = ' . intval ( $userinfo ['id'] );
				$sql_str = "update " . DB_PREFIX . "user set ".$_REQUEST ['user_api_field_name']."= '" . $_REQUEST ['user_api_field_value']  . "' where id = " . intval ( $userinfo ['id'] ); // lin
				$GLOBALS ['db']->query ( $sql_str );
			}
			//$ucdata = isset($users->ucdata)? $users->ucdata : '';
			if ($userinfo ['status']) {
				//返利
				if(floatval(a_fanweC("LOGIN_SCORE")) != 0){
					$user_id=intval ( $userinfo ['id'] );
					require_once ROOT_PATH.'app/source/func/com_order_pay_func.php';
					$first_time = a_gmtTime();
					$first_time = a_toDate($first_time,"Y-m-d");
					$first_time =  a_strtotime($first_time);
					$last_time = $first_time + 24*3600;
					if(intval(a_fanweC("LOGIN_SCORE_CLS"))==0)
					{
						$m_count =  $GLOBALS['db']->getOne("select count(*) from ". DB_PREFIX . "user_score_log where `memo_1`='".a_L("LOGIN_SCORE")."' and create_time between '{$first_time}' and '{$last_time}' and rec_module = 'Login' and user_id='{$user_id}'");
						if(intval($m_count) == 0)
						{
							s_user_score_log($user_id,"User","Login",a_fanweC("LOGIN_SCORE"),a_L("LOGIN_SCORE"));
						}
					}
					else
					{
						$m_count =  $GLOBALS['db']->getOne("select count(*) from ". DB_PREFIX . "user_money_log where `memo_1`='".a_L("LOGIN_SCORE")."' and create_time between '{$first_time}' and '{$last_time}' and rec_module = 'Login' and user_id='{$user_id}'");
						if(intval($m_count) == 0)
						{
							s_user_money_log($user_id,"User","Login",a_fanweC("LOGIN_SCORE"),a_L("LOGIN_SCORE"));
						}
					}
				}
				
				if ($auto_login == 1) {
					setcookie ( 'email', base64_encode ( serialize ( $userinfo ['email'] ) ), time () + 365 * 60 * 60 * 24 );
					setcookie ( 'password', base64_encode ( serialize ( $userinfo ['user_pwd'] ) ), time () + 365 * 60 * 60 * 24 );
				}

				$userScore = intval ( $userinfo ['score'] );
				$userGroupID = intval ( $userinfo ['group_id'] );

				$sql = "select max_points from " . DB_PREFIX . "user_group where id = " . $userGroupID;
				$maxPoints = intval ( $GLOBALS ['db']->getOne ( $sql ) );
				$sql = "select id from " . DB_PREFIX . "user_group where min_points <= $userScore AND max_points > $userScore AND id <> $userGroupID AND min_points >= $maxPoints AND status = 1";
				$group_id = $GLOBALS ['db']->getOne ( $sql );
				if ($group_id > 0) {
					$userinfo ['group_id'] = $group_id;
				}
				unset($_SESSION['smsSubscribe']);//用户登录完后清空验证码  2012-10-18
				$_SESSION ['user_name'] = $userinfo ['user_name'];
				$_SESSION ['user_id'] = $userinfo ['id'];
				$_SESSION ['group_id'] = $userinfo ['group_id'];
				$_SESSION ['user_email'] = $userinfo ['email'];
				$_SESSION ['score'] = $userinfo ['score'];
				$sql_str = 'update ' . DB_PREFIX . 'user set last_ip = \'' . $_SESSION ['CLIENT_IP'] . '\',active_sn = \'\',group_id= ' . intval ( $userinfo ['group_id'] ) . ' where id = ' . intval ( $userinfo ['id'] );
				$GLOBALS ['db']->query ( $sql_str );
				//清空购买车
				$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "cart where session_id = '" . session_id () . "' or user_id =".intval($_SESSION ['user_id']));

				if ($code == 'ucenter') {
					setcookie ( 'fanwe_user_id', base64_encode ( serialize ( $userinfo ['id'] ) ), time () + 365 * 60 * 60 * 24 );
					//echo $ucdata;
					if ($goods_id > 0){
						success ( a_L ( "LOGIN_SUCCESS" ), a_L ( "LOGIN_SUCCESS" ), __ROOT__ . "/index.php?m=Cart&a=index&id=" . $goods_id);
					}else{
						success ( a_L ( "LOGIN_SUCCESS" ), a_L ( "LOGIN_SUCCESS" ), $redirect);
					}
				}
				elseif($_SESSION['go_url'])
				{
					success ( a_L ( "LOGIN_SUCCESS" ), a_L ( "LOGIN_SUCCESS" ),$_SESSION['go_url']);
				}
				else {
					
					if ($is_cart == 1) {
						//redirect2(__ROOT__."/index.php");
						redirect2 ( __ROOT__ . "/index.php?m=Cart&a=index&id=" . $goods_id );
					} else {
						//echo $redirect; exit;
						redirect2 ( $redirect );
					}
				}
			} else {
				//销毁Session
				unset ( $_SESSION ['user_name'] );
				unset ( $_SESSION ['user_id'] );
				unset ( $_SESSION ['group_id'] );
				unset ( $_SESSION ['user_email'] );
				unset ( $_SESSION ['other_sys'] );
				
				if($_SESSION['qid'])
				{
					unset ( $_SESSION ['qid'] );
				}
				if($_SESSION['qname'])
				{
					unset ( $_SESSION ['qname'] );
				}
				if($_SESSION['qmail'])
				{
					unset ( $_SESSION ['qmail'] );
				}
				unset ( $_COOKIE ['email'] );
				unset ( $_COOKIE ['password'] );
				unset ( $_COOKIE ['fanwe_user_id'] );
				

				setcookie ( "email", null );
				setcookie ( "password", null );
				setcookie ( "fanwe_user_id", null );
				//Cookie::delete('email');
				//Cookie::delete('password');
				//add by chenfq 2010-04-18 同时登陆/退出 论坛
				//Cookie::delete('fanwe_user_id');
				$users = &init_users3 ();
				$users->logout ();

				redirect2 ( __ROOT__ . "/index.php?m=User&a=no_verify&user_id=" . intval ( $userinfo ['id'] ) );
			}
		} else {
			//echo $_SERVER['HTTP_REFERER']; exit;
			a_error ( $users->error, $users->error, "back" );
		}
	}

	function user_logout() {
		//清空购买车
		$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "cart where session_id = '" . session_id () . "' or user_id =".intval($_SESSION ['user_id']));
		unset ( $_SESSION ['user_name'] );
		unset ( $_SESSION ['user_id'] );
		unset ( $_SESSION ['group_id'] );
		unset ( $_SESSION ['user_email'] );
		unset ( $_SESSION ['other_sys'] );
		unset ( $_SESSION ['token'] );
		if($_SESSION['qid'])
				{
					unset ( $_SESSION ['qid'] );
				}
				if($_SESSION['qname'])
				{
					unset ( $_SESSION ['qname'] );
				}
				if($_SESSION['qmail'])
				{
					unset ( $_SESSION ['qmail'] );
				}
				if($_SESSION['token'])
				{
					unset ( $_SESSION ['token'] );
				}
		setcookie ( "email", null );
		setcookie ( "password", null );
		setcookie ( "fanwe_user_id", null );

		$users = &init_users3 ();
		$users->logout ();
		$code = a_fanweC ( 'INTEGRATE_CODE' );
		if (empty ( $code ))
			$code = 'fanwe';
		if ($code == 'ucenter') {
			//echo $ucdata;
			success ( a_L ( "LOGOUT_SUCCESS" ), a_L ( "LOGOUT_SUCCESS" ), __ROOT__ );
		} else {
			redirect2 ( a_getDomain () . __ROOT__ . "/index.php" );
		}
	}

	function user_ajax_register() {
		$result  =  array();
		$result['status']  = 0;//0:错误1:注册成功2:注册成功，但邮件发送失败;3:验证码错误
		$result['info'] = '';
		$result['url'] = '';
		if(!check_referer())
		{
			$result['url'] = a_u("Index/index");
			$result['info'] = a_L('_OPERATION_FAIL_');
   			header("Content-Type:text/html; charset=utf-8");
   			echo json_encode($result);
			exit;
		}

		//用户注册完后清空验证码 chenfq 2011-03-09
		if (a_fanweC('REGISTER_VERIFY') == 1){
			if($_SESSION['smsSubscribe'] != md5($_REQUEST['verify'])) {
				$result['status']  = 3;
				$result['info'] = a_L('VERIFY_ERROR');
	   			header("Content-Type:text/html; charset=utf-8");
	   			echo json_encode($result);
				exit;
			}
		}
		//=====================
		
		//手机验证 chenfq 2011-03-09
		if (a_fanweC('REGISTER_MOBILE_VERIFY') == 1){
			$sql = "select id from ".DB_PREFIX."sms_mobile_verify where status = 0 and mobile_phone = '".trim($_REQUEST['mobile_phone'])."' and code = '".trim($_REQUEST['mobile_verify'])."'";
			if(intval($GLOBALS ['db']->getOne($sql)) == 0) {
				$result['status']  = 3;
				$result['info'] = a_L('XY_MOBILE').a_L('VERIFY_ERROR');
	   			header("Content-Type:text/html; charset=utf-8");
	   			echo json_encode($result);
				exit;
			}
		}
		//=====================		

		$redirect = trim ( $_REQUEST ['redirect'] );
		$cfm_password = trim ( $_REQUEST ['user_pwd_confirm'] );
		$data ['user_name'] = trim ( $_REQUEST ['user_name'] );
		$data ['user_pwd'] = trim ( $_REQUEST ['user_pwd'] );
		$data ['email'] = trim ( $_POST['email'] );
		$data ['city_id'] = trim ( $_REQUEST ['city_id'] );
		$data ['address'] = trim ( $_REQUEST ['address'] );
		$data ['mobile_phone'] = trim ( $_REQUEST ['mobile_phone'] );
		$data ['subscribe'] = empty ( $_REQUEST ['subscribe'] ) ? 0 : 1;
		$goods_id = intval ( $_REQUEST ['goods_id'] );

		if (empty($redirect) || (strpos ( $redirect, 'login' ) > 0) || (strpos ( $redirect, 'register' ) > 0) || ($redirect == '')|| (strpos ( $redirect, 'logout' ) > 0)) {
			$redirect = "http://".$_SERVER['HTTP_HOST'].__ROOT__;
		}

		$data ['city_id'] = intval ( $data ['city_id'] ) == 0 ? C_CITY_ID : $data ['city_id'];

		$userNameLength = (strlen ( $data ['user_name'] ) + mb_strlen ( $data ['user_name'], 'UTF8' )) / 2;
		$err = "";

		//开始相关验证
		if (a_fanweC ( "MOBILE_PHONE_MUST" ) == 1 && $data ['mobile_phone'] == '') {
			$err = a_L ( "HC_MOBILE_NUMBER_ERROR" );

		} elseif ($data ['email'] == '') {
			$err = a_L ( "HC_PLEASE_ENTER_EMAIL" );

		} elseif (!a_checkEmail( $data ['email'] )) {
			$err = a_L ( "HC_EMAIL_ERROR" );

		} elseif ($GLOBALS ['db']->getOne ( "select count(*) as num from " . DB_PREFIX . "user where email='" . $data ['email'] . "'" ) > 0) {
			//$err = sprintf ( a_L ( "HC_RESET_PWD_LINK_TIP" ), __ROOT__ . "/index.php?m=User&a=resetreq" );
			$err = $data ['email'].":".a_L ( "HC_ERR_EMAIL_EXISTS" );
		} elseif ($userNameLength < 4) {
			$err = a_L ( "HC_USER_NAME_TOO_SHORT" );

		} elseif ($userNameLength > 12) {
			$err = a_L ( "HC_USER_NAME_TOO_LONG" );

		} elseif ($GLOBALS ['db']->getOne ( "select count(*) as num from " . DB_PREFIX . "user where user_name='" . $data ['user_name'] . "'" ) > 0) {
			$err = a_L ( "HC_ERR_USERNAME_EXISTS" );

		} elseif (strlen ( $data ['user_pwd'] ) < 4) {
			$err = a_L ( "HC_USER_PASSWORD_TOO_SHORT" );

		} elseif ($data ['user_pwd'] != $cfm_password) {
			$err = a_L ( "HC_PASSWORD_CONFIRM_FAILED" );

		} elseif (! empty ( $data ['mobile_phone'] ) && ! preg_match ("/^(\d+)$/", $data['mobile_phone'])) {
			$err = a_L ( "HC_MOBILE_NUMBER_ERROR" );
		//preg_match ( "/^(13\d{9}|14\d{9}|15\d{9}|18\d{9})|(0\d{9}|9\d{8})$/", $data ['mobile_phone'] )
		} elseif ($GLOBALS ['db']->getOne ( "select count(*) as num from " . DB_PREFIX . "user where mobile_phone='" . $data ['mobile_phone'] . "' and mobile_phone<> '' and status = 1" ) > 0) {
			$err = a_L ( "HC_MOBILE_NUMBER_EXISTS" );

		}

		//开始验证扩展字段的数据
		//$extend_fields = M("UserField")->where("is_show=1")->findAll();
		$extend_fields = $GLOBALS ['db']->getAll ( "select id, is_must,field_name,field_show_name from " . DB_PREFIX . "user_field where is_show=1" );
		foreach ( $extend_fields as $kk => $vv ) {
			if ($vv ['is_must'] == 1) {
				if ($_REQUEST [$vv ['field_name']] == '') {
					$err = a_L ( "XY_PLEASE_ENTER" ) . $vv ['field_show_name'];
				}
			}
		}

		if ($err != '') {
			$result['info'] = $err;
   			header("Content-Type:text/html; charset=utf-8");
   			echo json_encode($result);
			exit;
		}

		$err = '';

		$code = a_fanweC ( 'INTEGRATE_CODE' );
		if (empty ( $code ))
			$code = 'fanwe';

		$users = & init_users3 ();
		$users->need_sync = false;
		$is_add = $users->add_user ( trim ( $_REQUEST ['user_name'] ), trim ( $_REQUEST ['user_pwd'] ), trim ( $_REQUEST ['email'] ) );

		if (! $is_add && $code == 'ucenter') {
			// 插入会员数据失败
			if ($users->error == ERR_USERNAME_NOT_ALLOW) {
				$err = a_L ( "HC_ERR_USERNAME_NOT_ALLOW" );
			} elseif ($users->error == ERR_USERNAME_EXISTS) {
				$err = a_L ( "HC_ERR_USERNAME_EXISTS" );
			} elseif ($users->error == ERR_EMAIL_NOT_ALLOW) {
				$err = a_L ( "HC_ERR_EMAIL_NOT_ALLOW" );
			} elseif ($users->error == ERR_EMAIL_EXISTS) {
				$err = a_L ( "HC_ERR_EMAIL_EXISTS" );
			}else{
				$err = $users->error;
			}
		} else if ($users->is_fanwe == 0) {
			$user_id_arr = $users->get_profile_by_name ( $_REQUEST ['user_name'] );
			$data ['ucenter_id'] = $user_id_arr ['id'];
		}

		if ($err != '') {
			$result['info'] = $err;
   			header("Content-Type:text/html; charset=utf-8");
   			echo json_encode($result);
			exit;
		}

		$data ['last_ip'] = $_SESSION['CLIENT_IP'];
		$data ['user_pwd'] = md5 ( $data ['user_pwd'] );
		$data ['parent_id'] = intval ( $_SESSION ['referrals_uid'] );
		$data ['score'] = intval ( a_fanweC ( "DEFAULT_SCORE" ) );
		$data ['status'] = a_fanweC ( 'USER_AUTO_REG' );
		$data ['create_time'] = a_gmtTime ();
		$data ['update_time'] = a_gmtTime ();
		$data ['group_id'] = a_fanweC ( "DEFAULT_USER_GROUP" );

		//新浪微博登陆  add by chenfq 2011-03-16
		if (!empty($_REQUEST ['user_api_field_name']) && $_REQUEST ['user_api_field_value'] != '' ){
			//$data [$_REQUEST ['user_api_field_name']] = intval($_REQUEST ['user_api_field_value']);
			$data [$_REQUEST ['user_api_field_name']] = $_REQUEST ['user_api_field_value'];	// lin

			$data ['status'] = 1; //不需要，再邮箱认证了
		}

		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user", addslashes_deep ( $data ), 'INSERT' );
		$rs = intval ( $GLOBALS ['db']->insert_id () );

		if ($rs > 0) {
			unset($_SESSION['smsSubscribe']); //用户注册完后清空验证码 chenfq 2011-03-09
			unset($_SESSION['mobile_verify']);
			$sql = "update ".DB_PREFIX."sms_mobile_verify set status = 1 where mobile_phone = '".$data ['mobile_phone']."'";
			$GLOBALS['db']->query($sql);
									
			//开始处理扩展字段的数据
			$extend_fields = $GLOBALS ['db']->getAll ( "select id, is_must,field_name,field_show_name from " . DB_PREFIX . "user_field where is_show=1" );
			foreach ( $extend_fields as $kk => $vv ) {
				$ext_data ['field_value'] = $_REQUEST [$vv ['field_name']];
				$ext_data ['field_id'] = $vv ['id'];
				$ext_data ['user_id'] = $rs;

				$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_extend", addslashes_deep ( $ext_data ), 'INSERT' );
			}
			if ($data ['subscribe'] == 1) {

				$mailaddress = $GLOBALS ['db']->getRow ( "select id, user_id, city_id, mail_address, status from " . DB_PREFIX . "mail_address_list where mail_address='" . $data ['email'] . "'" );
				if ($mailaddress) {
					$mailaddress ['user_id'] = $rs;
					$mailaddress ['city_id'] = $data ['city_id'];

					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "mail_address_list", addslashes_deep ( $mailaddress ), 'UPDATE', "id = " . intval ( $mailaddress ['id'] ) );
				} else {
					$mail_item = array ();
					$mail_item ['mail_address'] = $data ['email'];
					$mail_item ['city_id'] = $data ['city_id'];
					$mail_item ['user_id'] = $rs;
					$mail_item ['status'] = $data ['status'];
					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "mail_address_list", addslashes_deep ( $mail_item ), 'INSERT' );
				}
			} else {
				$mailaddress = $GLOBALS ['db']->getRow ( "select id, user_id, city_id, mail_address, status from " . DB_PREFIX . "mail_address_list where mail_address='" . $data ['email'] . "'" );
				if ($mailaddress) {
					$mailaddress ['user_id'] = $rs;
					$mailaddress ['city_id'] = $data ['city_id'];
					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "mail_address_list", addslashes_deep ( $mailaddress ), 'UPDATE', "id = " . intval ( $mailaddress ['id'] ) );
				}
			}
			$score = intval ( $data ['score'] );
			$money = floatval ( $data ['money'] );
			if ($money > 0)
				s_user_money_log ( $rs, $rs, 'User', $money, a_L ( "USER_ADD_USER" ), true );

			if ($score > 0)
				s_user_score_log ( $rs, $rs, 'User', $score, a_L ( "USER_ADD_USER" ), true );
			if ($data ['status']) {
				//开始自动登录
				$userinfo = $GLOBALS ['db']->getRow ( "select id,user_name,email,user_pwd,status,group_id,score from " . DB_PREFIX . "user where id=" . $rs );
				$ucdata = '';
				if ($userinfo ['status']) {
					$users->login ( $userinfo ['user_name'], trim ( $_REQUEST ['user_pwd'] ) );
					$_SESSION ['user_name'] = $userinfo ['user_name'];
					$_SESSION ['user_id'] = $userinfo ['id'];
					$_SESSION ['group_id'] = $userinfo ['group_id'];
					$_SESSION ['user_email'] = $userinfo ['email'];
					$_SESSION ['score'] = $userinfo ['score'];
					setcookie ( 'fanwe_user_id', base64_encode ( serialize ( $userinfo ['id'] ) ), time () + 30 * 60 * 60 * 24 );
					$GLOBALS ['db']->query ( "update " . DB_PREFIX . "user set last_ip = '" . $_SESSION ['CLIENT_IP'] . "' where id =" . $rs );
				}
				if ($goods_id > 0){
					$err = sprintf ( a_L ( "HC_REGISTER_SUCCESS" ), $userinfo ['email'] );
					$result['url'] = __ROOT__ . "/index.php?m=Cart&a=index&id=".$goods_id;
					//success ( sprintf ( a_L ( "HC_REGISTER_SUCCESS" ), $userinfo ['email'] ), a_L ( "REG_SUCCESS" ),  );
				}else{
					//success ( sprintf ( a_L ( "HC_REGISTER_SUCCESS" ), $userinfo ['email'] ), a_L ( "REG_SUCCESS" ), $redirect);

					$err = sprintf ( a_L ( "HC_REGISTER_SUCCESS" ), $userinfo ['email'] );
					$result['url'] = $redirect;
				}
				$result['status'] = 1;
			} else {
				if (! s_sendUserActiveMail ( $rs, SHOP_NAME )) {
					$err = sprintf ( a_L ( "HC_SEND_EAMIL_FAILED_TIP" ), $data ['email'] ) . a_fanweC ( 'SMTP_ACCOUNT' );
					$result['status'] = 2;
					$result['url'] = __ROOT__ . "/index.php";
					//a_error ( sprintf ( a_L ( "HC_SEND_EAMIL_FAILED_TIP" ), $data ['email'] ) . a_fanweC ( 'SMTP_ACCOUNT' ), a_L ( "HC_SEND_EMAIL_FAILED" ), __ROOT__ . "/index.php" );
				} else {
					$err = '';
					$result['status'] = 1;
					$result['url'] = __ROOT__ . "/index.php?m=User&a=rse_success&user_id=".intval( $rs );
				}
			}

			$result['info'] = $err;
   			header("Content-Type:text/html; charset=utf-8");
   			echo json_encode($result);
		} else{
			$result['info'] = a_L ( "REG_FAILED" );
   			header("Content-Type:text/html; charset=utf-8");
   			echo json_encode($result);
		}
	}


	function user_do_register() {
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}

		//用户注册完后清空验证码 chenfq 2011-03-09
		if (a_fanweC('REGISTER_VERIFY') == 1){
			if($_SESSION['smsSubscribe'] != md5($_POST['verify'])) {
				a_error(a_L('VERIFY_ERROR'),"","back");
			}
		}
		//=====================

		$redirect = trim ( $_POST ['redirect'] );
		$cfm_password = trim ( $_POST ['user_pwd_confirm'] );
		$data ['user_name'] = trim ( $_POST ['user_name'] );
		$data ['user_pwd'] = trim ( $_POST ['user_pwd'] );
		$data ['email'] = trim ( $_POST ['email'] );
		$data ['city_id'] = trim ( $_POST ['city_id'] );
		$data ['address'] = trim ( $_POST ['address'] );
		$data ['mobile_phone'] = trim ( $_POST ['mobile_phone'] );
		$data ['subscribe'] = empty ( $_POST ['subscribe'] ) ? 0 : 1;
		$goods_id = intval ( $_REQUEST ['goods_id'] );

		//新浪微博登陆  add by chenfq 2011-03-16
		if (!empty($_REQUEST ['user_api_field_name']) && $_REQUEST ['user_api_field_value'] != '' ){
			//$data [$_REQUEST ['user_api_field_name']] = intval($_REQUEST ['user_api_field_value']);
			$data [$_REQUEST ['user_api_field_name']] = $_REQUEST ['user_api_field_value'];	// lin
		}

		if (empty($redirect) || (strpos ( $redirect, 'login' ) > 0) || (strpos ( $redirect, 'register' ) > 0) || ($redirect == '')|| (strpos ( $redirect, 'logout' ) > 0)) {
			$redirect = "http://".$_SERVER['HTTP_HOST'].__ROOT__;
		}


		$data ['city_id'] = intval ( $data ['city_id'] ) == 0 ? C_CITY_ID : $data ['city_id'];

		$userNameLength = (strlen ( $data ['user_name'] ) + mb_strlen ( $data ['user_name'], 'UTF8' )) / 2;
		$err = "";

		//开始相关验证
		if (a_fanweC ( "MOBILE_PHONE_MUST" ) == 1 && $data ['mobile_phone'] == '') {
			$err = a_L ( "HC_MOBILE_NUMBER_ERROR" );

		} elseif ($data ['email'] == '') {
			$err = a_L ( "HC_PLEASE_ENTER_EMAIL" );

		} elseif (!a_checkEmail( $data ['email'] )) {
			$err = a_L ( "HC_EMAIL_ERROR" );

		} elseif ($GLOBALS ['db']->getOne ( "select count(*) as num from " . DB_PREFIX . "user where email='" . $data ['email'] . "'" ) > 0) {
			$err = sprintf ( a_L ( "HC_RESET_PWD_LINK_TIP" ), __ROOT__ . "/index.php?m=User&a=resetreq" );

		} elseif ($userNameLength < 4) {
			$err = a_L ( "HC_USER_NAME_TOO_SHORT" );

		} elseif ($userNameLength > 15) {
			$err = a_L ( "HC_USER_NAME_TOO_LONG" );

		} elseif ($GLOBALS ['db']->getOne ( "select count(*) as num from " . DB_PREFIX . "user where user_name='" . $data ['user_name'] . "'" ) > 0) {
			$err = a_L ( "HC_ERR_USERNAME_EXISTS" );

		} elseif (strlen ( $data ['user_pwd'] ) < 4) {
			$err = a_L ( "HC_USER_PASSWORD_TOO_SHORT" );

		} elseif ($data ['user_pwd'] != $cfm_password) {
			$err = a_L ( "HC_PASSWORD_CONFIRM_FAILED" );

		} elseif (! empty ( $data ['mobile_phone'] ) && ! preg_match ( "/^(\d+)$/", $data ['mobile_phone'] )) {
			$err = a_L ( "HC_MOBILE_NUMBER_ERROR" );
		//preg_match ( "/^(13\d{9}|14\d{9}|15\d{9}|18\d{9})|(0\d{9}|9\d{8})$/", $data ['mobile_phone'] )
		} elseif ($GLOBALS ['db']->getOne ( "select count(*) as num from " . DB_PREFIX . "user where mobile_phone='" . $data ['mobile_phone'] . "' and mobile_phone<> '' and status = 1" ) > 0) {
			$err = a_L ( "HC_MOBILE_NUMBER_EXISTS" );

		}

		//开始验证扩展字段的数据
		//$extend_fields = M("UserField")->where("is_show=1")->findAll();
		$extend_fields = $GLOBALS ['db']->getAll ( "select id, is_must,field_name,field_show_name from " . DB_PREFIX . "user_field where is_show=1" );
		foreach ( $extend_fields as $kk => $vv ) {
			if ($vv ['is_must'] == 1) {
				if ($_REQUEST [$vv ['field_name']] == '') {
					$err = a_L ( "XY_PLEASE_ENTER" ) . $vv ['field_show_name'];
				}
			}
		}

		if ($err != '') {
			a_error ( $err, "", "back" );
		}

		$code = a_fanweC ( 'INTEGRATE_CODE' );
		if (empty ( $code ))
			$code = 'fanwe';

		$users = & init_users3 ();
		$users->need_sync = false;
		$is_add = $users->add_user ( trim ( $_POST ['user_name'] ), trim ( $_POST ['user_pwd'] ), trim ( $_POST ['email'] ) );

		if (! $is_add && $code == 'ucenter') {
			// 插入会员数据失败
			if ($users->error == ERR_USERNAME_NOT_ALLOW) {
				a_error ( a_L ( "HC_ERR_USERNAME_NOT_ALLOW" ), "", "back" );
			} elseif ($users->error == ERR_USERNAME_EXISTS) {
				a_error ( a_L ( "HC_ERR_USERNAME_EXISTS" ), "", "back" );
			} elseif ($users->error == ERR_EMAIL_NOT_ALLOW) {
				a_error ( a_L ( "HC_ERR_EMAIL_NOT_ALLOW" ), "", "back" );
			} elseif ($users->error == ERR_EMAIL_EXISTS) {
				a_error ( a_L ( "HC_ERR_EMAIL_EXISTS" ), "", "back" );
			}else{
				a_error ($users->error, "", "back" );
			}
		} else if ($users->is_fanwe == 0) {
			$user_id_arr = $users->get_profile_by_name ( $_REQUEST ['user_name'] );
			$data ['ucenter_id'] = $user_id_arr ['id'];
		}

		$data ['last_ip'] = $_SESSION['CLIENT_IP'];
		$data ['user_pwd'] = md5 ( $data ['user_pwd'] );
		$data ['parent_id'] = intval ( $_SESSION ['referrals_uid'] );
		$data ['score'] = intval ( a_fanweC ( "DEFAULT_SCORE" ) );
		$data ['status'] = a_fanweC ( 'USER_AUTO_REG' );
		$data ['create_time'] = a_gmtTime ();
		$data ['update_time'] = a_gmtTime ();
		$data ['group_id'] = a_fanweC ( "DEFAULT_USER_GROUP" );

		$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user", addslashes_deep ( $data ), 'INSERT' );
		$rs = intval ( $GLOBALS ['db']->insert_id () );

		if ($rs > 0) {
			unset($_SESSION['smsSubscribe']); //用户注册完后清空验证码 chenfq 2011-03-09
			//开始处理扩展字段的数据
			$extend_fields = $GLOBALS ['db']->getAll ( "select id, is_must,field_name,field_show_name from " . DB_PREFIX . "user_field where is_show=1" );
			foreach ( $extend_fields as $kk => $vv ) {
				$ext_data ['field_value'] = $_REQUEST [$vv ['field_name']];
				$ext_data ['field_id'] = $vv ['id'];
				$ext_data ['user_id'] = $rs;

				$GLOBALS ['db']->autoExecute ( DB_PREFIX . "user_extend", addslashes_deep ( $ext_data ), 'INSERT' );
			}
			if ($data ['subscribe'] == 1) {

				$mailaddress = $GLOBALS ['db']->getRow ( "select id, user_id, city_id, mail_address, status from " . DB_PREFIX . "mail_address_list where mail_address='" . $data ['email'] . "'" );
				if ($mailaddress) {
					$mailaddress ['user_id'] = $rs;
					$mailaddress ['city_id'] = $data ['city_id'];

					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "mail_address_list", addslashes_deep ( $mailaddress ), 'UPDATE', "id = " . intval ( $mailaddress ['id'] ) );
				} else {
					$mail_item = array ();
					$mail_item ['mail_address'] = $data ['email'];
					$mail_item ['city_id'] = $data ['city_id'];
					$mail_item ['user_id'] = $rs;
					$mail_item ['status'] = $data ['status'];
					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "mail_address_list", addslashes_deep ( $mail_item ), 'INSERT' );
				}
			} else {
				$mailaddress = $GLOBALS ['db']->getRow ( "select id, user_id, city_id, mail_address, status from " . DB_PREFIX . "mail_address_list where mail_address='" . $data ['email'] . "'" );
				if ($mailaddress) {
					$mailaddress ['user_id'] = $rs;
					$mailaddress ['city_id'] = $data ['city_id'];
					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "mail_address_list", addslashes_deep ( $mailaddress ), 'UPDATE', "id = " . intval ( $mailaddress ['id'] ) );
				}
			}
			$score = intval ( $data ['score'] );
			$money = floatval ( $data ['money'] );
			if ($money > 0)
				s_user_money_log ( $rs, $rs, 'User', $money, a_L ( "USER_ADD_USER" ), true );

			if ($score > 0)
				s_user_score_log ( $rs, $rs, 'User', $score, a_L ( "USER_ADD_USER" ), true );
			if ($data ['status']) {
				//开始自动登录
				$userinfo = $GLOBALS ['db']->getRow ( "select id,user_name,email,user_pwd,status,group_id,score from " . DB_PREFIX . "user where id=" . $rs );
				$ucdata = '';
				if ($userinfo ['status']) {
					$users->login ( $userinfo ['user_name'], trim ( $_POST ['user_pwd'] ) );
					$_SESSION ['user_name'] = $userinfo ['user_name'];
					$_SESSION ['user_id'] = $userinfo ['id'];
					$_SESSION ['group_id'] = $userinfo ['group_id'];
					$_SESSION ['user_email'] = $userinfo ['email'];
					$_SESSION ['score'] = $userinfo ['score'];
					
				//返利
				if(floatval(a_fanweC("LOGIN_SCORE")) != 0){
					$user_id=intval ( $userinfo ['id'] );
					require_once ROOT_PATH.'app/source/func/com_order_pay_func.php';
					$first_time = a_gmtTime();
					$first_time = a_toDate($first_time,"Y-m-d");
					$first_time =  a_strtotime($first_time);
					$last_time = $first_time + 24*3600;
					if(intval(a_fanweC("LOGIN_SCORE_CLS"))==0)
					{
						$m_count =  $GLOBALS['db']->getOne("select count(*) from ". DB_PREFIX . "user_score_log where `memo_1`='".a_L("LOGIN_SCORE")."' and create_time between '{$first_time}' and '{$last_time}' and rec_module = 'Login' and user_id='{$user_id}'");
						if(intval($m_count) == 0)
						{
							s_user_score_log($user_id,"User","Login",a_fanweC("LOGIN_SCORE"),a_L("LOGIN_SCORE"));
						}
					}
					else
					{
						$m_count =  $GLOBALS['db']->getOne("select count(*) from ". DB_PREFIX . "user_money_log where `memo_1`='".a_L("LOGIN_SCORE")."' and create_time between '{$first_time}' and '{$last_time}' and rec_module = 'Login' and user_id='{$user_id}'");
						if(intval($m_count) == 0)
						{
							s_user_money_log($user_id,"User","Login",a_fanweC("LOGIN_SCORE"),a_L("LOGIN_SCORE"));
						}
					}
				}

					$GLOBALS ['db']->query ( "update " . DB_PREFIX . "user set last_ip = '" . $_SESSION ['CLIENT_IP'] . "' where id =" . $rs );
				}
				if ($goods_id > 0){
					success ( sprintf ( a_L ( "HC_REGISTER_SUCCESS" ), $userinfo ['email'] ), a_L ( "REG_SUCCESS" ),  __ROOT__ . "/index.php?m=Cart&a=index&id=" . $goods_id);
				}else{
					//success ( a_L ( "LOGIN_SUCCESS" ), a_L ( "LOGIN_SUCCESS" ), $redirect);
					success ( sprintf ( a_L ( "HC_REGISTER_SUCCESS" ), $userinfo ['email'] ), a_L ( "REG_SUCCESS" ), $redirect);
				}

			} else {
				if (! s_sendUserActiveMail ( $rs, SHOP_NAME )) {
					a_error ( sprintf ( a_L ( "HC_SEND_EAMIL_FAILED_TIP" ), $data ['email'] ) . a_fanweC ( 'SMTP_ACCOUNT' ), a_L ( "HC_SEND_EMAIL_FAILED" ), __ROOT__ . "/index.php" );
				} else {
					redirect2 ( __ROOT__ . "/index.php?m=User&a=rse_success&user_id=" . intval ( $rs ) );
				}
			}
		} else
			a_error ( a_L ( "REG_FAILED" ), "", "back" );
	}
	function user_sendVerifySn() {
		$user_id = intval ( $_REQUEST ['user_id'] );
		$userinfo = $GLOBALS ['db']->getRow( "select * from " . DB_PREFIX . "user where id = " . $user_id );
		if (! $userinfo) {
			/*$GLOBALS['tpl']->assign("status",'error');
				$GLOBALS['tpl']->assign("title",$GLOBALS['lang']["HC_SEND_VERIFY_ERROR"]);
				$GLOBALS['tpl']->assign("message",$GLOBALS['lang']["HC_INVALID_USER_ID"].$user_id);	*/
			a_error ( $GLOBALS ['lang'] ["HC_INVALID_USER_ID"], $GLOBALS ['lang'] ["HC_SEND_VERIFY_ERROR"] );
			exit ();
		} else if ($userinfo ['status'] == 1) {
			/*$GLOBALS['tpl']->assign("status",'error');
				$GLOBALS['tpl']->assign("title",$GLOBALS['lang']["HC_EMAIL_NOT_VERIFY"]);
				$GLOBALS['tpl']->assign("message",$GLOBALS['lang']["HC_USER_ALREADY_VERIFY"]);*/
			a_error ( $GLOBALS ['lang'] ["HC_USER_ALREADY_VERIFY"], $GLOBALS ['lang'] ["HC_EMAIL_NOT_VERIFY"] );
			exit ();
		}

		if (! s_sendUserActiveMail ( $user_id, SHOP_NAME )) {
			/*$this->assign("status","error");
				$this->assign("title",l("HC_SEND_EMAIL_FAILED"));
				$this->assign("message",sprintf(l("HC_SEND_EMAIL_FAILED_TIP"),$userinfo['email'],fanweC("SMTP_ACCOUNT")));
				$this->assign("link",sprintf(l("HC_INDEX_JUMP_LINK"),u("Index/index")));
				$this->assign("url",U("Index/index"));
				$this->assign("content_page",'Inc:redirect');
				$this->display("Page:user_frame");*/
			$msg = sprintf ( $GLOBALS ['lang'] ["HC_SEND_EMAIL_FAILED_TIP"], $userinfo ['email'], a_fanweC ( "SMTP_ACCOUNT" ) );
			a_error ( $msg, $GLOBALS ['lang'] ["HC_SEND_EMAIL_FAILED"] );
			exit ();
		}

		redirect2 ( __ROOT__ . "/index.php?m=User&a=rse_success&user_id=" . $user_id );
		exit ();
	}

	function user_verify() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$sn = $_REQUEST ["sn"];
		$data = array ('navs' => array (array ('name' => a_L ( "HC_VERIFY_EMAIL" ), 'url' => '' ) ), 'keyword' => '', 'content' => '' );
		assignSeo ( $data );

		if (! empty ( $sn ))
			$userinfo = $GLOBALS ['db']->getRow( "select * from " . DB_PREFIX . "user where active_sn = '$sn' and status=0 " );
		if ($userinfo) {
			$users = & init_users3 ();
			if ($users->login ( $userinfo ['user_name'], '', '' )) {
				$_SESSION ['sn'] = $sn;
				$_SESSION ['user_name'] = $userinfo ['user_name'];
				$_SESSION ['user_id'] = $userinfo ['id'];
				$_SESSION ['group_id'] = $userinfo ['group_id'];
				$_SESSION ['user_email'] = $userinfo ['email'];
				$_SESSION ['score'] = $userinfo ['score'];
				$_COOKIE['fanwe_user_id'] = $userinfo ['id'];
				$sql_str = 'update ' . DB_PREFIX . 'user set last_ip = \'' . $_SESSION ["CLIENT_IP"] . '\',status = 1 where user_name = \'' . $userinfo ['user_name'] . '\' limit 1';
				$GLOBALS ['db']->query ( $sql_str );
				$GLOBALS ['db']->query ( "update " . DB_PREFIX . "mail_address_list set status=1 where user_id=" . $userinfo ['id'] );
				success ( a_L ( "HC_EMAIL_VERIFY_SUCCESS" ), "", a_u ( "Index/index" ) );
			} else {
				a_error ( a_L ( "HC_VERIFY_URL_ERROR" ), "", a_u ( "Index/index" ) );
			}
		} else {
			if ($_SESSION ['sn'] == $sn) {
				success ( a_L ( "HC_EMAIL_VERIFY_SUCCESS" ), "", a_u ( "Index/index" ) );
			} else {
				a_error ( a_L ( "HC_VERIFY_URL_ERROR" ), "", a_u ( "Index/index" ) );
			}

		}
	}

	function user_doResetreq() {
		if(!check_ip_operation($_SESSION['CLIENT_IP'],"getPassword",10))
    	{
			a_error ( a_L ( "HC_SUBMIT_TOO_FAST" ) );
		}

		$email = trim ( $_POST ['email'] );
		if ($email == '') {
			$err = a_L ( "HC_PLEASE_ENTER_EMAIL" );
		} elseif (!a_checkEmail($email )) {
			$err = a_L ( "HC_EMAIL_ERROR" );
		}
		if ($err != '') {
			a_error ( a_L ( "HC_ACCOUNT_NOT_EXIST" ), "","back");
			exit ();
		}

		$user_info = $GLOBALS ['db']->getRowCached("select * from ".DB_PREFIX."user where email='" . $email . "' and status=1");
		if ($user_info) {
			if (!sendPasswordMail ( $user_info, SHOP_NAME )) {
				$title = a_L( "HC_SEND_EMAIL_FAILED" );
				$msg = sprintf ( a_L("HC_SEND_EAMIL_FAILED_TIP" ), $email ) . a_fanweC ( 'SMTP_ACCOUNT' );
				a_error($msg,$title);
				exit ();
			} else {

				$title = a_L( "HC_RESET_PASSWORD" );
				$msg = sprintf ( a_L("HC_RESET_PASSWORD_SUCCESS_TIP" ), $email, a_fanweC('SMTP_ACCOUNT'));
				success($msg,$title,a_u("User/login"));
				exit ();
			}
		} else {
			a_error ( a_L ( "HC_ACCOUNT_NOT_EXIST" ), "", a_u ( "User/resetreq" ) );
			exit ();
		}

		if ($user_info ['pwd_question'] == '' || $user_info ['pwd_answer'] == '') {
			a_error ( a_L ( "CANT_GET_PASSWORD" ) );
		}
		if (trim ( $_POST ['pwd_answer'] ) != $user_info ['pwd_answer']) {
			a_error ( a_L ( "PWD_ANSWER_ERROR" ) );
		}

		$rand_pwd = reMakePassword ( $user_info['user_name']);
		if ($rand_pwd) {
			if (a_fanweC ( "GET_PASSWORD" ) == 1)
				$rs = sendPasswordMail ( $user_info , $rand_pwd );
				if ($rs) {
					//$this->assign("waitSecond",10);
					success ( a_L("PASSWORD_SEND_SUCCESS"),"",a_u("User/login") );
				} else {
					//$this->assign("waitSecond",30);
					success ( a_L ( "PLEASE_REMEBER_NEW_PWD" ) . "：" . $rand_pwd,"",a_u("User/login") );
				}
		}
	}

	function reMakePassword($user_name = '') {
		$rand_pwd = rand_string ( 16, 5 );
		$encode_pwd = md5 ( $rand_pwd );
		$rs = $GLOBALS ['db']->query ( "update " . DB_PREFIX . "user set user_pwd= '{$encode_pwd}' where user_name='" . $user_name . "' and status = 1" );
		if ($rs)
			return $rand_pwd;
		else
			return false;
	}

	function sendPasswordMail($user_info, $new_pwd) {
		if (a_fanweC ( "MAIL_ON" ) == 1) {
			$resetsn = strtoupper("U".md5(uniqid()));
			$mail_template = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "mail_template where `name`='get_password'" );
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "user set reset_sn='{$resetsn}' where id={$user_info['id']}" );

			if ($user_info) {
				$username = $user_info ['user_name'];
				if ($user_info ['nickname'] != '') {
					$username .= "(" . $user_info ['nickname'] . ")";
				}
			} else {
				return a_L ( "USERNAME_NOT_EXIST" );
			}
			if ($mail_template) {
				require_once (ROOT_PATH . 'services/Mail/Mail.class.php');
				$mail = new Mail ( );
				$mail->IsHTML ( $mail_template ['is_html'] ); // 设置邮件格式为 HTML
				$mail_title = $mail_template ['mail_title'];
				$mail_content = $mail_template ['mail_content'];
				$mail_title = str_replace ( "{\$shop_name}", SHOP_NAME, $mail_title );
				$mail_title = str_replace ( "{\$user.password}", $new_pwd, $mail_title );
				$mail_content = str_replace ( "{\$user.user_name}", $username, $mail_content );
				$mail_content = str_replace ( "{\$user.password}", $new_pwd, $mail_content );
				$mail_content = str_replace ( "{\$shop_name}", SHOP_NAME, $mail_content );
				$mail_content = str_replace ( "{\$user.reset_url}", a_getDomain().a_u("User/reset","sn-".$resetsn) , $mail_content );

				$mail->Subject = $mail_title; // 标题
				$mail->Body = $mail_content; // 内容
				$mail->AddAddress ( $user_info ['email'], $username );

				if (! $mail->Send ()) {
					return $mail->ErrorInfo;
				} else {
					return a_L ( "PASSWORD_SEND_SUCCESS" );
				}
			}
		} else {
			return false;
		}
	}

	function user_doReset()
	{
		$cfm_password = trim($_POST['user_pwd_confirm']);
    	$user_pwd = trim($_POST['user_pwd']);
		$sn = $_POST["sn"];

		$err = "";

		if(strlen($user_pwd) < 4)
		{
			$err = a_L("HC_USER_PASSWORD_TOO_SHORT");
		}
		elseif($user_pwd !=$cfm_password)
		{
			$err = a_L("HC_PASSWORD_CONFIRM_FAILED");
		}

		if($err != '')
		{
			a_error($err);
			exit;
		}


		if(!empty($sn))
			$userinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where reset_sn = '$sn'");

		if($userinfo)
		{
			$cfg = array('username'=>$userinfo['user_name'], 'password'=>$user_pwd);
	    	$users  = &init_users3();
			$users->need_sync = false;
			if ($users->edit_user($cfg))
			{
				$user_info['user_pwd'] = md5($user_pwd);
				$user_info['reset_sn'] = '';
				$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_info,"UPDATE","id=".$userinfo['id']);
				success(a_L("HC_RESET_PASSWORD_SUCCESS"),a_L("HC_RESET_PASSWORD_SUCCESS"),a_u("User/login"));
			}else{
				a_error(a_L("HC_RESET_PASSWORD_TB_FAILED"),a_L("HC_RESET_PASSWORD_FAILED"),"back");
			}
		}
		else
		{
			a_error(a_L("HC_RESET_PASSWORD_TB_FAILED"),a_L("HC_RESET_PASSWORD_FAILED"),a_u("Index/index"));
		}
	}
	
	//随机生成用户名和邮箱，并设置初始化密码为123456
	function  getRandUser($user_info) {
		$data ['user_name'] = $user_info['nickname'];
        $data ['user_pwd'] = md5("123456");
       	$data ['email'] = trim("sy_".dechex(rand(0,255)).rand(0,10000). '@xx.com');
        $data ['txqq_id'] = trim($user_info ['openid']);
        $data ['status'] = 1;

        $data ['last_ip'] = $_SESSION['CLIENT_IP'];
        $data ['score'] = intval(a_fanweC("DEFAULT_SCORE"));
        $data ['create_time'] = a_gmtTime();
        $data ['update_time'] = a_gmtTime();
        $data ['group_id'] = a_fanweC("DEFAULT_USER_GROUP");
        
        return $data;
	}
?>