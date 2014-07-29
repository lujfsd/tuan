<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `partner`;");
E_C(\"CREATE TABLE `partner` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `homepage` varchar(128) DEFAULT NULL,
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bank_name` varchar(128) DEFAULT NULL,
  `bank_no` varchar(128) DEFAULT NULL,
  `bank_user` varchar(128) DEFAULT NULL,
  `location` text NOT NULL,
  `contact` varchar(32) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `image1` varchar(128) DEFAULT NULL,
  `image2` varchar(128) DEFAULT NULL,
  `phone` varchar(18) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `other` text,
  `mobile` varchar(12) DEFAULT NULL,
  `open` enum('Y','N') NOT NULL DEFAULT 'N',
  `enable` enum('Y','N') NOT NULL DEFAULT 'Y',
  `head` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `longlat` varchar(255) DEFAULT NULL,
  `display` enum('Y','N') NOT NULL DEFAULT 'Y',
  `comment_good` int(11) NOT NULL DEFAULT '0',
  `comment_none` int(11) NOT NULL DEFAULT '0',
  `comment_bad` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ_ct` (`city_id`,`title`),
  UNIQUE KEY `UNQ_u` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `partner` values('1','133','e9f3adfa24a7b405188186ad9c8d91b5','123','0','123','1','多少V大v','12332326263235','程序辅导班v','出门','张珊','team/2013/1201/13858940165726.jpg',NULL,NULL,'13853986917','兰山','额度范文芳vfe','12313314','Y','Y','0','1','1385894016','','Y','0','0','0');");

require("../../inc/footer.php");
?>