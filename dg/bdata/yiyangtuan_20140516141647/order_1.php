<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `order`;");
E_C("CREATE TABLE `order` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `pay_id` varchar(32) default NULL,
  `buy_id` int(11) NOT NULL default '0',
  `service` varchar(16) NOT NULL default 'alipay',
  `user_id` int(10) unsigned NOT NULL default '0',
  `admin_id` int(10) unsigned NOT NULL default '0',
  `team_id` int(10) unsigned NOT NULL default '0',
  `city_id` int(10) unsigned NOT NULL default '0',
  `card_id` varchar(16) default NULL,
  `state` enum('unpay','pay') NOT NULL default 'unpay',
  `trade_no` varchar(32) default NULL,
  `allowrefund` enum('Y','N') NOT NULL default 'N',
  `rstate` enum('normal','askrefund','berefund','norefund') NOT NULL default 'normal',
  `rereason` text,
  `retime` int(11) default NULL,
  `quantity` int(10) unsigned NOT NULL default '1',
  `realname` varchar(32) default NULL,
  `mobile` varchar(128) default NULL,
  `zipcode` char(6) default NULL,
  `address` varchar(128) default NULL,
  `express` enum('Y','N') NOT NULL default 'Y',
  `express_xx` varchar(128) default NULL,
  `express_id` int(10) unsigned NOT NULL default '0',
  `express_no` varchar(32) default NULL,
  `price` double(10,2) NOT NULL default '0.00',
  `money` double(10,2) NOT NULL default '0.00',
  `origin` double(10,2) NOT NULL default '0.00',
  `credit` double(10,2) NOT NULL default '0.00',
  `card` double(10,2) NOT NULL default '0.00',
  `fare` double(10,2) NOT NULL default '0.00',
  `condbuy` varchar(128) default NULL,
  `remark` text,
  `create_time` int(10) unsigned NOT NULL default '0',
  `pay_time` int(10) unsigned NOT NULL default '0',
  `comment_content` text,
  `comment_display` enum('Y','N') NOT NULL default 'Y',
  `comment_grade` enum('good','none','bad') NOT NULL default 'good',
  `comment_wantmore` enum('Y','N') default NULL,
  `comment_time` int(11) default NULL,
  `partner_id` int(11) NOT NULL default '0',
  `sms_express` enum('Y','N') NOT NULL default 'N',
  `luky_id` int(11) NOT NULL default '0',
  `adminremark` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNQ_p` (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>