<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_payment_log`;");
E_C("CREATE TABLE `ylife_payment_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rec_id` int(11) unsigned NOT NULL DEFAULT '0',
  `payment_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL DEFAULT '0',
  `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:未处理；1：已处理',
  `rec_module` varchar(255) NOT NULL COMMENT 'UserIncharge：用户冲值/Order：订单',
  `create_time` int(10) NOT NULL,
  `money` float(10,4) NOT NULL,
  `pay_code` varchar(150) DEFAULT NULL,
  `pay_back_code` varchar(150) DEFAULT NULL,
  `update_time` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inx_payment_log_001` (`is_paid`,`update_time`)
) ENGINE=MyISAM AUTO_INCREMENT=363 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_payment_log` values('337','57','3','1','1','Order','1283538707','220.0000','337','','1283538707');");
E_D("replace into `ylife_payment_log` values('338','58','4','1','0','Order','1283538897','112.0000','338','','1283538897');");
E_D("replace into `ylife_payment_log` values('339','58','4','1','0','Order','1283538898','112.0000','339','','1283538898');");
E_D("replace into `ylife_payment_log` values('340','59','3','1','1','Order','1283539884','80.0000','340','','1283539884');");
E_D("replace into `ylife_payment_log` values('341','60','3','1','1','Order','1283540103','30.0000','341','','1283540103');");
E_D("replace into `ylife_payment_log` values('342','61','3','1','1','Order','1283540153','80.0000','342','','1283540153');");
E_D("replace into `ylife_payment_log` values('343','62','3','1','1','Order','1283540169','20.0000','343','','1283540169');");
E_D("replace into `ylife_payment_log` values('344','63','3','1','1','Order','1283541144','110.0000','344','','1283541144');");
E_D("replace into `ylife_payment_log` values('345','64','3','1','1','Order','1283541264','20.0000','345','','1283541264');");
E_D("replace into `ylife_payment_log` values('346','64','4','1','0','Order','1283541264','35.0000','346','','1283541264');");
E_D("replace into `ylife_payment_log` values('347','64','4','1','0','Order','1283541265','35.0000','347','','1283541265');");
E_D("replace into `ylife_payment_log` values('348','64','3','1','1','Order','1283541299','34.9000','348','','1283541299');");
E_D("replace into `ylife_payment_log` values('349','64','4','1','1','Order','1283541300','0.1000','349','','1283541300');");
E_D("replace into `ylife_payment_log` values('350','65','4','1','1','Order','1386889686','0.1000','fw-1312131508058-350','2013121372657353','0');");
E_D("replace into `ylife_payment_log` values('351','66','12','1','0','Order','1386896002','835.2700',NULL,NULL,'0');");
E_D("replace into `ylife_payment_log` values('352','66','14','1','0','Order','1386896080','827.0000','352',NULL,'0');");
E_D("replace into `ylife_payment_log` values('353','66','14','1','0','Order','1386896090','827.0000','353',NULL,'0');");
E_D("replace into `ylife_payment_log` values('354','66','14','1','0','Order','1386896094','827.0000','354',NULL,'0');");
E_D("replace into `ylife_payment_log` values('355','66','14','1','0','Order','1386896508','827.0000','355',NULL,'0');");
E_D("replace into `ylife_payment_log` values('356','66','14','1','0','Order','1386896512','827.0000','356',NULL,'0');");
E_D("replace into `ylife_payment_log` values('357','67','5','1','0','Order','1386896762','827.0000','357',NULL,'0');");
E_D("replace into `ylife_payment_log` values('358','67','5','1','0','Order','1386896773','827.0000','358',NULL,'0');");
E_D("replace into `ylife_payment_log` values('359','67','5','1','0','Order','1386896779','827.0000','359',NULL,'0');");
E_D("replace into `ylife_payment_log` values('360','72','4','1','0','Order','1387297918','45.0000','fw-1312180831576-360',NULL,'0');");
E_D("replace into `ylife_payment_log` values('361','72','4','1','0','Order','1387299150','45.0000','fw-1312180831576-361',NULL,'0');");
E_D("replace into `ylife_payment_log` values('362','72','4','1','0','Order','1387299162','45.0000','fw-1312180831576-362',NULL,'0');");

require("../../inc/footer.php");
?>