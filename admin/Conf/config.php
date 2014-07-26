<?php
// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 云淡风轻(97139915@qq.com)
// +----------------------------------------------------------------------

// 后台系统配置
if (!defined('THINK_PATH')) exit();
if(file_exists('./Public/db_config.php'))
$db_config	=	require './Public/db_config.php';
$sys_config = require './sys_config.php';
$global_config = require './Public/global_config.php';
function getConfVal($keys)
	{		
		if(file_exists('./Public/db_config.php'))
		$db_config	=	require './Public/db_config.php';
		$link = @mysql_connect($db_config['DB_HOST'].":".$db_config['DB_PORT'], $db_config['DB_USER'], $db_config['DB_PWD']);
		@mysql_select_db($db_config['DB_NAME'],$link);		
		$sql = "SELECT name,val FROM ".$db_config['DB_PREFIX']."sys_conf WHERE status=1 and name in(".$keys.")";
		$result = mysql_query($sql);						
		$res = array();
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	       $res[$row[0]] = $row[1];
	    }
		mysql_close($link);
	    return $res;
	}
	
if(is_array($db_config))
$ext_config = getConfVal("'USER_AUTH_KEY'");

$array=array(
	//===============权限认证RBAC配置=================//
	'USER_AUTH_ON'				=>	true,
	'USER_AUTH_TYPE'			=>	2,		// 默认认证类型 1 登录认证 2 实时认证
    'ADMIN_AUTH_KEY'			=>	'administrator',
	'USER_AUTH_MODEL'			=>	'Admin',	// 默认验证数据表模型
	'AUTH_PWD_ENCODER'			=>	'md5',	// 用户认证密码加密方式
	'USER_AUTH_GATEWAY'			=>	'?m=Public&a=login',	// 默认认证网关
	'NOT_AUTH_MODULE'			=>	'Public,Index',		// 默认无需认证模块
	'REQUIRE_AUTH_MODULE'		=>	'',		// 默认需要认证模块
	'NOT_AUTH_ACTION'			=>	'addVoteGroup,updateVoteGroup,removeVoteGroup,clearCache,main,getLangFileList,readTplContent,getFileList,getOptionUrl,getGroupTitle,getItemTitle,getVoteTitle,getOptionUrl,getIsMulti,getVoteTitle,getCityName,getItemUrl,getUserList,getSendStatusLink,getSendStatus,getSmsStatus,getCateList,getLayoutList,getPageList,getGroupCityName,getUsername,getGroupBondStatus,getEditLink,getUseNumber,getCreateNumber,getUserList,getAdvPositionName,getAdvInfo,getPaymentList,getPayment,checkAdm,checkSSL,getChildRegion,getRegionChildJS,getActionList,getOrderSN,showOrderList,getGroupBondLink,getOrderEditLink,getTypeAttr,setGallery',		// 默认无需认证操作
	'REQUIRE_AUTH_ACTION'		=>	'',		// 默认需要认证操作
    'GUEST_AUTH_ON'         	=> 	false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'           	=>  0,     // 游客的用户ID
	'AUTH_TYPE'	=>	array('NODE','MODULE','ACTION'),   //授权类型的常量

	//RABC权限认证的表映射
	'RBAC_ROLE_TABLE'	=>	'role',
	'RBAC_USER_TABLE'	=>	'admin',
	'RBAC_ACCESS_TABLE' =>	'role_access',
	'RBAC_NODE_TABLE'	=> 	'role_node',
	'RBAC_NAV_TABLE'	=> 	'role_nav',

	//令牌验证
	'TOKEN_ON'	=>	false,  // 是否开启令牌验证
	'TOKEN_NAME'=>'_fanwe_hash__',    // 令牌验证的表单隐藏字段名称
	'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则默认为MD5

	
	'TMPL_CACHE_ON'			=> false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
	//记录日志操作的模块与操作
	'LOG_APP' => array(
		'GoodsCate'=>array('insert','update','foreverdelete'),  //商品分类的增删改日志记录
		'Goods'=>array('insert','update','foreverdelete','delete','moveGoods','sendMail'),  //商品的增删改日志记录
		'GoodsGallery'=>array('doBatch'),  //图像批处理
		'Suppliers'=>array('insert','update','foreverdelete','doReset','reset'),  //供应商
		'ArticleCate'=>array('insert','update','foreverdelete'),  //文章分类的增删改日志记录
		'Article'=>array('insert','update','foreverdelete','moveArticle'),  //文章的增删改日志记录
		'Message'=>array('insert','update','foreverdelete','forbid','resume','swTopStatus'),  //留言
		'Order'=>array('save_uncharge','save_incharge','foreverdelete'),  //订单的增删改日志记录
		'OrderConsignment'=>array('save'),
		'OrderReConsignment'=>array('save'),
		'Public'=>array('checkLogin'),	//后台登录记录
		'UserGroup'	=>	array('insert','update','foreverdelete','forbid','resume'),
		'User'	=>	array('insert','update','foreverdelete','forbid','resume','updateMoney'),
		'UserMoney'	=> array('resumeIncharge','forbidIncharge','foreverdeleteIncharge','resumeUncharge','forbidUncharge','foreverdeleteUncharge'),
		'Referrals'	=> array('pay','unPay'),
		'EcvType'	=>	array('insert','update','foreverdelete','emptyAll'),
		'Ecv'	=>	array('insert','foreverdelete'),
		'Database'	=>	array('dump','restore'),
		'GroupBond'	=>	array('exportcsv','insert','update','batchInsert','batchUpdate','foreverdelete'),
		'Log'	=>	array('clearSysLog'),
	),	
	
	'URL_MODEL' => 0,  //后台只开启普通URL模式
	/* 后台用分页设置 */
	'PAGE_ROLLPAGE'         => 5,      // 分页显示页数
	'PAGE_LISTROWS'         => 20,     // 分页每页显示记录数	
	
	
	//关于后台模板
	'TMPL_TEMPLATE_SUFFIX'  => '.html',     // 默认模板文件后缀
	'DEFAULT_LANG'	=>	'zh-cn',

);

if(is_array($db_config))
$config = array_merge($global_config,$sys_config,$db_config,$ext_config,$array);
else
$config = array_merge($global_config,$sys_config,$array);
return $config;

?>