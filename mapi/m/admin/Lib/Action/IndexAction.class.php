<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class IndexAction extends CommonAction{
	//首页
    public function index(){
		$this->display();
    }
    

    //框架头
	public function top()
	{
		$this->display();
	}
	//框架左侧
	public function left()
	{
		$this->display();
	}
	//默认框架主区域
	public function main()
	{
		//会员数
		$this->display();
	}	
	//底部
	public function footer()
	{
		$this->display();
	}
	
	public function reset_sending()
	{
		$field = trim($_REQUEST['field']);
		if($field=='DEAL_MSG_LOCK'||$field=='PROMOTE_MSG_LOCK'||$field=='APNS_MSG_LOCK')
		{
			M("Conf")->where("name='".$field."'")->setField("value",'0');
			$this->success(L("RESET_SUCCESS"),1);
		}
		else
		{
			$this->error(L("INVALID_OPERATION"),1);
		}
	}
}
?>