<?php
return array(
	//调试的配置
	'APP_DEBUG' 				=>  false,
	'SHOW_RUN_TIME'				=>	false,			// 运行时间显示
	'SHOW_ADV_TIME'				=>	false,			// 显示详细的运行时间
	'SHOW_DB_TIMES'				=>	false,			// 显示数据库查询和写入次数
	'SHOW_CACHE_TIMES'			=>	false,			// 显示缓存操作次数
	'SHOW_USE_MEM'				=>	false,			// 显示内存开销
		
	//涉及到多语言的表与字段的配置
	'LANG_CONF'	=> array(
		'article'=>array('name'=>'varchar(255)','content'=>'text','seokeyword'=>'varchar(255)','seocontent'=>'varchar(255)','brief'=>'varchar(255)'),
		'article_cate'=>array('name'=>'varchar(255)','seokeyword'=>'varchar(255)','seocontent'=>'varchar(255)'),
		'attachment'=>array('name'=>'varchar(255)'),
		'goods_attr'=>array('attr_value'=>'varchar(255)'),
		'goods_type'=>array('name'=>'varchar(255)'),
		'goods_type_attr'=>array('name'=>'varchar(255)','attr_value'=>'varchar(255)'),
		'goods_cate'=>array('name'=>'varchar(255)','seokeyword'=>'varchar(255)','seocontent'=>'varchar(255)','cate_desc'=>'varchar(255)'),
		'goods'=>array('name'=>'varchar(255)','seokeyword'=>'varchar(255)','seocontent'=>'varchar(255)','goods_desc'=>'text','brief'=>'varchar(255)'),
		'brand'=>array('name'=>'varchar(255)','seokeyword'=>'varchar(255)','seocontent'=>'varchar(255)','desc'=>'varchar(255)'),
		'nav'=>array('name'=>'varchar(255)'),
		'user_group'=>array('name'=>'varchar(255)'),
		'payment'=>array('name'=>'varchar(255)','description'=>'varchar(255)'),
		'spec'=>array('spec_name'=>'varchar(255)'),
		'spec_type'=>array('name'=>'varchar(255)'),
		'goods_spec'=>array('spec_name'=>'varchar(255)'),
		'currency'=>array('name'=>'varchar(100)'),
		'weight'=>array('name'=>'varchar(100)'),
		'delivery'	=>	array('name'=>'varchar(255)','desc'=>'varchar(255)'),
		'promote'	=>	array('memo'=>'varchar(255)','card_name'=>'varchar(255)'),
		'user_money_log'=>array('memo'=>'varchar(255)'),
		'user_score_log'=>array('memo'=>'varchar(255)'),
	),
	
	
	// __autoLoad 机制额外检测路径设置,注意搜索顺序	
	'APP_AUTOLOAD_PATH'     => '@.ORG.,@.Payment.,@.Sms.,Think.Util.,,@.Lottery.',

);
?>