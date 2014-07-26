<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `mailer`;");
E_C("CREATE TABLE `mailer` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `email` varchar(128) default NULL,
  `city_id` int(10) unsigned NOT NULL default '0',
  `secret` varchar(32) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNQ_e` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>