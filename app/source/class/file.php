<?php
	// 自动转换字符集 支持数组转换
function auto_charset($fContents,$from,$to){
    $from   =  strtoupper($from)=='UTF8'? 'utf-8':$from;
    $to       =  strtoupper($to)=='UTF8'? 'utf-8':$to;
    if( strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents)) ){
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if(is_string($fContents) ) {
        if(function_exists('mb_convert_encoding')){
            return mb_convert_encoding ($fContents, $to, $from);
        }elseif(function_exists('iconv')){
            return iconv($from,$to,$fContents);
        }else{
            return $fContents;
        }
    }
    elseif(is_array($fContents)){
        foreach ( $fContents as $key => $val ) {
            $_key =     auto_charset($key,$from,$to);
            $fContents[$_key] = auto_charset($val,$from,$to);
            if($key != $_key )
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else{
        return $fContents;
    }
}
	/**
	 * 上传图片的通公基础方法
	 *
	 * @param integer $water  0:不加水印 1:打印水印
	 * @param string $dir  上传的文件夹
	 * @param integer $uploadType 0:普通图片 1:产品图片
	 * @return array
	 */
	function uploadFile($water = 0,$dir='attachment',$uploadType=0,$showstatus = false)
	{		
		require_once ROOT_PATH.'app/source/class/UploadFile.class.php';
		require_once ROOT_PATH.'app/source/class/Image.class.php';
		$water_mark = getcwd().a_fanweC("WATER_IMAGE");   //配置于config
	    $alpha = a_fanweC("WATER_ALPHA");
	    $place = a_fanweC("WATER_POSITION");
	    
		$upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = a_fanweC('MAX_UPLOAD') ;  /* 配置于config */
        //设置上传文件类型
		
        $upload->allowExts  =  explode(',',a_fanweC('ALLOW_UPLOAD_EXTS')); /* 配置于config */
        
        if($uploadType)
        	$save_rec_Path = "/Public/upload/".$dir."/origin/".a_toDate(a_gmtTime(),'Ym')."/";  //上传产品时先存放原图
        else
        	$save_rec_Path = "/Public/upload/".$dir."/".a_toDate(a_gmtTime(),'Ym')."/";  //上传至服务器的相对路径  
        	      
        $savePath = getcwd().$save_rec_Path; //绝对路径
		if(!is_dir($savePath))
		{
			mk_dir($savePath);			
		}	
			
		$upload->saveRule = "uniqid";   //唯一
		$upload->savePath = $savePath;
        if($upload->upload())
        {
        	$uploadList = $upload->getUploadFileInfo();
         	foreach($uploadList as $k=>$fileItem)
        	{
        		if($uploadType) //产品上传时
        		{
        			$big_width = a_fanweC("BIG_WIDTH");
        			$big_height = a_fanweC("BIG_HEIGHT");
        			$small_width = a_fanweC("SMALL_WIDTH");
        			$small_height = a_fanweC("SMALL_HEIGHT");
        			
        			$file_name = $fileItem['savepath'].$fileItem['savename'];  //上图原图的地址
        			//开始缩放处理产品大图
        			$big_save_path = str_replace("origin","big",$savePath);  //大图存放图径
        			
        			if(!is_dir($big_save_path))
					{
						mk_dir($big_save_path);			
					}	
					$big_file_name = str_replace("origin","big",$file_name);	
					
					if(a_fanweC("AUTO_GEN_IMAGE") == 1)				
					Image::thumb($file_name,$big_file_name,'',$big_width,$big_height);
					else
					@copy($file_name,$big_file_name);
        			if($water&&file_exists($water_mark)&&a_fanweC("AUTO_GEN_IMAGE") == 1)
	        		{
	        			Image::water($big_file_name,$water_mark,$big_file_name,$alpha,$place);	
	        		}
	        		
					//开始缩放处理产品小图
        			$small_save_path = str_replace("origin","small",$savePath);  //小图存放图径
        			if(!is_dir($small_save_path))
					{
						mk_dir($small_save_path);			
					}
					$small_file_name = str_replace("origin","small",$file_name);
					Image::thumb($file_name,$small_file_name,'',$small_width,$small_height);
        			
        			$big_save_rec_Path = str_replace("origin","big",$save_rec_Path);  //大图存放的相对路径
        			$small_save_rec_Path = str_replace("origin","small",$save_rec_Path);  //大图存放的相对路径
        			$uploadList[$k]['recpath'] = $save_rec_Path;
        			$uploadList[$k]['bigrecpath'] = $big_save_rec_Path;
        			$uploadList[$k]['smallrecpath'] = $small_save_rec_Path;
        		}
        		else 
        		{
	        		$uploadList[$k]['recpath'] = $save_rec_Path;
	        		$file_name = $fileItem['savepath'].$fileItem['savename'];	        		
	        		if($water&&file_exists($water_mark))
	        		{
	        			Image::water($file_name,$water_mark,$file_name,$alpha,$place);	
	        		}
        		}
        	} 
        	if($showstatus)
        	{
	        	$result['status'] = true;
	        	$result['uploadList'] = $uploadList;
	        	$result['msg'] = '';
	        	return $result;
        	}
        	else
        	return $uploadList;
        }
        else 
        {
        	if($showstatus)
        	{
	        	$result['status'] = false;
	        	$result['uploadList'] = false;
	        	$result['msg'] = $upload->getErrorMsg();
	        	return $result;
        	}
        	else
        	return $uploadList;
        }
	}
	
	// 循环创建目录
	function mk_dir($dir, $mode = 0755)
	{
	  if (is_dir($dir) || @mkdir($dir,$mode)) return true;
	  if (!mk_dir(dirname($dir),$mode)) return false;
	  return @mkdir($dir,$mode);
	}
?>