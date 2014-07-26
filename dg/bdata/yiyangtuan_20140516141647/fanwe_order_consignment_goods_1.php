<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_order_consignment_goods`;");
E_C("CREATE TABLE `fanwe_order_consignment_goods` (
  `id` int(11) NOT NULL auto_increment,
  `order_goods_id` int(11) NOT NULL,
  `order_consignment_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_order_consignment_goods` values('2','58','2','5');");
E_D("replace into `fanwe_order_consignment_goods` values('3','60','3','4');");
E_D("replace into `fanwe_order_consignment_goods` values('4','64','4','1');");

require("../../inc/footer.php");
?>