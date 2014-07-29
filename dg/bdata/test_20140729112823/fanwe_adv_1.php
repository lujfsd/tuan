<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_adv`;");
E_C(\"CREATE TABLE `fanwe_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position_id` mediumint(8) NOT NULL,
  `name` varchar(20) NOT NULL,
  `code` text NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1: 图片 2:flash 3:文字 4:外部调用',
  `status` tinyint(4) NOT NULL,
  `url` varchar(255) NOT NULL,
  `click_count` int(11) NOT NULL,
  `desc` text NOT NULL,
  `sort` int(11) DEFAULT '0',
  `adv_start_time` int(11) DEFAULT '0',
  `adv_end_time` int(11) DEFAULT '0',
  `is_vote` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `position_id` (`position_id`),
  KEY `inx_adv_001` (`status`,`position_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_adv` values('1','1','索珞','/Public/upload/adv/201312/529ddc9a4ddc4.jpg','1','0','','0','顶部第一个广告','2','0','0','0');");
E_D("replace into `fanwe_adv` values('2','3','右侧无团购时的广告1','/Public/upload/adv/201312/529d85da7f4ca.jpg','3','0','','68','','0','0','0','0');");
E_D("replace into `fanwe_adv` values('3','1','右侧无团购时的广告2','/Public/upload/adv/201312/52a018b1510ed.png','1','0','http://m670.taobao.com','1','','0','0','0','0');");
E_D("replace into `fanwe_adv` values('4','2','右侧无团购时的广告3','/Public/upload/adv/201004/4bbd938f04189.jpg','1','0','','0','','0','0','0','0');");
E_D("replace into `fanwe_adv` values('5','1','顶部通栏广告2','/Public/upload/adv/201407/53d075770f5d1.png','1','0','http://baidu.com','2','','0','0','0','0');");
E_D("replace into `fanwe_adv` values('6','1','顶部通栏广告3','/Public/upload/adv/201407/53d0742a308ea.png','1','0','http://hao123.com','2','手机客户端','1','0','0','0');");

require("../../inc/footer.php");
?>