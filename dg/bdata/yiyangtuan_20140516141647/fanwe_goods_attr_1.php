<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_attr`;");
E_C("CREATE TABLE `fanwe_goods_attr` (
  `id` int(11) NOT NULL auto_increment,
  `attr_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `attr_value_1` varchar(255) NOT NULL,
  `price` float(10,4) NOT NULL,
  `stock` int(11) NOT NULL,
  `supplier_goods_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `inx_goods_attr_001` (`goods_id`,`attr_value_1`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_goods_attr` values('60','2','88','14','6.0000','0','0');");
E_D("replace into `fanwe_goods_attr` values('59','2','88','13','5.0000','0','0');");
E_D("replace into `fanwe_goods_attr` values('58','2','88','12','4.0000','0','0');");
E_D("replace into `fanwe_goods_attr` values('57','1','88','黄','2.0000','0','0');");
E_D("replace into `fanwe_goods_attr` values('56','1','88','蓝','3.0000','0','0');");
E_D("replace into `fanwe_goods_attr` values('55','1','88','红','1.0000','0','0');");
E_D("replace into `fanwe_goods_attr` values('67','1','111','红','0.0000','0','0');");
E_D("replace into `fanwe_goods_attr` values('68','2','111','12','0.0000','0','0');");

require("../../inc/footer.php");
?>