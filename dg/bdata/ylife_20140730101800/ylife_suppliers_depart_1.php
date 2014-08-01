<?php 
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ylife_suppliers_depart`;");
E_C("CREATE TABLE `ylife_suppliers_depart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `depart_name` varchar(255) NOT NULL,
  `login_name` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `address` text,
  `map` text NOT NULL,
  `tel` varchar(255) NOT NULL,
  `operating` varchar(255) NOT NULL,
  `is_main` tinyint(1) NOT NULL,
  `bus` text NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `api_address` varchar(255) NOT NULL,
  `xpoint` varchar(255) NOT NULL,
  `ypoint` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `is_main` (`is_main`),
  KEY `index_1` (`supplier_id`,`is_main`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8");
E_D("replace into `ylife_suppliers_depart` values('20','无形陶艺（龙华店）','sh_wuxing_1','e10adc3949ba59abbe56e057f20f883e','上海龙华','','','','0','公交7号线','11','','上海市龙华','121.457595','31.178875');");
E_D("replace into `ylife_suppliers_depart` values('21','上海缘始陶艺吧（江桥万达广场店）','sh_ysty_1','e10adc3949ba59abbe56e057f20f883e','江桥万达广场','','15316550058','9:00--19:00','1','地铁13号线','10','','上海江桥万达广场缘始陶艺吧','121.330723','31.247015');");
E_D("replace into `ylife_suppliers_depart` values('19','无形陶艺（陆家嘴店）','sh_wuxing_2','e10adc3949ba59abbe56e057f20f883e','上海市陆家嘴','','18116345506','9:00——19:00','1','地铁2、4号线','11','','上海市陆家嘴','121.5099','31.244555');");

require("../../inc/footer.php");
?>