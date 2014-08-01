<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_goods_spec_item`;");
E_C("CREATE TABLE `ylife_goods_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `shop_price` float(10,4) NOT NULL,
  `weight` float(10,4) NOT NULL,
  `spec1_type_id` int(11) NOT NULL,
  `spec2_type_id` int(11) NOT NULL,
  `spec1_id` int(11) NOT NULL,
  `spec2_id` int(11) NOT NULL,
  `cost_price` float(10,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=328 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_goods_spec_item` values('327','DIY_1406251983','117','0','0.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `ylife_goods_spec_item` values('326','DIY_1406251765','116','0','0.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `ylife_goods_spec_item` values('325','DIY_1406251410','115','0','0.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `ylife_goods_spec_item` values('324','DIY_1406250082','114','0','0.0000','0.0000','0','0','0','0','0.0000');");

require("../../inc/footer.php");
?>