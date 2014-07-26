<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_sms_subscribe`;");
E_C("CREATE TABLE `fanwe_sms_subscribe` (
  `id` int(11) NOT NULL auto_increment,
  `mobile_phone` varchar(50) NOT NULL default '',
  `city_id` int(11) NOT NULL default '0',
  `code` varchar(20) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `add_time` int(11) NOT NULL default '0',
  `send_count` int(11) NOT NULL default '0',
  `goods_id` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `mobile_phone` (`mobile_phone`,`city_id`),
  KEY `user_id` (`user_id`),
  KEY `city_id` (`city_id`),
  KEY `inx_sms_subscribe_1` (`goods_id`,`user_id`),
  KEY `index_1` (`city_id`,`goods_id`,`user_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>