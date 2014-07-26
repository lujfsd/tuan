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


// 多语言模型的基类
class MultiLangModel extends CommonModel {
	public function __construct()
	{
		$lang_conf = C("LANG_CONF");
		$lang_envs = D("LangConf")->findAll();
		$validate = $this->_validate;
		$new_validate = array();

		foreach($validate as $item)
		{
			if(isset($lang_conf[parse_name(MODULE_NAME)][$item[0]]))
			{
				$base_name = $item[0];
				//验证字段为多语言字段
				foreach($lang_envs as $lang_item)
				{
					$item[0] = $base_name."_".$lang_item['id'];
					$new_validate[] = $item;
				}
			}
			else 
			{
				$new_validate[] = $item;
			}
		}
		$this->_validate = $new_validate;
		parent::__construct();
	}

}
?>