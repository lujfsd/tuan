<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `address`;");
E_C("CREATE TABLE `address` (
  `id` bigint(20) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `province` varchar(64) NOT NULL,
  `area` varchar(64) NOT NULL,
  `city` varchar(64) default NULL,
  `street` varchar(255) NOT NULL,
  `zipcode` char(6) NOT NULL,
  `name` varchar(32) NOT NULL,
  `mobile` varchar(16) NOT NULL,
  `default` enum('Y','N') NOT NULL default 'N',
  `create_time` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>