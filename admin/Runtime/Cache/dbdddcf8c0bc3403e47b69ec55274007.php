<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<div id="loader" ><?php echo (L("PAGE_LOADING")); ?></div><!-- 菜单区域  --><!-- 主页面开始 --><div id="main" class="main" ><!-- 主体内容  --><div class="content" ><div class="title"><?php echo (L("DATA_LIST")); ?></div><!--  功能操作区域  --><form method='post' action="<?php echo u($module_name.'/index');?>"><div class="operate" ><input type="button" id="" name="add" value="<?php echo (L("_ADD_DATA")); ?>" onclick="addData()" class="add imgButton"><input type="button" id="" name="edit" value="<?php echo (L("_EDIT_DATA")); ?>" onclick="editData()" class="edit imgButton"><input type="button" id="" name="delete" value="<?php echo (L("_DELETE_DATA")); ?>" onclick="foreverdel()" class="delete imgButton"><!-- 查询区域 --><input type="text" name="name" title="<?php echo (L("USER_GROUP")); ?>" class="medium" ><input type="hidden" name="SEARCH_TYPE" value="like" /><input type="submit" id="" name="search" value="<?php echo (L("_SEARCH_DATA")); ?>" onclick="" class="search imgButton"></div><!-- 高级查询区域 --></form><!-- 功能操作区域结束 --><!-- 列表显示区域  --><div class="list" ><!-- Think 系统列表组件开始 -->
<table id="checkList" class="list" cellpadding=0 cellspacing=0 ><tr class="row" ><th width="8"><input type="checkbox" id="check" onclick="CheckAll('checkList')"></th><th width="8%"><a href="javascript:sortBy('id','<?php echo ($sort); ?>','index')" title="按照<?php echo (L("ID")); ?><?php echo ($sortType); ?> "><?php echo (L("ID")); ?><?php if(($order)  ==  "id"): ?><img src="__TMPL__ThemeFiles/Images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('name_1','<?php echo ($sort); ?>','index')" title="按照<?php echo (L("USER_GROUP")); ?><?php echo ($sortType); ?> "><?php echo (L("USER_GROUP")); ?><?php if(($order)  ==  "name_1"): ?><img src="__TMPL__ThemeFiles/Images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('discount','<?php echo ($sort); ?>','index')" title="按照<?php echo (L("GROUP_DISCOUNT")); ?><?php echo ($sortType); ?> "><?php echo (L("GROUP_DISCOUNT")); ?><?php if(($order)  ==  "discount"): ?><img src="__TMPL__ThemeFiles/Images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('min_points','<?php echo ($sort); ?>','index')" title="按照<?php echo (L("GROUP_MIN_POINTS")); ?><?php echo ($sortType); ?> "><?php echo (L("GROUP_MIN_POINTS")); ?><?php if(($order)  ==  "min_points"): ?><img src="__TMPL__ThemeFiles/Images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th><a href="javascript:sortBy('max_points','<?php echo ($sort); ?>','index')" title="按照<?php echo (L("GROUP_MAX_POINTS")); ?><?php echo ($sortType); ?> "><?php echo (L("GROUP_MAX_POINTS")); ?><?php if(($order)  ==  "max_points"): ?><img src="__TMPL__ThemeFiles/Images/<?php echo ($sortImg); ?>.gif" width="12" height="17" border="0" align="absmiddle"><?php endif; ?></a></th><th >操作</th></tr><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$usergroup): ++$i;$mod = ($i % 2 )?><tr class="row" onmouseover="over(event)" onmouseout="out(event)" onclick="change(event)" ><td><?php if(($usergroup['level'] == '0')or($usergroup['level'] > 0)): ?><?php else: ?><input type="checkbox" class="key" name="key"	value="<?php echo ($usergroup["id"]); ?>" <?php if($usergroup['checked']): ?>checked="checked"<?php endif; ?> ><?php endif; ?></td><td><?php echo ($usergroup["id"]); ?>&nbsp;</td><td><a href="javascript:edit('<?php echo (addslashes($usergroup["id"])); ?>')" title="<?php echo (strip_tags($usergroup["name_1"])); ?>"><?php echo ($usergroup["name_1"]); ?></a>&nbsp;</td><td><?php echo (floatval($usergroup["discount"])); ?>&nbsp;</td><td><?php echo ($usergroup["min_points"]); ?>&nbsp;</td><td><?php echo ($usergroup["max_points"]); ?>&nbsp;</td><td><a href="javascript:edit('<?php echo ($usergroup["id"]); ?>')"><?php echo (L("_EDIT_DATA")); ?></a>&nbsp;<a href="javascript:foreverdel('<?php echo ($usergroup["id"]); ?>')"><?php echo (L("_DELETE_DATA")); ?></a>&nbsp;</td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table>
<!-- Think 系统列表组件结束 -->
 </div><!--  分页显示区域 --><div class="page"><?php echo ($page); ?></div><!-- 列表显示区域结束 --></div><!-- 主体内容结束 --></div><!-- 主页面结束 -->