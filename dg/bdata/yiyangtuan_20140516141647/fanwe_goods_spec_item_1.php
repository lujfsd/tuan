<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `fanwe_goods_spec_item`;");
E_C("CREATE TABLE `fanwe_goods_spec_item` (
  `id` int(11) NOT NULL auto_increment,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=324 DEFAULT CHARSET=utf8");
E_D("replace into `fanwe_goods_spec_item` values('296','FW_100324045629','86','1000','20.0000','0.2000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('297','FW_100328011523','87','1000','12.0000','0.0000','0','0','0','0','10.0000');");
E_D("replace into `fanwe_goods_spec_item` values('298','FW_100328093136','88','900000000','50.0000','1000.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('299','178_1386047527','89','10','216.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('300','111','90','10','90.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('301','178_1386559148','91','1000','231.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('302','178_1386560709','92','0','459.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('303','178_1386561446','93','1000','827.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('304','178_1386561886','94','999','261.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('305','178_1386631034','95','999','261.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('306','178_1386632277','96','0','120.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('307','178_1386632952','97','999','231.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('308','178_1386634174','98','999','273.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('309','178_1386636580','99','999','99.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('310','178_1386637331','100','999','99.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('311','178_1386638075','101','999','159.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('312','178_1386887088','102','21','0.1000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('313','001','103','0','55.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('314','178_1387147007','104','1000','17.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('315','178_1387148501','105','999','45.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('316','178_1387150121','106','998','55.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('317','178_1387150631','107','999','17.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('318','178_1387151012','108','999','17.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('319','178_1387151249','109','999','55.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('320','178_1387151558','110','0','40.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('321','111111111111111111','111','0','1.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('322','178_1387400130','112','999','20.0000','0.0000','0','0','0','0','0.0000');");
E_D("replace into `fanwe_goods_spec_item` values('323','178_1387404324','113','0','158.0000','0.0000','0','0','0','0','0.0000');");

require("../../inc/footer.php");
?>