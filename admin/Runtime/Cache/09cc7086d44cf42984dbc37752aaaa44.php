<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title><?php echo (L("PAGE_MSG")); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>" />
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/jquery.js"></script>
<script type="text/javascript" src="__TMPL__ThemeFiles/Js/jquery.json.js"></script>
<link rel="stylesheet" type="text/css" href="__TMPL__/ThemeFiles/Css/style.css" />
</head>
<body>
<div class="message">
<table class="message"  cellpadding=0 cellspacing=0 style="border-top:1px solid #ccc;border-right:1px solid #ccc">
	<tr class="big-row" >
		<th class="tCenter space"><?php echo ($msgTitle); ?></th>
	</tr>
	<?php if(isset($message)): ?><tr class="row">
		<td style="color:blue"><?php echo ($message); ?></td>
	</tr><?php endif; ?>
	<?php if(isset($error)): ?><tr class="row">
		<td style="color:red"><?php echo ($error); ?></td>
	</tr><?php endif; ?>
	<?php if(isset($closeWin)): ?><tr class="row">
		<td><?php echo (L("SYS_WILL")); ?> <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> <?php echo (L("PAGE_CLOSE_TIP")); ?> <a href="<?php echo ($jumpUrl); ?>"><?php echo (L("HERE")); ?></a> <?php echo (L("CLOSE")); ?></td>
	</tr><?php endif; ?>
	<?php if(!isset($closeWin)): ?><tr class="row">
		<td><?php echo (L("SYS_WILL")); ?> <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> <?php echo (L("PAGE_JUMP_TIP")); ?><a href="<?php echo ($jumpUrl); ?>"><?php echo (L("HERE")); ?></a> <?php echo (L("JUMP")); ?></td>
	</tr><?php endif; ?>
	
	</table>
</div>
</body>
</html>