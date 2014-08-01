<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_article_cate`;");
E_C("CREATE TABLE `ylife_article_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_1` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `seokeyword_1` varchar(255) NOT NULL,
  `seocontent_1` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0:普通 1:系统 2:帮助 3:公告 4:下载',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `id` (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_article_cate` values('17','用户帮助','0','1','','','2','1');");
E_D("replace into `ylife_article_cate` values('18','获取更新','0','1','','','2','2');");
E_D("replace into `ylife_article_cate` values('19','商务合作','0','1','','','2','3');");
E_D("replace into `ylife_article_cate` values('20','公司信息','0','1','','','2','4');");

require("../../inc/footer.php");
?>