<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_order_incharge`;");
E_C("CREATE TABLE `fanwe_order_incharge` (
  `id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `cost_payment_fee` float(10,4) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_radio` float(10,4) NOT NULL,
  `money` float(10,4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `payment_log_sn` varchar(255) NOT NULL,
  `status` tinyint(1) default '0',
  `payment_log_id` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_order_incharge` values('54','76','0.0000','1','1.0000','159.0000','1387301044','','2','','1','0');");
E_D("replace into `fanwe_order_incharge` values('53','65','0.0000','1','1.0000','0.1000','1386889813','订单在线支付:0.10','4','fw123456350','1','350');");

require("../../inc/footer.php");
?>