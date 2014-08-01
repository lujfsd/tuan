<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_group_city`;");
E_C("CREATE TABLE `ylife_group_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `is_defalut` tinyint(1) NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `is_hot` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `youhui` tinyint(1) NOT NULL,
  `py` varchar(255) NOT NULL,
  `verify` tinyint(1) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `tip` varchar(255) NOT NULL,
  `pid` int(11) DEFAULT '0',
  `notice` text NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `qq_1` varchar(255) NOT NULL,
  `qq_2` varchar(255) NOT NULL,
  `qq_3` varchar(255) NOT NULL,
  `qq_4` varchar(255) NOT NULL,
  `qq_5` varchar(255) NOT NULL,
  `qq_6` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `inx_group_city_001` (`is_defalut`,`sort`,`id`,`py`),
  KEY `inx_group_city_002` (`verify`),
  KEY `inx_group_city_003` (`py`,`verify`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_group_city` values('18','上海','1','1','1','1','1','1','shanghai','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','立即邮件订阅每日团购信息，不错过每一天的惊喜。','0','<p>&nbsp;欢迎来到Y生活，每天对生活&ldquo;Y&rdquo; 一次，精彩不断！</p>','Y生活-绿色生活','DIY、生活、手工、团购、','Y生活，健康态度，健康生活！','','','','','','');");
E_D("replace into `ylife_group_city` values('19','北京','0','1','1','2','1','1','beijing','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','立即邮件订阅每日团购信息，不错过每一天的惊喜。','0','','Y生活-绿色生活','DIY、生活、手工、团购、','Y生活，健康态度，健康生活！','','','','','','');");

require("../../inc/footer.php");
?>