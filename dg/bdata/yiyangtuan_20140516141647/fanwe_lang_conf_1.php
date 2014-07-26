<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_lang_conf`;");
E_C("CREATE TABLE `fanwe_lang_conf` (
  `id` int(11) NOT NULL auto_increment,
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
  PRIMARY KEY  (`id`),
  KEY `inx_lang_conf_001` (`lang_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_lang_conf` values('1','zh-cn','简体中文','8','meituan_best','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家。','只要凑够最低团购人数就能享受无敌折扣','弋阳团购网--每天团购一次，精品消费指南','弋阳团购网','1','1');");

require("../../inc/footer.php");
?>