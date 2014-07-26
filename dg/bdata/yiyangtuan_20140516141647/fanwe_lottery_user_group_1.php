<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_lottery_user_group`;");
E_C("CREATE TABLE `fanwe_lottery_user_group` (
  `lottery_id` int(11) NOT NULL default '0',
  `user_group_id` int(11) NOT NULL default '0',
  KEY `lottery_id` (`lottery_id`,`user_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>