<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `referer`;");
E_C(\"CREATE TABLE `referer` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL COMMENT '??id',
  `referer` varchar(400) COLLATE utf8_unicode_ci NOT NULL COMMENT '??',
  `create_time` int(10) unsigned NOT NULL COMMENT '????',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_o` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='??'");

require("../../inc/footer.php");
?>