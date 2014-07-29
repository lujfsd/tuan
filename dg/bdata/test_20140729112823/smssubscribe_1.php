<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `smssubscribe`;");
E_C(\"CREATE TABLE `smssubscribe` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(18) DEFAULT NULL,
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `secret` char(6) DEFAULT NULL,
  `enable` enum('Y','N') NOT NULL DEFAULT 'N',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_e` (`mobile`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `smssubscribe` values('1','13853986917','2','378547','N','0');");

require("../../inc/footer.php");
?>