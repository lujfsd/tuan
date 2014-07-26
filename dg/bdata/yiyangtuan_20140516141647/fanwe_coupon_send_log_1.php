<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_coupon_send_log`;");
E_C("CREATE TABLE `fanwe_coupon_send_log` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `send_time` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `uid` (`uid`),
  KEY `coupon_id` (`coupon_id`),
  KEY `mobile` (`mobile`),
  KEY `index_1` (`uid`,`coupon_id`,`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>