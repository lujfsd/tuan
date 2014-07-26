<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_group_city`;");
E_C("CREATE TABLE `fanwe_group_city` (
  `id` int(11) NOT NULL auto_increment,
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
  `pid` int(11) default '0',
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
  PRIMARY KEY  (`id`),
  KEY `inx_group_city_001` (`is_defalut`,`sort`,`id`,`py`),
  KEY `inx_group_city_002` (`verify`),
  KEY `inx_group_city_003` (`py`,`verify`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_group_city` values('1','北京','0','0','1','2','1','0','beijing','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('2','上海','0','0','1','2','1','0','shanghai','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('3','广州','0','0','0','3','1','0','guangzhou','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('4','深圳','0','0','0','4','1','0','shen','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('5','杭州','0','0','0','5','1','0','hangzhou','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('6','南京','0','0','0','6','1','0','nanjing','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('7','武汉','0','0','0','7','1','0','wuhan','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('8','成都','0','0','0','8','1','0','chengdu','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('9','天津','0','0','0','9','1','0','tianjin','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('10','西安','0','0','0','10','1','0','xian','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('11','福州','0','0','1','11','1','0','fuzhou','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('12','重庆','0','0','0','12','1','0','zhongqing','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('13','厦门','0','0','1','13','1','0','xiamen','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('14','青岛','0','0','0','14','1','0','qingdao','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('15','大连','0','0','0','15','1','0','dalian','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','','0','','','','','','','','','','');");
E_D("replace into `fanwe_group_city` values('16','临沂','1','1','1','1','1','1','linyi','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','立即邮件订阅每日团购信息，不错过每一天的惊喜。','0','','178团购网--每天团购一次，精品消费指南','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家。','只要凑够最低团购人数就能享受无敌折扣','925635661','','','','','');");
E_D("replace into `fanwe_group_city` values('17','兰山','0','1','1','16','1','1','lanshan','1','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家，只要凑够最低团购人数就能享受无敌折扣。','立即邮件订阅每日团购信息，不错过每一天的惊喜。','16','','178团购网--每天团购一次，精品消费指南','每天提供一单精品消费，为您精选餐厅、酒吧、KTV、SPA、美发店、瑜伽馆等特色商家。','只要凑够最低团购人数就能享受无敌折扣','','','','','','');");

require("../../inc/footer.php");
?>