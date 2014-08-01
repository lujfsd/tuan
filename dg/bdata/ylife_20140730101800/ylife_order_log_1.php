<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_order_log`;");
E_C("CREATE TABLE `ylife_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `author_ip` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `log_text` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>