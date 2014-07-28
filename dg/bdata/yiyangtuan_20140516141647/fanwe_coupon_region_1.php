<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_coupon_region`;");
E_C("CREATE TABLE `fanwe_coupon_region` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `api_address` text NOT NULL,
  `xpoint` varchar(255) NOT NULL,
  `ypoint` varchar(255) NOT NULL,
  `zoom` int(11) NOT NULL,
  `pid` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `city_id` (`city_id`),
  KEY `sort` (`sort`),
  KEY `status` (`status`),
  KEY `index_1` (`city_id`,`status`,`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
//E_D("replace into `fanwe_coupon_region` values('1','兰山','16','1','1','兰山','118.354302','35.070257','16','0');");

require("../../inc/footer.php");
?>