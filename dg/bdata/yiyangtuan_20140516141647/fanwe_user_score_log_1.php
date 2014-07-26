<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user_score_log`;");
E_C("CREATE TABLE `fanwe_user_score_log` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `memo_1` varchar(255) NOT NULL,
  `rec_id` int(11) NOT NULL,
  `rec_module` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `rec_id` (`rec_id`),
  KEY `index_1` (`rec_id`,`rec_module`,`user_id`),
  KEY `index_2` (`rec_module`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_user_score_log` values('61','54','10','1387256946','会员登录返利','0','Login');");
E_D("replace into `fanwe_user_score_log` values('52','54','10','1283538856','用户注册','54','User');");
E_D("replace into `fanwe_user_score_log` values('60','54','1','1386889813','订单获得积分(SN:1312131508058;goods_id:102;score:1)','65','Order');");
E_D("replace into `fanwe_user_score_log` values('59','55','10','1386887260','用户注册','55','User');");
E_D("replace into `fanwe_user_score_log` values('58','54','10','1386877056','会员登录返利','0','Login');");
E_D("replace into `fanwe_user_score_log` values('57','54','10','1386543962','用户注册','54','User');");
E_D("replace into `fanwe_user_score_log` values('62','54','10','1387297597','会员登录返利','0','Login');");
E_D("replace into `fanwe_user_score_log` values('63','56','10','1388367387','用户注册','56','User');");
E_D("replace into `fanwe_user_score_log` values('64','57','10','1388724358','用户注册','57','User');");

require("../../inc/footer.php");
?>