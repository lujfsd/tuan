<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `daysign`;");
E_C("CREATE TABLE `daysign` (
  `id` bigint(20) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `credit` double(10,2) default '0.00',
  `money` double(10,2) default '0.00',
  `create_time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>