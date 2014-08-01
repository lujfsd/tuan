<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_message`;");
E_C("CREATE TABLE `ylife_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `reply_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:主题留言 1:普通用户回复 2:管理员回复',
  `status` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL DEFAULT '',
  `user_email` varchar(255) NOT NULL DEFAULT '',
  `is_top` tinyint(1) NOT NULL DEFAULT '0',
  `rec_module` varchar(255) NOT NULL DEFAULT '' COMMENT '关联的模块（Order/Message/Goods/Article）',
  `rec_id` int(11) NOT NULL DEFAULT '0',
  `score` tinyint(1) NOT NULL DEFAULT '0' COMMENT '评分',
  `adm_title` varchar(255) NOT NULL DEFAULT '',
  `adm_content` text NOT NULL,
  `groupon_seller_name` varchar(255) DEFAULT NULL COMMENT '团购商家名称',
  `groupon_goods` text COMMENT '团购物品',
  `city_id` int(10) DEFAULT '0',
  `flag` tinyint(1) DEFAULT '0',
  `click_count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inx_message_001` (`status`,`rec_module`,`rec_id`),
  KEY `inx_message_002` (`status`,`rec_module`,`rec_id`,`pid`,`reply_type`),
  KEY `inx_message_003` (`is_top`,`create_time`),
  KEY `inx_message_004` (`status`,`pid`,`reply_type`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_message` values('19','第一次买','第一次买','0','0','1','1406250841','0','59','lujiefeng','lujfsd@163.com','0','Order','81','0','','',NULL,NULL,'0','0','0');");
E_D("replace into `ylife_message` values('20','不错，很好','不错，很好','0','0','1','1406251060','0','59','lujiefeng','lujfsd@163.com','0','Suppliers','10','3','','',NULL,NULL,'0','0','0');");

require("../../inc/footer.php");
?>