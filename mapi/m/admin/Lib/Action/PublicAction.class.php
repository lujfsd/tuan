<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

//开放的公共类，不需RABC验证
class PublicAction extends BaseAction{
	public function login()
	{				
		//验证是否已登录
		//管理员的SESSION
		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
		$adm_name = $adm_session['adm_name'];
		$adm_id = intval($adm_session['adm_id']);
		
		if($adm_id != 0)
		{
			//已登录
			$this->redirect(u("Index/index"));			
		}
		else
		{
			$this->display();
		}
	}
	public function verify()
	{	
        Image::buildImageVerify(4,1);
    }
    
    //登录函数
    public function do_login()
    {		
    	$adm_name = trim($_REQUEST['adm_name']);
    	$adm_password = trim($_REQUEST['adm_password']);
    	$ajax = intval($_REQUEST['ajax']);  //是否ajax提交

    	if($adm_password == '')
    	{
    		$this->error(L('ADM_PASSWORD_EMPTY',$ajax));
    	}

    	$adm_data = M("MConfig")->where("code='admin_pwd'")->find();
    	$admin_pwd = $adm_data['val'];
    	if (empty($admin_pwd)){
    		$data = array();
    		$data['code'] = 'admin_pwd';
    		$data['title'] = '后台管理帐户密码';
    		$data['val'] = md5('fanwe');
    		$data['type'] = 0;
    		$data['value_scope'] = '';
    		$data['title_scope'] = '';
    		$data['sort'] = 0;
    		$data['is_effect'] = 1;
    		$data['group_id'] = 0;
    		$data['description'] = ' 默认值为:fanwe';
    		    		
    		M("MConfig")->add ($data);
    		
    		$adm_data = M("MConfig")->where("code='admin_pwd'")->find();
    		$admin_pwd = $adm_data['val'];
    	}    	

		if($admin_pwd!=md5($adm_password))
		{				
			$this->error(L("ADM_PASSWORD_ERROR"),$ajax);
		}
		else
		{
				//登录成功
				$adm_session['adm_name'] = '管理员';
				$adm_session['adm_id'] = $adm_data['id'];
				
				
				es_session::set(md5(conf("AUTH_KEY")),$adm_session);
				
				//$this->success(L("LOGIN_SUCCESS"),$ajax);
				$this->success(u("Index/index"),$ajax);
				
		}
	
    }
	
    //登出函数
	public function do_loginout()
	{
	//验证是否已登录
		//管理员的SESSION
		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
		$adm_id = intval($adm_session['adm_id']);
		
		if($adm_id == 0)
		{
			//已登录
			$this->redirect(u("Public/login"));			
		}
		else
		{
			es_session::delete(md5(conf("AUTH_KEY")));
			$this->assign("jumpUrl",U("Public/login"));
			$this->assign("waitSecond",3);
			$this->success(L("LOGINOUT_SUCCESS"));
		}
	}
}
?>