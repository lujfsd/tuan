<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_lottery`;");
E_C(\"CREATE TABLE `fanwe_lottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lottery_type` varchar(100) NOT NULL DEFAULT '',
  `goods_ids` varchar(255) NOT NULL DEFAULT '',
  `integral_min` int(11) NOT NULL DEFAULT '0',
  `integral_sub` int(11) NOT NULL DEFAULT '0',
  `frequency_type` smallint(1) NOT NULL DEFAULT '0',
  `frequency_unit` smallint(5) NOT NULL DEFAULT '1',
  `frequency` smallint(5) NOT NULL DEFAULT '1',
  `begin_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `user_group` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `desc` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>