<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `team`;");
E_C("CREATE TABLE `team` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(128) default NULL,
  `summary` text,
  `city_id` int(10) unsigned NOT NULL default '0',
  `city_ids` text,
  `group_id` int(10) unsigned NOT NULL default '0',
  `partner_id` int(10) unsigned NOT NULL default '0',
  `system` enum('Y','N') NOT NULL default 'Y',
  `team_price` double(10,2) NOT NULL default '0.00',
  `market_price` double(10,2) NOT NULL default '0.00',
  `product` varchar(128) default NULL,
  `condbuy` varchar(255) default NULL,
  `per_number` int(10) unsigned NOT NULL default '1',
  `permin_number` int(10) default '1',
  `min_number` int(10) unsigned NOT NULL default '1',
  `max_number` int(10) unsigned NOT NULL default '0',
  `now_number` int(10) unsigned NOT NULL default '0',
  `pre_number` int(10) unsigned NOT NULL default '0',
  `allowrefund` enum('Y','N') NOT NULL default 'N',
  `image` varchar(128) default NULL,
  `image1` varchar(128) default NULL,
  `image2` varchar(128) default NULL,
  `flv` varchar(128) default NULL,
  `mobile` varchar(16) default NULL,
  `credit` int(10) unsigned NOT NULL default '0',
  `card` int(10) unsigned NOT NULL default '0',
  `fare` int(10) unsigned NOT NULL default '0',
  `farefree` int(11) NOT NULL default '0',
  `bonus` int(11) NOT NULL default '0',
  `address` varchar(128) default NULL,
  `detail` text,
  `systemreview` text,
  `userreview` text,
  `notice` text,
  `express` text,
  `delivery` varchar(16) NOT NULL default 'coupon',
  `state` enum('none','success','soldout','failure','refund') NOT NULL default 'none',
  `conduser` enum('Y','N') NOT NULL default 'Y',
  `buyonce` enum('Y','N') NOT NULL default 'Y',
  `team_type` varchar(20) default 'normal',
  `sort_order` int(11) NOT NULL default '0',
  `expire_time` int(10) unsigned NOT NULL default '0',
  `begin_time` int(10) unsigned NOT NULL default '0',
  `end_time` int(10) unsigned NOT NULL default '0',
  `reach_time` int(10) unsigned NOT NULL default '0',
  `close_time` int(10) unsigned NOT NULL default '0',
  `seo_title` varchar(255) default NULL,
  `seo_keyword` varchar(255) default NULL,
  `seo_description` text,
  `express_relate` text,
  `sub_id` int(10) NOT NULL default '0',
  `send_time` int(10) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `team` values('1','1','11111112','1111','0','@0@','0','0','Y','1.00','1.00','11111','','1','1','10','0','0','0','N','team/2013/1201/13858814732524.jpg',NULL,NULL,'','','0','0','0','0','0','','','','','11111','','coupon','none','N','Y','normal','0','1393689600','1385827200','1386000000','0','0',NULL,NULL,NULL,'N;','0','0');");
E_D("replace into `team` values('2','1','62855262','226263','0','@0@','0','0','Y','1.00','1.00','12374','','1','1','10','0','0','0','N','team/2013/1201/13858809817618.jpg',NULL,NULL,'','','0','0','0','0','0','','363366','852652','6363.6','85656263','','coupon','none','N','Y','normal','11','1393689600','1385913600','1386000000','0','0',NULL,NULL,NULL,'N;','0','0');");

require("../../inc/footer.php");
?>