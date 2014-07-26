<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * 静态缓存类
 * 支持静态缓存规则定义
 +------------------------------------------------------------------------------
 * @category   Think
 * @package  Think
 * @subpackage  Util
 * @author liu21st <liu21st@gmail.com>
 * @version  $Id$
 +------------------------------------------------------------------------------
 */
class HtmlCache extends Think
{
    //删除指定缓存
    static function delHtmlCache($m_name,$a_name, $city_id = 0) {
    	if (empty($city_id) || $city_id == 0){
    		$city_id = intval(Cookie::get('cityID'));
			if ($city_id==0)
				$city_id = intval(intval($_SESSION["cityID"]));			
    	}
    	$rule = $city_id.'#'.strtolower($m_name).'_'.strtolower($a_name).'#';
       	  
        $files = Dir::getList(HTML_PATH);
        //dump($rule);
        //dump($files);
    	foreach($files as $file)
		{
			if($file!='..'&&$file!='.' && preg_match("/{$rule}/",$file))
			{
				//dump(HTML_PATH.$file);
				@unlink(HTML_PATH.$file);				
			}
		}
    }
    /**
     +----------------------------------------------------------
     * 读取静态缓存
     +----------------------------------------------------------
     * @access static
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    static function readHTMLCache()
    {   
    	//4、静态化后的界面，将不再启用ThinkPHP框架，而是直接返回静态页面     
        return ;
    }

    /**
     +----------------------------------------------------------
     * 写入静态缓存
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $content 页面内容
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    static public function writeHTMLCache($content)
    {
    	//HTML_FILE_NAME,REQUIRE_CACHE 在index.php中定义了
        if(REQUIRE_CACHE == true && defined('HTML_FILE_NAME')) {
            //静态文件写入
            // 如果开启HTML功能 检查并重写HTML文件
            // 没有模版的操作不生成静态文件
            if(!is_dir(dirname(HTML_FILE_NAME)))
                mk_dir(dirname(HTML_FILE_NAME));
         	$content = preg_replace('|(<div class="sysmsg-tip-top"></div>)(.*)(<div class="sysmsg-tip-bottom"></div>)|Us','',$content);
         	$content = str_replace('./Public/upload/',CND_URL.'/Public/upload/',$content);//替换图片路径
         	
         	//$str = '<input id="enter-address-mail" name="email" class="f-input f-mail" type="text" value="{$user_email}" size="20" />';
         	//$content = preg_replace('|(<input id="enter-address-mail")(.*)(/>)|Us',$str,$content);
            if( false === file_put_contents( HTML_FILE_NAME , $content ))
                throw_exception(L('_CACHE_WRITE_ERROR_'));
            
            redirect($_SERVER['REQUEST_URI']);
        }
        
        return ;
    }

}
?>