<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($SHOP_NAME); ?>团购管理系统</title>
<link rel="stylesheet" type="text/css" href="__TMPL__ThemeFiles/Css/style.css" />
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/Base.js"></script>
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/prototype.js"></script>
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/mootools.js"></script>
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/Ajax/ThinkAjax.js"></script>
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/common.js"></script>
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/Util/ImageLoader.js"></script>
<script type='text/javascript' charset='utf-8' src='__TMPL__ThemeFiles/Js/kindeditor/kindeditor.js'></script>
<script type='text/javascript' charset='utf-8' src='__TMPL__ThemeFiles/Js/kindeditor/lang/zh_CN.js'></script>
<script language="JavaScript">
<!--
//指定当前组模块URL地址 
var URL = '__URL__';
var ROOT_PATH = '__ROOT__';
var admin_file = '<?php echo fanweC("ADMIN_FILE_NAME");?>';
var APP	 =	 '__APP__';
var PUBLIC = '__TMPL__ThemeFiles';
ThinkAjax.image = [	 '__TMPL__ThemeFiles/Images/loading2.gif', '__TMPL__ThemeFiles/Images/ok.gif','__TMPL__ThemeFiles/Images/update.gif' ]
ImageLoader.add("__TMPL__ThemeFiles/Images/bgline.gif","__TMPL__ThemeFiles/Images/bgcolor.gif","__TMPL__ThemeFiles/Images/titlebg.gif");
ImageLoader.startLoad();
var VAR_MODULE = '<?php echo c('VAR_MODULE');?>';
var VAR_ACTION = '<?php echo c('VAR_ACTION');?>';
var CURR_MODULE = '<?php echo ($module_name); ?>';
//-->
</script>
<script language="JavaScript">
//定义JS中使用的语言变量
var VIEW = '<?php echo (L("VIEW")); ?>';
var CONFIRM_DELETE = '<?php echo (L("CONFIRM_DELETE")); ?>';
var CONFIRM_DELETE_IMAGE = '<?php echo (L("CONFIRM_DELETE_IMAGE")); ?>';
var NO_SELECT = '<?php echo (L("NO_SELECT")); ?>';
var CHOOSE_RECYCLE_ITEM = '<?php echo (L("CHOOSE_RECYCLE_ITEM")); ?>';
var SELECT_EDIT_ITEM = '<?php echo (L("SELECT_EDIT_ITEM")); ?>';
var SELECT_DEL_ITEM	=	'<?php echo (L("SELECT_DEL_ITEM")); ?>';
var CONFIRM_DELETE_FILE = '<?php echo (L("CONFIRM_DELETE_FILE")); ?>';
var CONFIRM_FOREVER_DELETE = '<?php echo (L("CONFIRM_FOREVER_DELETE")); ?>';
var CONFIRM_DELETE_USER_DATA = '<?php echo (L("CONFIRM_DELETE_USER_DATA")); ?>';
var CONFIRM_RESTORE = '<?php echo (L("CONFIRM_RESTORE")); ?>';
var ATTR_PRICE	=	'<?php echo L("ATTR_PRICE");?>';
var ATTR_STOCK	=	'<?php echo L("ATTR_STOCK");?>';

//ThinkAjax.send(ROOT_PATH+'/services/ajax.php?run=autoSendMail','',doDelete);
//ThinkAjax.send(ROOT_PATH+'/services/ajax.php?run=autoSend','',doDelete);
</script>
</head>

<body onload="loadBar(0)">

