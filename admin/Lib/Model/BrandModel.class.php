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

//品牌
class BrandModel extends MultiLangModel {
	protected $_validate = array(
			array('name','require',BRAND_NAME_REQUIRE), 
			array('brand_url','checkUrl',URL_FORMAT_ERROR,2,'function'), // 自定义函数验证密码格式
		);		
	protected $_map = array(
		'logo'	=>	'logo',  //用于单独操作的字段映射，在上传文件后更新
	);
		
}
?>