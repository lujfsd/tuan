<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_sms_subscribe`;");
E_C("CREATE TABLE `ylife_sms_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile_phone` varchar(50) NOT NULL DEFAULT '',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `send_count` int(11) NOT NULL DEFAULT '0',
  `goods_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `mobile_phone` (`mobile_phone`,`city_id`),
  KEY `user_id` (`user_id`),
  KEY `city_id` (`city_id`),
  KEY `inx_sms_subscribe_1` (`goods_id`,`user_id`),
  KEY `index_1` (`city_id`,`goods_id`,`user_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>