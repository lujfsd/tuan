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
class EcvModel extends RelationModel {
	protected $_link = array(
		'ecvType'=>array(
			'mapping_type'=>BELONGS_TO,
			'class_name'  =>'EcvType',
			'mapping_name'=>'ecvType',
			'foreign_key' =>'ecv_type'
		)
	);
	
	public function getEcvType($id)
	{
		$evc = $this->where("id = $id")->relation(true)->find();
		return $evc['ecvType']['name'];
	}
}
?>