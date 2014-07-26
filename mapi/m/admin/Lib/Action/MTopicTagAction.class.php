<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class MTopicTagAction extends CommonAction{
	public function index()
	{
		parent::index();
	}	
	public function add()
	{
		$cate_list = M("MTopicTagCate")->findAll();
		$sort = M("MTopicTag")->max("sort");
		$this->assign("new_sort",$sort+1);
		$this->assign("cate_list",$cate_list);
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M('MTopicTag')->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$cate_list = M("MTopicTagCate")->findAll();
		foreach($cate_list as $k=>$v)
		{
			$cate_list[$k]['checked'] = M("MTopicTagCateLink")->where("tag_id = ".$id." and cate_id = ".$v['id'])->count();
		}

		$this->assign("cate_list",$cate_list);
		$this->display ();
	}	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M('MTopicTag')->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M('MTopicTag')->where ( $condition )->delete();			
				if ($list!==false) {
					M("MTopicTagCateLink")->where(array ('tag_id' => array ('in', explode ( ',', $id ) ) ))->delete();					
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
		$data = M('MTopicTag')->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['name']))
		{
			$this->error(L("TAG_NAME_EMPTY_TIP"));
		}	
		// 更新数据
		$log_info = $data['name'];
		$list=M('MTopicTag')->add($data);
		if (false !== $list) {
			foreach($_REQUEST['cate_id'] as $cate_id)
			{
				$link_data = array();
				$link_data['cate_id'] = $cate_id;
				$link_data['tag_id'] = $list;
				M("MTopicTagCateLink")->add($link_data);
			}
			//成功提示
			$this->success(L("INSERT_SUCCESS"));
		} else {
			$info = M()->getDbError();
			//错误提示			
			$this->error(L("INSERT_FAILED").$info);
		}
	}	
	
	public function update() {
		B('FilterString');
		$data = M('MTopicTag')->create ();
		
		
		$log_info = M('MTopicTag')->where("id=".intval($data['id']))->getField("name");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("TAG_NAME_EMPTY_TIP"));
		}	

		// 更新数据
		$list=M('MTopicTag')->save ($data);
		if (false !== $list) {
			M("TopicTagCateLink")->where("tag_id=".$data['id'])->delete();
			foreach($_REQUEST['cate_id'] as $cate_id)
			{
				$link_data = array();
				$link_data['cate_id'] = $cate_id;
				$link_data['tag_id'] = $data['id'];
				M("TopicTagCateLink")->add($link_data);
			}
			//成功提示			
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			$info = M()->getDbError();
			//错误提示			
			$this->error(L("UPDATE_FAILED").$info);
		}
	}
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M('MTopicTag')->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M('MTopicTag')->where("id=".$id)->setField("sort",$sort);		
		$this->success(l("SORT_SUCCESS"),1);
	}
}
?>