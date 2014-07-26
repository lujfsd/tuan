<?php
	$ma = $_REQUEST['m']."_". strtolower ( $_REQUEST ['a'] );
	$ma();

	function Message_index() {
		//初始化分页
		$page = intval ( $_REQUEST ['p'] );
		$f = intval ( $_REQUEST ["f"] );
		if ($page == 0)
			$page = 1;

		$result = getMessageList2 ( 'Message', 0, $page, C_CITY_ID, $f );
		$navs = array ('name' => $GLOBALS ['lang'] ["MESSAGE_BOARD"], 'url' => a_u ( "Message/index" ) );
		//输出当前页seo内容
		$data = array ('navs' => array ($navs ), 'keyword' => "", 'content' => "" );
		assignSeo ( $data );
		$GLOBALS ['tpl']->assign ( "message_list", $result ['list'] );
		//分页
		$page = new Pager ( $result ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象
		$p = $page->show ();
		$GLOBALS ['tpl']->assign ( 'pages', $p );
		//end 分页
		if(intval($_REQUEST['success'])==1)
			$GLOBALS ['tpl']->assign("success",a_L("MESSAGE_SUCCESS"));

		$GLOBALS ['tpl']->assign ( 'currentCityID', C_CITY_ID );

		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
		$GLOBALS ['tpl']->display ( 'Page/message_index.moban' );
	}

	function Message_comment() {
		//初始化分页
		$page = intval ( $_REQUEST ['p'] );

		if ($page == 0)
			$page = 1;

		//开始处理分页

		$res = searchGoodsList($page,0,0,0,2,0,0);
		foreach($res['list'] as $k=>$res_item)
    	{
    		$res['list'][$k]['comment'] = $GLOBALS ['db']->getRow("select * from ".DB_PREFIX."message where rec_module='Goods' and rec_id=".$res_item['id']." and status = 1 and reply_type = 0 order by is_top,create_time desc");
    	}
		$GLOBALS ['tpl']->assign ( 'info_list', $res ['list'] );
		//分页
		$page = new Pager ( $res ['total'], a_fanweC ( "GOODS_PAGE_LISTROWS" ) ); //初始化分页对象
		$p = $page->show ();
		$GLOBALS ['tpl']->assign ( 'pages', $p );

		$GLOBALS ['tpl']->assign ( "caction", "comment" );

		$navs = array ('name' => a_L("MESSAGE_COMMENT"), 'url' => a_u ( "Message/index" ) );
		//输出当前页seo内容
		$data = array ('navs' => array ($navs ) );

		assignSeo ( $data );

		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
		$GLOBALS ['tpl']->display ( 'Page/message_comment.moban' );
	}
	function Message_commentlist() {
		//初始化分页
		$page = intval ( $_REQUEST ['p'] );
		$rec_id = intval ( $_REQUEST ['id'] );

		if ($page == 0)
			$page = 1;

		$GLOBALS ['tpl']->assign ( "module_name", "Goods" );
		$GLOBALS ['tpl']->assign ( "rec_id", $rec_id );

		$result = getMessageList2 ( 'Goods', $rec_id, $page );

		//var_dump($result);
		$GLOBALS ['tpl']->assign ( "message_list", $result ['list'] );
		$page = new Pager ( $result ['total'], a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象
		$p = $page->show ();
		$GLOBALS ['tpl']->assign ( 'pages', $p );

		$goods = getGoodsItem ( $rec_id );
		$GLOBALS ['tpl']->assign ( 'goods', $goods );
		$navs = array ('name' => $GLOBALS ['lang'] ["HC_GROUPON_FORUM"], 'url' => a_u ( "Message/commentList", "id-" . $rec_id ) );
		//输出当前页seo内容
		$data = array ('navs' => array ($navs ), 'keyword' => "", 'content' => "" );
		assignSeo ( $data );
		if(intval($_REQUEST['success'])==1)
			$GLOBALS ['tpl']->assign("success",a_L("MESSAGE_SUCCESS"));
		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );

		$GLOBALS ['tpl']->display ( "Page/message_commentlist.moban");
	}

	// 买家评论
	function Message_buycomment() {
		// myself
		if( isset( $_REQUEST['my'] ) && intval( $_SESSION['user_id'] ) != 0 )
			$user_id = intval( $_SESSION['user_id'] );
		else
			$user_id = 0;

		//初始化分页
		$page = intval( $_REQUEST['p'] );
		if( $page <= 0 ) $page = 1;

		$res = get_buy_comment( $page, $user_id );
		$GLOBALS ['tpl']->assign ( 'comment_list', $res['list'] );

		//分页
		$page = new Pager ( $page, a_fanweC( "GOODS_PAGE_LISTROWS" ) ); //初始化分页对象
		$p = $page->show ();
		$GLOBALS ['tpl']->assign ( 'pages', $p );

		$GLOBALS ['tpl']->assign ( "caction", "comment" );

		$navs = array ('name' => a_L("MESSAGE_COMMENT"), 'url' => a_u ( "Message/index" ) );
		//输出当前页seo内容
		$data = array ('navs' => array ($navs ) );

		assignSeo ( $data );

		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
		$GLOBALS ['tpl']->display ( 'Page/message_buycomment.moban' );
	}


	function Message_groupmessage() {

		$cityID = C_CITY_ID;
		
		$pcityID = $GLOBALS['db']->getAll("select pid from ".DB_PREFIX."group_city where id='".$cityID."'");
		
		//初始化分页
		$page = intval ( $_REQUEST ["p"] );
		if ($page == 0)
			$page = 1;
		$GLOBALS ['tpl']->assign ( "page", $page );

		//开始处理分页
		$page_size = $page;
		$page_count = a_fanweC ( "PAGE_LISTROWS" );
		$limit = ($page_size - 1) * $page_count . "," . $page_count;

		//查询当前页商品数据
		$group_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "group_message where pid=0 and city_id in(" . $cityID . ",".intval($pcityID).") order by create_time desc limit {$limit}" );
		$group_list_total = $GLOBALS ['db']->getOne ( "select count(*) as countx from " . DB_PREFIX . "group_message where pid=0  and city_id=" . $cityID );

		foreach ( $group_list as $k => $v ) {
			$group_list [$k] ['user_name'] = $GLOBALS ['db']->getOneCached ( "select user_name from " . DB_PREFIX . "user where id=" . $v ['user_id'] );
			$group_list [$k] ['comments'] = $GLOBALS ['db']->getOneCached ( "select count(*) as countx from " . DB_PREFIX . "group_message where pid=" . $v ['id'] );
			$group_list [$k] ['url'] = a_u ( "Message/showGroupMessage", "id-" . $v ['id'] );
			$group_list [$k] ['follow_url'] = a_u ( "Message/followGroupMessage", "id-" . $v ['id'] );

		}

		$GLOBALS ['tpl']->assign ( 'group_list', $group_list );

		//分页
		$page = new Pager ( $group_list_total, a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象
		$p = $page->show ();
		$GLOBALS ['tpl']->assign ( 'pages', $p );
		//end 分页


		$navs = array ('name' => $GLOBALS ["lang"] ["MESSAGE_GROUPMESSAGE"], 'url' => a_u ( "Message/groupMessage" ) );
		//输出当前页seo内容
		$data = array ('navs' => array ($navs ) );

		assignSeo ( $data );

		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );

		$GLOBALS ['tpl']->assign ( "caction", "groupMessage" );
		$GLOBALS ['tpl']->display ( "Page/message_groupmessage.moban" );
	}
	function Message_addgroupmessage() {
		$navs = array ('name' => $GLOBALS ['lang'] ["MESSAGE_ADDGROUPMESSAGE"], 'url' => a_u ( "Message/addGroupMessage" ) );
		//输出当前页seo内容
		$data = array ('navs' => array ($navs ) );
		assignSeo ( $data );

		//输出主菜单
		$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
		//输出城市
		$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
		//输出帮助
		$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );

		$GLOBALS ['tpl']->assign ( "caction", "addGroupMessage" );
		$GLOBALS ['tpl']->display ( "Page/message_addgroupmessage.moban" );
	}

	function Message_addcomment() {
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
		//开始验证是否仅开放给会员
		if (a_fanweC ( "MESSAGE_USER_ONLY" ) && intval ( $_SESSION ['user_id'] ) == 0) {
			a_error ( a_L ( "PLEASE_LOGIN" ), '', a_u ( "User/login" ) );
		}
		if (a_fanweC ( "VERIFY_ON" ) == 1) {
			if ($_SESSION ['verify'] != md5 ( $_POST ['verify'] )) {
				a_error ( a_L ( 'VERIFY_ERROR' ) );
			}
		}
		//字段验证
		if ($_REQUEST ['content'] == '') {
			a_error ( a_L ( "CONTENT_EMPTY" ) );
		}

		if(a_fanweC("CLOSE_BUY_MSG") == 1)
		{
			if(intval($GLOBALS['db']->getOneCached("SELECT count(*) FROM ".DB_PREFIX."order_goods where rec_id=".intval ( $_REQUEST ['rec_id'] )." and user_id=".intval ( $_SESSION ['user_id'] ))) ==0)
			{
				a_error(a_L("MUST_BE_ONE_AND_MSG"));
			}
		}

		if (check_ip_operation ( $GLOBALS ['client_ip'], "Message", a_fanweC ( "MESSAGE_INTEVAL" ) )) {
			$data ['content'] = $_REQUEST ['content'];
			$data ['pid'] = intval ( $_REQUEST ['pid'] );
			$data ['reply_type'] = $data ['pid'] == 0 ? 0 : 1;
			$data ['status'] = a_fanweC ( "MESSAGE_AUTO_CHECK" );
			$data ['create_time'] = a_gmtTime ();
			$data ['user_id'] = intval ( $_SESSION ['user_id'] );
			$data ['user_name'] = $_SESSION ['user_name'];
			$data ['user_email'] = $_SESSION ['user_email'];
			$data ['rec_module'] = $_REQUEST ['rec_module'];
			$data ['rec_id'] = intval ( $_REQUEST ['rec_id'] );
			$data ['score'] = intval ( $_REQUEST ['score'] );
			$data ['city_id'] = C_CITY_ID;

			if ($_REQUEST ['rec_module'] == 'Goods') {
				$sql = "select name_1 from " . DB_PREFIX . "goods where id =" . intval ( $_REQUEST ['rec_id'] );
				$data ['title'] = $GLOBALS ['db']->getOneCached ( $sql );
			} else {
				$data ['title'] = htmlspecialchars ( $_REQUEST ['content'], ENT_QUOTES );
			}

			//$rs = D("Message")->add($data);
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "message", addslashes_deep ( $data ), 'INSERT' );
			$rs = intval ( $GLOBALS ['db']->insert_id () );
			if ($rs) {
				//返利
				if(floatval(a_fanweC("MESSAGE_SCORE")) != 0){
					$user_id=intval ( $_SESSION ['user_id'] );
					$m_count =  $GLOBALS['db']->getOne("select count(*) from ". DB_PREFIX . "message where rec_module='{$_REQUEST ['rec_module']}' and rec_id ='".intval ( $_REQUEST ['rec_id'] )."' and user_id='{$user_id}'");
					if(intval($m_count)==1)
					{
						require ROOT_PATH.'app/source/func/com_order_pay_func.php';
						if(intval(a_fanweC("MESSAGE_SCORE_CLS"))==0)
						{
							s_user_score_log($user_id,"Message","commentlist",a_fanweC("MESSAGE_SCORE"),a_L("MESSAGE_SCORE"));
						}
						else
						{
							s_user_money_log($user_id,"Message","commentlist",a_fanweC("MESSAGE_SCORE"),a_L("MESSAGE_SCORE"));
						}
					}
				}
				if ($data ['status']) {
					//修改为当前的留言
					if ($data ['rec_module'] == 'Goods') {
						redirect2 ( a_getDomain().a_u ( 'Message/commentList', 'id-' . $data ['rec_id'] . '|success-1' ) );
					} elseif ($data ['rec_module'] == 'Payment' || $data ['rec_module'] == 'Order' || $data ['rec_module'] == 'OrderReConsignment' || $data ['rec_module'] == 'OrderUncharge') {
						success ( a_L ( "MESSAGE_SUCCESS" ) );
					} else {
						redirect2 (a_getDomain(). a_u ( "Message/index", "success-1" ) );
					}
				} else {
					success ( a_L ( "WAIT_FOR_CHECK" ) );
				}
			} else {
				a_error ( a_L ( "MESSAGE_ERROR" ) );
			}
		} else {
			a_error ( a_L ( "MESSAGE_TOO_QUICK" ) );
		}
	}

	function Message_insertgroupmessage() {
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
		$tg_title = htmlspecialchars ( $_REQUEST ['tg_title'], ENT_QUOTES );
		$tg_content = $_REQUEST ['tg_content'];
		if ($tg_title == '') {
			a_error ( a_l ( "HC_PLEASE_ENTER_TITLE" ) );
		}
		if ($tg_content == '') {
			a_error ( a_l ( "CONTENT_EMPTY" ) );
		}
		$msgData ['tg_title'] = $tg_title;
		$msgData ['tg_content'] = $tg_content;
		$msgData ['user_id'] = intval ( $_SESSION ['user_id'] );
		$msgData ['create_time'] = a_gmtTime ();
		$msgData ['city_id'] = intval ( $_REQUEST ['city_id'] );
		if (check_ip_operation ( $GLOBALS ['client_ip'], "GroupMessage", 3600 )) {
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "group_message", addslashes_deep ( $msgData ), 'INSERT' );
			success ( a_l ( "HC_PUBLIC_SUCCESS" ) );
		} else {
			a_error ( a_l ( "HC_PUBLIC_LIMIT_TIME" ) );
		}
	}

	function Message_followgroupmessage() {
		//$ajax = intval ( $_REQUEST ['ajax'] );
		$id = intval ( $_REQUEST ['id'] );
		$user_id = intval ( $_SESSION ['user_id'] );
		if ($user_id == 0) {
			a_error ( $GLOBALS ['lang'] ["PLEASE_LOGIN"], "", a_u ( "User/login" ) );
		} else {
			if ($GLOBALS ['db']->getOne ( "select count(*) as countx from " . DB_PREFIX . "group_message_follow where user_id=" . $user_id . " and message_id=" . $id ) > 0) {
				a_error ( $GLOBALS ['lang'] ["HC_HAS_FOLLOW"] );
			} elseif ($GLOBALS ['db']->getOneCached ( "select user_id from " . DB_PREFIX . "group_message where id=" . $id ) == $user_id) {
				a_error ( $GLOBALS ['lang'] ["HC_FLOLLOW_SELF"] );
			} else {
				$sql = "insert into ".DB_PREFIX."group_message_follow(user_id,message_id) values(".$user_id.",".$id.")";
				$GLOBALS ['db']->query($sql);
				//$GLOBALS ['db']->autoExecute ( DB_PREFIX . "group_message_follow", array ("user_id" => $user_id, "message_id" => $id ) );
				//$GLOBALS ['db']->autoExecute ( DB_PREFIX . "group_message", array ("follows" => "follows+1", "update", "id=" . $id ) );
				$sql = "update ".DB_PREFIX."group_message set follows = follows + 1 where id=".$id;
				$GLOBALS ['db']->query($sql);
				success ( $GLOBALS ['lang'] ["HC_FOLLOW_SUCCESS"] );
			}
		}
	}
	function Message_showgroupmessage() {
		$page = intval ( $_REQUEST ["p"] );
		if ($page == 0)
			$page = 1;
		$GLOBALS ['tpl']->assign ( "page", $page );

		//开始处理分页
		$page_size = $page;
		$page_count = a_fanweC ( "PAGE_LISTROWS" );
		$limit = ($page_size - 1) * $page_count . "," . $page_count;

		$id = intval ( $_REQUEST ['id'] );
		$data = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "group_message where id={$id}" );
		if ($data) {
			//$data['user_name'] = M("User")->where("id=".$data['user_id'])->getField("user_name");
			$data ['user_name'] = $GLOBALS ['db']->getOneCached ( "select user_name from " . DB_PREFIX . "user where id=" . $data ['user_id'] );
			//$comments = M("GroupMessage")->where("pid=".$data['id'])->order("create_time desc")->limit($limit)->findAll();
			$comments = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "group_message where pid={$data['id']} order by create_time desc limit {$limit}" );
			//$comments_count = M("GroupMessage")->where("pid=".$data['id'])->count();
			$comments_count = $GLOBALS ['db']->getOne ( "select count(*) as countx from " . DB_PREFIX . "group_message where pid=" . $data ['id'] );
			foreach ( $comments as $k => $v ) {
				$comments [$k] ['user_name'] = $GLOBALS ['db']->getOneCached ( "select user_name from " . DB_PREFIX . "user where id=" . $v ['user_id'] );
			}
			$data ['comments'] = $comments;
			$GLOBALS ['tpl']->assign ( "msgData", $data );

			//分页
			$page = new Pager ( $comments_count, a_fanweC ( "PAGE_LISTROWS" ) ); //初始化分页对象
			$p = $page->show ();
			$GLOBALS ['tpl']->assign ( 'pages', $p );
			//end 分页


			$navs = array ('name' => $data ['tg_title'], 'url' => a_u ( "Message/addGroupMessage" ) );
			//输出当前页seo内容
			$data = array ('navs' => array ($navs ) );

			assignSeo ( $data );
			//输出主菜单
			$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
			//输出城市
			$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
			//输出帮助
			$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );

			$GLOBALS ['tpl']->display ( "Page/message_showgroupmessage.moban" );
		} else {
			a_error ( $GLOBALS ['lang'] ["HC_GROUPON_NOT_EXIST"] );
		}
	}

	function Message_addgroupmessagecomment() {
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
		$pid = intval ( $_REQUEST ['pid'] );
		$p_data = $GLOBALS ['db']->getRowCached ( "select * from " . DB_PREFIX . "group_message where id={$pid}" );
		$tg_title = sprintf ( $GLOBALS ['lang'] ["HC_GROUPON_COMMENTS"], $p_data ['tg_title'] );
		$tg_content = $_REQUEST ['tg_content'];
		if ($tg_content == '') {
			a_error ( $GLOBALS ['lang'] ["CONTENT_EMPTY"] );
		}
		$msgData ['tg_title'] = htmlspecialchars ( $tg_title );
		$msgData ['tg_content'] = htmlspecialchars ( $_REQUEST ['tg_content'], ENT_QUOTES );
		$msgData ['user_id'] = intval ( $_SESSION ['user_id'] );
		$msgData ['create_time'] = a_gmtTime ();
		$msgData ['city_id'] = intval ( $p_data ['city_id'] );
		$msgData ['pid'] = $pid;
		if (check_ip_operation ( $_SESSION ['CLIENT_IP'], "GroupMessageComment", 30 )) {
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "group_message", $msgData );

			//HtmlCache::delHtmlCache('Message','feedback');
			success ( $GLOBALS ['lang'] ["HC_SUBMIT_SUCCESS"] );
		} else {
			a_error ( $GLOBALS ['lang'] ["HC_SUBMIT_TOO_FAST"] );
		}
	}

	function Message_feedback()
	{
		if(!$GLOBALS['tpl']->is_cached("Page/message_feedback.moban" ,md5("feedback"))){
			$navs = array('name'=>$GLOBALS ['lang']["FEEDBACK"],'url'=>a_u("Message/feedback"));
	    	//输出当前页seo内容
		    $data = array(
		    	'navs' => array(
		    		$navs,
		    	),
		    	'keyword'=>	"",
		    	'content'=>	"",
		    );
		   	assignSeo($data);
		   	//输出主菜单
			$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
			//输出城市
			$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
			//输出帮助
			$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );

		}
		$GLOBALS ['tpl']->display ( "Page/message_feedback.moban",md5("feedback") );
	}

	function Message_addfeedback() {
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
		//字段验证
		if ($_REQUEST ['content'] == '') {
			a_error ( a_L ( "CONTENT_EMPTY" ) );
		}
		if ($_REQUEST ['title'] == '') {
			a_error ( a_L ( "HC_PLEASE_ENTER_CONTACT" ) );
		}
		if ($_REQUEST ['user_name'] == '') {
			a_error ( a_L ( "HC_PLAESE_ENTER_NAME" ) );
		}
		if (check_ip_operation ( $GLOBALS ['client_ip'], "Feedback", a_fanweC ( "MESSAGE_INTEVAL" ) )) {
			$data ['user_name'] = $_REQUEST ['user_name'];
			$data ['title'] = $_REQUEST ['title'];
			$data ['content'] = $_REQUEST ['content'];
			$data ['rec_module'] = 'Feedback';
			$data ['status'] = 0;
			$data ['create_time'] = a_gmtTime ();
			$data ['update_time'] = a_gmtTime ();
			//$rs = D("Message")->add($data);


			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "message", addslashes_deep ( $data ), 'INSERT' );
			$rs = intval ( $GLOBALS ['db']->insert_id () );
			if ($rs > 0) {
				//if(C('HTML_CACHE_ON')) //开启静态缓存，则自动清空缓存 add by chenfq 2010-05-28
				//HtmlCache::delHtmlCache('Message','feedback');
				success ( a_l ( "HC_SUBMIT_SUCCESS" ) );
			} else {
				a_error ( a_l ( "HC_SUBMIT_FAILED" ) );
			}
		} else {
			a_error ( a_l ( "HC_SUBMIT_TOO_FAST" ) );
		}
	}

	function Message_add() {
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
		//开始验证是否仅开放给会员
		if (a_fanweC ( "MESSAGE_USER_ONLY" ) && intval ( $_SESSION ['user_id'] ) == 0) {
			a_error ( a_L ( "PLEASE_LOGIN" ), '', a_u ( "User/login" ) );
		}
		if (a_fanweC ( "VERIFY_ON" ) == 1) {
			if ($_SESSION ['verify'] != md5 ( $_POST ['verify'] )) {
				a_error ( a_L ( 'VERIFY_ERROR' ) );
			}
		}
		//字段验证
		if ($_REQUEST ['content'] == '') {
			a_error ( a_L ( "CONTENT_EMPTY" ) );
		}
		if (check_ip_operation ( $GLOBALS ['client_ip'], "Message", a_fanweC ( "MESSAGE_INTEVAL" ) )) {
			$data ['content'] = htmlspecialchars ( $_REQUEST ['content'], ENT_QUOTES );
			$data ['pid'] = intval ( $_REQUEST ['pid'] );
			$data ['reply_type'] = $data ['pid'] == 0 ? 0 : 1;
			$data ['status'] = a_fanweC ( "MESSAGE_AUTO_CHECK" );
			$data ['create_time'] = a_gmtTime ();
			$data ['user_id'] = intval ( $_SESSION ['user_id'] );
			$data ['user_name'] = $_SESSION ['user_name'];
			$data ['user_email'] = $_SESSION ['user_email'];
			$data ['rec_module'] = $_REQUEST ['rec_module'];
			$data ['rec_id'] = intval ( $_REQUEST ['rec_id'] );
			$data ['score'] = intval ( $_REQUEST ['score'] );
			$data ['city_id'] = intval ( $_REQUEST ['city_id'] );
			$data ['flag'] = intval ( $_REQUEST ['flag'] );
			if ($_REQUEST ['rec_module'] == 'Goods') {
				$sql = "select name_1 from " . DB_PREFIX . "goods where id =" . intval ( $_REQUEST ['rec_id'] );
				$data ['title'] = $GLOBALS ['db']->getOneCached ( $sql );
			} else {
				$data ['title'] = htmlspecialchars ( $_REQUEST ['content'], ENT_QUOTES );
			}

			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "message", addslashes_deep ( $data ), 'INSERT' );
			$rs = intval ( $GLOBALS ['db']->insert_id () );
			//var_dump($rs);
			if ($rs) {
				if ($data ['status']) {
					//修改为当前的留言
					if ($data ['rec_module'] == 'Goods') {
						redirect2 ( a_u ( "Message/commentList", 'id-' . $data ['rec_id'] . "|success-1" ) );
					} elseif ($data ['rec_module'] == 'Payment' || $data ['rec_module'] == 'Order' || $data ['rec_module'] == 'OrderReConsignment' || $data ['rec_module'] == 'OrderUncharge') {

						success ( a_L ( "MESSAGE_SUCCESS" ) );
					} else {
						redirect2 ( a_u ( "Message/index", "f-".$data['flag']."|success-1" ));
					}

				} else {
					success ( a_L ( "WAIT_FOR_CHECK" ) );
				}
			} else {
				a_error ( a_L ( "MESSAGE_ERROR" ) );
			}
		} else {
			a_error ( a_L ( "MESSAGE_TOO_QUICK" ) );
		}
	}
	function Message_sellermsg()
	{
		if(!$GLOBALS ['tpl']->is_cached("Page/message_sellermsg.moban",md5("message_sellmsg"))){
			$navs = array('name'=>$GLOBALS['lang']["HC_SELLER_INFO"],'url'=>a_u("Message/sellerMsg"));
	    		//输出当前页seo内容
		    	$data = array(
		    		'navs' => array(
		    			$navs,
		    		),
		    		'keyword'=>	"",
		    		'content'=>	"",
		    	);
		    assignSeo($data);
		    //输出主菜单
			$GLOBALS ['tpl']->assign ( "main_navs", assignNav ( 2 ) );
			//输出城市
			$GLOBALS ['tpl']->assign ( "city_list", getGroupCityList () );
			//输出帮助
			$GLOBALS ['tpl']->assign ( "help_center", assignHelp () );
		}
	    $GLOBALS ['tpl']->display("Page/message_sellermsg.moban",md5("message_sellmsg"));
	}
	function Message_addsellermsg()
	{
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
		//字段验证
    	if($_REQUEST['content']=='')
		{
			a_error($GLOBALS['lang']["CONTENT_EMPTY"],"","back");
		}
    	if($_REQUEST['title']=='')
		{
			a_error($GLOBALS['lang']["HC_PLEASE_ENTER_CONTACT"],"","back");
		}
    	if($_REQUEST['user_name']=='')
		{
			a_error($GLOBALS['lang']["HC_PLAESE_ENTER_NAME"],"","back");
		}

		if($_SESSION['smsSubscribe'] != md5($_REQUEST['groupon_verify'])) {
			a_error($GLOBALS['lang']["VERIFY_ERROR"],"","back");
		}
		if(check_ip_operation($_SESSION['CLIENT_IP'],"Seller",a_fanweC("MESSAGE_INTEVAL")))
    	{
    		$data['user_name'] = $_REQUEST['user_name'];
    		$data['title'] = $_REQUEST['title'];
    		$data['content'] = $_REQUEST['content'];
    		$data['rec_module'] = 'Seller';
    		$data['status'] = 0;
    		$data['create_time'] = a_gmtTime();
    		$data['update_time'] = a_gmtTime();
    		$data['groupon_seller_name'] = $_REQUEST['groupon_seller_name'];
    		$data['groupon_goods'] = $_REQUEST['groupon_goods'];
    		if($GLOBALS['db']->autoExecute(DB_PREFIX."message",$data))
    		{
    			unset($_SESSION['smsSubscribe']); //用户注册完后清空验证码
    			success($GLOBALS['lang']["HC_SUBMIT_SUCCESS"],"",a_u("Index/index"));
    		}
    		else
    		{
    			a_error($GLOBALS['lang']["HC_SUBMIT_FAILED"],"","back");
    		}
    	}
    	else
    	{
    		a_error($GLOBALS['lang']["HC_SUBMIT_TOO_FAST"],"","back");
    	}
	}
	function Message_addsuppliercomment()
	{
		if(!check_referer())
		{
			a_error(a_L('_OPERATION_FAIL_'),'',a_u("Index/index"));
		}
		//开始验证是否仅开放给会员
		if(a_fanweC("MESSAGE_USER_ONLY")&&intval($_SESSION['user_id'])==0)
		{
			a_error(a_L("PLEASE_LOGIN"),"",a_u("User/login"));
		}

		//字段验证
    	if($_REQUEST['content']=='')
		{
			a_error(a_L("CONTENT_EMPTY"));
		}
		if(check_ip_operation($_SESSION['C_CITY_ID'],"Message",a_fanweC("MESSAGE_INTEVAL")))
    	{
    		$data['content'] = htmlspecialchars($_REQUEST['content'],ENT_QUOTES);
    		$data['pid'] = intval($_REQUEST['pid']);
    		$data['reply_type'] = $data['pid']==0?0:1;
    		$data['status'] = a_fanweC("MESSAGE_AUTO_CHECK");
    		$data['create_time'] = a_gmtTime();
    		$data['user_id'] = intval($_SESSION['user_id']);
    		$data['user_name']	=	$_SESSION['user_name'];
    		$data['user_email']	=  $_SESSION['user_email'];
    		$data['rec_module'] = $_REQUEST['rec_module'];
    		$data['rec_id'] = intval($_REQUEST['rec_id']);
    		$data['score']	= intval($_REQUEST['score']);
    		$data['city_id'] = intval($_REQUEST['city_id']);
    		$data['title'] = htmlspecialchars($_REQUEST['content'],ENT_QUOTES);

    		if($GLOBALS['db']->autoExecute(DB_PREFIX."message",$data))
    		{
    			if($data['status'])
    			{
    				success(a_L("MESSAGE_SUCCESS"));

    			}
    			else
    			{
    				success(a_L("WAIT_FOR_CHECK"));
    			}
    		}
    		else
    		{
    			a_error(a_L("MESSAGE_ERROR"));
    		}
    	}
    	else
    	{
    		a_error(a_L("MESSAGE_TOO_QUICK"));
    	}
	}

?>