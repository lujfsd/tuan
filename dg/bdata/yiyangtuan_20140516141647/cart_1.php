<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `cart`;");
E_C("CREATE TABLE `cart` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0',
  `admin_id` int(10) unsigned NOT NULL default '0',
  `detail` text,
  `money` double(10,2) NOT NULL default '0.00',
  `action` varchar(16) NOT NULL default 'buy',
  `state` varchar(16) NOT NULL default 'unpay',
  `create_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>