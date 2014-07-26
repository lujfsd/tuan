<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_role_nav`;");
E_C("CREATE TABLE `fanwe_role_nav` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_role_nav` values('1','系统配置','1','16');");
E_D("replace into `fanwe_role_nav` values('2','团购','1','1');");
E_D("replace into `fanwe_role_nav` values('5','留言信息','1','5');");
E_D("replace into `fanwe_role_nav` values('6','订单','1','2');");
E_D("replace into `fanwe_role_nav` values('7','会员','1','3');");
E_D("replace into `fanwe_role_nav` values('8','前台设置','1','11');");
E_D("replace into `fanwe_role_nav` values('9','后台权限','1','14');");
E_D("replace into `fanwe_role_nav` values('12','支付配送','1','10');");
E_D("replace into `fanwe_role_nav` values('13','邮件短信','1','9');");
E_D("replace into `fanwe_role_nav` values('14','数据库','1','15');");

require("../../inc/footer.php");
?>