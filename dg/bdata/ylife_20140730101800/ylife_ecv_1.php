<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_ecv`;");
E_C("CREATE TABLE `ylife_ecv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ecv_type` int(11) NOT NULL,
  `sn` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_sn` varchar(255) NOT NULL,
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `use_user_id` int(11) NOT NULL DEFAULT '0',
  `use_date_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `use_count` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `ecv_type` (`ecv_type`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_sn`),
  KEY `id` (`id`),
  KEY `index_1` (`ecv_type`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_ecv` values('1','1','FWDJ_66633966','33353861','52','','0','0','0','0','0','1');");
E_D("replace into `ylife_ecv` values('2','1','FWDJ_33633965','35623061','54','','0','0','0','0','0','1');");
E_D("replace into `ylife_ecv` values('3','2','FWCZ39333431','32616562','52','','0','52','1283539133','1','1','0');");
E_D("replace into `ylife_ecv` values('4','2','FWCZ65303864','66303766','54','','0','0','0','0','1','1');");

require("../../inc/footer.php");
?>