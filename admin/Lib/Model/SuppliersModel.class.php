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

class SuppliersModel extends CommonModel{
	protected $_validate = array(
			array('name','require',SUPPLIERS_NAME_REQUIRE),
			array('cate_id','gtZero',"请选择分类",0,'function'), // 自定义函数验证密码格式
	);
		
	protected $_auto = array ( 		
		array('status','1'),  // 新增的时候把status字段设置为1
	);
	
	//生成图片
	public function MakeSupplierMaps($supppid=0,$suppliersinfo=array(),$reMk = false)
	{
		$re_name= "";
		if(!$suppliersinfo)
			$suppliersinfo = M("SuppliersDepart")->where("supplier_id ={$supppid}")->order("is_main desc")->limit(1)->find();
		
		if(!empty($suppliersinfo['xpoint'])&&!empty($suppliersinfo['ypoint']))
		{
			if(!is_dir(getcwd().'/Public/upload/ditu/'))
				mkdir(getcwd().'/Public/upload/ditu/');
				
			$file_name =getcwd()."/Public/upload/ditu/".md5($suppliersinfo['depart_name']).".jpg";
			$re_name ="/Public/upload/ditu/".md5($suppliersinfo['depart_name']).".jpg";
			if(!file_exists($file_name)|| $reMk ==true)
			{
				//$server = "http://maps.google.com/staticmap";
				//if(a_fanweC('DEFAULT_LANG') == 'zh-cn')
					//$server = "http://ditu.google.cn/staticmap";		
				//$str = @file_get_contents($server."?center=".$suppliersinfo['ypoint'].",".$suppliersinfo['xpoint']."&zoom=14&size=255x255&maptype=mobile&markers=".$suppliersinfo['ypoint'].",".$suppliersinfo['xpoint']);
				if(fanweC('DEFAULT_LANG') == 'en-us')
				{
					$server = "http://maps.google.com/staticmap";	
					$str = @file_get_contents($server."?center=".$suppliersinfo['ypoint'].",".$suppliersinfo['xpoint']."&zoom=14&size=255x255&maptype=mobile&markers=".$suppliersinfo['ypoint'].",".$suppliersinfo['xpoint']);
				}
				else
				{
					$server = "http://api.map.baidu.com/staticimage";					
					$str = @file_get_contents($server."?center=".$suppliersinfo['xpoint'].",".$suppliersinfo['ypoint']."&zoom=14&width=200&height=200&markers=".$suppliersinfo['xpoint'].",".$suppliersinfo['ypoint']);
				}				
			
				$google_map_im = @imagecreatefromstring($str);
				imagejpeg($google_map_im,$file_name,100);
			}
		}
		return $re_name;
	}
}
?>