<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `paycard`;");
E_C("CREATE TABLE `paycard` (
  `id` varchar(16) NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `value` int(10) unsigned NOT NULL DEFAULT '0',
  `consume` enum('Y','N') NOT NULL DEFAULT 'N',
  `recharge_time` int(10) unsigned NOT NULL DEFAULT '0',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>