<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_supplier_goods`;");
E_C("CREATE TABLE `fanwe_supplier_goods` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `max_count` int(11) NOT NULL,
  `min_count` int(11) NOT NULL,
  `user_max_count` int(11) NOT NULL,
  `promote_begin_time` int(11) NOT NULL,
  `promote_end_time` int(11) NOT NULL,
  `brief` text NOT NULL,
  `contents` text NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `origin_price` double(20,4) NOT NULL,
  `shop_price` double(20,4) NOT NULL,
  `other_desc` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `goods_type` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_supplier_goods` values('1','1233','0','0','0','0','0','','','0','7','0.0000','0.0000','','1386559074','0');");
E_D("replace into `fanwe_supplier_goods` values('2','123456','100','10','1','1386559080','1389237480','3131231','<p>\r\n	321316\r\n</p>\r\n<p>\r\n	&nbsp;\r\n</p>','0','7','100.0000','50.0000','2414316','1386559159','2');");

require("../../inc/footer.php");
?>