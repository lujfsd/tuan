<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class ConfAction extends CommonAction{
	public function index()
	{
		
		$this->display();
	}
	
	
	
	public function mobile()
	{


		$delivery_list = array();
		$cate_list = array();
		if (SYS_M == 'easethink'){
			$delivery_list = M("Delivery")->where("is_effect = 1")->order("sort asc")->findAll();
			$cate_list = M("DealCate")->where("is_effect = 1")->order("sort asc")->findAll();
		}else if (SYS_M == 'ecshop'){
			$delivery_list = M("Shipping")->where("enabled = 1")->field("shipping_name as name, shipping_id as id")->order("id asc")->findAll();
			$cate_list = M("Category")->field("cat_name as name, cat_id as id")->order("id asc")->findAll();
			
			
		}else if (SYS_M == 'shopex'){
			
		}else if (SYS_M == 'fanwe'){
			$delivery_list = M("Delivery")->field("name_1 as name, id")->order("sort asc")->findAll();
			$cate_list = M("GoodsCate")->field("name_1 as name, id")->where("status = 1")->order("sort asc")->findAll();			
		}
		
		//echo SYS_M; exit;
		$d_time_list = M("MConfigList")->where("`group` = 2 and is_verify = 1")->findAll();
		//echo M("MConfigList")->getLastSql();
		$pay_list = M("MConfigList")->where("`group` = 1 and is_verify = 1")->findAll();
		
		$conf_res = M("MConfig")->where("is_effect = 1")->order("sort asc")->findAll();
		//print_r($conf_res);
		foreach($conf_res as $k=>$v)
		{
			$v['name'] = htmlspecialchars($v['code']);
			$v['value'] = htmlspecialchars($v['val']);			
			$v['value_scope'] = explode(",",$v['value_scope']);
			$v['title_scope'] = explode(",",$v['title_scope']);
			$v['input_type'] = intval($v['type']);
			
			if ($v['name'] == 'select_delivery_time_id'){
				$v['value_scope'] = array();
				$v['title_scope'] = array();
				foreach($d_time_list as $k1=>$v1)
				{					
					$v['value_scope'][] = $v1['code'];
					$v['title_scope'][] = $v1['title'];
				}					
			}

			if ($v['name'] == 'select_payment_id'){
				$v['value_scope'] = array();
				$v['title_scope'] = array();
				foreach($pay_list as $k1=>$v1)
				{					
					$v['value_scope'][] = $v1['pay_id'];
					$v['title_scope'][] = $v1['pay_id']."-".$v1['title'];
				}					
			}
			
			if ($v['name'] == 'delivery_id'){
				$v['value_scope'] = array();
				$v['title_scope'] = array();
				foreach($delivery_list as $k1=>$v1)
				{
					$v['value_scope'][] = $v1['id'];
					$v['title_scope'][] = $v1['id']."-".$v1['name'];
				}
			}			
			
			if ($v['name'] == 'catalog_id'){
				$v['value_scope'] = array();
				$v['title_scope'] = array();
				$v['value_scope'][] = 0;
				$v['title_scope'][] = '0-全部分类';				
				foreach($cate_list as $k1=>$v1)
				{
					$v['value_scope'][] = $v1['id'];
					$v['title_scope'][] = $v1['id']."-".$v1['name'];
				}
			}			
			
			$conf[$v['group_id']][] = $v;
		}

		//dump($conf);
		//dump($d_time_list); 
		//exit;
		$this->assign("conf",$conf);		
		//$this->assign("config",$config);
			
		$this->display();
	}
	
	public function savemobile()
	{
		//dump($_POST);
		foreach($_POST as $k=>$v)
		{
			if ($k == 'admin_pwd'){
				if (strlen($v) == 32){
					M("MConfig")->where("code='".$k."'")->setField("val",$v);
				}else if(empty($v)){
					M("MConfig")->where("code='".$k."'")->setField("val",md5('fanwe'));
				}else{
					M("MConfig")->where("code='".$k."'")->setField("val",md5($v));
				}
			}else{
				M("MConfig")->where("code='".$k."'")->setField("val",$v);
			}	
			//echo M("MConfig")->getLastSql()."<br>";
		}
		//exit;
		$this->success("保存成功");
	}
	
	public function insertnews()
	{
			//B('FilterString');
		$name="MConfigList";
		$model = D ($name);
		if (false ===$data= $model->create ()) {
			$this->error ( $model->getError () );
		}
		$data['is_verify'] = 1;
		$data['group'] = 4;
		//保存当前数据对象
		$list=$model->add ($data);
		if ($list!==false) { //保存成功
			//$this->saveLog(1,$list);
			$this->success (L('INSERT_SUCCESS'));
		} else {
			//失败提示
			//$this->saveLog(0,$list);
			$this->error (L('INSERT_FAILED'));
		}
	}
	function edit() {
		$name = "MConfigList";
		$model = D($name);
		
		$id = $_REQUEST [$model->getPk ()];
		$vo = $model->getById($id);
		$this->assign ( 'vo', $vo );
		$this->display ();
	}
	
	public function news()
	{
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$map['group'] = 4;
		$name=$this->getActionName();
		$model = D ("MConfigList");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	
	function updatenews() {
		//B('FilterString');
		$name="MConfigList";
		$model = D ( $name );
		if (false === $data = $model->create ()) {
			$this->error ( $model->getError () );
		}
		// 更新数据
		$list=$model->save ($data);
		$id = $data[$model->getPk()];
		if (false !== $list) {
			//成功提示
			//$this->saveLog(1,$id);
			$this->success (L('UPDATE_SUCCESS'));
		} else {
			//错误提示
			//$this->saveLog(0,$id);
			$this->error (L('UPDATE_FAILED'));
		}
	}
	
	
	public function add_youhui()
	{
		//输出现有模板文件夹		 
		$this->assign ('group', 3);
		$this->display("edit_youhui");
	}
	
	public function add_invoice()
	{
		//输出现有模板文件夹
		$this->assign ('group', 6);
		$this->display("edit_mconf");
	}	
	
	public function add_delivery_time()
	{
		//输出现有模板文件夹
		$this->assign ('group', 2);
		$this->display("edit_mconf");
	}
		
	public function youhui()
	{
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$map['group'] = 3;
		$name=$this->getActionName();
		$model = D ("MConfigList");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}	
	
	function edit_youhui() {
		$name = "MConfigList";
		$model = D($name);
	
		$id = $_REQUEST [$model->getPk ()];
		$vo = $model->getById($id);
		$this->assign ( 'vo', $vo );
		$this->assign ( 'group', $vo['group'] );
		$this->display ();		
	}

	
	function edit_mconf() {
		$name = "MConfigList";
		$model = D($name);
		
		$id = $_REQUEST [$model->getPk ()];
		$vo = $model->getById($id);
		$this->assign ( 'vo', $vo );
		$this->assign ( 'group', $vo['group'] );
		if (intval($vo['group']) == 1){		
			
			$pay_list = array();			
			if (SYS_M == 'easethink'){
				$pay = M("Payment")->where("class_name = '".$vo['code']."'")->find();
				if ($pay){
					$pay_list[] = array("id"=>$pay['id'],"name"=>$pay['id']."-".$pay['name']);
				}
			}else if (SYS_M == 'ecshop'){				
				$pay = M("Payment")->where("lower(pay_code) = '".strtolower($vo['code'])."'")->find();				
				if ($pay){
					$pay_list[] = array("id"=>$pay['pay_id'],"name"=>$pay['pay_id']."-".$pay['pay_name']);
				}					
			}else if (SYS_M == 'shopex'){
					
			}else if (SYS_M == 'fanwe'){
				$pay = M("Payment")->where("class_name = '".$vo['code']."'")->find();
				if ($pay){
					$pay_list[] = array("id"=>$pay['id'],"name"=>$pay['id']."-".$pay['name_1']);
				}								
			}

			if (count($pay_list) == 0){
				$pay_list[] = array("id"=>0,"name"=>"0-还未安装");
			}
			
			$this->assign ( 'pay_list', $pay_list );
			$this->display ("edit_mpay");
		}else{
			$this->display ();
		}
		
	}
		
	function update_mconf() {
		
		$id = intval($_REQUEST["id"]);
		$group = intval($_REQUEST["group"]);
		$name="MConfigList";
		$model = D ( $name );
		if (false === $data = $model->create ()) {
			$this->error ( $model->getError () );
		}
		$data['group'] = $group;
		if ($id == 0){
			$list=$model->add ($data);
			if ($list!==false) {
				$this->success (L('INSERT_SUCCESS'));
			} else {
				$this->error (L('INSERT_FAILED'));
			}		
		}else{
			// 更新数据
			$list=$model->save ($data);
			$id = $data[$model->getPk()];
			if (false !== $list) {
				$this->success (L('UPDATE_SUCCESS'));
			} else {
				$this->error (L('UPDATE_FAILED'));
			}	
		}				
	}

	public function del_mconf() {
		$id = intval($_REQUEST['id']);
		$name="MConfigList";
		$model = D($name);		
		if(false !==$model->where ("id =".$id)->delete()){
			$this->success (L('FOREVER_DELETE_SUCCESS'));
		}else{
			$this->error (L('FOREVER_DELETE_FAILED'));
		}
		
	}
	
	
	public function invoice()
	{
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$map['group'] = 6;
		$name=$this->getActionName();
		$model = D ("MConfigList");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}

	public function delivery_time()
	{
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$map['group'] = 2;
		$name=$this->getActionName();
		$model = D ("MConfigList");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}	

	public function mpaylist()
	{
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$map['group'] = 1;
		$name=$this->getActionName();
		$model = D ("MConfigList");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}	
		
	public function foreverdelete() {
		//删除指定记录
		$result = array('isErr'=>0,'content'=>'');
		$id = $_REQUEST['id'];
		if(!empty($id))
		{
			$name="MConfigList";
			$model = D($name);
			$pk = $model->getPk ();
			$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
			if(false !== $model->where ( $condition )->delete ())
			{
				//$this->saveLog(1,$id);
			}
			else
			{
				//$this->saveLog(0,$id);
				$result['isErr'] = 1;
				$result['content'] = L('FOREVER_DELETE_SUCCESS');
			}
		}
		else
		{
			$result['isErr'] = 1;
			$result['content'] = L('FOREVER_DELETE_FAILED');
		}
		
		die(json_encode($result));
	}
	

	public function toogle_status()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$field = $_REQUEST['field'];
		$info = $id."_".$field;
		$c_is_effect = M("MConfigList")->where("id=".$id)->getField($field);  //当前状态

		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M("MConfigList")->where("id=".$id)->setField($field,$n_is_effect);
				
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
	
}
?>