<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user_consignee`;");
E_C("CREATE TABLE `fanwe_user_consignee` (
  `id` int(11) NOT NULL auto_increment,
  `consignee` varchar(255) NOT NULL,
  `region_lv1` varchar(255) NOT NULL,
  `region_lv2` varchar(255) NOT NULL,
  `region_lv3` varchar(255) NOT NULL,
  `region_lv4` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `fix_phone` varchar(255) NOT NULL,
  `fax_phone` varchar(255) NOT NULL,
  `mobile_phone` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_user_consignee` values('3','何永军','1','22','292','2408','355','','','','18653991358','54');");
E_D("replace into `fanwe_user_consignee` values('4','何永军','1','22','292','2408','通达路355号','276000','','','18653991358','54');");

require("../../inc/footer.php");
?>