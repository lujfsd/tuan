<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_express`;");
E_C("CREATE TABLE `fanwe_express` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `sort` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_express` values('1','顺丰快递','','0','0','sf');");
E_D("replace into `fanwe_express` values('2','申通快递','','0','0','st');");
E_D("replace into `fanwe_express` values('3','Ems','','0','0','Ems');");
E_D("replace into `fanwe_express` values('4','圆通速递','','0','0','yt');");
E_D("replace into `fanwe_express` values('5','到商家付款','1','0','1','1');");

require("../../inc/footer.php");
?>