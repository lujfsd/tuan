<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_order_re_consignment_goods`;");
E_C("CREATE TABLE `ylife_order_re_consignment_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_goods_id` int(11) NOT NULL,
  `order_re_consignment_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>