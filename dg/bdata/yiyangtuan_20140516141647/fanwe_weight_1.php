<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_weight`;");
E_C("CREATE TABLE `fanwe_weight` (
  `id` int(11) NOT NULL auto_increment,
  `name_1` varchar(100) NOT NULL,
  `radio` float(10,4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_weight` values('5','公斤','1000.0000');");
E_D("replace into `fanwe_weight` values('2','克','1.0000');");
E_D("replace into `fanwe_weight` values('3','盎司','31.1039');");
E_D("replace into `fanwe_weight` values('4','克拉','0.2000');");

require("../../inc/footer.php");
?>