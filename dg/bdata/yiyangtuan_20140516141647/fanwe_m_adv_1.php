<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_m_adv`;");
E_C("CREATE TABLE `fanwe_m_adv` (
  `id` smallint(6) NOT NULL auto_increment,
  `name` varchar(100) default '',
  `img` varchar(255) default '',
  `page` enum('sharelist','index') default 'sharelist',
  `type` tinyint(1) default '0' COMMENT '1.标签集,2.url地址,3.分类排行,4.最亮达人,5.搜索发现,6.一起拍,7.热门单品排行,8.直接显示某个分享',
  `data` text,
  `sort` smallint(5) default '10',
  `status` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_m_adv` values('8','广告3','./public/attachment/201312/05/08/52a03da9854c1.jpg','index','2','a:1:{s:3:\"url\";s:22:\"http://m670.taobao.com\";}','1','1');");
E_D("replace into `fanwe_m_adv` values('11','广告2','./public/attachment/201203/16/01/4f62948c32575.jpg','index','9','a:1:{s:7:\"cate_id\";i:0;}','4','1');");
E_D("replace into `fanwe_m_adv` values('12','团购网','./public/attachment/201312/05/08/52a03b515905a.png','index','2','a:1:{s:3:\"url\";s:22:\"http://m670.taobao.com\";}','5','1');");

require("../../inc/footer.php");
?>