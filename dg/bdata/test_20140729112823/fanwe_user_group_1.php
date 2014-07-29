<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user_group`;");
E_C(\"CREATE TABLE `fanwe_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_1` varchar(255) NOT NULL,
  `min_points` int(11) unsigned NOT NULL DEFAULT '0',
  `max_points` int(11) unsigned NOT NULL DEFAULT '0',
  `discount` float(10,4) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_user_group` values('1','普通会员','0','2000','1.0000','1');");
E_D("replace into `fanwe_user_group` values('2','VIP会员','2000','5000','0.9000','1');");
E_D("replace into `fanwe_user_group` values('3','白金会员','5000','10000','0.8500','1');");

require("../../inc/footer.php");
?>