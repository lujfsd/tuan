<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_payment_money_log`;");
E_C(\"CREATE TABLE `fanwe_payment_money_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(10) NOT NULL,
  `payment_name` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `operator_module` varchar(20) NOT NULL,
  `operator_name` varchar(255) DEFAULT NULL,
  `operator_id` int(11) NOT NULL,
  `rec_id` int(11) NOT NULL,
  `rec_module` varchar(20) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `money` float(20,4) NOT NULL COMMENT '0:失败 1:成功',
  `log_msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_payment_money_log` values('8','3','余额支付','1283538707','User','fwfw','52','43','OrderIncharge','127.0.0.1','220.0000','会员在线支付订单金额：220.0000');");
E_D("replace into `fanwe_payment_money_log` values('9','4','支付宝','1283538926','Admin','fanwe','8','44','OrderIncharge','127.0.0.1','112.0000','fanwe管理员后台收订单金额：112.0000');");
E_D("replace into `fanwe_payment_money_log` values('10','3','余额支付','1283539884','User','fwfw','52','45','OrderIncharge','127.0.0.1','80.0000','会员在线支付订单金额：80.0000');");
E_D("replace into `fanwe_payment_money_log` values('11','3','余额支付','1283540103','User','fwfw','52','46','OrderIncharge','127.0.0.1','30.0000','会员在线支付订单金额：30.0000');");
E_D("replace into `fanwe_payment_money_log` values('12','3','余额支付','1283540153','User','fwfw','52','47','OrderIncharge','127.0.0.1','80.0000','会员在线支付订单金额：80.0000');");
E_D("replace into `fanwe_payment_money_log` values('13','3','余额支付','1283540169','User','fwfw','52','48','OrderIncharge','127.0.0.1','20.0000','会员在线支付订单金额：20.0000');");
E_D("replace into `fanwe_payment_money_log` values('14','3','余额支付','1283541027','Admin','fanwe','8','8','OrderUncharge','127.0.0.1','-112.0000','管理员后台退订单金额：112.0000');");
E_D("replace into `fanwe_payment_money_log` values('15','3','余额支付','1283541144','User','fanwe','54','49','OrderIncharge','127.0.0.1','110.0000','会员在线支付订单金额：110.0000');");
E_D("replace into `fanwe_payment_money_log` values('16','3','余额支付','1283541264','User','fwfw','52','50','OrderIncharge','127.0.0.1','20.0000','会员在线支付订单金额：20.0000');");
E_D("replace into `fanwe_payment_money_log` values('17','3','余额支付','1283541299','User','fwfw','52','51','OrderIncharge','127.0.0.1','34.9000','会员在线支付订单金额：34.9000');");
E_D("replace into `fanwe_payment_money_log` values('18','4','支付宝','1283541351','User','fwfw','52','52','OrderIncharge','127.0.0.1','0.1000','会员在线支付订单金额：0.1000');");
E_D("replace into `fanwe_payment_money_log` values('19','3','余额支付','1386649568','Admin','fanwe','8','3','Payment','112.53.68.13','-833.9000','fanwe管理员后台“调整”金额：-833.9');");
E_D("replace into `fanwe_payment_money_log` values('20','4','支付宝','1386885731','Admin','fanwe','8','4','Payment','112.53.68.12','-112.1000','fanwe管理员后台“调整”金额：-112.1');");
E_D("replace into `fanwe_payment_money_log` values('21','4','支付宝','1386889813','User','醉清风','54','53','OrderIncharge','110.75.145.2','0.1000','会员在线支付订单金额：0.1000');");
E_D("replace into `fanwe_payment_money_log` values('22','2','到商家付款','1387301045','Admin','fanwe','8','54','OrderIncharge','112.53.68.12','159.0000','fanwe管理员后台收订单金额：159.0000');");
E_D("replace into `fanwe_payment_money_log` values('23','2','银行汇款','1387302700','Admin','fanwe','8','2','Payment','112.53.68.12','-159.0000','fanwe管理员后台“调整”金额：-159');");

require("../../inc/footer.php");
?>