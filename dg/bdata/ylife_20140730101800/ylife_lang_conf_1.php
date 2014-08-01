<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_lang_conf`;");
E_C("CREATE TABLE `ylife_lang_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(20) NOT NULL,
  `show_name` varchar(255) NOT NULL,
  `time_zone` int(11) NOT NULL,
  `tmpl` varchar(255) NOT NULL,
  `seokeyword` varchar(255) NOT NULL,
  `seocontent` varchar(255) NOT NULL,
  `shop_title` varchar(255) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `default` tinyint(1) NOT NULL,
  `currency` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `inx_lang_conf_001` (`lang_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_lang_conf` values('1','zh-cn','简体中文','8','11','DIY、生活、手工、团购、','Y生活，健康态度，健康生活！','Y生活-生活更精彩','Y生活网','1','1');");

require("../../inc/footer.php");
?>