<?php
// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: awfigq(67877605@qq.com)
// +----------------------------------------------------------------------

class GroupBondModel extends CommonModel{
	protected $_validate = array(
		array('goods_id','require','商品ＩＤ不能为空'), 
		array('sn','require','序列号不能为空'), 
		array('end_time','require','截止日期不能为空')
	);
		
	protected $_auto = array ( 		
		array('status','0'),  // 新增的时候把status字段设置为1
		array('create_time','gmtTime',1,'function'), // 对create_time字段在插入的时候写入当前时间戳
		array('end_time','localStrToTimeMax',3,'function'), 
		array('use_time','localStrToTimeMax',3,'function'), 
	);
}
?>