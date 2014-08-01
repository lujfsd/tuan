<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_lottery_user`;");
E_C("CREATE TABLE `ylife_lottery_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lottery_id` int(11) NOT NULL DEFAULT '0',
  `lottery_item_index` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `rec_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(100) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>