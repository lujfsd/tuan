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

// 支付模型
class PaymentModel extends MultiLangModel {
	protected $_validate = array(
			array('name','require',PAYMENT_NAME_REQUIRE), 
		);
	public function save($data)
	{
		require_once(VENDOR_PATH.'payment3/'.$data['class_name'].'Payment.class.php');
		$class_name = $data['class_name']."Payment";
		if(class_exists($class_name))
		{
			$model = new $class_name;
			foreach($model->config as $k=>$item)
			{
				if(is_array($item))
				{					
					foreach($item as $kk=>$vv)
					{
						$data['config'][$k][$kk] = $data[$k][$kk];
					}
				}
				else
				$data['config'][$k] = $data[$k];
			}
			
			$data['config'] = serialize($data['config']);	
			
		if($data['fee_type']==0)
			{
				$data['fee'] = setBaseMoney($data['fee'],intval($data['currency']));
			}
		if($data['cost_fee_type']==0)
			{
				$data['cost_fee'] = setBaseMoney($data['cost_fee'],intval($data['currency']));
			}
			
			return parent::save($data);
		}
		else
		{
			return 0;
		}
	}
}
?>