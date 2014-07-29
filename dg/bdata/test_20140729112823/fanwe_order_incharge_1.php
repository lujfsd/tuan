<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_order_incharge`;");
E_C(\"CREATE TABLE `fanwe_order_incharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `cost_payment_fee` float(10,4) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_radio` float(10,4) NOT NULL,
  `money` float(10,4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `payment_log_sn` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `payment_log_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>