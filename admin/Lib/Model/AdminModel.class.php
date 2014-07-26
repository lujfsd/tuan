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

// 管理员模型
class AdminModel extends CommonModel {
	public $_validate	=	array(
		array('adm_name','/^[a-z]\w{3,}$/i',ADM_NAME_FORMAT_ERROR),
		array('adm_name','',ADM_NAME_EXIST,0,'unique',1), // 在新增的时候验证name字段是否唯一	
		array('adm_pwd','require',ADM_PWD_REQUIRE),
		array('repassword','require',ADM_PWD_REQUIRE),
		array('repassword','adm_pwd',CONFIRM_PWD_ERROR,self::EXISTS_VAILIDATE,'confirm'),
		array('repassword_new','adm_pwd_new',CONFIRM_PWD_ERROR,1,'confirm'), // 验证确认密码是否和密码一致
		);

	public $_auto		=	array(
		array('status','1'),  // 新增的时候把status字段设置为1
		array('adm_pwd','pwdHash',self::MODEL_BOTH,'callback'),
		array('create_time','gmtTime',self::MODEL_INSERT,'function'),
		array('update_time','gmtTime',self::MODEL_UPDATE,'function'),
		);

	protected function pwdHash() {
		if(isset($_POST['adm_pwd'])) {
			return pwdHash($_POST['adm_pwd']);
		}else{
			return false;
		}
	}
	protected $_map = array(
		'adm_pwd'	=>	'adm_pwd',  //用于单独操作的字段映射，
	);
}
?>