<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_cate`;");
E_C("CREATE TABLE `fanwe_goods_cate` (
  `id` int(11) NOT NULL auto_increment,
  `name_1` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `seokeyword_1` varchar(255) NOT NULL,
  `seocontent_1` varchar(255) NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  `is_hot` tinyint(1) NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `py` varchar(100) default '',
  `cate_desc_1` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_goods_cate` values('40','餐厅','0','1','','','0','0','0','cangting','','1');");
E_D("replace into `fanwe_goods_cate` values('41','酒吧','0','1','','','0','0','0','jiuba','','2');");
E_D("replace into `fanwe_goods_cate` values('42','KTV','0','1','','','0','0','0','ktv','','3');");
E_D("replace into `fanwe_goods_cate` values('43','SPA','0','1','','','0','0','0','spa','','4');");
E_D("replace into `fanwe_goods_cate` values('44','美发店','0','1','','','0','0','0','meifadian','','5');");
E_D("replace into `fanwe_goods_cate` values('45','瑜伽馆','0','1','','','0','0','0','yujiaguang','','6');");
E_D("replace into `fanwe_goods_cate` values('46','商品类','0','1','','','0','0','0','shangpinlei','','7');");
E_D("replace into `fanwe_goods_cate` values('47','化妆品','46','1','化妆品','','0','0','0','huazhuangpin','','8');");
E_D("replace into `fanwe_goods_cate` values('48','电子产品','46','1','电子产品','电子产品','0','0','0','dianzichanpin','','9');");

require("../../inc/footer.php");
?>