<div id="loader" ><?php echo (L("PAGE_LOADING")); ?></div>
<div id="main" class="main" >
<div class="content">
<div class="title"><?php echo (L("EDIT_DATA")); ?> [ <a href="<?php echo u($module_name.'/index');?>"><?php echo (L("BACK_LIST")); ?></a> ]</div>
<div id="result" class="result none"></div>
<form method='post' id="form" name="form" action="<?php echo u('LangConf/update');?>"  enctype="multipart/form-data">
<table cellpadding=0 cellspacing=0 class="dataEdit" >
<tr>
	<td class="tRight" width="180"><?php echo (L("LANG_NAME")); ?>：</td>
	<td class="tLeft" >
	<input type="text" class="bLeftRequire" name="lang_name" value="<?php echo ($vo["lang_name"]); ?>" /><?php echo (L("LANG_NAME_TIP")); ?>
	</td>
</tr>
<tr style="display:none;"> 
	<td class="tRight tTop"><?php echo (L("SHOW_NAME")); ?>：</td>
	<td class="tLeft">
	<input type="text" class="bLeftRequire" name="show_name" value="<?php echo ($vo["show_name"]); ?>" />
	</td>
</tr>
<tr style="display:none;"> 
	<td class="tRight tTop"><?php echo (L("TIME_ZONE")); ?>：</td>
	<td class="tLeft">
	<input type="text" class="bLeft" name="time_zone" value="<?php echo ($vo["time_zone"]); ?>" /> <?php echo (L("TIME_ZONE_TIP")); ?>
	</td>
</tr>
<tr style="display:none;"> 
	<td class="tRight tTop"><?php echo (L("DEFAULT_CURRENCY")); ?>：</td>
	<td class="tLeft">
	<select name="currency" class="bLeft">
	<?php if(is_array($currency_list)): foreach($currency_list as $key=>$currency_item): ?><option value="<?php echo ($currency_item["id"]); ?>" <?php if($vo['currency'] == $currency_item['id']): ?>selected="selected"<?php endif; ?>><?php echo ($currency_item["name"]); ?></option><?php endforeach; endif; ?>
	</select>
	</td>
</tr>
<tr> 
	<td class="tRight tTop"><?php echo (L("SHOP_NAME")); ?>：</td>
	<td class="tLeft">
	<input type="text" class="bLeft" name="shop_name" value="<?php echo ($vo["shop_name"]); ?>" /> 
	</td>
</tr> 
<tr> 
	<td class="tRight tTop"><?php echo (L("SHOP_TITLE")); ?>：</td>
	<td class="tLeft">
	<input type="text" class="bLeft" name="shop_title" value="<?php echo ($vo["shop_title"]); ?>" /> 
	</td>
</tr>  
<tr> 
	<td class="tRight tTop"><?php echo (L("SEO_KEYWORD")); ?>：</td>
	<td class="tLeft">
	<textarea rows="3" cols="50" class="bLeft" name="seokeyword"><?php echo ($vo["seokeyword"]); ?></textarea>
	</td>
</tr> 
<tr> 
	<td class="tRight tTop"><?php echo (L("SEO_CONTENT")); ?>：</td>
	<td class="tLeft">
	<textarea rows="3" cols="50" class="bLeft" name="seocontent"><?php echo ($vo["seocontent"]); ?></textarea> 
	</td>
</tr> 
<tr> 
	<td class="tRight tTop"><?php echo (L("TMPL_NAME")); ?>：</td>
	<td class="tLeft">
	<select name="tmpl" class="bLeft">
	<?php if(is_array($themes)): foreach($themes as $key=>$theme): ?><option value="<?php echo ($theme); ?>" <?php if($vo['tmpl'] == $theme): ?>selected="selected"<?php endif; ?> ><?php echo ($theme); ?></option><?php endforeach; endif; ?>
	</select>
	</td>
</tr>    
<tr>
	<td></td>
	<td class="center"><div style="width:85%;margin:5px">
	<input type="hidden" class="huge bLeftRequire" name="id" value="<?php echo ($vo["id"]); ?>">
	<input type="submit" value="<?php echo (L("SAVE_DATA")); ?>"  class="button small"> <input type="reset" class="button small" onclick="resetEditor()" value="<?php echo (L("RESET_DATA")); ?>" > 
	</div></td>
</tr>
</form>
</table>
</div>
</div>