<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_message`;");
E_C("CREATE TABLE `fanwe_message` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `pid` int(11) NOT NULL default '0',
  `reply_type` tinyint(1) NOT NULL default '0' COMMENT '0:主题留言 1:普通用户回复 2:管理员回复',
  `status` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL default '',
  `user_email` varchar(255) NOT NULL default '',
  `is_top` tinyint(1) NOT NULL default '0',
  `rec_module` varchar(255) NOT NULL default '' COMMENT '关联的模块（Order/Message/Goods/Article）',
  `rec_id` int(11) NOT NULL default '0',
  `score` tinyint(1) NOT NULL default '0' COMMENT '评分',
  `adm_title` varchar(255) NOT NULL default '',
  `adm_content` text NOT NULL,
  `groupon_seller_name` varchar(255) default NULL COMMENT '团购商家名称',
  `groupon_goods` text COMMENT '团购物品',
  `city_id` int(10) default '0',
  `flag` tinyint(1) default '0',
  `click_count` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `inx_message_001` (`status`,`rec_module`,`rec_id`),
  KEY `inx_message_002` (`status`,`rec_module`,`rec_id`,`pid`,`reply_type`),
  KEY `inx_message_003` (`is_top`,`create_time`),
  KEY `inx_message_004` (`status`,`pid`,`reply_type`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_message` values('11','QQ1345678','我有一些商品要参加团购','0','0','0','1269982677','1269982677','0','张先生','','0','Seller','0','0','','','','','0','0','0');");
E_D("replace into `fanwe_message` values('13','139999999','有一个很好的服务','0','0','0','1270010374','1270010374','0','刘生','','0','Seller','0','0','','','','','0','0','0');");
E_D("replace into `fanwe_message` values('14','QQ54879241','很好的服务,与我联系','0','0','0','1270010459','1270010459','0','李生','','0','Seller','0','0','','','','','0','0','0');");
E_D("replace into `fanwe_message` values('16','纯生物制剂 DNA系列产品 索珞营养粉底液','用了，挺好的！','0','0','1','1386877088','0','54','醉清风','925635661@qq.com','0','Goods','97','0','','',NULL,NULL,'16','0','0');");
E_D("replace into `fanwe_message` values('17','1','1','0','0','1','1387300051','0','54','醉清风','925635661@qq.com','0','Order','75','0','','',NULL,NULL,'0','0','0');");
E_D("replace into `fanwe_message` values('18','1','1','0','0','1','1387300099','0','54','醉清风','925635661@qq.com','0','Order','75','0','','',NULL,NULL,'0','0','0');");

require("../../inc/footer.php");
?>