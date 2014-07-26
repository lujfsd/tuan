<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/jquery.js"></script>
<div class="main" >
<div class="content">
<TABLE id="checkList" class="list" cellpadding=0 cellspacing=0 style="border-top:2px solid #9CB8CC;border-bottom:none" >

<TR class="big-row" ><th colspan="3" class="space"><?php echo (L("SYS_INFO")); ?></th></tr>
<?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): ++$i;$mod = ($i % 2 )?><TR class="row" ><TD width="15%"><?php echo ($key); ?></TD><TD><?php echo ($v); ?></TD></TR><?php endforeach; endif; else: echo "" ;endif; ?>

</TABLE>
</div>


<div class="content">
<TABLE id="checkList" class="list" cellpadding=0 cellspacing=0  style="border-bottom:none">
<TR class="big-row" ><th colspan="3" class="space">订单提醒</th></tr>
<?php if(is_array($order_memo)): $i = 0; $__LIST__ = $order_memo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): ++$i;$mod = ($i % 2 )?><TR class="row" ><TD width="15%"><?php echo ($key); ?></TD><TD><?php echo ($v); ?></TD></TR><?php endforeach; endif; else: echo "" ;endif; ?>

</TABLE>
</div>

<div class="content">
<TABLE id="checkList" class="list" cellpadding=0 cellspacing=0 >

<TR class="big-row" ><th colspan="3" class="space">留言提醒</th></tr>
<?php if(is_array($message_memo)): $i = 0; $__LIST__ = $message_memo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): ++$i;$mod = ($i % 2 )?><TR class="row" ><TD width="15%"><?php echo ($key); ?></TD><TD><?php echo ($v); ?></TD></TR><?php endforeach; endif; else: echo "" ;endif; ?>

</TABLE>
</div>


</div> 
<!-- 主页面结束 -->