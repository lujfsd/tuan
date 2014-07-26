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

//语言
class LangConfModel extends CommonModel {
	protected $_validate = array(
		array('lang_name','require',LANG_NAME_REQUIRE), 
		array('show_name','require',SHOW_NAME_REQUIRE),
		array('time_zone','number',TIME_ZONE_MUST_BE_NUM,2),  	
	);	
	
	/*public function where($where){
		//后台只有中文 add by chenfq 2011-04-12
		if (fanweC('DEFAULT_LANG') != C('DEFAULT_LANG')){
			$where = str_replace(C('DEFAULT_LANG'),fanweC('DEFAULT_LANG'),$where);
		}
		return parent::where($where);
	}*/
}
?>