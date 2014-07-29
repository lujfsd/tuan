<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user_money_log`;");
E_C(\"CREATE TABLE `fanwe_user_money_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `money` float(10,4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `memo_1` varchar(255) NOT NULL,
  `rec_id` int(11) NOT NULL,
  `rec_module` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  KEY `index_1` (`rec_id`,`rec_module`,`user_id`),
  KEY `index_2` (`rec_module`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_user_money_log` values('57','52','20.0000','1386876475','增加从会员 醉清风的现金邀请返利','1','Referrals');");

require("../../inc/footer.php");
?>