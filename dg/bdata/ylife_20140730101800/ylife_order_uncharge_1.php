<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_order_uncharge`;");
E_C("CREATE TABLE `ylife_order_uncharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `cost_payment_fee` float(10,4) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_radio` float(10,4) NOT NULL,
  `money` float(10,4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `uncharge_score` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_1` (`order_id`,`payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>