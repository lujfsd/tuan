<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class MTopicTagCateAction extends CommonAction{
	public function index()
	{
		parent::index();
	}	
	public function add()
	{
		$sort = M("MTopicTagCate")->max("sort");
		//echo M("MTopicTagCate")->getLastSql();
		//exit;
		$this->assign("new_sort",$sort+1);
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M("MTopicTagCate")->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$this->display ();
	}	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("MTopicTagCate")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("MTopicTagCate")->where ( $condition )->delete();			
				if ($list!==false) {
					M("MTopicTag")->where(array ('cate_id' => array ('in', explode ( ',', $id ) ) ))->setField("cate_id",0);
					//M("TopicTitle")->where(array ('cate_id' => array ('in', explode ( ',', $id ) ) ))->setField("cate_id",0);					
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {					
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M("MTopicTagCate")->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['name']))
		{
			$this->error(L("TAG_CATE_NAME_EMPTY_TIP"));
		}	
		// 更新数据
		$log_info = $data['name'];
		$list=M("MTopicTagCate")->add($data);
		if (false !== $list) {
			//成功提示			
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示			
			$this->error(L("INSERT_FAILED"));
		}
	}	
	
	public function update() {
		B('FilterString');
		$data = M("MTopicTagCate")->create ();
		
		
		$log_info = M("MTopicTagCate")->where("id=".intval($data['id']))->getField("name");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("TAG_CATE_NAME_EMPTY_TIP"));
		}	

		// 更新数据
		$list=M("MTopicTagCate")->save ($data);
		if (false !== $list) {
			//成功提示			
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示			
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M("MTopicTagCate")->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("MTopicTagCate")->where("id=".$id)->setField("sort",$sort);		
		$this->success(l("SORT_SUCCESS"),1);
	}		
}
?>