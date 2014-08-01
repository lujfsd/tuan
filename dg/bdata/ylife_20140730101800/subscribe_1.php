<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `subscribe`;");
E_C("CREATE TABLE `subscribe` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) DEFAULT NULL,
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `secret` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_e` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `subscribe` values('4','546806462@qq.com','2','e49fa4d11ef70c870ea1fcb5ac97611d');");
E_D("replace into `subscribe` values('3','925635661@qq.com','0','22df447a3f3aaefaae45ed1aa21adcb1');");

require("../../inc/footer.php");
?>