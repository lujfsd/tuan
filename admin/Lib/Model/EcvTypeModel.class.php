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

//代金券
class EcvTypeModel extends CommonModel {
	protected $_validate = array(
			array('name','require','代金券名称不能为空'), 
			array('money','is_numeric','代金券金额必须为数字',2,'function'),
			array('use_start_date','checkDateFormat','请输入正确的时间',2,'function'),
			array('use_end_date','checkDateFormat','请输入正确的时间',2,'function'),
		);
		
	protected $_auto = array ( 		
		array('status','1'),  // 新增的时候把status字段设置为1
		array('use_start_date','localStrToTimeMin',3,'function'), 	   
		array('use_end_date','localStrToTimeMax',3,'function'), 	   
		
	);
}
?>