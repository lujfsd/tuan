<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_layout`;");
E_C(\"CREATE TABLE `fanwe_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(255) NOT NULL,
  `tmpl` varchar(255) NOT NULL,
  `layout_id` varchar(255) NOT NULL,
  `rec_module` varchar(255) NOT NULL,
  `rec_id` int(11) NOT NULL,
  `item_limit` int(11) NOT NULL,
  `cate_limit` int(11) NOT NULL,
  `target_id` varchar(11) NOT NULL,
  `cate_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `inx_layout_001` (`tmpl`,`page`,`layout_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_layout` values('3','index_index','meituan','顶部通栏广告','AdvPosition','1','2','0','','0');");
E_D("replace into `fanwe_layout` values('11','index_index','meituan_best','右侧无团购时的广告位','AdvPosition','2','1','0','','0');");
E_D("replace into `fanwe_layout` values('9','','meituan','底部通栏广告','AdvPosition','4','1','0','','0');");
E_D("replace into `fanwe_layout` values('10','','meituan','首页右侧广告位','AdvPosition','3','2','0','','0');");
E_D("replace into `fanwe_layout` values('12','','meituan_best','首页右侧广告位','AdvPosition','3','1','0','','0');");
E_D("replace into `fanwe_layout` values('13','index_index','meituan_best','右侧广告','AdvPosition','3','1','0','','0');");
E_D("replace into `fanwe_layout` values('15','','meituan_best','顶部通栏广告','AdvPosition','1','5','0','','0');");
E_D("replace into `fanwe_layout` values('16','','11','顶部通栏广告','AdvPosition','1','3','0','','0');");

require("../../inc/footer.php");
?>