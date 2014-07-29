<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_payment`;");
E_C(\"CREATE TABLE `fanwe_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_1` varchar(255) NOT NULL,
  `online_pay` tinyint(4) NOT NULL,
  `config` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `description_1` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `fee` float(10,4) NOT NULL,
  `currency` int(11) NOT NULL,
  `fee_type` tinyint(1) NOT NULL,
  `cost_fee` float(10,4) NOT NULL,
  `cost_fee_type` tinyint(1) NOT NULL,
  `logo` varchar(255) NOT NULL DEFAULT '',
  `money` double(15,2) DEFAULT '0.00',
  `sort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_payment` values('1','贝宝','1','a:2:{s:14:\"paypal_account\";s:0:\"\";s:15:\"paypal_currency\";s:0:\"\";}','0','PayPal 是在线付款解决方案的全球领导者，在全世界有超过七千一百六十万个帐户用户。PayPal 可在 56 个市场以 7 种货币（加元、欧元、英镑、美元、日元、澳元、港元）使用。（网址：http://www.paypal.com）','Paypal','20.0000','1','0','3.5000','0','','0.00','0');");
E_D("replace into `fanwe_payment` values('2','银行汇款','0','a:2:{s:15:\"postpay_account\";s:1:\"0\";s:16:\"postpay_username\";s:1:\"0\";}','0','','Postpay','0.0000','1','0','0.0000','0','','0.00','0');");
E_D("replace into `fanwe_payment` values('17','商家付款','0','N;','0','','Cod','0.0000','1','0','0.0000','0','/Public/upload/public/201312/52b0fefb6c555.gif','0.00','0');");
E_D("replace into `fanwe_payment` values('3','余额支付','1','N;','0','余额支付','Accountpay','0.0000','1','1','0.0000','0','/Public/upload/public/201312/52aaca2ebb0d8.gif','0.00','0');");
E_D("replace into `fanwe_payment` values('4','支付宝','1','a:4:{s:14:\"alipay_service\";s:1:\"1\";s:14:\"alipay_account\";s:13:\"mz108@126.com\";s:14:\"alipay_partner\";s:16:\"2088002342567593\";s:10:\"alipay_key\";s:32:\"h6zmfajnajlv55767n9ez3ytq3k06t7h\";}','1','推荐用户使用','Alipay','0.0000','1','1','0.0000','0','/Public/upload/public/201312/52aaa2e30f8da.png','0.10','0');");
E_D("replace into `fanwe_payment` values('5','网银在线','1','a:2:{s:17:\"chinabank_account\";s:8:\"21566325\";s:13:\"chinabank_key\";s:14:\"abcdefghtidksl\";}','0','支持招商、工行、建行、中行、交行等主流银行的网银支付','Chinabank','0.0000','1','1','0.0000','0','','0.00','0');");
E_D("replace into `fanwe_payment` values('11','财付通[即时到帐]','1','','0','财付通[即时到帐]','Tencentpay','0.0000','1','0','0.0000','0','','0.00','0');");
E_D("replace into `fanwe_payment` values('12','财付通集成网关支付','1','a:4:{s:13:\"tencentpay_id\";s:10:\"1206479601\";s:14:\"tencentpay_key\";s:32:\"cfb442255ed83ecea335e8f9f05d1cd5\";s:15:\"tencentpay_sign\";s:12:\"方维团购\";s:18:\"tencentpay_gateway\";a:19:{i:0;i:1;i:1002;i:1;i:1001;i:1;i:1003;i:1;i:1005;i:1;i:1004;i:1;i:1008;i:1;i:1009;i:1;i:1032;i:1;i:1022;i:1;i:1006;i:1;i:1021;i:1;i:1027;i:1;i:1010;i:1;i:1052;i:1;i:1020;i:1;i:1030;i:1;i:1042;i:1;i:1028;i:1;}}','0','','TenpayBank','1.0000','1','1','0.0000','0','','0.00','0');");
E_D("replace into `fanwe_payment` values('14','财付通支付','1','','0','','Tencentpay','0.0000','0','0','0.0000','0','','0.00','0');");
E_D("replace into `fanwe_payment` values('18','汇款转帐','0','','0','','Postpay','0.0000','0','0','0.0000','0','','0.00','0');");

require("../../inc/footer.php");
?>