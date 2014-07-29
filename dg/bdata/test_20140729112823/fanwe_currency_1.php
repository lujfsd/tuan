<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_currency`;");
E_C(\"CREATE TABLE `fanwe_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_1` varchar(100) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `radio` float(10,4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_currency` values('1','人民币','￥','1.0000');");

require("../../inc/footer.php");
?>