<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user_extend`;");
E_C(\"CREATE TABLE `fanwe_user_extend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `field_value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `field_id` (`field_id`),
  KEY `user_id` (`user_id`),
  KEY `index_1` (`field_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_user_extend` values('20','2','59','');");
E_D("replace into `fanwe_user_extend` values('19','1','59','男');");

require("../../inc/footer.php");
?>