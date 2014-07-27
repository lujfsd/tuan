<?php

	user_enter_init(); //会员菜单初始化
			
	$userid = intval($_SESSION['user_id']);
	
	$ma = $_REQUEST['m']."_".$_REQUEST['a'];
	if($ma=="UcGroupBond_download" || $ma=="UcGroupBond_printbond")
	{
		UcGroupBond_download($userid);
	}
	else
	{
		$ma($userid);
	}
	
		//订单团购券
	function UcGroupBond_order($userid)	
	{
		$order_sn = $_REQUEST['sn'];
		$mobile_phone = trim($_REQUEST['mobile_phone']);
		$GLOBALS['db']->query("update ".DB_PREFIX."order set `mobile_phone_sms` = '".$mobile_phone."' where sn='".$order_sn."' and user_id=".$userid." limit 1");
		
		
		$sql = "select id from ".DB_PREFIX."group_bond where user_id={$userid} and order_id='".$order_sn."' and send_count <=". intval(a_fanweC("SMS_LIMIT"));
		$bond_list = $GLOBALS['db']->getAll($sql);
		foreach($bond_list as $key=>$bond){
			UcGroupBond_sms($userid,$bond['id'], false);
		}
		success('','',a_u("UcGroupBond/index"));
	}
		
	function UcGroupBond_sms($userid, $id=0, $jump = true){
	 	if(a_fanweC("IS_SMS") != 1)
		{
			//$this->redirect('UcGroupBond/index');
			a_error(a_L("SMS_CLOSED"),'',a_u("UcGroupBond/index"));
			//exit;
		}
			//aa
		$id	= intval($id);
		if ($id == 0){
			$id = intval($_REQUEST['id']);
		}
		
		/*2010/07/06 awfigq　避免重复发送 */
		$sessionName = "UcGroupBondSMS_".$id;
		if(isset($_SESSION[$sessionName]))
		{
			if(intval($_SESSION[$sessionName]) + 5 > a_gmtTime())
			{
				//$this->redirect('UcGroupBond/index');
				a_error(a_L("MESSAGE_TOO_QUICK"),'',a_u("UcGroupBond/index"));
				exit;
			}
		}
		else
		{
			$_SESSION[$sessionName] = a_gmtTime();
		}
		
		$bond = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."group_bond where id=".$id);
	 	/*检测发送的团购券是否属于用户 */
		if(intval($bond['user_id']) != $userid)
		{
			//$this->redirect('UcGroupBond/index');
			a_error(a_L("SMS_CLOSED"),'',a_u("UcGroupBond/index"));
		}
	 	
		$send_count = $bond['send_count'];
		if($send_count > a_fanweC("SMS_LIMIT"))
		{		
			a_error(sprintf(a_l("RESEND_OVERLIMIT"),a_fanweC("SMS_LIMIT")),'',__ROOT__."/index.php?m=UcGroupBond&a=index");	
			//$this->error(sprintf(l("RESEND_OVERLIMIT"),fanweC("SMS_LIMIT")));
		}				
		
		$user_info = $GLOBALS['db']->getRow("select mobile_phone,user_name from ".DB_PREFIX."user where id = ".$userid);
		//开始判断是否发送给其他人
		if(a_fanweC("SMS_SEND_OTHER") == 1)
		{
			if($_REQUEST['mobile']&&$_REQUEST['mobile']!='')
			{
				$user_info['mobile_phone'] = $_REQUEST['mobile'];
			}
			else
			{
				$order_sn = $bond['order_id'];
	    		$mobile_other = $GLOBALS['db']->getOne("select `mobile_phone_sms` from ".DB_PREFIX."order where sn='".$order_sn."' and user_id=".$userid);
				
				//$order_sn = M("GroupBond")->where("id=".$id)->getField('order_id');
				//$mobile_other = M("Order")->where("sn='".$order_sn."'")->getField("mobile_phone_sms");
				if($mobile_other&&trim($mobile_other)!='')
				{
					$user_info['mobile_phone'] = $mobile_other;
				}
			}			
		}
		if(empty($user_info['mobile_phone']))
		{
			a_error(a_L("HC_CANT_SEND_SMS"),'',a_u("UcGroupBond/index"));
		}
		
		
		//$bond = D("GroupBond")->where("id = $id")->find();
		//修改 by hc 增加发短信时的goods allow_sms判断
		$goods = $GLOBALS['db']->getRow("select `allow_sms`,goods_short_name,suppliers_id,is_order_sms,promote_end_time,promote_begin_time from ".DB_PREFIX."goods where id=".intval($bond['goods_id']));
		$allow_sms = $goods['allow_sms'];
		if($allow_sms==0)
		{
			a_error(a_L("SMS_CLOSED"),'',a_u("UcGroupBond/index"));
		}
		
		$groupbond_id = $id;
		
		$goods_short_name = $goods['goods_short_name'];
		$seller_info_id = $goods['suppliers_id'];
		
		$depart_id = intval($_REQUEST['depart_id']);
		
		if($depart_id>0)
			$seller_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers_depart where supplier_id=".$seller_info_id." and id=".$depart_id);
		else
			$seller_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."suppliers_depart where supplier_id=".$seller_info_id." and is_main=1 order by is_main desc");
		
		
		$count = 1;
		if ($goods['is_order_sms'] == 1){
			$sql = "select sum(og.number) from ".DB_PREFIX."order as o left join ".DB_PREFIX."order_goods  as og on og.order_id = o.id where o.sn = '".$bond['order_id']."' and og.rec_id = ".intval($bond['goods_id'])." and o.money_status = 2";
			$count = $GLOBALS['db']->getOne($sql);
		}			

		$smsObjs = array(
						 	"user_name"=>$user_info['user_name'],
							"bond"=>array(
										"goods_name"=>$bond['goods_name'],
										"goods_short_name"	=>	$goods_short_name,
										"name"=>a_fanweC("GROUPBOTH"),
										"sn"=>$bond['sn'],
										"password"=>$bond['password'],
										"order_sn" =>	$bond['order_id'],
										"id"	=> $bond['id'],
										"tel"	=>	$seller_info['tel'],
										"address"	=>$seller_info['address'],
										"starttime" =>a_toDate($goods['promote_begin_time'],'Ymd'),
										"endtime"	=>a_toDate($bond['end_time'],'Ymd'),
										"count"	=>	$count,
									)
						);

		require_once(ROOT_PATH.'app/source/func/com_send_sms_func.php');
		require_once(ROOT_PATH.'services/Sms/SmsPlf.class.php');

		$mail_template = $GLOBALS['db']->getOneCached("select mail_content from ".DB_PREFIX."mail_template where name='group_bond_sms'");		
		if($mail_template)
			$str = a_templateFetch($mail_template,$smsObjs);
			
		
		$sms= new SmsPlf();	
		if($sms->sendSMS($user_info['mobile_phone'],$str))
		{
			//修改 by hc 发送成功后追加发送次数 
			$GLOBALS['db']->query("update ".DB_PREFIX."group_bond set send_count= send_count + 1 where id =".$groupbond_id);
			if ($jump)
				success(a_l("HC_SEND_SMS_SUCCESS"),'',a_u("UcGroupBond/index"));
		}
		else
		{
			if ($jump)
				a_error(a_l("HC_SEND_SMS_FAILED").$sms->message,'',a_u("UcGroupBond/index"));
		} 	
	 }
	 
	 function UcGroupBond_download($userid){
	
		if(a_fanweC("GROUPBOND_PRINTTYPE")==0) 
	    {    
			$id = intval($_REQUEST['id']);
			$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$userid);
			$bond = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."group_bond where id =".$id." and is_valid = 1");
			/*检测打印的团购券是否属于用户 */
			if(intval($bond['user_id']) != $userid)
			{
				a_error(a_L('INVALID_OPERATION'));
			}
			
			$GLOBALS['db']->query("update ".DB_PREFIX."group_bond set is_lookat = 1 where id =".$id);
			
			$bond['end_time_format'] = a_toDate($bond['end_time'],'Y-m-d');
			
			$suppliers = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id in(select suppliers_id from ".DB_PREFIX."goods where id =".intval($bond['goods_id']).")");
			
			$img_url = __ROOT__."/ThinkPHP/Vendor/barcode/barcode.php?codebar=BCGcode39&resolution=1&text=".$bond['sn'];
			
			$suppliers['barcode'] = "<img src='$img_url' />";
				
			$depart_id = intval($_REQUEST['depart_id']);
			
			if($depart_id>0)
			{
				$depart_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers_depart where id =".$depart_id);
				$suppliers['address'] = $depart_info['address'];
				$suppliers['tel'] = $depart_info['tel'];
				$suppliers['bus'] = $depart_info['bus'];
				$suppliers['api_address'] = $depart_info['api_address'];
				$suppliers['xpoint'] = $depart_info['xpoint'];
				$suppliers['ypoint'] = $depart_info['ypoint'];
				$suppliers['operating'] = $depart_info['operating'];
			}
			else
			{
				$main_depart = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".$suppliers['id']." and is_main = 1");		
				$suppliers['address'] = $main_depart['address'];
				$suppliers['tel'] = $main_depart['tel'];
				$suppliers['bus'] = $main_depart['bus'];
				$suppliers['api_address'] = $main_depart['api_address'];
				$suppliers['xpoint'] = $main_depart['xpoint'];
				$suppliers['ypoint'] = $main_depart['ypoint'];
				$suppliers['operating'] = $main_depart['operating'];
			}	
			
			$GLOBALS['tpl']->assign("suppliers", $suppliers);
			$GLOBALS['tpl']->assign("user", $user);
			$GLOBALS['tpl']->assign("bond", $bond);
					
			$tpl_content = $suppliers['bond_tmpl'];	
		    
		    $result = $GLOBALS['tpl']->fetch("str:".$tpl_content);
		    
		    
	    	//loader_groupbond_api_address 团购券API定位
	    	$GLOBALS['tpl']->assign("groupbond_api_address", $suppliers['api_address']);	    
		    $GLOBALS['tpl']->assign("groupbond_html", $result);
		    //$content = preg_replace("/<loader_groupbond_html([^>]*)>/i",$result,$content); 

		    $GLOBALS['tpl']->display('Inc/user_center/printbond.moban');
		}else{
	  	    //开始生成图片
	    	$userid = intval($_SESSION['user_id']);
			$id = intval($_REQUEST['id']);
			$user = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."user where id = ".$userid);
			$bond = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."group_bond where id =".$id." and is_valid = 1");
			
			/*2010/06/04 awfigq　检测打印的团购券是否属于用户 */
			if(intval($bond['user_id']) != $userid)
			{
				redirect2(__ROOT__."/index.php?m=UcGroupBond&a=index");
				exit;
			}
			
			$GLOBALS['db']->query("update ".DB_PREFIX."group_bond set is_lookat = 1 where id =".$id);
			
			$bond['end_time_format'] = a_toDate($bond['end_time'],'Y-m-d');
			
			
			$suppliers = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers where id in (select suppliers_id from ".DB_PREFIX."goods where id =".intval($bond['goods_id']).")");
			$img_url = __ROOT__."/ThinkPHP/Vendor/barcode/barcode.php?codebar=BCGcode39&resolution=1&text=".$bond['sn'];
			
			$depart_id = intval($_REQUEST['depart_id']);
			
			if($depart_id>0)
			{
				$depart_info = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers_depart where id =".$depart_id);
				$suppliers['address'] = $depart_info['address'];
				$suppliers['tel'] = $depart_info['tel'];
				$suppliers['bus'] = $depart_info['bus'];
				$suppliers['api_address'] = $depart_info['api_address'];
				$suppliers['xpoint'] = $depart_info['xpoint'];
				$suppliers['ypoint'] = $depart_info['ypoint'];
				$suppliers['operating'] = $depart_info['operating'];
			}
			else
			{
				$main_depart = $GLOBALS['db']->getRowCached("select * from ".DB_PREFIX."suppliers_depart where supplier_id = ".$suppliers['id']." and is_main = 1");		
				$suppliers['address'] = $main_depart['address'];
				$suppliers['tel'] = $main_depart['tel'];
				$suppliers['bus'] = $main_depart['bus'];
				$suppliers['api_address'] = $main_depart['api_address'];
				$suppliers['xpoint'] = $main_depart['xpoint'];
				$suppliers['ypoint'] = $main_depart['ypoint'];
				$suppliers['operating'] = $main_depart['operating'];
			}	
			
			$img_url_use = a_fanweC("SHOP_URL")."/ThinkPHP/Vendor/barcode/barcode.php?codebar=".$suppliers['codebar']."&resolution=1&text=".$bond['sn'];
			if(!is_dir(ROOT_PATH."/app/Runtime/Temp/"))
				mkdir(ROOT_PATH."/app/Runtime/Temp/");
	
			$cache_file_name = ROOT_PATH."/app/Runtime/Temp/".md5($bond['sn'].$depart_id).".jpg";
			$file_name = "http://" . $_SERVER ['HTTP_HOST'].__ROOT__."/app/Runtime/Temp/".md5($bond['sn'].$depart_id).".jpg";
			
			if(!file_exists($cache_file_name))
			{	
				//$server = "http://maps.google.com/staticmap";
				//if(a_fanweC('DEFAULT_LANG') == 'zh-cn')
					//$server = "http://ditu.google.cn/staticmap";		
				//$str = file_get_contents($server."?center=".$suppliers['ypoint'].",".$suppliers['xpoint']."&zoom=14&size=255x255&maptype=mobile&markers=".$suppliers['ypoint'].",".$suppliers['xpoint']);
				if(a_fanweC('DEFAULT_LANG') == 'en-us')
				{
					$server = "http://maps.google.com/staticmap";
					$str = file_get_contents($server."?center=".$suppliers['ypoint'].",".$suppliers['xpoint']."&zoom=14&size=255x255&maptype=mobile&markers=".$suppliers['ypoint'].",".$suppliers['xpoint']);
				}
				else
				{
					$server = "http://api.map.baidu.com/staticimage";					
					$str = @file_get_contents($server."?center=".$suppliers['xpoint'].",".$suppliers['ypoint']."&zoom=14&width=255&height=255&markers=".$suppliers['xpoint'].",".$suppliers['ypoint']);
				}
				$google_map_im = @imagecreatefromstring($str);
				
				$tmpl_im = @imagecreatefromjpeg(ROOT_PATH.a_fanweC("GROUP_IMG_TMPL"));
				
				$barcode_im = @file_get_contents($img_url_use);
				$barcode_im = @imagecreatefromstring($barcode_im);
				
				$backColor = @imagecolorallocate($tmpl_im, 255,255,255);  //分配背影色为白色
				$textcolor = @imagecolorallocate($tmpl_im, 0, 0, 0); //文本色
				
				//开始绘制序列号
				@imagefilledrectangle($tmpl_im,387,42,592,68,$backColor);
				imagettftext($tmpl_im, 10, 0, 397,58, $textcolor, ROOT_PATH.'/global/msyh.ttf', a_L("XY_GROUPBOTH_SN").$bond['sn']);
				
				//开始绘制密码		
				//@imagefilledrectangle($tmpl_im,387,70,592,96,$backColor);
				//if($bond['password']!='')
				//imagettftext($tmpl_im, 10, 0, 397,86, $textcolor, ROOT_PATH.'/global/msyh.ttf', a_L('XY_GROUPBOTH_PWD').$bond['password']);
				
				
				//开始绘制标题
				@imagefilledrectangle($tmpl_im,28,111,588,177,$backColor);
				imagettftext($tmpl_im, 18, 0, 38,150, $textcolor, ROOT_PATH.'/global/msyh.ttf', $bond['goods_name']);
				
				
				//开始绘制用户名
				@imagefilledrectangle($tmpl_im,26,212,153,239,$backColor);
				imagettftext($tmpl_im, 10, 0, 36,229, $textcolor, ROOT_PATH.'/global/msyh.ttf', $user['user_name']);
				
				//开始绘制有效期
				@imagefilledrectangle($tmpl_im,26,268,153,295,$backColor);
				imagettftext($tmpl_im, 10, 0, 36,285, $textcolor, ROOT_PATH.'/global/msyh.ttf', $bond['end_time_format']);
				
				//开始绘制联系电话
				@imagefilledrectangle($tmpl_im,26,329,153,356,$backColor);
				imagettftext($tmpl_im, 10, 0, 36,346, $textcolor, ROOT_PATH.'/global/msyh.ttf', $suppliers['tel']);
				
				//开始绘制营业时间
				@imagefilledrectangle($tmpl_im,26,394,153,421,$backColor);
				imagettftext($tmpl_im, 10, 0, 36,411, $textcolor, ROOT_PATH.'/global/msyh.ttf', $suppliers['operating']);
				
				//开始绘制地址
				@imagefilledrectangle($tmpl_im,26,455,309,524,$backColor);
				if(strlen($suppliers['address'])<=48) // 一行
				{
					imagettftext($tmpl_im, 10, 0, 36,478, $textcolor, ROOT_PATH.'/global/msyh.ttf', $suppliers['address']);
				}
				elseif(strlen($suppliers['address'])>48&&strlen($suppliers['address'])<=96)
				{
					$ad1 = a_msubstr($suppliers['address'],0,20);
					$ad2 = a_msubstr($suppliers['address'],20,20);
					imagettftext($tmpl_im, 10, 0, 36,478, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad1);
					imagettftext($tmpl_im, 10, 0, 36,498, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad2);
				}
				else
				{
					$ad1 = a_msubstr($suppliers['address'],0,20);
					$ad2 = a_msubstr($suppliers['address'],20,20);
					$ad3 = a_msubstr($suppliers['address'],40,20);
					imagettftext($tmpl_im, 10, 0, 36,478, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad1);
					imagettftext($tmpl_im, 10, 0, 36,498, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad2);
					imagettftext($tmpl_im, 10, 0, 36,518, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad3);
				}
					
				//开始绘制交通地址
				@imagefilledrectangle($tmpl_im,26,555,309,624,$backColor);
				if(strlen($suppliers['bus'])<=48) // 一行
				{
					imagettftext($tmpl_im, 10, 0, 36,578, $textcolor, ROOT_PATH.'/global/msyh.ttf', $suppliers['bus']);
				}
				elseif(strlen($suppliers['bus'])>48&&strlen($suppliers['bus'])<=96)
				{
					$ad1 = a_msubstr($suppliers['bus'],0,20);
					$ad2 = a_msubstr($suppliers['bus'],20,20);
					imagettftext($tmpl_im, 10, 0, 36,578, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad1);
					imagettftext($tmpl_im, 10, 0, 36,598, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad2);
				}
				else
				{
					$ad1 = a_msubstr($suppliers['bus'],0,20);
					$ad2 = a_msubstr($suppliers['bus'],20,20);
					$ad3 = a_msubstr($suppliers['bus'],40,20);
					imagettftext($tmpl_im, 10, 0, 36,578, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad1);
					imagettftext($tmpl_im, 10, 0, 36,598, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad2);
					imagettftext($tmpl_im, 10, 0, 36,618, $textcolor, ROOT_PATH.'/global/msyh.ttf', $ad3);
				}
				
				//开始绘制地图
				@imagefilledrectangle($tmpl_im,332,184,587,439,$backColor);
				//imagettftext($tmpl_im, 10, 0, 36,346, $textcolor, $this->getRealPath().'/global/msyh.ttf', $suppliers['tel']);
				imagecopyresampled ($tmpl_im, $google_map_im, 332, 184, 0, 0, 255, 255, 255, 255 );
		
				//开始绘制条码
				@imagefilledrectangle($tmpl_im,344,457,582,549,$backColor);
				//imagettftext($tmpl_im, 10, 0, 36,346, $textcolor, $this->getRealPath().'/global/msyh.ttf', $suppliers['tel']);
				imagecopyresampled ($tmpl_im, $barcode_im, 404, 487, 0, 0, 104, 50, 104, 50 );
								
				//header("Content-type: image/png");
				imagejpeg($tmpl_im,$cache_file_name,100);
			}

	    	//end 生成图片
			if ($_REQUEST['a'] == 'download'){
				header("Content-type: image/jpeg");
				header('Content-Disposition: attachment; filename="'.md5(time()).'.jpg"'); 
				readfile($cache_file_name);				
			}else{
	    		$GLOBALS['tpl']->assign("groupbond_img", $file_name);
	    		$GLOBALS['tpl']->display('Inc/user_center/printbond.moban');				
			}
		}
	 }	  
?>