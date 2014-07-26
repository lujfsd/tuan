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

// fanwe标签库，主要用于部份控件的多语方展示

import('TagLib');
class TagLibFanwe extends TagLib
{//类定义开始
  	public function _initialize() {
        $this->xml = dirname(__FILE__).'/Tags/fanwe.xml';
    }

    /**
     +----------------------------------------------------------
     * editor标签解析 插入可视化编辑器
     * 格式： <html:editor id="editor" name="remark" type="FCKeditor" content="{$vo.remark}" />
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string|void
     +----------------------------------------------------------
     */
    public function _editor($attr)
    {
    	$pk = D(MODULE_NAME)->getPk();
		$item_id = intval($_REQUEST[$pk]);

    	$lang_conf = C("LANG_CONF");  //存在多语言的表及列  by matthew    
    	//$lang_envs = D("LangConf")->findAll(); //当前所有的语言环境
    	$table_name = parse_name(MODULE_NAME);	
    	
    	
        $tag        =	$this->parseXmlAttr($attr,'editor');
        $id			=	!empty($tag['id'])?$tag['id']: 'editor_'.rand(1000,9999);
        $name   	=	$tag['name'];
        $style   	    =	!empty($tag['style'])?$tag['style']:'';
        $width		=	!empty($tag['width'])?$tag['width']: '650';
        $height     =	!empty($tag['height'])?$tag['height'] :'300px';
        $type       =   $tag['type'] ;
		$value = $tag['dataSource'];
        if(isset($lang_conf[$table_name][$tag['name']]))
        {
//        	if(strtoupper($type)=='KINDEDITOR'||strtoupper($type)=='EMAILEDITOR')
//        	$parseStr="<script type='text/javascript' charset='utf-8' src='__TMPL__ThemeFiles/Js/kindeditor/kindeditor.js'></script>";
//        	else
        	$parseStr = "";
        	//foreach($lang_envs as $lang_item)
        	//{
        		$lang_item['id'] = 1;
        		if($value)
        		{
        			$dataSource = $value;
        		}
        		else
        		{
        		$dataSource = D(MODULE_NAME)->getById($item_id);
        		
        		$dataSource = $dataSource[$tag['name']."_".$lang_item['id']];  
        		
        		} 
				
	        	switch(strtoupper($type)) {
		            case 'FCKEDITOR':
		                $parseStr.=	"<div  style='margin-bottom:5px; '>".'<!-- 编辑器调用开始 --><script type="text/javascript" src="__TMPL__ThemeFiles/Js/fckeditor/fckeditor.js"></script><textarea id="'.$id."_".$lang_item['id'].'" name="'.$name."_".$lang_item['id'].'">'.$dataSource.'</textarea><script type="text/javascript"> var oFCKeditor = new FCKeditor( "'.$id."_".$lang_item['id'].'","'.$width.'","'.$height.'" ) ; oFCKeditor.BasePath = "__TMPL__ThemeFiles/Js/fckeditor/" ; oFCKeditor.ReplaceTextarea() ;function resetEditor(){setContents("'.$id."_".$lang_item['id'].'",document.getElementById("'.$id."_".$lang_item['id'].'").value)}; function saveEditor(){document.getElementById("'.$id."_".$lang_item['id'].'").value = getContents("'.$id."_".$lang_item['id'].'");} function InsertHTML(html){ var oEditor = FCKeditorAPI.GetInstance("'.$id."_".$lang_item['id'].'") ;if (oEditor.EditMode == FCK_EDITMODE_WYSIWYG ){oEditor.InsertHtml(html) ;}else	alert( "FCK必须处于WYSIWYG模式!" ) ;}</script> <!-- 编辑器调用结束 -->'."".'</div>';
		                break;  
					case 'FCKBASIC':
		                $parseStr.=	"<div  style='margin-bottom:5px; '>".'<!-- 编辑器调用开始 --><script type="text/javascript" src="__TMPL__ThemeFiles/Js/fckeditor/fckeditor.js"></script><textarea id="'.$id."_".$lang_item['id'].'" name="'.$name."_".$lang_item['id'].'">'.$dataSource.'</textarea><script type="text/javascript"> var oFCKeditor = new FCKeditor( "'.$id."_".$lang_item['id'].'","'.$width.'","'.$height.'" ) ; oFCKeditor.BasePath = "__TMPL__ThemeFiles/Js/fckeditor/" ;oFCKeditor.ToolbarSet = "Basic" ; oFCKeditor.ReplaceTextarea() ;function resetEditor(){setContents("'.$id."_".$lang_item['id'].'",document.getElementById("'.$id."_".$lang_item['id'].'").value)}; function saveEditor(){document.getElementById("'.$id."_".$lang_item['id'].'").value = getContents("'.$id."_".$lang_item['id'].'");} function InsertHTML(html){ var oEditor = FCKeditorAPI.GetInstance("'.$id."_".$lang_item['id'].'") ;if (oEditor.EditMode == FCK_EDITMODE_WYSIWYG ){oEditor.InsertHtml(html) ;}else	alert( "FCK必须处于WYSIWYG模式!" ) ;}</script> <!-- 编辑器调用结束 -->'."".'</div>';
		                break;  
		            case 'KINDEDITOR':
						$parseStr.="<script type='text/javascript'>".
						"var editor;".
						"KindEditor.ready(function(K) {".
    					"editor = K.create('#".$id."_".$lang_item['id']."', {".
						"uploadJson :'__ROOT__/".fanweC("ADMIN_FILE_NAME")."?m=FileUpload&a=doUpload',".
       					"cssPath : '__TMPL__ThemeFiles/Css/style.css',".
						"allowFileManager : true".
   						
						"});".
						"});".
  						"</script>";
		            	$parseStr.="<div  style='margin-bottom:5px;widht:100% '><textarea id='".$id."_".$lang_item['id']."' name='".$name."_".$lang_item['id']."' style='".$style."' >".$dataSource."</textarea> </div>";
		            	break;
		            case 'EMAILEDITOR':
						$parseStr.="<script type='text/javascript'>".
    					"var editor;".
						"KindEditor.ready(function(K) {".
    					"editor = K.create('#".$id."', {".
        				"uploadJson :'__ROOT__/".fanweC("ADMIN_FILE_NAME")."?m=FileUpload&a=doUpload',".
       					"cssPath : '__TMPL__ThemeFiles/Css/style.css',".
						"allowFileManager : false,".
						"resizeMode : 0,".
						"items : [".
				        "'fontname','fullscreen', 'fontsize', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',".
				        "'removeformat', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',".
				        "'insertunorderedlist', 'emoticons', 'image', 'link']".
										
   						"});".
				        "});".
  						"</script>";
		            	$parseStr.="<div  style='margin-bottom:5px;widht:100% '><textarea id='".$id."' name='".$name."' style='".$style."' >".$dataSource."</textarea> </div>";
		            	break;
		            default :
		                $parseStr.=  "<div  style='margin-bottom:5px;widht:100%'>".'<textarea id="'.$id."_".$lang_item['id'].'" style="'.$style.'" name="'.$name."_".$lang_item['id'].'" >'.$dataSource.'</textarea></div>';
		        }
        	//}
        }
        else 
        {        
		
//        	if(strtoupper($type)=='KINDEDITOR'||strtoupper($type)=='EMAILEDITOR')
//        	$parseStr="<script type='text/javascript'  src='__TMPL__ThemeFiles/Js/kindeditor/kindeditor.js'></script>";
//        	else
        	$parseStr = "";
			
        	$dataSource = D(MODULE_NAME)->getById($item_id);
        	$dataSource = $dataSource[$tag['name']];
			
        	if(!$dataSource)
        	{
        		$dataSource = $tag['datasource'];
        	}
        	
        	
        		switch(strtoupper($type))
        		{
	            case 'FCKEDITOR':
	                $parseStr   =	'<!-- 编辑器调用开始 --><script type="text/javascript" src="__TMPL__ThemeFiles/Js/fckeditor/fckeditor.js"></script><textarea id="'.$id.'" name="'.$name.'">'.$dataSource.'</textarea><script type="text/javascript"> var oFCKeditor = new FCKeditor( "'.$id.'","'.$width.'","'.$height.'" ) ; oFCKeditor.BasePath = "__TMPL__ThemeFiles/Js/fckeditor/" ; oFCKeditor.ReplaceTextarea() ;function resetEditor(){setContents("'.$id.'",document.getElementById("'.$id.'").value)}; function saveEditor(){document.getElementById("'.$id.'").value = getContents("'.$id.'");} function InsertHTML(html){ var oEditor = FCKeditorAPI.GetInstance("'.$id.'") ;if (oEditor.EditMode == FCK_EDITMODE_WYSIWYG ){oEditor.InsertHtml(html) ;}else	alert( "FCK必须处于WYSIWYG模式!" ) ;}</script> <!-- 编辑器调用结束 -->';
	                break;  
				case 'FCKBASIC':
	                $parseStr   =	'<!-- 编辑器调用开始 --><script type="text/javascript" src="__TMPL__ThemeFiles/Js/fckeditor/fckeditor.js"></script><textarea id="'.$id.'" name="'.$name.'">'.$dataSource.'</textarea><script type="text/javascript"> var oFCKeditor = new FCKeditor( "'.$id.'","'.$width.'","'.$height.'" ) ; oFCKeditor.BasePath = "__TMPL__ThemeFiles/Js/fckeditor/" ; oFCKeditor.ToolbarSet = "Basic" ;oFCKeditor.ReplaceTextarea() ;function resetEditor(){setContents("'.$id.'",document.getElementById("'.$id.'").value)}; function saveEditor(){document.getElementById("'.$id.'").value = getContents("'.$id.'");} function InsertHTML(html){ var oEditor = FCKeditorAPI.GetInstance("'.$id.'") ;if (oEditor.EditMode == FCK_EDITMODE_WYSIWYG ){oEditor.InsertHtml(html) ;}else	alert( "FCK必须处于WYSIWYG模式!" ) ;}</script> <!-- 编辑器调用结束 -->';
	                break;   
	            case 'KINDEDITOR':
						$parseStr.="<script type='text/javascript'>".
    					"var editor;".
						"KindEditor.ready(function(K) {".
    					"editor = K.create('#".$id."', {".
        				"uploadJson :'__ROOT__/".fanweC("ADMIN_FILE_NAME")."?m=FileUpload&a=doUpload',".
       					"cssPath : '__TMPL__ThemeFiles/Css/style.css',".
						"allowFileManager : true".
   						"});".
						"});".
  						"</script>";
		            	$parseStr.="<div  style='margin-bottom:5px;widht:100%;'><textarea id='".$id."' name='".$name."' style='".$style."' >".$dataSource."</textarea> </div>";
		            	break;    
		        case 'EMAILEDITOR':
						$parseStr.="<script type='text/javascript'>".
    					"var editor;".
						"KindEditor.ready(function(K) {".
    					"editor = K.create('#".$id."', {".
						"uploadJson :'__ROOT__/".fanweC("ADMIN_FILE_NAME")."?m=FileUpload&a=doUpload',".
    					"urlType : 'domain',".
       					"cssPath : '__TMPL__ThemeFiles/Css/style.css',".
						"allowFileManager : true,".
						"resizeMode : 0,".
						"filterMode : false,".
						"items : [".
				        "'source' ,'fullscreen','fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',".
				        "'removeformat', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',".
				        "'insertunorderedlist', 'emoticons', 'image', 'link']".
						
				        "});".				
   						"});".
  						"</script>";
		            	$parseStr.="<div  style='margin-bottom:5px;widht:100% '><textarea id='".$id."' name='".$name."' style='".$style."' >".$dataSource."</textarea> </div>";
		            	break;     
	            default :
	                $parseStr  =  '<textarea id="'.$id.'" style="'.$style.'" name="'.$name.'" >'.$dataSource.'</textarea>';
	       		}	
	        
        } 
        
        
        

        return $parseStr;
    }
   
    
   /**
     +----------------------------------------------------------
     * textbox标签解析 该标签可自动解析当前所有语言的相关字段 
     * 格式： <fanwe:textbox dataSource="" name="" id="" class="" />
     *
     * 其中 dataSource 为json封装的数组 ，格式为: array('name_1'=>'中文','name_3'=>'英文')
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function _textbox($attr)
    {
    	$pk = D(MODULE_NAME)->getPk();
    	$id = intval($_REQUEST[$pk]);
    	$lang_conf = C("LANG_CONF");  //存在多语言的表及列  by matthew    
    	//$lang_envs = D("LangConf")->findAll(); //当前所有的语言环境
    		
        $tag  = $this->parseXmlAttr($attr,'list');
        $table_name = parse_name(MODULE_NAME);			 //当前操作的表名
        if(isset($lang_conf[$table_name][$tag['name']])||$tag['ignore'])
        {
        	$parseStr = "";
        	if($tag['ignore'])
        	{
        		$array_end = ($tag['isarray'])?"[]":"";
//        	    foreach($lang_envs as $lang_item)
//        		{
					$lang_item['id'] = 1;
        			if($tag['value']!='')
        			$curr_value = "{".$tag['value']."_".$lang_item['id']."}";
        			else 
        			$curr_value = "";
	       			$parseStr .= "<input type='text' name='".$tag['name']."_".$lang_item['id'].$array_end."' id='".$tag['id']."_".$lang_item['id']."' class='".$tag['class']."' value='".$curr_value."' /> &nbsp;";
//        		}       		
        	}
        	else 
        	{
//	        	foreach($lang_envs as $lang_item)
//	        	{
					$lang_item['id'] = 1;
	        		$dataSource = D(MODULE_NAME)->getById($id);
	        		$dataSource = $dataSource[$tag['name']."_".$lang_item['id']];   
	        		    		
	        		$parseStr .= "<div  style='margin-bottom:5px; '><input type='text' name='".$tag['name']."_".$lang_item['id']."' id='".$tag['id']."_".$lang_item['id']."' class='".$tag['class']."' value='".$dataSource."' /> </div>";
//	        	}
        	}
        }
        else 
        {        
        	$dataSource = D(MODULE_NAME)->getById($id);
        	$dataSource = $dataSource[$tag['name']];	
        	$parseStr = "<input type='text' name='".$tag['name']."' id='".$tag['id']."' class='".$tag['class']."' value='".$dataSource."'  />";
        }       
        return $parseStr;
    }
    

    public function _label($attr)
    {
    	$pk = D(MODULE_NAME)->getPk();
    	$id = intval($_REQUEST[$pk]);
    	$lang_conf = C("LANG_CONF");  //存在多语言的表及列  by matthew    
    	//$lang_envs = D("LangConf")->findAll(); //当前所有的语言环境
    		
        $tag  = $this->parseXmlAttr($attr,'list');
        $table_name = parse_name(MODULE_NAME);			 //当前操作的表名
        $parseStr = "";
        if(isset($lang_conf[$table_name][$tag['name']])||$tag['ignore'])
        {
        	
        		$array_end = ($tag['isarray'])?"[]":"";
//        	    foreach($lang_envs as $lang_item)
//        		{
        			$lang_item['id'] = 1;
        			if($tag['value']!='')
        			$curr_value = "{".$tag['value']."_".$lang_item['id']."}";
        			else 
        			$curr_value = "";
	       			$parseStr .= "<label>".$curr_value."</label> (".$lang_item['lang_name'].")&nbsp;";
//        		}          	
        }
        return $parseStr;
    }
    
    
/**
     +----------------------------------------------------------
     * textarea标签解析 该标签可自动解析当前所有语言的相关字段 
     * 格式： <fanwe:textarea name="" id="" class="" />
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function _textarea($attr)
    {
    	$pk = D(MODULE_NAME)->getPk();
    	$id = intval($_REQUEST[$pk]);
    	
    	$lang_conf = C("LANG_CONF");  //存在多语言的表及列  by matthew    
    	//$lang_envs = D("LangConf")->findAll(); //当前所有的语言环境
    		
        $tag  = $this->parseXmlAttr($attr,'list');
        $table_name = parse_name(MODULE_NAME);			 //当前操作的表名
        if(isset($lang_conf[$table_name][$tag['name']]))
        {
        	$parseStr = "";
//        	foreach($lang_envs as $lang_item)
//        	{
				$lang_item['id'] = 1;
        		$dataSource = D(MODULE_NAME)->getById($id);        		
        		$dataSource = $dataSource[$tag['name']."_".$lang_item['id']];           		
        		$parseStr .= "<div style='margin-bottom:5px; '><textarea name='".$tag['name']."_".$lang_item['id']."' id='".$tag['id']."_".$lang_item['id']."' class='".$tag['class']."' rows='".$tag['rows']."' cols='".$tag['cols']."' >".$dataSource."</textarea> </div>";
//        	}
        }
        else 
        {        
        	$dataSource = D(MODULE_NAME)->getById($id);
        	$dataSource = $dataSource[$tag['name']];	
        	$parseStr = "<textarea name='".$tag['name']."' id='".$tag['id']."' class='".$tag['class']."' rows='".$tag['rows']."' cols='".$tag['cols']."' style='".$tag['style']."' >".$dataSource."</textarea>";
        }       
        return $parseStr;
    }
    
    
    
/**
     +----------------------------------------------------------
     * list标签解析 该标签可自动解析当前所有语言的相关字段 
     * 格式： <fanwe:list datasource="" show="" />
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function _list($attr)
    {
    	$lang_conf = C("LANG_CONF");  //存在多语言的表及列  by matthew    
    	//$lang_envs = D("LangConf")->findAll(); //当前所有的语言环境
    		
        $tag        = $this->parseXmlAttr($attr,'list');
        $id         = $tag['id'];                       //表格ID
        $datasource = $tag['datasource'];               //列表显示的数据源VoList名称
        $pk         = empty($tag['pk'])?'id':$tag['pk'];//主键名，默认为id
        $style      = $tag['style'];                    //样式名
        $name       = !empty($tag['name'])?$tag['name']:'vo';                 //Vo对象名
        $action     = $tag['action'];                   //是否显示功能操作
        $checkbox   = $tag['checkbox'];                 //是否显示Checkbox
        $order   = $tag['order'];                 //是否排序
        $child = $tag['child'];
		$preload = $tag['preload'];
        $ext = $tag['ext'];  //扩展的参数
		if (empty($order)){
			$order = true;
		}
		else 
		{
			$order = false;
		}
        if(isset($tag['actionlist'])) {
            $actionlist = explode(',',trim($tag['actionlist']));    //指定功能列表
        }

        if(substr($tag['show'],0,1)=='$') {
            $show   = $this->tpl->get(substr($tag['show'],1));
        }else {
            $show   = $tag['show'];
        }
        $tmp_show       = explode(',',$show);                //列表显示字段列表	
        
        $show = array();	
        $table_name = parse_name(MODULE_NAME);			 //当前操作的表名
   		foreach($tmp_show as $key=>$val) {
        	$fields[] = explode(':',$val);
        } 		

        foreach($fields as $field) 
        {//显示指定的字段
            $property = explode('|',$field[0]);
            $showname = explode('|',$field[1]);
            $field_2 = isset($field[2])?":".$field[2]:"";
            if(isset($lang_conf[$table_name][$property[0]]))
            {            	
//            	foreach($lang_envs as $lang_item)
//            	{
					$lang_item['id'] = 1;
            		$item = "";
            		$item.=$property[0]."_".$lang_item['id'];  //生成当前语言环境的字段名称
            		for($i=1;$i<count($property);$i++)
            		{
            			$item.="|".$property[$i];
            			
            		}
            		$item.=":".$showname[0];
            		for($i=1;$i<count($showname);$i++)
            		{
            			$item.="|".$showname[$i];
              		}
            		$item.=$field_2;
               		$show[] = $item;
//            	}
            }
            else 
            {
            		$item = "";
            		$item.=$property[0];  //生成当前语言环境的字段名称
            		for($i=1;$i<count($property);$i++)
            		{
            			$item.="|".$property[$i];
            		}
            		$item.=":".$showname[0];
            		for($i=1;$i<count($showname);$i++)
            		{
            			$item.="|".$showname[$i];
            		}
            		$item.=$field_2;
            		$show[] = $item;
            }
        }

        //计算表格的列数
        $colNum     = count($show);
        if(!empty($checkbox))   $colNum++;
        if(!empty($action))     $colNum++;

        //显示开始
		$parseStr	= "<!-- Think 系统列表组件开始 -->\n";
        $parseStr  .= '<table id="'.$id.'" class="'.$style.'" cellpadding=0 cellspacing=0 >';
        //$parseStr  .= '<tr><td height="5" colspan="'.$colNum.'" class="topTd" ></td></tr>';
        $parseStr  .= '<tr class="row" >';
        //列表需要显示的字段
        $fields = array();
        foreach($show as $key=>$val) {
        	$fields[] = explode(':',$val);
        }
        if(!empty($checkbox) && 'true'==strtolower($checkbox)) { //如果指定需要显示checkbox列
            $parseStr .='<th width="8"><input type="checkbox" id="check" onclick="CheckAll(\''.$id.'\')"></th>';
        }
        

        
        foreach($fields as $field) {//显示指定的字段
            $property = explode('|',$field[0]);
            $showname = explode('|',$field[1]);
            if(isset($showname[1])) {
                $parseStr .= '<th width="'.$showname[1].'">';
            }else {
                $parseStr .= '<th>';
            }
            $showname[2] = isset($showname[2])?$showname[2]:$showname[0];
 
            if ($order){
            	if($ext&&$ext!='')
	            	$parseStr .= '<a href="javascript:sortBy(\''.$property[0].'\',\'{$sort}\',\''.ACTION_NAME.'\',\''.$ext.'\')" title="按照'.$showname[2].'{$sortType} ">'.$showname[0].'<eq name="order" value="'.$property[0].'" ><img src="'.__TMPL__.'ThemeFiles/Images/{$sortImg}.gif" width="12" height="17" border="0" align="absmiddle"></eq></a></th>';
	            else
	            	$parseStr .= '<a href="javascript:sortBy(\''.$property[0].'\',\'{$sort}\',\''.ACTION_NAME.'\')" title="按照'.$showname[2].'{$sortType} ">'.$showname[0].'<eq name="order" value="'.$property[0].'" ><img src="'.__TMPL__.'ThemeFiles/Images/{$sortImg}.gif" width="12" height="17" border="0" align="absmiddle"></eq></a></th>';
            }else{
	           $parseStr .= $showname[0].'</th>';
            }
        }
        if(!empty($action)) {//如果指定显示操作功能列
            $parseStr .= '<th >操作</th>';
        }

        $parseStr .= '</tr>';
        $parseStr .= '<volist name="'.$datasource.'" id="'.$name.'" ><tr class="row" onmouseover="over(event)" onmouseout="out(event)" onclick="change(event)" >';	//支持鼠标移动单元行颜色变化 具体方法在js中定义

        $keyname= 'key';
        if($preload) $keyname='key[]';
        if(!empty($checkbox)) {//如果需要显示checkbox 则在每行开头显示checkbox
            $parseStr .= '<td><if condition="($'.$name.'[\'level\'] eq \'0\')or($'.$name.'[\'level\'] gt 0)"><else /><input type="checkbox" class="'.$keyname.'" name="'.$keyname.'"	value="{$'.$name.'.'.$pk.'}" <if condition="$'.$name.'[\'checked\']">checked="checked"</if> ></if></td>';
        }

        foreach($fields as $field) {
            //显示定义的列表字段
            $parseStr   .=  '<td>';
            if(!empty($field[2])) {
                // 支持列表字段链接功能 具体方法由JS函数实现
                $href = explode('|',$field[2]);
                
            	$property = explode('|',$field[0]);
	                if(count($property)>1) {
	                    $field_show = $property[0];
	                }else {
	                    $field_show = $field[0];
	                }
	                
                if(count($href)>1) {
                    //指定链接传的字段值
                    // 支持多个字段传递
                   
                    $array = explode('^',$href[1]);
                    $temp = array();
	                
                    if(count($array)>1) {
                        foreach ($array as $a){                        	
                            $temp[] =  '\'{$'.$name.'.'.$a.'|addslashes}\'';
                        }
                        $parseStr .= '<a href="javascript:'.$href[0].'('.implode(',',$temp).')" title="{$'.$name.'.'.$field_show.'|strip_tags}">';
                    }else{
                        $parseStr .= '<a href="javascript:'.$href[0].'(\'{$'.$name.'.'.$href[1].'|addslashes}\')" title="{$'.$name.'.'.$field_show.'|strip_tags}">';
                    }
                }else {
                    //如果没有指定默认传编号值
                    $parseStr .= '<a href="javascript:'.$field[2].'(\'{$'.$name.'.'.$pk.'|addslashes}\')" title="{$'.$name.'.'.$field_show.'|strip_tags}">';
                }
            }
            if(strpos($field[0],'^')) {
                $property = explode('^',$field[0]);
                foreach ($property as $p){
                    $unit = explode('|',$p);
                    if(count($unit)>1) {
                        $parseStr .= '{$'.$name.'.'.$unit[0].'|'.$unit[1].'} ';
                    }else {
                        $parseStr .= '{$'.$name.'.'.$p.'} ';
                    }
                }
            }else{
                $property = explode('|',$field[0]);
                if(count($property)>1) {
                    $parseStr .= '{$'.$name.'.'.$property[0].'|'.$property[1].'}';
                }else {
                    $parseStr .= '{$'.$name.'.'.$field[0].'}';
                }
            }
            if(!empty($field[2])) {
                $parseStr .= '</a>';
            }
            $parseStr .= '&nbsp;</td>';

        }
        if(!empty($action)) {//显示功能操作
            if(!empty($actionlist[0])) {//显示指定的功能项
                $parseStr .= '<td>';
                foreach($actionlist as $val) {
					if(strpos($val,':')) {
						$a = explode(':',$val);
						$b = explode('|',$a[1]);
						if(count($b)>1) {
							$c = explode('|',$a[0]);
							if(count($c)>1) {
								$parseStr .= '<a href="javascript:'.$c[1].'(\'{$'.$name.'.'.$pk.'}\')"><?php if(0== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[1].'<?php } ?></a><a href="javascript:'.$c[0].'({$'.$name.'.'.$pk.'})"><?php if(1== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[0].'<?php } ?></a>&nbsp;';
							}else {
								$parseStr .= '<a href="javascript:'.$a[0].'(\'{$'.$name.'.'.$pk.'}\')"><?php if(0== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[1].'<?php } ?><?php if(1== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[0].'<?php } ?></a>&nbsp;';
							}

						}else {
							$parseStr .= '<a href="javascript:'.$a[0].'(\'{$'.$name.'.'.$pk.'}\')">'.$a[1].'</a>&nbsp;';
						}
					}else{
						$array	=	explode('|',$val);
						if(count($array)>2) {
							$parseStr	.= ' <a href="javascript:'.$array[1].'(\'{$'.$name.'.'.$array[0].'}\')">'.$array[2].'</a>&nbsp;';
						}else{
							$parseStr .= ' {$'.$name.'.'.$val.'}&nbsp;';
						}
					}
                }
                $parseStr .= '</td>';
            }
        }
       // $parseStr	.= '</tr></volist><tr><td height="5" colspan="'.$colNum.'" class="bottomTd"></td></tr></table>';
        $parseStr	.= '</tr></volist></table>';
        $parseStr	.= "\n<!-- Think 系统列表组件结束 -->\n";
        return $parseStr;
    }
    
/**
     +----------------------------------------------------------
     * imageBtn标签解析
     * 格式： <html:imageBtn type="" value="" />
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string|void
     +----------------------------------------------------------
     */
    public function _imageBtn($attr)
    {
        $tag        = $this->parseXmlAttr($attr,'imageBtn');
        $name       = $tag['name'];                //名称
        $value      = $tag['value'];                //文字
        $id         = $tag['id'];                //ID
        $style      = $tag['style'];                //样式名
        $click      = $tag['click'];                //点击
        $type       = empty($tag['type'])?'button':$tag['type'];                //按钮类型

        if(!empty($name)) {
            $parseStr   = '<input type="'.$type.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" onclick="'.$click.'" class="'.$name.' imgButton">';
        }else {
        	$parseStr   = '<input type="'.$type.'" id="'.$id.'"  name="'.$name.'" value="'.$value.'" onclick="'.$click.'" class="button">';
        }

        return $parseStr;
    }
    
    
    
/**
     +----------------------------------------------------------
	<!-- 带数据源的 textarea --> 
     * textarea标签解析 该标签可自动解析当前所有语言的相关字段,
     * 格式： <fanwe:textarea_n name="" id="" class="" />
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function _textarea_n($attr)
    {
    	
    	$lang_conf = C("LANG_CONF");  //存在多语言的表及列  by matthew    
    	//$lang_envs = D("LangConf")->findAll(); //当前所有的语言环境
    		
        $tag  = $this->parseXmlAttr($attr,'list');
        $table_name = parse_name(MODULE_NAME);			 //当前操作的表名
        if(isset($lang_conf[$table_name][$tag['name']]))
        {
        	$parseStr = "";
//        	foreach($lang_envs as $lang_item)
//        	{
				$lang_item['id'] = 1;
        		$dataSourceName = "{\$".$tag['datasource']."_".$lang_item['id']."}";          		       		
        		$parseStr .= "<div style='margin-bottom:5px; '><textarea name='".$tag['name']."_".$lang_item['id']."' id='".$tag['id']."_".$lang_item['id']."' class='".$tag['class']."' rows='".$tag['rows']."' cols='".$tag['cols']."' >".$dataSourceName."</textarea> (".$lang_item['lang_name'].")</div>";
//        	}
        }
        else 
        {        
        	$dataSource = "{\$".$tag['datasource']."}";
        	$parseStr = "<textarea name='".$tag['name']."' id='".$tag['id']."' class='".$tag['class']."' rows='".$tag['rows']."' cols='".$tag['cols']."' >".$dataSource."</textarea>";
        }       
        return $parseStr;
    }
        
    
   /**
     +----------------------------------------------------------
	<!-- 带数据源的 textbox_n --> 
     * textbox标签解析 该标签可自动解析当前所有语言的相关字段 
     * 格式： <fanwe:textbox dataSource="" name="" id="" class="" />
     *
     * 其中 dataSource 为json封装的数组 ，格式为: array('name_1'=>'中文','name_3'=>'英文')
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function _textbox_n($attr)
    {
    	$lang_conf = C("LANG_CONF");  //存在多语言的表及列  by matthew    
    	//$lang_envs = D("LangConf")->findAll(); //当前所有的语言环境
    		
        $tag  = $this->parseXmlAttr($attr,'list');
        $table_name = parse_name(MODULE_NAME);			 //当前操作的表名
        if(isset($lang_conf[$table_name][$tag['name']]))
        {
        	$parseStr = "";
//	        foreach($lang_envs as $lang_item)
//	        {
				$lang_item['id'] = 1;
	        	$dataSourceName = "{\$".$tag['datasource']."_".$lang_item['id']."}";     		
	        	$parseStr .= "<div  style='margin-bottom:5px; '><input type='text' name='".$tag['name']."_".$lang_item['id']."' id='".$tag['id']."_".$lang_item['id']."' class='".$tag['class']."' value='".$dataSourceName."' /> (".$lang_item['lang_name'].")</div>";
//	        }
        }else 
        {        
        	$dataSource = "{\$".$tag['datasource']."}";	
        	$parseStr = "<input type='text' name='".$tag['name']."' id='".$tag['id']."' class='".$tag['class']."' value='".$dataSource."'  />";
        }       
        return $parseStr;
    }    
}//类定义结束
?>