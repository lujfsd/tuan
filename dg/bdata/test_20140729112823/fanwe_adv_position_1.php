<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_adv_position`;");
E_C(\"CREATE TABLE `fanwe_adv_position` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `is_flash` tinyint(1) NOT NULL DEFAULT '0',
  `flash_style` varchar(60) NOT NULL,
  `style` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_adv_position` values('1','顶部通栏广告','960','120','0','','<table cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n{foreach from=\"\$adv_list\" item=\"adv\"}\r\n<td>{\$adv.html}</td>\r\n{/foreach}\r\n</tr>\r\n</table>');");
E_D("replace into `fanwe_adv_position` values('2','右侧无团购时的广告位','232','80','0','','<table cellpadding=\"0\" cellspacing=\"0\">\r\n{foreach from=\"\$adv_list\" item=\"adv\"}\r\n<tr><td style=\"padding-bottom:10px;\"><div class=\"blank\"></div>{\$adv.html}</td></tr>\r\n{/foreach}\r\n</table>');");
E_D("replace into `fanwe_adv_position` values('3','首页右侧广告位','230','150','0','','{foreach from=\"\$adv_list\" item=\"adv\"}\r\n<div>\r\n{\$adv.html}\r\n</div>\r\n<div class=\"blank\"></div>\r\n{/foreach}');");
E_D("replace into `fanwe_adv_position` values('4','底部通栏广告','960','80','1','redfocus','<script type=\"text/javascript\">\r\ndocument.write(\'<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"{\$adv_position.width}\" height=\"{\$adv_position.height}\"><param name=\"allowScriptAccess\" value=\"sameDomain\"><param name=\"movie\" value=\"{\$adv_path}\"><param name=\"quality\" value=\"high\"><param name=\"bgcolor\" value=\"#F0F0F0\"><param name=\"menu\" value=\"false\"><param name=wmode value=\"opaque\"><param name=\"FlashVars\" value=\"pics={\$adv_pics}&links={\$adv_links}&borderwidth={\$adv_position.width}&borderheight={\$adv_position.height}&textheight=0\"><embed src=\"{\$adv_path}\" FlashVars=\"pics={\$adv_pics}&links={\$adv_links}&borderwidth={\$adv_position.width}&borderheight={\$adv_position.height}&textheight=0\" quality=\"high\" width=\"{\$adv_position.width}\" height=\"{\$adv_position.height}\" wmode=\"opaque\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" /></object>\');\r\n</script>');");

require("../../inc/footer.php");
?>