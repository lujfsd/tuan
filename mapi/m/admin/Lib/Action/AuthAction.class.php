<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

//后台验证的基础类

class AuthAction extends BaseAction{
	public function __construct()
	{
		parent::__construct();
		$this->check_auth();		
	}
	
	private function check_auth()
	{
		/*
		if(intval(app_conf("EXPIRED_TIME"))>0&&es_session::is_expired())
		{
			es_session::delete(md5(conf("AUTH_KEY")));
			es_session::delete("expire");
		}
		*/	
		//管理员的SESSION
		$adm_session = es_session::get(md5(conf("AUTH_KEY")));		
		$adm_id = intval($adm_session['adm_id']);		
		if($adm_id == 0)
		{			
			$this->redirect("Public/login");
		}
	}
	
	//index列表的前置通知,输出页面标题
	public function _before_index()
	{
		$this->assign("main_title",L(MODULE_NAME."_INDEX"));
	}
	public function _before_trash()
	{
		$this->assign("main_title",L(MODULE_NAME."_INDEX"));
	}
}
?>