<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_attr`;");
E_C(\"CREATE TABLE `fanwe_goods_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attr_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `attr_value_1` varchar(255) NOT NULL,
  `price` float(10,4) NOT NULL,
  `stock` int(11) NOT NULL,
  `supplier_goods_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `inx_goods_attr_001` (`goods_id`,`attr_value_1`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>