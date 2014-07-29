<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_user_score_log`;");
E_C(\"CREATE TABLE `fanwe_user_score_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `memo_1` varchar(255) NOT NULL,
  `rec_id` int(11) NOT NULL,
  `rec_module` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rec_id` (`rec_id`),
  KEY `index_1` (`rec_id`,`rec_module`,`user_id`),
  KEY `index_2` (`rec_module`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_user_score_log` values('69','59','10','1406594922','会员登录返利','0','Login');");
E_D("replace into `fanwe_user_score_log` values('68','59','5','1406500541','订单获得积分(SN:1407281435418;goods_id:114;score:5)','83','Order');");
E_D("replace into `fanwe_user_score_log` values('67','59','10','1406500402','会员登录返利','0','Login');");
E_D("replace into `fanwe_user_score_log` values('66','59','5','1406250366','订单获得积分(SN:1407251706065;goods_id:114;score:5)','81','Order');");
E_D("replace into `fanwe_user_score_log` values('65','59','10','1406250344','用户注册','59','User');");
E_D("replace into `fanwe_user_score_log` values('70','59','5','1406594938','订单获得积分(SN:1407291648584;goods_id:114;score:5)','85','Order');");
E_D("replace into `fanwe_user_score_log` values('71','59','5','1406595134','订单获得积分(SN:1407291652148;goods_id:114;score:5)','86','Order');");
E_D("replace into `fanwe_user_score_log` values('72','59','10','1406595226','订单获得积分(SN:1407291653469;goods_id:114;score:10)','87','Order');");
E_D("replace into `fanwe_user_score_log` values('73','59','20','1406595290','订单获得积分(SN:1407291654501;goods_id:114;score:20)','88','Order');");

require("../../inc/footer.php");
?>