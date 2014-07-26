<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel='stylesheet' type='text/css' href='__TMPL__/ThemeFiles/Css/style.css'>
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/jquery.js"></script>
<style>
html{overflow-x : hidden;}
body{ background:#DEE4ED}
</style>
<base target="main" />
</head>

<body >
<div style="padding-top:5px;">
    <div class="fanwe-menu" valign="top">
        <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): ++$i;$mod = ($i % 2 )?><dl>
        	<dt><div><strong><?php echo ($item["name"]); ?></strong></div></dt>
            <?php if(is_array($item['navs'])): $i = 0; $__LIST__ = $item['navs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): ++$i;$mod = ($i % 2 )?><dd><p><a href="<?php echo u($nav['module'].'/'.$nav['action']);?>" id="<?php echo ($key); ?>"><?php echo ($nav['action_name']); ?></a></p></dd><?php endforeach; endif; else: echo "" ;endif; ?>
        </dl><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<script language="JavaScript">
<!--
	function refreshMainFrame(url)
	{
		parent.main.document.location = url;
	}
	
	if($("a:first").attr("href"))
	{
		setTimeout(function(){
			top.document.getElementById("main-frame").src = $("a:first").attr("href");
		},100);
		$("a:first").parent().parent().addClass("cur");
	};
	$("a").click(function(){
		$("a").each(function(){
			$(this).parent().parent().removeClass("cur");
		});
		$(this).parent().parent().addClass("cur");
		$(this).blur();
	});
	
/*	if (document.anchors(0))
	{
		refreshMainFrame(document.anchors(0).href);
	}*/
//-->
</script>
</body>
</html>