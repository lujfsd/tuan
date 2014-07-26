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

//用户模型
class UserModel extends CommonModel {
	protected $_validate = array(
			array('user_name','require',USER_NAME_REQUIRE), 
			
			array('user_name','',USER_NAME_EXIST,0,'unique',1), // 在新增的时候验证name字段是否唯一	
			array('email','require','请填写EMAIL'), 		
			array('email','','该Email已被使用',0,'unique',1), // 在新增的时候验证email字段是否唯一
			array('user_pwd','require',USER_PWD_REQUIRE), 
			array('user_pwd_confirm','require',USER_PWD_CONFIRM_REQUIRE), 
			array('user_pwd_confirm','user_pwd',USER_PWD_CONFIRM_ERROR,0,'confirm'), // 验证确认密码是否和密码一致
			array('new_user_pwd_confirm','new_user_pwd',USER_PWD_CONFIRM_ERROR,1,'confirm'), // 验证确认密码是否和密码一致
		);
	protected $_auto = array ( 		
		array('status','1'),  // 新增的时候把status字段设置为1
		array('create_time','gmtTime',1,'function'), // 对create_time字段在插入的时候写入当前时间戳	
		array('update_time','gmtTime',1,'function'), // 对update_time字段在插入的时候写入当前时间戳	
		array('user_pwd','md5',3,'function') // 对password字段在新增的时候使md5函数处理		
	);
	protected $_map = array(
		'user_pwd'	=>	'user_pwd',  //用于单独操作的字段映射，
	);
	
   public function getUserConsignee($user_id){
   		$sql_str =  'SELECT a.id,'.
					'       a.user_id,'.
					'       a.region_lv1,'.
					'       a.region_lv2,'.
					'       a.region_lv3,'.
					'       a.region_lv4,'.
					'       a.address,'.
   					'       a.consignee,'.
					'       a.zip,'.
					'       a.mobile_phone,'.
					'       a.fix_phone,'.
					'       r1.name as r1name,'.
					'       r2.name as r2name,'.
					'       r3.name as r3name,'.
					'       r4.name as r4name'.
					'  FROM '.C("DB_PREFIX").'user_consignee a'.
					'  left outer join '.C("DB_PREFIX").'region_conf r1 on r1.id = a.region_lv1'.
					'  left outer join '.C("DB_PREFIX").'region_conf r2 on r2.id = a.region_lv2'.
					'  left outer join '.C("DB_PREFIX").'region_conf r3 on r3.id = a.region_lv3'.
					'  left outer join '.C("DB_PREFIX").'region_conf r4 on r4.id = a.region_lv4'.
					' where a.user_id = '.$user_id;
   		$rs = $this->query($sql_str, false);
   		return $rs;
   }	
}
?>