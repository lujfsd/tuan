<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_admin_city`;");
E_C("CREATE TABLE `fanwe_admin_city` (
  `id` int(11) NOT NULL auto_increment,
  `admin_id` int(11) NOT NULL default '0',
  `city_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `city_id` (`city_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>