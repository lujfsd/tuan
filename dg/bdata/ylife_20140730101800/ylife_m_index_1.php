<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_m_index`;");
E_C("CREATE TABLE `ylife_m_index` (
  `id` mediumint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `vice_name` varchar(100) DEFAULT NULL,
  `desc` varchar(100) DEFAULT '',
  `img` varchar(255) DEFAULT '',
  `type` tinyint(1) DEFAULT '0' COMMENT '1.标签集,2.url地址,3.分类排行,4.最亮达人,5.搜索发现,6.一起拍,7.热门单品排行,8.直接显示某个分享',
  `data` text,
  `sort` smallint(5) DEFAULT '10',
  `status` tinyint(1) DEFAULT '1',
  `is_hot` tinyint(1) DEFAULT '0',
  `is_new` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_m_index` values('11','团购网','团购网','团购网','./public/attachment/201312/05/09/52a040d23d21c.png','2','a:1:{s:3:\"url\";s:22:\"http://m670.taobao.com\";}','1','1','1','0');");
E_D("replace into `ylife_m_index` values('15','公告列表','公告列表','公告列表','./public/attachment/201203/16/02/4f62a07dd5cd2.png','21','','5','1','0','0');");
E_D("replace into `ylife_m_index` values('16','休闲娱乐','休闲娱乐','休闲娱乐','./public/attachment/201203/16/02/4f62a05f85d75.png','9','a:1:{s:7:\"cate_id\";i:0;}','6','1','0','0');");
E_D("replace into `ylife_m_index` values('19','儿童游艺','儿童游艺','','./public/attachment/201203/16/01/4f629dc432837.png','9','a:1:{s:7:\"cate_id\";i:0;}','9','1','0','0');");
E_D("replace into `ylife_m_index` values('24','商品类','商品类','','','9','a:1:{s:7:\"cate_id\";i:0;}','10','1','0','0');");

require("../../inc/footer.php");
?>