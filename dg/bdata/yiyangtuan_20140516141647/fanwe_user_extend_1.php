<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user_extend`;");
E_C("CREATE TABLE `fanwe_user_extend` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `field_value` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `field_id` (`field_id`),
  KEY `user_id` (`user_id`),
  KEY `index_1` (`field_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_user_extend` values('11','1','55','女');");
E_D("replace into `fanwe_user_extend` values('15','1','56','女');");
E_D("replace into `fanwe_user_extend` values('14','2','54','');");
E_D("replace into `fanwe_user_extend` values('13','1','54','男');");
E_D("replace into `fanwe_user_extend` values('12','2','55','');");
E_D("replace into `fanwe_user_extend` values('16','2','56','');");
E_D("replace into `fanwe_user_extend` values('17','1','57','男');");
E_D("replace into `fanwe_user_extend` values('18','2','57','');");

require("../../inc/footer.php");
?>