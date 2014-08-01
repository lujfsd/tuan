<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_order_consignment`;");
E_C("CREATE TABLE `ylife_order_consignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `delivery_code` varchar(255) NOT NULL,
  `delivery_fee` float(10,4) NOT NULL,
  `protect_fee` float(10,4) NOT NULL,
  `protect` tinyint(1) NOT NULL,
  `cost_calc` tinyint(1) NOT NULL,
  `region_lv1` int(11) NOT NULL,
  `region_lv2` int(11) NOT NULL,
  `region_lv3` int(11) NOT NULL,
  `region_lv4` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile_phone` varchar(255) NOT NULL,
  `fix_phone` varchar(255) NOT NULL,
  `consignee` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `memo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  `express_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_order_consignment` values('2','59','9','','10.0000','0.6000','0','1','1','4','53','519','博美诗帮5-1402','13999999999','','方维测试','350000','','mail@7dit.com','1283540200','1');");
E_D("replace into `ylife_order_consignment` values('3','61','9','ABC_12345','10.0000','0.0000','0','1','1','4','53','519','博美诗帮5-1402','13999999999','','方维测试','350000','','mail@7dit.com','1283540234','1');");
E_D("replace into `ylife_order_consignment` values('4','65','1','','5.0000','0.0000','0','1','0','-1','-1','-1','','18653991358','','','','','925635661@qq.com','1386897546','2');");

require("../../inc/footer.php");
?>