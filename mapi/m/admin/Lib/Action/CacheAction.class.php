<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class CacheAction extends CommonAction{

	public function clear_admin()
	{
		set_time_limit(0);
		es_session::close();
		clear_dir_file(get_real_path()."public/runtime/admin/Cache/");	
		clear_dir_file(get_real_path()."public/runtime/admin/Data/_fields/");		
		clear_dir_file(get_real_path()."public/runtime/admin/Temp/");	
		clear_dir_file(get_real_path()."public/runtime/admin/Logs/");	
		@unlink(get_real_path()."public/runtime/admin/~app.php");
		@unlink(get_real_path()."public/runtime/admin/~runtime.php");
		@unlink(get_real_path()."public/runtime/admin/lang.js");

		if (SYS_M == 'easethink'){

		}else if (SYS_M == 'ecshop'){
				
		}else if (SYS_M == 'shopex'){
				
		}else if (SYS_M == 'fanwe'){
			clear_dir_file(get_real_path()."app/Runtime/caches/");			
		}
		
		header("Content-Type:text/html; charset=utf-8");
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div>");
	}
	
	public function clear_parse_file()
	{
		set_time_limit(0);
		es_session::close();
		clear_dir_file(get_real_path()."public/runtime/statics/");	
		
		header("Content-Type:text/html; charset=utf-8");
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}
	
	public function clear_data()
	{
		set_time_limit(0);
		es_session::close();
		if(intval($_REQUEST['is_all'])==0)
		{
			
			//删除相关未自动清空的数据缓存
			clear_auto_cache("page_image");
			clear_auto_cache("recommend_hot_sale_list");
			clear_auto_cache("recommend_uc_topic");
			clear_auto_cache("youhui_page_recommend_youhui_list");
		}
		else
		{

			clear_dir_file(get_real_path()."public/runtime/data/");	
			
			$GLOBALS['cache']->clear();

			
			//后台
			clear_dir_file(get_real_path()."public/runtime/admin/Cache/");	
			clear_dir_file(get_real_path()."public/runtime/admin/Data/_fields/");		
			clear_dir_file(get_real_path()."public/runtime/admin/Temp/");	
			clear_dir_file(get_real_path()."public/runtime/admin/Logs/");	
			@unlink(get_real_path()."public/runtime/admin/~app.php");
			@unlink(get_real_path()."public/runtime/admin/~runtime.php");
			@unlink(get_real_path()."public/runtime/admin/lang.js");
			@unlink(get_real_path()."public/runtime/app/config_cache.php");	
			
			
		}
		header("Content-Type:text/html; charset=utf-8");
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}

	public function clear_image()
	{
		set_time_limit(0);
		es_session::close();
		$path  = APP_ROOT_PATH."public/attachment/";
		$this->clear_image_file($path);
		$path  = APP_ROOT_PATH."public/images/";
		$this->clear_image_file($path);
	

		
		header("Content-Type:text/html; charset=utf-8");
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}
	
	private function clear_image_file($path)
	{
	   if ( $dir = opendir( $path ) )
	   {
	            while ( $file = readdir( $dir ) )
	            {
	                $check = is_dir( $path. $file );
	                if ( !$check )
	                {
	                	if(preg_match("/_(\d+)x(\d+)/i",$file,$matches))
	                    @unlink ( $path . $file);                       
	                }
	                else 
	                {
	                 	if($file!='.'&&$file!='..')
	                 	{
	                 		$this->clear_image_file($path.$file."/");              			       		
	                 	} 
	                 }           
	            }
	            closedir( $dir );
	            return true;
	   }
	}
}
?>