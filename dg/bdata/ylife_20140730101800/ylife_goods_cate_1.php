<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_goods_cate`;");
E_C("CREATE TABLE `ylife_goods_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_1` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `seokeyword_1` varchar(255) NOT NULL,
  `seocontent_1` varchar(255) NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  `is_hot` tinyint(1) NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `py` varchar(100) DEFAULT '',
  `cate_desc_1` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_goods_cate` values('1','陶艺','0','1','','','0','0','0','taoyi','','1');");

require("../../inc/footer.php");
?>